<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ERP') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            document.documentElement.classList.add('dark');
        </script>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
           
           .bg-cyan-600 {
               background-color: #0891b2 !important; /* Tailwind cyan-600 hex */
           }
           .text-white {
               color: #fff !important;
           }
                      
                              </style>
    </head>
    <body class="h-full bg-gray-100 dark:bg-gray-900 font-sans antialiased ">
        <div class="min-h-screen flex">
            <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 bg-cyan-600">
                <div class="flex items-center h-16 px-4 ">
                    <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-900 dark:text-gray-100">ERP Admin</a>
                </div>
               
                @include('layouts.sidebar')
              
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-3 py-2 rounded bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">Logout</button>
                    </form>
                </div>
            </div>

            <div class="md:pl-64 flex-1 flex flex-col">
                <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-cyan-600 md:px-6">
                    <button type="button" class="px-4 text-gray-500 focus:outline-none md:hidden" @click="sidebarOpen = !sidebarOpen">
                        <span class="sr-only">Open sidebar</span>
                        <!-- icon -->
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex-1 flex items-center justify-between px-4 md:px-0 ">
                        <div class="text-gray-900 dark:text-gray-100 font-semibold">
                            {{ $header ?? '' }}
                        </div>
                        <div class="flex items-center space-x-3 text-gray-700 dark:text-gray-200">
                            <span>{{ auth()->user()->name ?? '' }}</span>
                        </div>
                    </div>
                </div>

                <main class="p-4 md:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
    </html>


