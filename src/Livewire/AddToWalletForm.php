<?php

namespace KN\WalletCore\Livewire;

use KN\WalletCore\Models\LoyaltyCustomer;
use KN\WalletCore\Services\AppleWalletService;
use Livewire\Component as BaseComponent;
use KN\WalletCore\Services\GoogleWalletService;


class AddToWalletForm extends BaseComponent
{

    public $name;
    public $email;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
    ];


    public function addGoogle()
    {

        $this->validate();

        $googleWalletService = new GoogleWalletService();
        $user = new LoyaltyCustomer([
            'name' => $this->name,
            'email' => $this->email,
            'wallet_type' => 'google',
            'loyalty_points' => 0,
        ]);
        $user->save();

        session()->flash('message', 'Google Wallet pass created!');
        return redirect()->route('google.wallet', ['user' => $user->id]);
    }

    public function addApple()
    {
        $this->validate();

        $user = new LoyaltyCustomer([
            'name' => $this->name,
            'email' => $this->email,
            'wallet_type' => 'apple',
            'loyalty_points' => 0,
        ]);
        $user->save();

        session()->flash('message', 'Apple Wallet pass created!');
        return redirect()->route('apple.pass', ['user' => $user->id]);
    }

    public function render()
    {
        return view('livewire.add-to-wallet-form');
    }
}
