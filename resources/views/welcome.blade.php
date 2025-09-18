<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>@yield('title')</title>
</head>
<body class="h-screen w-screen flex flex-col items-between justify-center">
    <div class=" h-full min-h-[70vh] w-full flex flex-col items-center justify-center bg-gradient-to-b from-gray-50 to-white px-4">
        <div class="w-full max-w-2xl text-center">


            <h1 class="text-3xl md:text-5xl font-semibold tracking-tight text-gray-700 flex items-center justify-center gap-2">
                <x-heroicon-s-academic-cap class="w-8 h-8 md:w-12 md:h-12 text-gray-700" />
                Bienvenue sur CloudClass
            </h1>
            <p class="mt-3 text-gray-600 md:text-lg">Apprenez, collaborez et progressez en toute simplicité.</p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                <a href="{{route('dashboard')}}" class="inline-flex items-center justify-center rounded-full px-6 py-3 bg-gray-900 text-white whitespace-nowrap shadow-sm hover:bg-gray-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-black transition">
                    Acceuil
                </a>

                @else
                <a href="{{route('login')}}" class="inline-flex items-center justify-center rounded-full px-6 py-3 bg-black text-white whitespace-nowrap shadow-sm hover:bg-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-black transition">
                    Se connecter
                </a>
                <a href="{{route('register')}}" class="inline-flex items-center justify-center rounded-full px-6 py-3 border border-gray-300 text-gray-900 bg-white whitespace-nowrap hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-gray-400 transition">
                    S'inscrire
                </a>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>
