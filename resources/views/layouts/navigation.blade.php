<nav x-data="{ open: false }"
    class="fixed left-0 top-0 h-full w-64 bg-white/10 backdrop-blur-md border-0 border-white/20 shadow-lg z-40 transform transition-transform duration-300 lg:translate-x-0"
    :class="{ '-translate-x-full': !open, 'translate-x-0': open }" @click.away="open = false">

    <!-- Overlay for mobile -->
    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden"
        @click="open = false"></div>

    <div class="flex flex-col h-full">
        <!-- Logo Section -->
        <div class="px-4 justify-center py-6 ">
            <a class="px-4 w-auto flex items-center" href="{{ route('home') }}">
                {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12"> --}}
                <h2 class="font-birthstone font-bold text-3xl text-white">Cat Media</h2>
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1">
            <nav class="space-y-2 px-4">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Home
                </x-nav-link>

                <!-- Create Post -->
                <x-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')">
                    <div class="w-5 h-5 mr-3">
                        <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill="#ffffff" fill-rule="evenodd" d="M10 3a7 7 0 100 14 7 7 0 000-14zm-9 7a9 9 0 1118 0 9 9 0 01-18 0zm14 .069a1 1 0 01-1 1h-2.931V14a1 1 0 11-2 0v-2.931H6a1 1 0 110-2h3.069V6a1 1 0 112 0v3.069H14a1 1 0 011 1z"></path> </g></svg>
                    </div>
                    Create Post
                </x-nav-link>

                <!-- Search -->
                <x-nav-link :href="route('search')" :active="request()->routeIs('search')">
                    <div class="w-5 h-5 mr-3">
                       <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </div>
                    Search People
                </x-nav-link>


                <x-nav-link :href="route('profile.index')" :active="request()->routeIs('profile')" >
                    <div class="flex-shrink-0 w-5 h-5 rounded-full mr-3 overflow-hidden">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                 alt="{{ Auth::user()->first_name }}'s profile picture"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <span class="text-white font-semibold text-xs">
                                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 text-left">
                        <div class="truncate text-white text-md font-medium overflow-ellipsis">{{ Auth::user()->first_name }} {{ Auth::user()->middle_name }} {{ Auth::user()->last_name }}
                        </div>
                    </div>
                </x-nav-link>
            </nav>
        </div>

        <!-- Logout Button Section -->
        <div class="border-t border-white/20 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    class="flex items-center w-full px-4 py-3 text-sm font-medium text-white/80 rounded-lg hover:bg-red-500/20 hover:text-red-300 focus:outline-none transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Mobile Menu Button (for responsive design) -->
<div class="lg:hidden fixed top-4 left-4 z-50">
    <button @click="open = ! open"
        class="inline-flex items-center justify-center p-2 rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out shadow-lg">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
