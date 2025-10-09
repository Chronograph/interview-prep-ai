<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center">
                                <span class="text-white font-bold text-lg">H</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900 hidden sm:block">
                                {{ config('app.name') }}
                            </span>
                        </div>
                    </a>
                    <a href="https://www.figma.com/make/lvbFvKhkR9rcaZaX2ZmDFJ/HireCamp-Dashboard-modals?node-id=0-1&p=f&t=UKAriqEKTXMndVU3-0&fullscreen=1"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="ml-4 text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd" />
                        </svg>
                        Figma Design
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:ml-10 sm:flex">
                    @php
                        $navLinks = [
                            [
                                'route' => 'dashboard',
                                'label' => 'Dashboard',
                                'activeClass' => 'text-blue-600',
                                'inactiveClass' => 'text-gray-600 hover:text-gray-900',
                            ],
                            [
                                'route' => 'practice.mock-interviews',
                                'label' => 'Practice',
                                'activeClass' => '',
                                'inactiveClass' => 'text-gray-600 hover:text-gray-900',
                            ],
                            [
                                'route' => 'analytics.applications.index',
                                'label' => 'Job Applications',
                                'activeClass' => '',
                                'inactiveClass' => 'text-gray-600 hover:text-gray-900',
                            ],
                            [
                                'route' => 'cheat-sheets.index',
                                'label' => 'Cheat Sheets',
                                'activeClass' => '',
                                'inactiveClass' => 'text-gray-600 hover:text-gray-900',
                            ],
                            [
                                'route' => 'resumes.index',
                                'label' => 'Resume Builder',
                                'activeClass' => 'text-blue-600 hover:text-blue-700',
                                'inactiveClass' => 'text-gray-600 hover:text-gray-900',
                            ],
                            [
                                'route' => 'teams.index',
                                'label' => 'Teams',
                                'activeClass' => 'text-blue-600 hover:text-blue-700',
                                'inactiveClass' => 'text-gray-600 hover:text-gray-900',
                            ],
                            [
                                'route' => 'billing.index',
                                'label' => 'Billing',
                                'activeClass' => 'text-blue-600 hover:text-blue-700',
                                'inactiveClass' => 'text-gray-600 hover:text-gray-900',
                            ],
                        ];
                    @endphp
                    @foreach($navLinks as $link)
                        <a href="{{ route($link['route']) }}"
                           class="px-3 py-2 text-sm font-medium
                                {{ request()->routeIs($link['route']) ? ($link['activeClass'] ?: $link['inactiveClass']) : $link['inactiveClass'] }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>



            <!-- User Profile Icon -->
            <div class="flex items-center">
                <x-dropdown width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <div class="px-4 py-2 text-sm text-gray-700">
                        <div class="font-medium">{{ Auth::user()->name }}</div>
                        <div class="text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <x-dropdown.item separator />
                    <x-dropdown.item
                        icon="user"
                        label="{{ __('Profile') }}"
                        :href="route('profile.edit')"
                    />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown.item
                            icon="arrow-right-on-rectangle"
                            label="{{ __('Log Out') }}"
                            :href="route('logout')"
                            @click.prevent="event.target.closest('form').submit();"
                        />
                    </form>
                </x-dropdown>
            </div>

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
        <div class="pt-2 pb-3 space-y-1">
            @php
                $navLinks = [
                    [
                        'route' => 'dashboard',
                        'label' => 'Dashboard',
                        'activeClass' => 'text-blue-600 bg-blue-50',
                        'defaultClass' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                    ],
                    [
                        'route' => 'practice.mock-interviews',
                        'label' => 'Practice',
                        'activeClass' => '',
                        'defaultClass' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                    ],
                    [
                        'route' => 'analytics.applications.index',
                        'label' => 'Job Applications',
                        'activeClass' => '',
                        'defaultClass' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                    ],
                    [
                        'route' => 'cheat-sheets.index',
                        'label' => 'Cheat Sheets',
                        'activeClass' => '',
                        'defaultClass' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                    ],
                    [
                        'route' => 'resumes.index',
                        'label' => 'Resume Builder',
                        'activeClass' => 'text-blue-600 hover:text-blue-700 hover:bg-blue-50',
                        'defaultClass' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                    ],
                    [
                        'route' => 'teams.index',
                        'label' => 'Teams',
                        'activeClass' => 'text-blue-600 hover:text-blue-700 hover:bg-blue-50',
                        'defaultClass' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                    ],
                    [
                        'route' => 'billing.index',
                        'label' => 'Billing',
                        'activeClass' => 'text-blue-600 hover:text-blue-700 hover:bg-blue-50',
                        'defaultClass' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                    ],
                ];
            @endphp
            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="block px-3 py-2 text-base font-medium
                        {{ request()->routeIs($link['route']) ? $link['activeClass'] : $link['defaultClass'] }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
