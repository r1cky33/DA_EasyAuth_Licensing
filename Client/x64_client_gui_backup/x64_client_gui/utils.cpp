#include "stdafx.h"

namespace utils {
	bool create_file_from_memory(const std::string& desired_file_path, const char* address, size_t size)
	{
		std::ofstream file_ofstream(desired_file_path.c_str(), std::ios_base::out | std::ios_base::binary);
		if (!file_ofstream.write(address, size))
		{
			file_ofstream.close();
			return false;
		}

		file_ofstream.close();
		return true;
	}

	bool check_expiry_date(const globals::datetime* expiry_date, const globals::datetime* current_date)
	{
		if (NULL == expiry_date || NULL == current_date)
		{
			return false;
		}
		else
		{
			if (expiry_date->yyyy > current_date->yyyy)
			{
				return false;
			}
			else if (expiry_date->yyyy < current_date->yyyy)
			{
				return true;
			}
			else
			{
				if (expiry_date->mm > current_date->mm)
				{
					return false;
				}
				else if (expiry_date->mm < current_date->mm)
				{
					return true;
				}
				else
				{
					return expiry_date->dd >= current_date->dd ? 0 : 1;
				}
			}
		}
	}
}