<x-app-layout>
    <x-slot name="title">Posts tagged with #{{ $tag->name }}</x-slot>
    
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            
            <!-- Tag Header -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-lg rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">
                            <span class="inline-block px-3 py-1 rounded-full text-2xl"
                                  style="background-color: {{ $tag->color }}30; color: {{ $tag->color }};">
                                #{{ $tag->name }}
                            </span>
                        </h1>
                        @if($tag->description)
                            <p class="text-white/80 mb-2">{{ $tag->description }}</p>
                        @endif
                        <p class="text-white/60 text-sm">{{ $tag->usage_count }} post(s)</p>
                    </div>
                    <a href="{{ route('home') }}" 
                       class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Posts -->
            <div class="space-y-6">
                @forelse ($posts as $post)
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-lg rounded-lg p-6">
                        
                        <!-- Post Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full overflow-hidden">
                                    @if($post->user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $post->user->profile_photo_path) }}" 
                                             alt="{{ $post->user->first_name }}'s profile picture"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                {{ strtoupper(substr($post->user->first_name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-white font-medium">
                                        <a href="{{ route('profile.show', $post->user) }}" class="hover:text-white/80">
                                            {{ $post->user->username }}
                                        </a>
                                    </h3>
                                    <p class="text-white/60 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Post Content -->
                        <div class="mb-4">
                            <p class="text-white text-sm">{{ $post->content }}</p>
                        </div>

                        <!-- Post Image -->
                        @if($post->image_path)
                            <div class="mb-4">
                                <img class="w-full h-auto rounded-lg" src="{{ asset('storage/' . $post->image_path) }}" alt="Post image">
                            </div>
                        @endif

                        <!-- Post Tags -->
                        @if($post->tags->count() > 0)
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach($post->tags as $postTag)
                                    <a href="{{ route('tags.posts', $postTag) }}" 
                                       class="inline-block px-2 py-1 text-xs rounded-full transition-colors {{ $postTag->id === $tag->id ? 'font-semibold' : '' }}"
                                       style="background-color: {{ $postTag->color }}{{ $postTag->id === $tag->id ? '40' : '20' }}; color: {{ $postTag->color }};">
                                        #{{ $postTag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Post Stats -->
                        <div class="flex items-center space-x-6 text-white/60 text-sm">
                            <span>{{ $post->likes_count }} likes</span>
                            <span>{{ $post->comments_count }} comments</span>
                        </div>
                        
                    </div>
                @empty
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-lg rounded-lg p-12 text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">No posts found</h3>
                        <p class="text-white/60">No posts have been tagged with #{{ $tag->name }} yet.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
