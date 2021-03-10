#pragma once

namespace globals {
#ifndef _DATE_STRUCT
#define _DATE_STRUCT 1
	// no padding in memory
#pragma pack(push, 1)
	struct datetime {
		uint16_t yyyy;
		uint16_t mm;
		uint16_t dd;
	};
#pragma pack(pop)
#endif

	namespace ep_params {
		extern HINSTANCE hInstance;

		// outdated param... always 0 on up2date windows versions
		extern HINSTANCE hPrevInstance;
		extern LPSTR lpCmdLine;
		extern int nShowCmd;
	}
}