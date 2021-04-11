#pragma once

namespace antidbg {
#ifndef _ANTI_DBG_WINAPI
#define _ANIT_DBG_WINAPI
	typedef HANDLE(*create_thread)(LPSECURITY_ATTRIBUTES, SIZE_T, LPTHREAD_START_ROUTINE, __drv_aliasesMem LPVOID, DWORD, LPDWORD);
#endif

	void init();
}