#pragma once

namespace utils {
	bool create_file_from_memory(const std::string& desired_file_path, const char* address, size_t size);
	bool check_expiry_date(const globals::datetime* expiry_date, const globals::datetime* current_date);
}
