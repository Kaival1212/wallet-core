<?php

use App\Http\Controllers\AppleWalletApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;



// return nice version of API status and that this the api for Copper Chimney powered by KNConsulting & Innovation Ltd
Route::get('/', function (Request $request) {

    return response()->json([
        'status' => 'API is running',
        'application' => 'Copper Chimney',
        'powered_by' => 'KNConsulting & Innovation Ltd',
        'version' => '1.0.0',
    ]);

});


// Apple Wallet related routes
Route::prefix('apple-wallet/v1')->group(function () {

    Route::post('devices/{deviceId}/registrations/{passTypeId}/{serialNumber}', [AppleWalletApi::class, 'registerDevice']);
    Route::get('passes/{passTypeId}/{serialNumber}', [AppleWalletApi::class, 'getLatestPass']);

    // devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}?passesUpdatedSince={previousLastUpdated}
    Route::get('devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}', [AppleWalletApi::class, 'getUpdatedPasses']);
});


