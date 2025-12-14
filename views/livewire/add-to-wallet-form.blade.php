<div
    class="w-full max-w-lg mx-auto mt-10 bg-black/40 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-white/10"
>
    {{-- Loading indicator --}}
    <div
        wire:loading
        wire:target="addGoogle"
        class="mb-4 p-3 bg-yellow-200 text-yellow-900 rounded-xl"
    >
        Processing...
    </div>

    @if (session()->has('message'))
    <div
        class="mb-6 p-4 text-green-900 bg-green-100/80 border border-green-300 rounded-xl text-center font-semibold"
    >
        {{ session("message") }}
    </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-8">
        {{-- Full Name --}}
        <div class="text-left">
            <label class="block text-sm text-gray-300 font-medium mb-2"
                >Full Name</label
            >
            <input
                type="text"
                wire:model.defer="name"
                class="w-full px-5 py-3.5 rounded-2xl bg-white/10 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/60"
                placeholder="John Doe"
            />
            @error('name')
            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="text-left">
            <label class="block text-sm text-gray-300 font-medium mb-2"
                >Email Address</label
            >
            <input
                type="email"
                wire:model.defer="email"
                class="w-full px-5 py-3.5 rounded-2xl bg-white/10 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/60"
                placeholder="you@example.com"
            />
            @error('email')
            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        {{-- Buttons --}}
        <div class="space-y-5 flex flex-col items-center justify-center mt-4">
            {{-- Google Wallet --}}
            <button
                wire:click.prevent="addGoogle"
                class="transition transform hover:scale-105"
            >
                <img
                    src="{{
                        asset(
                            'storage/enUS_add_to_google_wallet_add-wallet-badge.svg'
                        )
                    }}"
                    alt="Google Wallet Logo"
                    class="w-64"
                />
            </button>

            {{-- Apple Wallet --}}
            <button
                wire:click.prevent="addApple"
                class="transition transform hover:scale-105"
            >
                <img
                    src="{{
                        asset(
                            'storage/US-UK_Add_to_Apple_Wallet_RGB_101421.svg'
                        )
                    }}"
                    alt="Apple Wallet Logo"
                    class="w-64"
                />
            </button>
        </div>
    </form>
</div>
