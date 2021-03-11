#include "stdafx.h"

/*
	For the graphical user interface (GUI) we need to manually create our own window and setup DirectX11 for rendering. 

	For the DirectX dependencies we just install the SDK and include its libraries and headers in our project settings (VC++ Directories) 
	-	"C:\Program Files %28x86%29\Microsoft DirectX SDK %28June 2010%29\Lib\x64"
	-	"C:\Program Files %28x86%29\Microsoft DirectX SDK %28June 2010%29\Inlude"
*/

namespace gui {
	static ID3D11Device* g_pd3dDevice = nullptr;
	static ID3D11DeviceContext* g_pd3dDeviceContext = nullptr;
	static IDXGISwapChain* g_pSwapChain = nullptr;
	static ID3D11RenderTargetView* g_mainRenderTargetView = nullptr;

	uint32_t screen_width = ::GetSystemMetrics(SM_CXSCREEN);
	uint32_t screen_height = ::GetSystemMetrics(SM_CYSCREEN);

	void create_render_target() {
		ID3D11Texture2D* pBackBuffer;
		g_pSwapChain->GetBuffer(0, IID_PPV_ARGS(&pBackBuffer));
		g_pd3dDevice->CreateRenderTargetView(pBackBuffer, NULL, &g_mainRenderTargetView);
		pBackBuffer->Release();
	}

	void cleanup_render_target() {
		if (g_mainRenderTargetView) { 
			g_mainRenderTargetView->Release(); 
			g_mainRenderTargetView = nullptr; 
		}
	}

	void cleanup_d3ddevice() {
		cleanup_render_target();
		if (g_pSwapChain) { g_pSwapChain->Release(); g_pSwapChain = NULL; }
		if (g_pd3dDeviceContext) { g_pd3dDeviceContext->Release(); g_pd3dDeviceContext = NULL; }
		if (g_pd3dDevice) { g_pd3dDevice->Release(); g_pd3dDevice = NULL; }
	}

	bool create_d3ddevice(HWND hWnd)
	{
		// setup dx swapchain
		DXGI_SWAP_CHAIN_DESC sd;
		ZeroMemory(&sd, sizeof(sd));
		sd.BufferCount = 2;
		sd.BufferDesc.Width = 0;
		sd.BufferDesc.Height = 0;
		sd.BufferDesc.Format = DXGI_FORMAT_R8G8B8A8_UNORM;
		sd.BufferDesc.RefreshRate.Numerator = 60;
		sd.BufferDesc.RefreshRate.Denominator = 1;
		sd.Flags = DXGI_SWAP_CHAIN_FLAG_ALLOW_MODE_SWITCH;
		sd.BufferUsage = DXGI_USAGE_RENDER_TARGET_OUTPUT;
		sd.OutputWindow = hWnd;
		sd.SampleDesc.Count = 1;
		sd.SampleDesc.Quality = 0;
		sd.Windowed = TRUE;
		sd.SwapEffect = DXGI_SWAP_EFFECT_DISCARD;

		UINT createDeviceFlags = 0;
		//createDeviceFlags |= D3D11_CREATE_DEVICE_DEBUG;
		D3D_FEATURE_LEVEL featureLevel;
		const D3D_FEATURE_LEVEL featureLevelArray[2] = { D3D_FEATURE_LEVEL_11_0, D3D_FEATURE_LEVEL_10_0, };
		if (D3D11CreateDeviceAndSwapChain(NULL, D3D_DRIVER_TYPE_HARDWARE, NULL, createDeviceFlags, featureLevelArray, 2, D3D11_SDK_VERSION, &sd, &g_pSwapChain, &g_pd3dDevice, &featureLevel, &g_pd3dDeviceContext) != S_OK)
			return false;

		create_render_target();
		return true;
	}

	bool d3d_setup() {
		// WNDCLASS for registering our window
		WNDCLASSEX wc = {
			sizeof(WNDCLASSEX),
			CS_CLASSDC,
			wnd_proc,
			0L,
			0L,
			::GetModuleHandle(nullptr),
			0,
			0,
			0,
			0,
			_T("EasyAuth Window"),
			0
		};

		// register and create GUI window
		::RegisterClassEx(&wc);
		HWND hwnd = ::CreateWindow(wc.lpszClassName,
			_T("EasyAuth v0.1"), 
			WS_CAPTION | WS_SYSMENU | WS_VISIBLE | WS_MINIMIZEBOX, /*non-resizable window*/
			screen_width / 2, 
			screen_height / 2, 
			350, 
			240, 
			nullptr, 
			nullptr, 
			wc.hInstance, 
			nullptr);

		if (!hwnd) { return false; }

		// initialize DirectX
		if (!create_d3ddevice(hwnd)) {
			cleanup_d3ddevice();
			::UnregisterClass(wc.lpszClassName, wc.hInstance);
			return false;
		}

		// show and update the window
		::ShowWindow(hwnd, SW_SHOWDEFAULT);
		::UpdateWindow(hwnd);

		// setup GUI-Framework
		IMGUI_CHECKVERSION();
		ImGui::CreateContext();
		ImGuiIO& io = ImGui::GetIO(); (void)io;

		ImGui_ImplWin32_Init(hwnd);
		ImGui_ImplDX11_Init(g_pd3dDevice, g_pd3dDeviceContext);

		// state
		bool showLoader = false;
		ImVec4 clear_color = ImVec4(0.45f, 0.55f, 0.60f, 1.00f);
		MSG msg;
		ZeroMemory(&msg, sizeof(msg));

		bool show_another_window = false;
		std::string strLicenseKey = {};

		// main GUI loop
		while (msg.message != WM_QUIT)
		{
			if (::PeekMessage(&msg, NULL, 0U, 0U, PM_REMOVE))
			{
				::TranslateMessage(&msg);
				::DispatchMessage(&msg);
				continue;
			}

			// Start the Dear ImGui frame
			ImGui_ImplDX11_NewFrame();
			ImGui_ImplWin32_NewFrame();
			ImGui::NewFrame();

			{
				show_ldr_form(&showLoader, strLicenseKey);

				if (!strLicenseKey.empty()) {
					auth::authenticate(strLicenseKey);
				}
			}

			ImGui::EndFrame();

			// Rendering
			ImGui::Render();
			g_pd3dDeviceContext->OMSetRenderTargets(1, &g_mainRenderTargetView, NULL);
			g_pd3dDeviceContext->ClearRenderTargetView(g_mainRenderTargetView, (float*)&clear_color);
			ImGui_ImplDX11_RenderDrawData(ImGui::GetDrawData());

			g_pSwapChain->Present(1, 0);
		}

		return true;
	}

	// For processing Windows-Messages to your window
	LRESULT WINAPI wnd_proc(HWND hWnd, UINT msg, WPARAM wParam, LPARAM lParam)
	{
		if (ImGui_ImplWin32_WndProcHandler(hWnd, msg, wParam, lParam))
			return true;

		switch (msg)
		{
		case WM_SIZE:
			if (g_pd3dDevice != NULL && wParam != SIZE_MINIMIZED)
			{
				cleanup_render_target();
				g_pSwapChain->ResizeBuffers(0, (UINT)LOWORD(lParam), (UINT)HIWORD(lParam), DXGI_FORMAT_UNKNOWN, 0);
				create_render_target();
			}
			return 0;
		case WM_SYSCOMMAND:
			if ((wParam & 0xfff0) == SC_KEYMENU)
				return 0;
			break;
		case WM_DESTROY:
			::PostQuitMessage(0);
			return 0;

		}
		return DefWindowProc(hWnd, msg, wParam, lParam);
	}

	void show_ldr_form(bool* p_open, std::string& strLicenseKey) {
        ImGui::PushItemWidth(ImGui::GetFontSize() * -12);
        ImGuiStyle* style = &ImGui::GetStyle();

		// disable window borders
		style->WindowBorderSize = 0.0f;

		// wnd cfg
		static bool no_titlebar = true;
		static bool no_move = true;
		static bool no_resize = true;

		ImGuiWindowFlags wnd_flags = {};
		if (no_titlebar)        wnd_flags |= ImGuiWindowFlags_NoTitleBar;
		if (no_move)            wnd_flags |= ImGuiWindowFlags_NoMove;
		if (no_resize)          wnd_flags |= ImGuiWindowFlags_NoResize;
		wnd_flags |= ImGuiWindowFlags_NoCollapse;

        style->WindowTitleAlign = ImVec2(0.5f, 0.5f);
        style->WindowPadding = ImVec2(15, 8);
        style->WindowRounding = 5.0f;
        style->FramePadding = ImVec2(5, 5);
        style->FrameRounding = 4.0f;
        style->ItemSpacing = ImVec2(12, 8);
        style->ItemInnerSpacing = ImVec2(8, 6);
        style->IndentSpacing = 25.0f;
        style->ScrollbarSize = 15.0f;
        style->ScrollbarRounding = 9.0f;
        style->GrabMinSize = 5.0f;
        style->GrabRounding = 3.0f;

		style->Colors[ImGuiCol_Text] = ImVec4(1.00f, 1.00f, 1.00f, 1.00f);
		style->Colors[ImGuiCol_TextDisabled] = ImVec4(0.50f, 0.50f, 0.50f, 1.00f);
		style->Colors[ImGuiCol_WindowBg] = ImVec4(0.13f, 0.14f, 0.15f, 1.00f);
		style->Colors[ImGuiCol_ChildBg] = ImVec4(0.13f, 0.14f, 0.15f, 1.00f);
		style->Colors[ImGuiCol_PopupBg] = ImVec4(0.13f, 0.14f, 0.15f, 1.00f);
		style->Colors[ImGuiCol_Border] = ImVec4(0.43f, 0.43f, 0.50f, 0.50f);
		style->Colors[ImGuiCol_BorderShadow] = ImVec4(0.00f, 0.00f, 0.00f, 0.00f);
		style->Colors[ImGuiCol_FrameBg] = ImVec4(0.25f, 0.25f, 0.25f, 1.00f);
		style->Colors[ImGuiCol_FrameBgHovered] = ImVec4(0.38f, 0.38f, 0.38f, 1.00f);
		style->Colors[ImGuiCol_FrameBgActive] = ImVec4(0.67f, 0.67f, 0.67f, 0.39f);
		style->Colors[ImGuiCol_TitleBg] = ImVec4(0.08f, 0.08f, 0.09f, 1.00f);
		style->Colors[ImGuiCol_TitleBgActive] = ImVec4(0.08f, 0.08f, 0.09f, 1.00f);
		style->Colors[ImGuiCol_TitleBgCollapsed] = ImVec4(0.00f, 0.00f, 0.00f, 0.51f);
		style->Colors[ImGuiCol_MenuBarBg] = ImVec4(0.14f, 0.14f, 0.14f, 1.00f);
		style->Colors[ImGuiCol_ScrollbarBg] = ImVec4(0.02f, 0.02f, 0.02f, 0.53f);
		style->Colors[ImGuiCol_ScrollbarGrab] = ImVec4(0.31f, 0.31f, 0.31f, 1.00f);
		style->Colors[ImGuiCol_ScrollbarGrabHovered] = ImVec4(0.41f, 0.41f, 0.41f, 1.00f);
		style->Colors[ImGuiCol_ScrollbarGrabActive] = ImVec4(0.51f, 0.51f, 0.51f, 1.00f);
		style->Colors[ImGuiCol_CheckMark] = ImVec4(0.11f, 0.64f, 0.92f, 1.00f);
		style->Colors[ImGuiCol_SliderGrab] = ImVec4(0.11f, 0.64f, 0.92f, 1.00f);
		style->Colors[ImGuiCol_SliderGrabActive] = ImVec4(0.08f, 0.50f, 0.72f, 1.00f);
		style->Colors[ImGuiCol_Button] = ImVec4(0.25f, 0.25f, 0.25f, 1.00f);
		style->Colors[ImGuiCol_ButtonHovered] = ImVec4(0.38f, 0.38f, 0.38f, 1.00f);
		style->Colors[ImGuiCol_ButtonActive] = ImVec4(0.67f, 0.67f, 0.67f, 0.39f);
		style->Colors[ImGuiCol_Header] = ImVec4(0.22f, 0.22f, 0.22f, 1.00f);
		style->Colors[ImGuiCol_HeaderHovered] = ImVec4(0.25f, 0.25f, 0.25f, 1.00f);
		style->Colors[ImGuiCol_HeaderActive] = ImVec4(0.67f, 0.67f, 0.67f, 0.39f);
		style->Colors[ImGuiCol_Separator] = style->Colors[ImGuiCol_Border];
		style->Colors[ImGuiCol_SeparatorHovered] = ImVec4(0.41f, 0.42f, 0.44f, 1.00f);
		style->Colors[ImGuiCol_SeparatorActive] = ImVec4(0.26f, 0.59f, 0.98f, 0.95f);
		style->Colors[ImGuiCol_ResizeGrip] = ImVec4(0.00f, 0.00f, 0.00f, 0.00f);
		style->Colors[ImGuiCol_ResizeGripHovered] = ImVec4(0.29f, 0.30f, 0.31f, 0.67f);
		style->Colors[ImGuiCol_ResizeGripActive] = ImVec4(0.26f, 0.59f, 0.98f, 0.95f);
		style->Colors[ImGuiCol_Tab] = ImVec4(0.08f, 0.08f, 0.09f, 0.83f);
		style->Colors[ImGuiCol_TabHovered] = ImVec4(0.33f, 0.34f, 0.36f, 0.83f);
		style->Colors[ImGuiCol_TabActive] = ImVec4(0.23f, 0.23f, 0.24f, 1.00f);
		style->Colors[ImGuiCol_TabUnfocused] = ImVec4(0.08f, 0.08f, 0.09f, 1.00f);
		style->Colors[ImGuiCol_TabUnfocusedActive] = ImVec4(0.13f, 0.14f, 0.15f, 1.00f);
		style->Colors[ImGuiCol_PlotLines] = ImVec4(0.61f, 0.61f, 0.61f, 1.00f);
		style->Colors[ImGuiCol_PlotLinesHovered] = ImVec4(1.00f, 0.43f, 0.35f, 1.00f);
		style->Colors[ImGuiCol_PlotHistogram] = ImVec4(0.90f, 0.70f, 0.00f, 1.00f);
		style->Colors[ImGuiCol_PlotHistogramHovered] = ImVec4(1.00f, 0.60f, 0.00f, 1.00f);
		style->Colors[ImGuiCol_TextSelectedBg] = ImVec4(0.26f, 0.59f, 0.98f, 0.35f);
		style->Colors[ImGuiCol_DragDropTarget] = ImVec4(0.11f, 0.64f, 0.92f, 1.00f);
		style->Colors[ImGuiCol_NavHighlight] = ImVec4(0.26f, 0.59f, 0.98f, 1.00f);
		style->Colors[ImGuiCol_NavWindowingHighlight] = ImVec4(1.00f, 1.00f, 1.00f, 0.70f);
		style->Colors[ImGuiCol_NavWindowingDimBg] = ImVec4(0.80f, 0.80f, 0.80f, 0.20f);
		style->Colors[ImGuiCol_ModalWindowDimBg] = ImVec4(0.80f, 0.80f, 0.80f, 0.35f);

		// basic input form
		{
			if (!ImGui::Begin("EasyAuth Form", p_open, wnd_flags)) {
				ImGui::End();
				return;
			}

			ImGui::SetWindowSize(ImVec2{ 335.f, 260.f });
			ImGui::SetWindowPos(ImVec2{ -1.f, -1.f });

			// form
			ImGui::Separator();
			ImGui::SetWindowFontScale(3.f);
			ImGui::Text("   EasyAuth");

			static char licenseKey[128] = {};

			ImGui::SetWindowFontScale(1.f);
			ImGui::NewLine();
			ImGui::Text("License Key: ");
			ImGui::SameLine();
			ImGui::InputText("Hello", licenseKey, IM_ARRAYSIZE(licenseKey));

			if (ImGui::Button("Sumbit")) {
				// return input
				strLicenseKey = std::string(licenseKey);
			}

			ImGui::End();
		}
	}
}