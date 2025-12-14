<?php

namespace App\Livewire;

use App\Models\LoyaltyCustomer;
use App\Services\AppleWalletService;
use Livewire\Component;
use App\Services\GoogleWalletService;

class AddToWalletForm extends Component
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
