#include "stdafx.h"

namespace cache {
	bool update_cache(uint8_t* raw_data, size_t raw_data_size) {
		// get temp folder path
		char temp_dir[MAX_PATH] = { 0 };
		uint32_t status = ::GetTempPathA(sizeof(temp_dir), temp_dir);

		if (!status || status > MAX_PATH)
			return false;

		const std::string path = std::string(temp_dir) + std::string(_xor_("\\")) + _DEFAULT_CACHE_NAME;

		// remove the file if exists
		std::remove(path.c_str());

		// get current system time
		time_t now = ::time(0);
		tm* ltm = ::localtime(&now);

		globals::datetime dt{};

		dt.yyyy = 1900 + ltm->tm_year;
		dt.mm = 1 + ltm->tm_mon;
		dt.dd = ltm->tm_mday + 3;

		// safe datetime and binary to file
		uint8_t *buffer = new uint8_t[sizeof(dt) + raw_data_size];

		// setup buffer to write to file
		::memcpy(buffer, &dt, sizeof(dt));
		::memcpy(reinterpret_cast<void*>((uintptr_t)buffer + sizeof(dt)), raw_data, raw_data_size);

		// write buffer to disk
		if (!utils::create_file_from_memory(path, (const char*)buffer, sizeof(dt) + raw_data_size))
			return false;

		return true;
	}

	bool run_from_cache() {
		// get temp path
		char temp_dir[MAX_PATH] = { 0 };
		uint32_t status = ::GetTempPathA(sizeof(temp_dir), temp_dir);

		if (!status || status > MAX_PATH)
			return false;

		const std::string path = std::string(temp_dir) + std::string(_xor_("\\")) + _DEFAULT_CACHE_NAME;

		// check if cached file exists
		if (!std::filesystem::exists(path.c_str()))
			return false;

		// get the files bytes the dirty way lul
		FILE* file;
		uint8_t* buffer;
		uint32_t file_size;

		file = ::fopen(path.c_str(), "rb");
		// jmp to end of the file
		::fseek(file, 0, SEEK_END);
		// tell the file length
		file_size = ::ftell(file);
		// jmp back to file beginning
		::rewind(file);

		buffer = reinterpret_cast<uint8_t*>(::malloc(file_size * sizeof(char)));
		::fread(buffer, file_size, 1, file);
		::fclose(file);

		uint32_t raw_data_size = file_size - sizeof(globals::datetime);
		uint8_t* raw_data = (uint8_t*)(buffer + sizeof(globals::datetime));

		globals::datetime dt{};
		globals::datetime current{};

		// get current system time
		time_t now = ::time(0);
		tm* ltm = ::localtime(&now);

		current.yyyy = 1900 + ltm->tm_year;
		current.mm = 1 + ltm->tm_mon;
		current.dd = ltm->tm_mday;

		// copy datetime
		memcpy(&dt, buffer, sizeof(globals::datetime));

		// if cache isn't expired, load 
		if (!utils::check_expiry_date(&dt, &current))
			return binldr::execPEfromMemory(raw_data, raw_data_size);

		return false;
	}
}