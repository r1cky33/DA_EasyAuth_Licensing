#include "stdafx.h"

BOOL CALLBACK enumWindowCallback(HWND hWnd, LPARAM lparam) {
	int length = GetWindowTextLength(hWnd);
	char* buffer = new char[length + 1];
	::GetWindowTextA(hWnd, buffer, length + 1);
	std::string windowTitle(buffer);

	if (::IsWindowVisible(hWnd) && length != 0) {
		if (windowTitle.find(std::string(_xor_("OLLYDBG"))) != std::string::npos) {
			::exit(-1);
		}
		if (windowTitle.find(std::string(_xor_("WinDbgFrameClass"))) != std::string::npos) {
			::exit(-1);
		}
		if (windowTitle.find(std::string(_xor_("IDA"))) != std::string::npos) {
			::exit(-1);
		}
		if (windowTitle.find(std::string(_xor_("Zeta Debugger"))) != std::string::npos) {
			::exit(-1);
		}
		if (windowTitle.find(std::string(_xor_("ObsidianGUI"))) != std::string::npos) {
			::exit(-1);
		}
		if (windowTitle.find(std::string(_xor_("Rock Debugger"))) != std::string::npos) {
			::exit(-1);
		}
		if (windowTitle.find(std::string(_xor_("x64dbg"))) != std::string::npos) {
			::exit(-1);
		}
	}
	return TRUE;
}

namespace antidbg {
	byte shellcode[] = { 0x48, 0xB8, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xD0 };

	void patch_dbg_ui_remote_breakin() {
		HANDLE hProcess = ::GetCurrentProcess();
		HMODULE hMod = ::GetModuleHandleW(_xor_(L"ntdll.dll"));
		if (hMod) {
			void* f_dbg_ui_remote_breakin = reinterpret_cast<void*>(::GetProcAddress(hMod, _xor_("DbgUiRemoteBreakin")));

			*(uint64_t*)&shellcode[2] = (uint64_t)& ::ExitProcess;

			DWORD dwOldProtect = 0;
			::VirtualProtect(f_dbg_ui_remote_breakin, sizeof(shellcode), PAGE_EXECUTE_READWRITE, &dwOldProtect);
			::memcpy(f_dbg_ui_remote_breakin, &shellcode, sizeof(shellcode));
			::VirtualProtect(f_dbg_ui_remote_breakin, sizeof(shellcode), dwOldProtect, 0);
		}
	}

	void wnd_check() {
		while (true) {
			::EnumWindows(enumWindowCallback, NULL);
			::Sleep(5000);
		}
	}

	void init() {
		create_thread manual_create_thread = nullptr;

		// Manuelles Auflösen von CreateThread
		manual_create_thread = (create_thread)::GetProcAddress(::LoadLibraryA(_xor_("kernel32.dll")), _xor_("CreateThread"));
		if (!manual_create_thread) {
			::MessageBoxA(nullptr, _xor_("Failed to find API"), _xor_("ERROR"), 0);
			::ExitProcess(1);
		}

		// Durchsuchen von offenen Fenstern
		manual_create_thread(0, 0, (LPTHREAD_START_ROUTINE)wnd_check, 0, 0, 0);

		// Anwendung wird geschlossen, sobald ein Debugger attached
		patch_dbg_ui_remote_breakin();
	}
}