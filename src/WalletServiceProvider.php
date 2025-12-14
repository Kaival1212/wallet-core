<?php

    namespace KN\WalletCore;

    use KN\WalletCore\Livewire\AddToWalletForm;
    use Illuminate\Support\ServiceProvider;
    use Livewire\Livewire;

    class WalletServiceProvider extends ServiceProvider
    {

        public function register()
        {
            $this->mergeConfigFrom(
                __DIR__.'/../config/walletcore.php',
                'walletcore'
            );
        }
        public function boot()
        {
            // Load routes
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

            $this->loadViewsFrom(__DIR__.'/../resources/views', 'walletcore');
            
            Livewire::component('walletcore.add-to-wallet-form', AddToWalletForm::class);
            // Load migrations
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            $this->publishes([
                __DIR__.'/../config/walletcore.php' => config_path('walletcore.php'),
            ], 'walletcore-config');
        }
    }
