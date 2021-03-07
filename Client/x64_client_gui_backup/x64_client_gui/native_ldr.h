#pragma once

namespace native_ldr {
	// map buffer to memory
	void* mapImageToMemory(void* image_base_address);

	// fix mapped image imports and relocations
	void fixImageIAT(PIMAGE_DOS_HEADER dos_header, PIMAGE_NT_HEADERS nt_header);
	bool resolveImageRelocations(PIMAGE_DOS_HEADER dos_header, PIMAGE_NT_HEADERS nt_header, ULONG_PTR delta);

	// execute entrypoint of mapped image
	void execImageEntrypoint(void* original_entry_point, bool b_exec_with_new_thread);
}
