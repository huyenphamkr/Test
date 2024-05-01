<?php
use Illuminate\Support\Facades\Cache;
Route::get('/create-cache', function () {
    // Dữ liệu bạn muốn cache
    $data = [
        'key' => 'value',
        'foo' => 'bar',
    ];

    // Lưu dữ liệu vào cache với khóa 'my_cache_key' trong 10 phút
    Cache::put('my_cache_key', $data, 10);

    return 'Cache created successfully!';
});



Route::get('/show-cache', function () {
    // Lấy dữ liệu từ cache
    $cachedData = Cache::get('my_cache_key');

    if ($cachedData !== null) {
        // Nếu có dữ liệu trong cache, trả về dữ liệu đó
        return $cachedData;
    } else {
        // Nếu không có dữ liệu trong cache, trả về thông báo
        return 'No data found in cache!';
    }
});


Route::get('/clear-cache', function () {
    // Xóa cache của 'cached_data'
    //Cache::forget('cached_data');

    Cache::flush();

    return 'Cache cleared';
});

