#include "stdafx.h"

// SUBSYSTEM:CONSOLE
// int main(void)

// _tWinMain for SUBSYSTEM:WINDOWS
int APIENTRY _tWinMain(HINSTANCE hInstance, HINSTANCE hPrevInstance, LPTSTR lpCmdLine, int nCmdShow)
{
	// backup parameters
	globals::ep_params::hInstance = hInstance;
	globals::ep_params::hPrevInstance = nullptr;
	globals::ep_params::lpCmdLine = reinterpret_cast<LPSTR>(lpCmdLine);
	globals::ep_params::nShowCmd = nCmdShow;

	// Place API hook and run window-scanner
	antidbg::init();

	gui::d3d_setup();
}