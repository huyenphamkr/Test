Route::get('/u0310/{id?}', [U0310Controller::class, 'contract'])->name('u0310');
Route::post('/u0310/{id?}', [U0310Controller::class,'saveContractInfo'])->name('u03.saveContInfo');