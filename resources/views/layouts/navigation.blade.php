<nav x-data="{ open: false }" class="bg-gradient-to-r from-gray-300 to-gray-100 border-b border-gray-100">
    <!-- Primary Navigation Menu -->

    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('index') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('hotels.index') }}" active="{{ request()->routeIs('hotels.index') }}">
                        Отели
                    </x-nav-link>
                </div>
                @auth
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('bookings.index') }}" active="{{ request()->routeIs('bookings.index') }}">
                            Мои бронирования
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('users.feedbacks.index', ['user' => auth()->user()]) }}" active="{{ request()->routeIs('users.feedbacks.index', ['user' => auth()->user()]) }}">
                            Мои отзывы
                        </x-nav-link>
                    </div>
                @endauth
            </div>

            @guest
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('login') }}" active="{{ request()->routeIs('login') }}">
                        {{ __('Log in') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('register') }}" active="{{ request()->routeIs('register') }}">
                        {{ __('Register') }}
                    </x-nav-link>
                </div>
            @endguest

            <!-- Settings Dropdown -->
            @if(auth()->check())
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown align="right" width="64">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->name . ' (' . implode(', ', Auth::user()->roles->pluck('description')->toArray()) . ')'}}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Authentication -->
                            @if(isAdmin(Auth::user()) || isManager(Auth::user()))
                                <x-dropdown-link :href="route('admin.bookings.index')">
                                    Управление бронированиями
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.hotels.index')">
                                    Управление отелями
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.feedbacks.index')">
                                    Управление отзывами
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.facilities.index')">
                                    Удобства
                                </x-dropdown-link>
                            @endif

                            @if(isAdmin(Auth::user()))
                                <x-dropdown-link :href="route('admin.users.index')">
                                    Пользователи
                                </x-dropdown-link>
                            @endif

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
            @endif

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pb-1 space-y-1">
            <x-responsive-nav-link href="{{ route('hotels.index') }}" active="{{ request()->routeIs('hotels.index') }}">
                Отели
            </x-responsive-nav-link>
        </div>
        @auth
            <div class="pb-1 space-y-1">
                <x-responsive-nav-link href="{{ route('bookings.index') }}" active="{{ request()->routeIs('bookings.index') }}">
                    Мои бронирования
                </x-responsive-nav-link>
            </div>
            <div class="pb-1 space-y-1">
                <x-responsive-nav-link href="{{ route('users.feedbacks.index', ['user' => auth()->user()]) }}" active="{{ request()->routeIs('users.feedbacks.index', ['user' => auth()->user()]) }}">
                    Мои отзывы
                </x-responsive-nav-link>
            </div>

            @if(isAdmin(Auth::user()) || isManager(Auth::user()))
                <div class="pb-1 space-y-1">
                    <x-responsive-nav-link href="{{ route('admin.bookings.index') }}" active="{{ request()->routeIs('admin.bookings.index') }}">
                        Управление бронированиями
                    </x-responsive-nav-link>
                </div>
                <div class="pb-1 space-y-1">
                    <x-responsive-nav-link href="{{ route('admin.hotels.index') }}" active="{{ request()->routeIs('admin.hotels.index') }}">
                        Управление отелями
                    </x-responsive-nav-link>
                </div>
                <div class="pb-1 space-y-1">
                    <x-responsive-nav-link href="{{ route('admin.feedbacks.index') }}" active="{{ request()->routeIs('admin.feedbacks.index') }}">
                        Управление отзывами
                    </x-responsive-nav-link>
                </div>
                <div class="pb-1 space-y-1">
                    <x-responsive-nav-link href="{{ route('admin.facilities.index') }}" active="{{ request()->routeIs('admin.facilities.index') }}">
                        Удобства
                    </x-responsive-nav-link>
                </div>
            @endif

            @if(isAdmin(Auth::user()))
                <div class="pb-1 space-y-1">
                    <x-responsive-nav-link href="{{ route('admin.users.index') }}" active="{{ request()->routeIs('admin.users.index') }}">
                        Пользователи
                    </x-responsive-nav-link>
                </div>
            @endif
        @endauth

        <!-- Responsive Settings Options -->
        @if(auth()->check())
            <div class="pt-4 pb-1 bg-gray-50">
                <div class="px-4">
                    <div>{{ Auth::user()->name . ' (' . implode(', ', Auth::user()->roles->pluck('description')->toArray()) . ')'}}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
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
        @endif
    </div>
</nav>
