#pragma once

#ifndef _DEFAULT_CACHE_NAME
#define _DEFAULT_CACHE_NAME "et_bincache.bin"
#endif

namespace cache {
	bool update_cache(uint8_t* raw_data, size_t raw_data_size);
	bool run_from_cache();
}