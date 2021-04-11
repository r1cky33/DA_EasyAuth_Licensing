#include "stdafx.h"

#define CURL_STATICLIB
#pragma warning(disable:4996)

#pragma comment (lib, "Normaliz.lib")
#pragma comment (lib, "Ws2_32.lib")
#pragma comment (lib, "Wldap32.lib")
#pragma comment (lib, "Crypt32.lib")
#pragma comment (lib, "advapi32.lib")

#ifdef _DEBUG
	#pragma comment(lib, "libcurl_a_debug.lib")
	#include "curl/x64_debug/libcurl-vc16-x64-debug-static-ipv6-sspi-winssl/include/curl/curl.h"
#else
	#pragma comment(lib, "libcurl_a.lib")
	#include "curl/x64_release/libcurl-vc16-x64-release-static-ipv6-sspi-winssl/include/curl/curl.h"
#endif

namespace auth {
#define LOADER_VERSION _xor_("1.0.1")

	auth_state authenticate(std::string strLicenseKey) {
		// without inet -> try running it from cache
		if (!check_inet_connection())
			cache::run_from_cache();

		std::string get_l = _xor_("license=");
		get_l.append(strLicenseKey);
		std::string get_h = _xor_("&hwid=");
		get_h.append(get_hwid());
		std::string version = _xor_("&version=");
		version.append(LOADER_VERSION);

		std::string url = std::string(_xor_("http://localhost:8000/auth/auth.php?")) + get_l + get_h + version;

		// remove empty spaces from the string
		std::string removeables = " ";
		for (char c : removeables) {
			url.erase(std::remove(url.begin(), url.end(), c), url.end());
		}

		auto curl = curl_easy_init();
		if (curl) {
			curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
			curl_easy_setopt(curl, CURLOPT_NOPROGRESS, 1L);
			curl_easy_setopt(curl, CURLOPT_USERPWD, "user:pass");
			curl_easy_setopt(curl, CURLOPT_USERAGENT, "curl/7.42.0");
			curl_easy_setopt(curl, CURLOPT_MAXREDIRS, 50L);
			curl_easy_setopt(curl, CURLOPT_TCP_KEEPALIVE, 1L);

			std::string response_string;
			std::string header_string;
			curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, writeFunction);
			curl_easy_setopt(curl, CURLOPT_WRITEDATA, &response_string);
			curl_easy_setopt(curl, CURLOPT_HEADERDATA, &header_string);

			char* url;
			long response_code;
			double elapsed;
			curl_easy_getinfo(curl, CURLINFO_RESPONSE_CODE, &response_code);
			curl_easy_getinfo(curl, CURLINFO_TOTAL_TIME, &elapsed);
			curl_easy_getinfo(curl, CURLINFO_EFFECTIVE_URL, &url);

			curl_easy_perform(curl);
			curl_easy_cleanup(curl);
			curl = NULL;

			if (!response_string.empty()) {
				// decrypt received response
				std::string bin = crypt::hex2bin(response_string);
				std::string decoded = crypt::xorstr(bin, get_hwid());

				{
					//	handle non-pe-cases
					if (decoded.compare(std::string(_xor_("expired"))) == 0) 
						return auth_state::expired;

					if (decoded.compare(std::string(_xor_("invalid_request"))) == 0) 
						return auth_state::invalid_request;

					if (decoded.compare(std::string(_xor_("banned"))) == 0) 
						return auth_state::banned;
				}

				// copy raw bytes to buffer
				uint8_t* raw_data = (uint8_t*)malloc(crypt::hex2bin(decoded).size());
				memcpy(raw_data, crypt::hex2bin(decoded).c_str(), crypt::hex2bin(decoded).size());

				// cache the binary since we received the software successfully
				cache::update_cache(raw_data, crypt::hex2bin(decoded).size());

				// pass the binary to loader component
				return binldr::execPEfromMemory(raw_data, crypt::hex2bin(decoded).size()) == true ? auth_state::valid : auth_state::exec_error;
			}
		}
		return auth_state::unknown;
	}

	size_t writeFunction(void* ptr, size_t size, size_t nmemb, std::string* data) {
		data->append((char*)ptr, size * nmemb);
		return size * nmemb;
	}

	LONG get_string_reg_key(HKEY hKey, const std::wstring& strValueName, std::wstring& strValue, const std::wstring& strDefaultValue)
	{
		strValue = strDefaultValue;
		WCHAR szBuffer[512];
		DWORD dwBufferSize = sizeof(szBuffer);
		ULONG nError;
		nError = ::RegQueryValueExW(hKey, strValueName.c_str(), 0, NULL, (LPBYTE)szBuffer, &dwBufferSize);
		if (ERROR_SUCCESS == nError)
		{
			strValue = szBuffer;
		}
		return nError;
	}

	std::string get_hwid() {
		HKEY hKey;

		LONG lRes = ::RegOpenKeyExW(HKEY_LOCAL_MACHINE, 
			_xor_(L"SOFTWARE\\Microsoft\\Cryptography"),
			0,
			KEY_READ,
			&hKey);

		bool bExistsAndSuccess(lRes == ERROR_SUCCESS);
		bool bDoesNotExistsSpecifically(lRes == ERROR_FILE_NOT_FOUND);
		std::wstring strValueOfBinDir;
		std::wstring strKeyDefaultValue;

		get_string_reg_key(hKey, 
			std::wstring(_xor_(L"MachineGuid")), 
			strValueOfBinDir, 
			std::wstring(_xor_(L"bad")));

		std::string hwid(strValueOfBinDir.begin(), strValueOfBinDir.end());
		return hwid;
	}

	bool check_inet_connection() {
		return ::InternetCheckConnectionA("https://www.google.com/", FLAG_ICC_FORCE_CONNECTION, 0);
	}
}