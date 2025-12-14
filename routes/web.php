<?php


use KN\WalletCore\Services\GoogleWalletService;
use Illuminate\Support\Facades\Route;
use KN\WalletCore\Services\AppleWalletService;
use KN\WalletCore\Models\LoyaltyCustomer;
use Illuminate\Support\Facades\Response;


Route::get('/wallet', function () {
    return view('walletcore::welcomeCustomerAddForm');
})->name('home');

Route::get('/google-wallet/{user}', function (LoyaltyCustomer $user) {
    $service = new GoogleWalletService();
    $url = $service->createLoyaltyCardForCustomer($user);
    return redirect($url);

})->name('google.wallet');


Route::get('/apple-pass/{user}', function (LoyaltyCustomer $user) {
    $service = new AppleWalletService();
    $pkpass = $service->createLoyaltyCard($user);

    return Response::make($pkpass, 200, [
        'Content-Type' => 'application/vnd.apple.pkpass',
        'Content-Disposition' => 'attachment; filename="loyalty.pkpass"',
        'Content-Transfer-Encoding' => 'binary',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'no-cache, must-revalidate',
    ]);

})->name('apple.pass');

