<x-app-layout>
    <x-slot name="title">Search People</x-slot>
    
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Search Form -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-lg rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('search') }}" class="flex space-x-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="query" 
                            value="{{ $query }}" 
                            placeholder="Search for people..." 
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm"
                            autofocus
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg"
                    >
                        Search
                    </button>
                </form>
            </div>

            <!-- Search Results -->
            @if($query && $users->count() > 0)
                <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-lg rounded-lg p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Search Results for "{{ $query }}"</h3>
                    <div class="space-y-4">
                        @foreach($users as $user)
                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <!-- Profile Picture -->
                                    <div class="flex-shrink-0 w-12 h-12 rounded-full overflow-hidden">
                                        @if($user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                                 alt="{{ $user->first_name }}'s profile picture"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                                <span class="text-white font-semibold text-lg">
                                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- User Info -->
                                    <div>
                                        <a href="{{ route('profile.show', $user) }}" class="block">
                                            <h4 class="text-lg font-semibold text-white hover:text-white/80 transition-colors">
                                                {{ $user->username }}
                                            </h4>
                                            <p class="text-white/60">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </p>
                                            @if($user->occupation)
                                                <p class="text-white/50 text-sm">{{ $user->occupation }}</p>
                                            @endif
                                        </a>
                                    </div>
                                </div>

                                <!-- Follow/Unfollow Button -->
                                <div>
                                    @if(Auth::user()->isFollowing($user))
                                        <div class="flex space-x-2">
                                            <span class="px-4 py-2 bg-green-600/20 text-green-300 font-medium rounded-lg text-sm">
                                                Following
                                            </span>
                                            <form method="POST" action="{{ route('unfollow', $user) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-300 hover:text-red-200 font-medium rounded-lg transition-all duration-200 text-sm">
                                                    Unfollow
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('follow', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                                                Follow
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($query && $users->count() === 0)
                <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-lg rounded-lg p-6 text-center">
                    <p class="text-white/60">No users found for "{{ $query }}"</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
