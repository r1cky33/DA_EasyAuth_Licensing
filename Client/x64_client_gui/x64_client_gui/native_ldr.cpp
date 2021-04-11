#include "stdafx.h"

namespace native_ldr {
	void* mapImageToMemory(void* image_base_address) {
		void* mapped_base = nullptr;

		try {
			PIMAGE_DOS_HEADER raw_image = (PIMAGE_DOS_HEADER)image_base_address;

			// ensure the NT-SIG is valid
			PIMAGE_NT_HEADERS nt_header = (PIMAGE_NT_HEADERS)(raw_image->e_lfanew + (UINT_PTR)raw_image);
			if (IMAGE_NT_SIGNATURE != nt_header->Signature)
				return nullptr;

			// only x64 modules will be loaded
			if (IMAGE_FILE_MACHINE_AMD64 != nt_header->FileHeader.Machine)
				return nullptr;

			/*
				-	Allocate memory where we will map the image to
				Buffer needs to be sized with the virtual size of the image + (RWX)
			*/
			mapped_base = ::VirtualAlloc(
				nullptr,
				nt_header->OptionalHeader.SizeOfImage,
				MEM_COMMIT | MEM_RESERVE,
				PAGE_EXECUTE_READWRITE);

			if (!mapped_base)
				return nullptr;

			/*
				We can write the images headers directly from the buffer. This step is not 100% necessary
				because the header isn't needed to execute the image in memory.
			*/
			::memcpy(mapped_base, (LPVOID)raw_image, nt_header->OptionalHeader.SizeOfHeaders);

			// Copy each section to its foreseen virtual address.
			PIMAGE_SECTION_HEADER section_header =
				(PIMAGE_SECTION_HEADER)(raw_image->e_lfanew + sizeof(*nt_header) + (UINT_PTR)raw_image);

			for (int i = 0; i < nt_header->FileHeader.NumberOfSections; i++) {
				::memcpy(
					(LPVOID)(section_header->VirtualAddress + (UINT_PTR)mapped_base),
					(LPVOID)(section_header->PointerToRawData + (UINT_PTR)raw_image),
					section_header->SizeOfRawData);
				section_header++;
			}
		}
		catch (...) {
			delete[] image_base_address;

			::MessageBoxA(nullptr, _xor_("Exception occured on 'mapImageToMemory'!"), _xor_("ERROR"), 0);
			::exit(-1);
		}

		return mapped_base;
	}

	bool resolveImageRelocations(PIMAGE_DOS_HEADER dos_header, PIMAGE_NT_HEADERS nt_header, ULONG_PTR delta) {
		ULONG_PTR size;
		PULONG_PTR intruction;

		PIMAGE_BASE_RELOCATION reloc_block =
			(PIMAGE_BASE_RELOCATION)(nt_header->OptionalHeader.DataDirectory[IMAGE_DIRECTORY_ENTRY_BASERELOC].VirtualAddress +
				(UINT_PTR)dos_header);

		while (reloc_block->VirtualAddress) {
			size = (reloc_block->SizeOfBlock - sizeof(reloc_block)) / sizeof(WORD);
			PWORD fixup = (PWORD)((ULONG_PTR)reloc_block + sizeof(reloc_block));

			for (int i = 0; i < size; i++, fixup++) {
				if (IMAGE_REL_BASED_DIR64 == *fixup >> 12) {
					intruction = (PULONG_PTR)(reloc_block->VirtualAddress + (ULONG_PTR)dos_header + (*fixup & 0xfff));
					*intruction += delta;
				}
			}
			reloc_block = (PIMAGE_BASE_RELOCATION)(reloc_block->SizeOfBlock + (ULONG_PTR)reloc_block);
		}
		return true;
	}

	void fixImageIAT(PIMAGE_DOS_HEADER dos_header, PIMAGE_NT_HEADERS nt_header) {
		PIMAGE_THUNK_DATA thunk;
		PIMAGE_THUNK_DATA fixup;
		DWORD iat_rva;
		SIZE_T iat_size;
		HMODULE import_base;
		PIMAGE_IMPORT_DESCRIPTOR import_table =
			(PIMAGE_IMPORT_DESCRIPTOR)(nt_header->OptionalHeader.DataDirectory[IMAGE_DIRECTORY_ENTRY_IMPORT].VirtualAddress +
				(UINT_PTR)dos_header);

		DWORD iat_loc =
			(nt_header->OptionalHeader.DataDirectory[IMAGE_DIRECTORY_ENTRY_IAT].VirtualAddress) ?
			IMAGE_DIRECTORY_ENTRY_IAT :
			IMAGE_DIRECTORY_ENTRY_IMPORT;

		iat_rva = nt_header->OptionalHeader.DataDirectory[iat_loc].VirtualAddress;
		iat_size = nt_header->OptionalHeader.DataDirectory[iat_loc].Size;

		LPVOID iat = (LPVOID)(iat_rva + (UINT_PTR)dos_header);
		DWORD op;
		::VirtualProtect(iat, iat_size, PAGE_READWRITE, &op);
		__try {

			/*
				Iterate through the IAT and manually set the current routine addresses.
			*/
			while (import_table->Name) {
				import_base = ::LoadLibraryA((LPCSTR)(import_table->Name + (UINT_PTR)dos_header));
				fixup = (PIMAGE_THUNK_DATA)(import_table->FirstThunk + (UINT_PTR)dos_header);
				if (import_table->OriginalFirstThunk) {
					thunk = (PIMAGE_THUNK_DATA)(import_table->OriginalFirstThunk + (UINT_PTR)dos_header);
				}
				else {
					thunk = (PIMAGE_THUNK_DATA)(import_table->FirstThunk + (UINT_PTR)dos_header);
				}

				while (thunk->u1.Function) {
					PCHAR func_name;

					/*
						Get API NAME and resolve its current address according to its module (user32, kernel32, ...)
					*/

					if (thunk->u1.Ordinal & IMAGE_ORDINAL_FLAG64) {
						fixup->u1.Function =
							(UINT_PTR)::GetProcAddress(import_base, (LPCSTR)(thunk->u1.Ordinal & 0xFFFF));

					}
					else {
						func_name =
							(PCHAR)(((PIMAGE_IMPORT_BY_NAME)(thunk->u1.AddressOfData))->Name + (UINT_PTR)dos_header);
						fixup->u1.Function = (UINT_PTR)::GetProcAddress(import_base, func_name);
					}
					fixup++;
					thunk++;
				}
				import_table++;
			}
		}
		__except (1) {
			::MessageBoxA(nullptr, _xor_("Exception occured on 'fixImageIAT'!"), _xor_("ERROR"), 0);
			::exit(-1);
		}

		return;
	}

	void execImageEntrypoint(void* original_entry_point, bool b_exec_with_new_thread) {
		if (!b_exec_with_new_thread) {
			// define the entrypoints address as a function and simply call it
			((void(*)())(original_entry_point))();
		}
		else {
			// create a new thread that will execute the entrypoint
			HANDLE hThread = ::CreateThread(0, 0, (LPTHREAD_START_ROUTINE)original_entry_point, nullptr, 0, 0);

			// Wait for thread to finish execution
			::WaitForSingleObject(hThread, INFINITE);
			::CloseHandle(hThread);
		}
	}
}