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
                __DIR__.'/../config/AppleWalletService.php',
                'AppleWalletService'
            );

            $this->mergeConfigFrom(
                __DIR__.'/../config/GoogleWalletService.php',
                'GoogleWalletService'
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

            // Publish config files
            $this->publishes([
                __DIR__.'/../config/AppleWalletService.php' => config_path('AppleWalletService.php'),
                __DIR__.'/../config/GoogleWalletService.php' => config_path('GoogleWalletService.php'),
            ], 'walletcore-config');
        }
    }
