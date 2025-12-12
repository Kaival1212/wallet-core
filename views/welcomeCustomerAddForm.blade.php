<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Laravel</title>

    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
      href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
      rel="stylesheet"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js']) @fluxAppearance
    @livewireStyles @livewireScripts
  </head>
  <body class="min-h-screen relative flex items-center justify-center">
    <!-- Background -->
    <div
      class="absolute inset-0 bg-cover bg-center"
      style="background-image: url('{{ asset('storage/bg.jpg') }}');"
    ></div>

    <!-- Dark gradient overlay -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <!-- Main Content -->
    <div class="relative max-w-3xl w-full text-center px-8 py-10">
      <!-- Brand emblem -->
      <div
        class="text-[#D4AF37] text-lg tracking-[0.25em] mb-3 font-semibold opacity-90"
      >
        COPPER CHIMNEY
      </div>

      <!-- Title -->
      <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">
        Copper Chimney Loyalty Program
      </h1>

      <!-- Description -->
      <p class="mt-6 text-gray-200 text-xl leading-relaxed max-w-2xl mx-auto">
        Since 1972, Copper Chimney has brought the legendary flavours,
        ingredients, and culinary craftsmanship of Undivided North India. Join
        our loyalty program and enjoy exclusive rewards and benefits.
      </p>
      <livewire:add-to-wallet-form />
    </div>
  </body>
</html>
