#pragma once

namespace gui {
	extern bool create_d3ddevice(HWND hWnd);
	extern void cleanup_d3ddevice();
	extern void create_render_target();
	extern void cleanup_render_target();
	extern LRESULT WINAPI wnd_proc(HWND hWnd, UINT msg, WPARAM wParam, LPARAM lParam);

	extern bool d3d_setup();
	extern void show_ldr_form(bool* p_open, std::string &strLicenseKey);
}
