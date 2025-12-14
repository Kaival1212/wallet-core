<?php

namespace App\Services;

use Byte5\PassGenerator;
use KN\WalletCore\Models\LoyaltyCustomer;
use Pushok\AuthProvider\Token;
use Illuminate\Support\Facades\Log;


class AppleWalletService
{
    protected $certificate;
    protected $certificatePassword;
    protected $teamId;
    protected $passTypeIdentifier;
    protected $organizationName;

    public function __construct()
    {
        $this->certificate = storage_path('app/apple-wallet/Certificates.p12');
        $this->certificatePassword = 'KNConsulting30@7@4';
        $this->teamId = 'R4C59D8WNJ';
        $this->passTypeIdentifier = 'pass.uk.knconsulting.copperchimney';
        $this->organizationName = 'Copper Chimney';
    }

    public function createLoyaltyCard($user)
    {
        $pass = new PassGenerator($user->qr_code . '-' . time());

        // Enhanced pass definition with better visual hierarchy
        $passDefinition = [
            'description' => 'Copper Chimney Loyalty Card',
            'formatVersion' => 1,
            'organizationName' => $this->organizationName,
            'passTypeIdentifier' => $this->passTypeIdentifier,
            'serialNumber' => $user->qr_code,
            'teamIdentifier' => $this->teamId,

            // Refined color scheme - deep burgundy with metallic copper accents
            'backgroundColor' => 'rgb(10, 11, 8)',
            'foregroundColor' => 'rgb(242, 198, 145)',
            'labelColor' => 'rgb(185, 142, 98)',

            'logoText' => 'COPPER CHIMNEY',
            'suppressStripShine' => true,

            'storeCard' => [
                'primaryFields' => [
                    [
                        'key' => 'points',
                        'label' => 'LOYALTY POINTS',
                        'value' => $user->loyalty_points ?? 0,
                        'textAlignment' => 'PKTextAlignmentCenter',
                    ],
                ],
                'secondaryFields' => [
                    [
                        'key' => 'member',
                        'label' => 'MEMBER',
                        'value' => $user->name,
                        'textAlignment' => 'PKTextAlignmentLeft',
                    ],
                    [
                        'key' => 'memberSince',
                        'label' => 'MEMBER SINCE',
                        'value' => $user->created_at ? $user->created_at->format('Y') : date('Y'),
                        'textAlignment' => 'PKTextAlignmentRight',
                    ],
                ],
                'auxiliaryFields' => [
                    [
                        'key' => 'memberId',
                        'label' => 'MEMBER ID',
                        'value' => str_pad($user->id, 8, '0', STR_PAD_LEFT),
                        'textAlignment' => 'PKTextAlignmentRight',
                    ],
                ],
                'backFields' => [
                    [
                        'key' => 'terms',
                        'label' => 'TERMS & CONDITIONS',
                        'value' => "Earn 1 point for every £1 spent. Points never expire. Present this card before payment to earn points. Valid at all Copper Chimney locations.",
                    ],
                    [
                        'key' => 'website',
                        'label' => 'WEBSITE',
                        'value' => 'www.copperchimney.co.uk',
                    ],
                    [
                        'key' => 'phone',
                        'label' => 'CONTACT',
                        'value' => '+44 (0) 20 1234 5678',
                    ],
                ],
            ],

            'barcode' => [
                'format' => 'PKBarcodeFormatQR',
                'message' => $user->qr_code,
                'messageEncoding' => 'iso-8859-1',
            ],

            'locations' => [
                [
                    'latitude' => 51.5074,
                    'longitude' => -0.1278,
                    'relevantText' => 'Welcome to Copper Chimney! Show this card to earn points.',
                ],
            ],

            'lastUpdated' => now()->toIso8601String(),

            'webServiceURL' => config('appleWalletPass.web_service_url'),
            'authenticationToken' => hash('sha256', $user->qr_code . $user->email),
        ];

        $pass->setPassDefinition($passDefinition);

        // Add all required assets
        $pass->addAsset(storage_path('app/private/apple-wallet/icon.png'));


        $pass->addAsset(storage_path('app/private/apple-wallet/logo.png'));

        $pass->addAsset(storage_path('app/private/apple-wallet/footer.png'), 'strip');
        $pass->addAsset(storage_path('app/private/apple-wallet/thumbnail.png'));

        // Create the pass
        $pkpass = $pass->create();

        return response($pkpass, 200, [
            'Content-Type' => 'application/vnd.apple.pkpass',
            'Content-Disposition' => 'attachment; filename="copper-chimney-loyalty.pkpass"',
            'Content-Length' => strlen($pkpass),
        ]);
    }


    /**
     * Update an existing pass (for when points change)
     */
    public function updateLoyaltyCard($user)
    {

        $pass = new PassGenerator($user->qr_code . '-' . time());

        // Enhanced pass definition with better visual hierarchy
        $passDefinition = [
            'description' => 'Copper Chimney Loyalty Card',
            'formatVersion' => 1,
            'organizationName' => $this->organizationName,
            'passTypeIdentifier' => $this->passTypeIdentifier,
            'serialNumber' => $user->qr_code ,
            'teamIdentifier' => $this->teamId,

            // Refined color scheme - deep burgundy with metallic copper accents
            'backgroundColor' => 'rgb(10, 11, 8)',
            'foregroundColor' => 'rgb(242, 198, 145)',
            'labelColor' => 'rgb(185, 142, 98)',

            'logoText' => 'COPPER CHIMNEY',
            'suppressStripShine' => true,

            'storeCard' => [
                'primaryFields' => [
                    [
                        'key' => 'points',
                        'label' => 'LOYALTY POINTS',
                        'value' => $user->loyalty_points ?? 0,
                        'textAlignment' => 'PKTextAlignmentCenter',
                    ],
                ],
                'secondaryFields' => [
                    [
                        'key' => 'member',
                        'label' => 'MEMBER',
                        'value' => $user->name,
                        'textAlignment' => 'PKTextAlignmentLeft',
                    ],
                    [
                        'key' => 'memberSince',
                        'label' => 'MEMBER SINCE',
                        'value' => $user->created_at ? $user->created_at->format('Y') : date('Y'),
                        'textAlignment' => 'PKTextAlignmentRight',
                    ],
                ],
                'auxiliaryFields' => [
                    [
                        'key' => 'memberId',
                        'label' => 'MEMBER ID',
                        'value' => str_pad($user->id, 8, '0', STR_PAD_LEFT),
                        'textAlignment' => 'PKTextAlignmentRight',
                    ],
                ],
                'backFields' => [
                    [
                        'key' => 'terms',
                        'label' => 'TERMS & CONDITIONS',
                        'value' => "Earn 1 point for every £1 spent. Points never expire. Present this card before payment to earn points. Valid at all Copper Chimney locations.",
                    ],
                    [
                        'key' => 'website',
                        'label' => 'WEBSITE',
                        'value' => 'www.copperchimney.co.uk',
                    ],
                    [
                        'key' => 'phone',
                        'label' => 'CONTACT',
                        'value' => '+44 (0) 20 1234 5678',
                    ],
                ],
            ],

            'barcode' => [
                'format' => 'PKBarcodeFormatQR',
                'message' => $user->qr_code,
                'messageEncoding' => 'iso-8859-1',
            ],

            'locations' => [
                [
                    'latitude' => 51.5074,
                    'longitude' => -0.1278,
                    'relevantText' => 'Welcome to Copper Chimney! Show this card to earn points.',
                ],
            ],

            'webServiceURL' => url(config('appleWalletPass.web_service_url')),
            'authenticationToken' => hash('sha256', $user->qr_code . $user->email . config('app.key')),
        ];

        $pass->setPassDefinition($passDefinition);

        $pass->addAsset(storage_path('app/private/apple-wallet/icon.png'));

        $pass->addAsset(storage_path('app/private/apple-wallet/logo.png'));

        $pass->addAsset(storage_path('app/private/apple-wallet/footer.png'), 'strip');
        $pass->addAsset(storage_path('app/private/apple-wallet/thumbnail.png'));

        // Create the pass
        $pkpass = $pass->create();

        return $pkpass;

    }


    public function updateLoyaltyPoints(LoyaltyCustomer $user){

        $device = $user->appleDevices()->first();
        $pushToken = $device->push_token;

        try{
            $authProvider = Token::create([
                'key_id' => config('appleWalletPass.apn.key_id'),
                'team_id' => config('appleWalletPass.apn.team_id'),
                'private_key_path' => config('appleWalletPass.apn.private_key_path'),
                'app_bundle_id' => config('appleWalletPass.apn.pass_type_identifier'),
            ]);

            $client = new \Pushok\Client($authProvider, true);
            $payload = \Pushok\Payload::create();

            $notification = new \Pushok\Notification($payload, $pushToken);
            $client->addNotification($notification);
            $responses = $client->push();
        } catch (\Exception $e){
            // Log the error or handle it as needed
            Log::error('Failed to send push notification: ' . $e->getMessage());

    }
    }
}
