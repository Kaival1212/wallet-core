<?php

namespace KN\WalletCore\Http\Controllers;

use Illuminate\Http\Request;
use KN\WalletCore\Models\AppleDevices;
use KN\WalletCore\Models\LoyaltyCustomer;
use KN\WalletCore\Services\AppleWalletService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller as BaseController;

class AppleWalletApi extends BaseController
{

    function registerDevice($deviceId, $passTypeId, $serialNumber, Request $request)
    {

        $user = LoyaltyCustomer::where('qr_code', $serialNumber)->get()->first();

        // 200 Serial Number Already Registered for Device
        $existingDevice = AppleDevices::where('serial_number', $serialNumber)->first();
        if ($existingDevice) {
            return response()->json(['message' => 'Serial number already registered for this device', 'device' => $existingDevice], 200);
        }

        $device = AppleDevices::create([
            'loyalty_customer_id' => $user->id,
            'device_id' => $deviceId,
            'pass_type_id' => $passTypeId,
            'serial_number' => $serialNumber,
            'push_token' => $request->input('pushToken'),
        ]);

        if (!$device) {
            return response()->json(['message' => 'Failed to register device'], 401);
        }

        return response()->json(['message' => 'Device registered successfully', 'device' => $device], 201);
    }

    function getLatestPass($passTypeId, $serialNumber, Request $request)
    {

                Log::info('Apple Wallet pass refresh request', [
        'passTypeId' => $passTypeId,
        'serialNumber' => $serialNumber,
        'headers' => $request->headers->all(),
        'ip' => $request->ip(),
        'auth_header' => $request->header('Authorization'),
    ]);

        $deviceToken = $request->header('Authorization');


        $service = new AppleWalletService();
        $user = LoyaltyCustomer::where('qr_code', $serialNumber)->get()->first();

        $pass =  $service->updateLoyaltyCard($user);

        return response($pass, 200, [
            'Content-Type' => 'application/vnd.apple.pkpass',
            'Content-Disposition' => 'attachment; filename="loyalty_card.pkpass"',
        ]);

    }

    function getUpdatedPasses($deviceLibraryIdentifier, $passTypeIdentifier, Request $request)
    {
        $device = AppleDevices::where('device_id', $deviceLibraryIdentifier)->first();

        Log::info('Get updated passes called', [
            'deviceLibraryIdentifier' => $deviceLibraryIdentifier,
            'passTypeIdentifier' => $passTypeIdentifier,
            'query' => $request->query(),
        ]);

        if (!$device) {
            return response()->noContent(); // 204
        }
        
        return response()->json([
            'serialNumbers' => [$device->serial_number],
            'lastUpdated' => now()->toIso8601String(),
        ],200);
    }

}
