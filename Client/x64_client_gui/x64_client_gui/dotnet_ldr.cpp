// Dependencies
#include <SDKDDKVer.h>
#include <stdlib.h>
#include <malloc.h>
#include <memory.h>

// .NET Runtime
#include <atlbase.h>
#include <metahost.h>
#import "C:\Windows\Microsoft.NET\Framework64\v4.0.30319\mscorlib.tlb" raw_interfaces_only rename("ReportEvent","ReportEventMsCoree")
#include <atlstr.h>

#pragma comment(lib, "mscoree.lib")
#include "stdafx.h"

using namespace mscorlib;

namespace dotnet_ldr {
	HRESULT hostInCLR(uint8_t* raw_data, size_t raw_data_size) {
		ICLRMetaHost* pMetaHost = nullptr;
		HRESULT hr = {};

		/*Create CLR Instance*/
		hr = ::CLRCreateInstance(CLSID_CLRMetaHost, IID_ICLRMetaHost, (VOID**)&pMetaHost);

		if (FAILED(hr)) {
			delete[] raw_data;

			::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
			return -1;
		}

		{
			/*Iterate installed runtimes*/

			IEnumUnknown* installedRuntimes;
			hr = pMetaHost->EnumerateInstalledRuntimes(&installedRuntimes);

			ICLRRuntimeInfo* runtimeInfo = NULL;
			ULONG fetched = 0;
			LPCWSTR version = L"";
			while ((hr = installedRuntimes->Next(1, (IUnknown**)&runtimeInfo, &fetched)) == S_OK && fetched > 0) {
				wchar_t versionString[20];
				DWORD versionStringSize = 20;
				hr = runtimeInfo->GetVersionString(versionString, &versionStringSize);
				version = versionString;
			}

			hr = pMetaHost->GetRuntime(version, IID_ICLRRuntimeInfo, (VOID**)&runtimeInfo);

			BOOL bLoadable;
			hr = runtimeInfo->IsLoadable(&bLoadable);

			if (FAILED(hr) || !bLoadable)
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}

			ICorRuntimeHost* pRuntimeHost = NULL;
			hr = runtimeInfo->GetInterface(CLSID_CorRuntimeHost, IID_ICorRuntimeHost, (VOID**)&pRuntimeHost);

			if (FAILED(hr))
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}

			/*Start Runtimehost*/
			hr = pRuntimeHost->Start();

			if (FAILED(hr))
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}

			IUnknownPtr pAppDomainThunk = NULL;
			hr = pRuntimeHost->GetDefaultDomain(&pAppDomainThunk);


			if (FAILED(hr))
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}

			_AppDomainPtr pDefaultAppDomain = NULL;

			/* Equivalent of System.AppDomain.CurrentDomain in C# */
			hr = pAppDomainThunk->QueryInterface(__uuidof(_AppDomain), (VOID**)&pDefaultAppDomain);

			if (FAILED(hr))
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}

			_AssemblyPtr pAssembly = NULL;
			SAFEARRAYBOUND rgsabound[1];

			rgsabound[0].cElements = raw_data_size;
			rgsabound[0].lLbound = 0;

			SAFEARRAY* pSafeArray = ::SafeArrayCreate(VT_UI1, 1, rgsabound);
			void* pvData = NULL;

			hr = ::SafeArrayAccessData(pSafeArray, &pvData);

			if (FAILED(hr))
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}
			
			memcpy(pvData, raw_data, raw_data_size);
			hr = ::SafeArrayUnaccessData(pSafeArray);

			if (FAILED(hr))
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}

			/* Equivalent of System.AppDomain.CurrentDomain.Load(byte[] rawAssembly) */
			hr = pDefaultAppDomain->Load_3(pSafeArray, &pAssembly);

			if (FAILED(hr))
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}

			_MethodInfoPtr pMethodInfo = NULL;

			/* Assembly.EntryPoint Property */
			hr = pAssembly->get_EntryPoint(&pMethodInfo);

			if (FAILED(hr))
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}

			VARIANT retVal;
			ZeroMemory(&retVal, sizeof(VARIANT));

			VARIANT obj;
			ZeroMemory(&obj, sizeof(VARIANT));
			obj.vt = VT_NULL;

			SAFEARRAY* psaStaticMethodArgs = ::SafeArrayCreateVector(VT_VARIANT, 0, 0);
			hr = pMethodInfo->Invoke_3(obj, psaStaticMethodArgs, &retVal);

			if (FAILED(hr))
			{
				delete[] raw_data;

				::MessageBoxA(nullptr, _xor_("Exception occured on 'hostInCLR'!").c_str(), _xor_("ERROR").c_str(), 0);
				return -1;
			}
		}
		return S_OK;
	}
}