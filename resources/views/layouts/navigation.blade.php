<nav x-data="{ open: false }" class="bkk-navbar">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="bkk-logo-container">
                        <div class="text-4xl">😊</div>
                        <div class="bkk-brand-text">
                            <div class="bkk-brand-main">Bank Tersenyum</div>
                            <div class="bkk-brand-sub">PT Tersenyum Abadi</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="bkk-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        🏠 Dashboard
                    </a>
                    <a href="{{ route('legal-cases.index') }}" class="bkk-nav-link {{ request()->routeIs('legal-cases.*') ? 'active' : '' }}">
                        ⚖️ Kasus Hukum
                    </a>
                    @if(in_array(auth()->user()->role, ['admin', 'manager']))
                        <a href="{{ route('case-types.index') }}" class="bkk-nav-link {{ request()->routeIs('case-types.*') ? 'active' : '' }}">
                            📋 Jenis Kasus
                        </a>
                    @endif
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('users.index') }}" class="bkk-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            👥 Pengguna
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="bkk-user-dropdown inline-flex items-center text-sm leading-4 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-yellow-300 hover:bg-blue-800 focus:outline-none focus:bg-blue-800 focus:text-yellow-300 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bkk-mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="bkk-mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }} block">
                🏠 Dashboard
            </a>
            <a href="{{ route('legal-cases.index') }}" class="bkk-mobile-link {{ request()->routeIs('legal-cases.*') ? 'active' : '' }} block">
                ⚖️ Kasus Hukum
            </a>
            @if(in_array(auth()->user()->role, ['admin', 'manager']))
                <a href="{{ route('case-types.index') }}" class="bkk-mobile-link {{ request()->routeIs('case-types.*') ? 'active' : '' }} block">
                    📋 Jenis Kasus
                </a>
            @endif
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.index') }}" class="bkk-mobile-link {{ request()->routeIs('users.*') ? 'active' : '' }} block">
                    👥 Pengguna
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-yellow-400">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-yellow-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
