<div class="min-h-screen w-64 bg-gray-800 text-white flex flex-col">
    <div class="p-4 border-b border-gray-700">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <x-application-logo class="block h-9 w-auto fill-current text-white" />
            <span class="ml-3 text-xl font-semibold">{{ config('app.name') }}</span>
        </a>
    </div>
    
    <div class="flex-1 overflow-y-auto py-4">
        <nav>
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('Dashboard') }}
            </x-sidebar-link>
            
            <div x-data="{ open: false }" class="mt-2">
                <button @click="open = !open" class="w-full flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>{{ __('Sales') }}</span>
                    <svg class="ml-auto h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="pl-6 mt-1 space-y-1">
                    <x-sidebar-sublink :href="route('sales.index')" :active="request()->routeIs('sales.index')">
                        {{ __('All Sales') }}
                    </x-sidebar-sublink>
                    <x-sidebar-sublink :href="route('sales.create')" :active="request()->routeIs('sales.create')">
                        {{ __('New Sale') }}
                    </x-sidebar-sublink>
                    <x-sidebar-sublink :href="route('sales.trash')" :active="request()->routeIs('sales.trash')">
                        {{ __('Trash') }}
                    </x-sidebar-sublink>
                </div>
            </div>
            
            <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m-8-4l8 4m8 0l-8 4m8-4v10a1 1 0 01-1 1h-6a1 1 0 01-1-1v-6a1 1 0 011-1h6a1 1 0 011 1v6z"></path>
                </svg>
                {{ __('Products') }}
            </x-sidebar-link>
            
            <x-sidebar-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                {{ __('Customers') }}
            </x-sidebar-link>
        </nav>
    </div>
</div>