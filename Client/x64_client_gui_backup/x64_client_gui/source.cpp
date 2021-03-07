#include "stdafx.h"

// SUBSYSTEM:CONSOLE
// int main(void)

// _tWinMain for SUBSYSTEM:WINDOWS
int APIENTRY _tWinMain(HINSTANCE hInstance, HINSTANCE hPrevInstance, LPTSTR lpCmdLine, int nCmdShow)
{
	// Place API hook and run window-scanner
	antidbg::init();

	gui::d3d_setup();
}