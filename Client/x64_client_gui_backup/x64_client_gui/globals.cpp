#include "stdafx.h"

namespace globals {
	namespace ep_params {
		HINSTANCE hInstance = nullptr;

		// outdated param... always 0 on up2date windows versions
		HINSTANCE hPrevInstance = nullptr;
		LPSTR lpCmdLine = nullptr;
		int nShowCmd = 0;
	}
}