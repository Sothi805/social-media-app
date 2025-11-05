<x-app-layout>
    <div class="py-6 w-5xl mx-auto">
        <div class="flex flex-col space-y-6">
            <div class="flex">
                <div class="w-1/3 flex items-center justify-center">
                    <div class="flex-shrink-0 w-48 h-48 rounded-full mr-3 overflow-hidden">
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                 alt="{{ $user->first_name }}'s profile picture"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <span class="text-white font-semibold text-7xl">
                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="w-2/3 flex flex-col space-y-4">
                    <div class="flex items-center mb-4 space-x-6">
                        <h2 class="text-2xl font-bold text-white">{{ $user->username }}</h2>
                        <!-- Follow/Unfollow Buttons -->
                        @if($isFollowing)
                            <div class="flex space-x-3">
                                <span class="py-2 px-4 bg-green-600/20 text-green-300 font-semibold rounded-lg">
                                    Following
                                </span>
                                <form method="POST" action="{{ route('unfollow', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="py-2 px-4 bg-red-600/20 hover:bg-red-600/40 text-red-300 hover:text-red-200 font-semibold rounded-lg transition-all duration-200">
                                        Unfollow
                                    </button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('follow', $user) }}" class="inline">
                                @csrf
                                <button type="submit" class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200">
                                    Follow
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="flex items-center space-x-6">
                        <h3 class="text-lg font-semibold text-white">{{ $postsCount }}<span class="ml-1 font-medium text-white/50">posts</span></h3>
                        <h3 class="text-lg font-semibold text-white">{{ $followersCount }}<span class="ml-1 font-medium text-white/50">followers</span></h3>
                        <h3 class="text-lg font-semibold text-white">{{ $followingCount }}<span class="ml-1 font-medium text-white/50">following</span></h3>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</h3>
                        @if($user->occupation)
                            <h3 class="text-md font-medium text-white/50">{{ $user->occupation }}</h3>
                        @endif
                    </div>
                    @if($user->bio)
                        <h3 class="text-lg font-semibold text-white">{{ $user->bio }}</h3>
                    @endif
                </div>
            </div>
            
            <div class="flex justify-around border-b border-white/20 pb-4 px-8">
                <!-- Tab icons can be added here if needed -->
            </div>
            <!-- Posts Grid -->
            <div class="posts-content" id="posts-grid">
                @if($posts->count() > 0)
                    <div class="grid grid-cols-3 gap-1">
                        @foreach($posts as $post)
                            <div class="relative group aspect-square">
                                @if($post->image_path)
                                    <img src="{{ asset('storage/' . $post->image_path) }}" 
                                         alt="Post by {{ $post->user->username }}"
                                         class="w-full h-full object-cover cursor-pointer">
                                @else
                                    <!-- Text-only post display -->
                                    <div class="w-full h-full bg-white/10 backdrop-blur-sm flex items-center justify-center p-4 cursor-pointer">
                                        <p class="text-white text-sm text-center line-clamp-6">{{ Str::limit($post->content, 100) }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Share Photos</h3>
                        <p class="text-white/60 mb-4">When this user shares photos, they will appear here.</p>
                    </div>
                @endif
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
