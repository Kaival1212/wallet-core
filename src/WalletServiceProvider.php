<?php

namespace KN\WalletCore;

use App\Livewire\AddToWalletForm;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class WalletServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'walletcore');
        
        Livewire::component('add-to-wallet-form', AddToWalletForm::class);
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
