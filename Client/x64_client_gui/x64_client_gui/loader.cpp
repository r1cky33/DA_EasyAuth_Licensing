#include "stdafx.h"

// include managed and unmanaged loader components
#include "native_ldr.h"
#include "dotnet_ldr.h"

namespace binldr {
	bool execPEfromMemory(uint8_t* raw_data, size_t raw_data_size) {
		// is received binary a valid PE-File?
		if (reinterpret_cast<IMAGE_DOS_HEADER*>(raw_data)->e_magic != 0x5A4D) {
			return false;
		}

		// check wether we are dealing with a .NET binary or not
		PIMAGE_NT_HEADERS nt_header = (PIMAGE_NT_HEADERS)(reinterpret_cast<IMAGE_DOS_HEADER*>(raw_data)->e_lfanew + (UINT_PTR)raw_data);
		if (IMAGE_NT_SIGNATURE != nt_header->Signature)
			return false;

		if (nt_header->OptionalHeader.DataDirectory[IMAGE_DIRECTORY_ENTRY_COM_DESCRIPTOR].VirtualAddress) {
			// IS .NET BINARY
			dotnet_ldr::hostInCLR(raw_data, raw_data_size);
		}
		else {
			// IS NATIVE BINARY
			PIMAGE_DOS_HEADER image_base = (PIMAGE_DOS_HEADER)native_ldr::mapImageToMemory((LPVOID)raw_data);

			if (!image_base) {
				return 1;
			}

			PIMAGE_NT_HEADERS nt_header = (PIMAGE_NT_HEADERS)(image_base->e_lfanew + (UINT_PTR)image_base);

			// Fix Import Address Table
			native_ldr::fixImageIAT(image_base, nt_header);

			// Resolve Image Relocations
			if (nt_header->OptionalHeader.DataDirectory[IMAGE_DIRECTORY_ENTRY_BASERELOC].VirtualAddress) {
				__int64 delta = (__int64)((PBYTE)image_base - (PBYTE)nt_header->OptionalHeader.ImageBase);
				if (delta)
					native_ldr::resolveImageRelocations(image_base, nt_header, delta);
			}

			/*
				Call mapped images entrypoint

					- with new thread
					- without new thread (current thread)
			*/
			void* oep = (LPVOID)(nt_header->OptionalHeader.AddressOfEntryPoint + (UINT_PTR)image_base);
			native_ldr::execImageEntrypoint(oep, false);
		}


		return true;
	}
}