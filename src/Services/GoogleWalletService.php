<?php

namespace KN\WalletCore\Services;

use Google\Client;
use Google\Service\Walletobjects\{LoyaltyClass,TextModuleData, LoyaltyObject, Image, Barcode, LoyaltyPoints, LoyaltyPointsBalance};
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class GoogleWalletService
{
    protected $credentialsPath;

    public function __construct()
    {
        $this->credentialsPath = storage_path('app/google-wallet/service-account.json');
    }

    protected function getClient()
    {
        $client = new Client();
        $client->setAuthConfig($this->credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/wallet_object.issuer');
        return $client;
    }

    protected function generateJwt($objectId){
                // Create JWT for Save to Google Wallet
        $claims = [
            'iss' => json_decode(file_get_contents($this->credentialsPath), true)['client_email'],
            'aud' => 'google',
            'typ' => 'savetowallet',
            'payload' => [
                'loyaltyObjects' => [
                    ['id' => $objectId]
                ]
            ]
        ];

        $creds = json_decode(file_get_contents($this->credentialsPath), true);

        $privateKey = $creds['private_key']; // The PEM RSA key

        $jwt = JWT::encode(
            $claims,
            $privateKey,
            'RS256'
        );

        return $jwt;
    }

    public function createLoyaltyCardForCustomer($user){
        $client = $this->getClient();
        $service = new \Google\Service\Walletobjects($client);

        $issuerId = config('googleWalletPass.GOOGLE_WALLET_ISSUER_ID');
        $classId = "{$issuerId}.Copper-Chimney-Rewards";
        $objectId = "{$issuerId}.user-{$user->qr_code}";
        $jwt = $this->generateJwt($objectId);

        $loyaltyObject = new LoyaltyObject([
            'id' => $objectId,
            'classId' => $classId,
            'state' => 'active',
            'accountName' => $user->name,
            'accountId' => "USER-{$user->qr_code}",

            'textModulesData' => [
            new TextModuleData([
                'header' => 'Member Name',
                'body' => $user->name,
                'id' => 'member_name'
            ])
            ],
            'barcode' => new Barcode([
                'type' => 'qrCode',
                'value' => $user->qr_code,
                'alternateText' => 'Scan to redeem',
            ]),
            'loyaltyPoints' => new LoyaltyPoints([
                'balance' => new LoyaltyPointsBalance(['int' => $user->loyalty_points ?? 0]),
                'label' => 'Points',
            ]),
        ]);


            $service->loyaltyobject->insert($loyaltyObject);


        return "https://pay.google.com/gp/v/save/{$jwt}";
    }

    public function updateLoyaltyPoints($user){
        $client = $this->getClient();
        $service = new \Google\Service\Walletobjects($client);

        $issuerId = '3388000000023039893'; // Replace with actual issuer ID
        $objectId = "{$issuerId}.user-{$user->qr_code}";
        $loyaltyObject = $service->loyaltyobject->get($objectId);
        $loyaltyObject->setLoyaltyPoints(new LoyaltyPoints([
            'balance' => new LoyaltyPointsBalance(['int' => $user->loyalty_points ?? 0]),
            'label' => 'Points',
        ]));
        $service->loyaltyobject->update($objectId, $loyaltyObject);

    }

}
