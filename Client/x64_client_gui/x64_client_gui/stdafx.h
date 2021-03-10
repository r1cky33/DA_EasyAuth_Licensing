#pragma once

/*
	- General
	This is the main CLIENT responsible for loading x64 binaries and .NET-Applications (x64 / x86) utilizing the technique of CLR-Hosting.

	With using CLR-Hosting unmanaged (Native) hosts can integrate the common language runtime (CLR) into their applications. 
	When you start the .NET runtime inside a native process, that native application becomes a host for the runtime. This lets you 
	add .NET capabilities to your native applications.
*/


/*
	This file includes the necessary dependencies for various functionalities of this program.
*/

// enable unsafe functions like sprintf()
#define _CRT_SECURE_NO_WARNINGS 1

#include <iostream>
#include <Windows.h>
#include <tchar.h>
#include <vector>
#include <ctime>
#include <string>

// access to the filesystem
#include <filesystem>
#include <fstream>

// check inet connection
#include <wininet.h>
#pragma comment(lib,"Wininet.lib")

// DirectX dependencies
#pragma comment(lib, "d3d11.lib")
#pragma comment(lib, "d3dcompiler.lib")
#pragma comment(lib, "dxgi.lib")

#include <d3d11.h>

// Dear ImGui dependencies
#include "imgui/imgui.h"
#include "imgui/imgui_impl_win32.h"
#include "imgui/imgui_impl_dx11.h"

extern IMGUI_IMPL_API LRESULT ImGui_ImplWin32_WndProcHandler(HWND hWnd, UINT msg, WPARAM wParam, LPARAM lParam);

#include "globals.h"
#include "gui.h"

// Authentication
#include "auth.h"

// Cryptography
#include "crypt.h"
#include "xor.hpp"

// Loader
#include "loader.h"

// Security
#include "antidbg.h"

// Caching the received binarry
#include "cache.h"

// Basic utilities
#include "utils.h"