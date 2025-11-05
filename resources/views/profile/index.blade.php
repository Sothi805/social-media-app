<x-app-layout>
    <div class="py-6 w-5xl mx-auto">
        <div class="flex flex-col space-y-6">
            <div class="flex">
                <div class="w-1/3 flex items-center justify-center">
                    <div class="flex-shrink-0 w-48 h-48 rounded-full mr-3 overflow-hidden">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                 alt="{{ Auth::user()->first_name }}'s profile picture"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <span class="text-white font-semibold text-7xl">
                                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="w-2/3 flex flex-col space-y-4">
                    <div class="flex items-center mb-4 space-x-6">
                        <h2 class="text-2xl font-bold text-white">{{ Auth::user()->username }}</h2>
                        <a href="{{ route('profile.edit') }}"
                            class="py-2 px-4 cursor-pointer bg-white/10 text-white font-semibold rounded-lg hover:bg-white/25 transition-all duration-200">
                            Edit Profile
                        </a>
                    </div>
                    <div class="flex items-center space-x-6">
                        <h3 class="text-lg font-semibold text-white">{{ $postsCount }}<span class="ml-1 font-medium text-white/50">posts</span></h3>
                        <h3 class="text-lg font-semibold text-white">{{ $followersCount }}<span class="ml-1 font-medium text-white/50">followers</span></h3>
                        <h3 class="text-lg font-semibold text-white">{{ $followingCount }}<span class="ml-1 font-medium text-white/50">following</span></h3>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">{{Auth::user()->first_name}} {{Auth::user()->middle_name}} {{Auth::user()->last_name}}</h3>
                        <h3 class="text-md font-medium text-white/50">{{Auth::user()->occupation}}</h3>
                    </div>
                    <h3 class="text-lg font-semibold text-white">{{Auth::user()->bio}}</h3>
                </div>
            </div>
            <div class="flex justify-around border-b border-white/20 pb-4 px-8">
                <button class="posts-tab flex flex-col items-center space-y-1 text-white cursor-pointer" data-tab="posts">
                    <div class="w-5 h-5">
                        <svg fill="#ffffff" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M493.526,31.67H18.474C8.288,31.67,0,39.958,0,50.144v411.711c0,10.186,8.288,18.474,18.474,18.474h475.052 c10.186,0,18.474-8.288,18.474-18.474V50.144C512,39.958,503.712,31.67,493.526,31.67z M15.835,110.845h63.34v58.062h-63.34 V110.845z M15.835,184.742h63.34v58.062h-63.34V184.742z M15.835,258.639h63.34v58.062h-63.34V258.639z M15.835,332.536h63.34 v58.062h-63.34V332.536z M496.165,461.856c0,1.43-1.209,2.639-2.639,2.639H18.474c-1.43,0-2.639-1.209-2.639-2.639v-55.423h480.33 V461.856z M163.629,184.742v58.062H95.01v-58.062H163.629z M95.01,168.907v-58.062h68.619v58.062H95.01z M163.629,258.639v58.062 H95.01v-58.062H163.629z M163.629,332.536v58.062H95.01v-58.062H163.629z M248.082,184.742v58.062h-68.619v-58.062H248.082z M179.464,168.907v-58.062h68.619v58.062H179.464z M248.082,258.639v58.062h-68.619v-58.062H248.082z M248.082,332.536v58.062 h-68.619v-58.062H248.082z M332.536,184.742v58.062h-68.619v-58.062H332.536z M263.918,168.907v-58.062h68.619v58.062H263.918z M332.536,258.639v58.062h-68.619v-58.062H332.536z M332.536,332.536v58.062h-68.619v-58.062H332.536z M416.99,184.742v58.062 h-68.619v-58.062H416.99z M348.371,168.907v-58.062h68.619v58.062H348.371z M416.99,258.639v58.062h-68.619v-58.062H416.99z M416.99,332.536v58.062h-68.619v-58.062H416.99z M496.165,390.598h-63.34v-58.062h63.34V390.598z M496.165,316.701h-63.34 v-58.062h63.34V316.701z M496.165,242.804h-63.34v-58.062h63.34V242.804z M496.165,168.907h-63.34v-58.062h63.34V168.907z M496.165,95.01H15.835V50.144c0,1.43,1.209-2.639,2.639-2.639h475.052c1.43,0,2.639,1.209,2.639,2.639V95.01z"></path> </g> </g> <g> <g> <rect x="39.588" y="427.546" width="295.588" height="15.835"></rect> </g> </g> <g> <g> <rect x="356.289" y="427.546" width="116.124" height="15.835"></rect> </g> </g> </g></svg>
                    </div>
                    <span class="text-xs font-medium">POSTS</span>
                </button>
                <button class="saved-tab flex flex-col items-center space-y-1 text-white/60 cursor-pointer" data-tab="saved">
                    <div class="w-5 h-5">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            stroke="currentColor" stroke-width="1.5">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path
                                                    d="M5 6c0-1.4 0-2.1.272-2.635a2.5 2.5 0 0 1 1.093-1.093C6.9 2 7.6 2 9 2h6c1.4 0 2.1 0 2.635.272a2.5 2.5 0 0 1 1.092 1.093C19 3.9 19 4.6 19 6v13.208c0 1.056 0 1.583-.217 1.856a1 1 0 0 1-.778.378c-.349.002-.764-.324-1.593-.976L12 17l-4.411 3.466c-.83.652-1.245.978-1.594.976a1 1 0 0 1-.778-.378C5 20.791 5 20.264 5 19.208V6z"
                                                    fill=""></path>
                                            </g>
                                        </svg>
                    </div>
                    <span class="text-xs font-medium">SAVED</span>
                </button>
                <button class="tagged-tab flex flex-col items-center space-y-1 text-white/60 cursor-pointer" data-tab="tagged">
                    <div class="w-5 h-5">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.12 12.78C12.05 12.77 11.96 12.77 11.88 12.78C10.12 12.72 8.71997 11.28 8.71997 9.50998C8.71997 7.69998 10.18 6.22998 12 6.22998C13.81 6.22998 15.28 7.69998 15.28 9.50998C15.27 11.28 13.88 12.72 12.12 12.78Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M18.74 19.3801C16.96 21.0101 14.6 22.0001 12 22.0001C9.40001 22.0001 7.04001 21.0101 5.26001 19.3801C5.36001 18.4401 5.96001 17.5201 7.03001 16.8001C9.77001 14.9801 14.25 14.9801 16.97 16.8001C18.04 17.5201 18.64 18.4401 18.74 19.3801Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </div>
                    <span class="text-xs font-medium">TAGGED</span>
                </button>
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
                                
                                <!-- Hover overlay with stats -->
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <div class="flex items-center space-x-4 text-white">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-semibold">{{ $post->likes_count }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-semibold">{{ $post->comments_count }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dropdown menu -->
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="relative">
                                        <button class="post-menu-btn bg-black/70 text-white p-1 rounded-full hover:bg-black/90 transition-colors" 
                                                data-post-id="{{ $post->id }}">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                        <div class="post-menu absolute right-0 top-8 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg py-2 w-32 hidden z-50" 
                                             data-post-id="{{ $post->id }}">
                                            <a href="{{ route('posts.edit', $post) }}" 
                                               class="block px-4 py-2 text-white hover:bg-white/20 transition-colors text-sm">
                                                Edit Post
                                            </a>
                                            <form method="POST" action="{{ route('posts.destroy', $post) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete this post?')" 
                                                  class="block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full text-left px-4 py-2 text-red-400 hover:bg-white/20 transition-colors text-sm">
                                                    Delete Post
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
                        <p class="text-white/60 mb-4">When you share photos, they will appear on your profile.</p>
                        <a href="{{ route('posts.create') }}" 
                           class="text-blue-400 hover:text-blue-300 font-semibold">
                            Share your first photo
                        </a>
                    </div>
                @endif
            </div>

            <!-- Saved Content -->
            <div class="saved-content hidden" id="saved-grid">
                @if($savedPosts->count() > 0)
                    <div class="grid grid-cols-3 gap-1">
                        @foreach($savedPosts as $post)
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
                                
                                <!-- Hover overlay with stats and saved indicator -->
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <div class="flex items-center space-x-4 text-white">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-semibold">{{ $post->likes_count }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-semibold">{{ $post->comments_count }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Saved indicator and unsave button -->
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="relative">
                                        <button class="save-btn bg-black/70 text-white p-1 rounded-full hover:bg-black/90 transition-colors" 
                                                data-post-id="{{ $post->id }}">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Post info overlay -->
                                <div class="absolute bottom-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-full overflow-hidden">
                                            @if($post->user->profile_photo_path)
                                                <img src="{{ asset('storage/' . $post->user->profile_photo_path) }}" 
                                                     alt="{{ $post->user->username }}'s profile"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-white/20 flex items-center justify-center">
                                                    <span class="text-white font-semibold text-xs">
                                                        {{ strtoupper(substr($post->user->first_name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="text-white text-xs font-medium">{{ $post->user->username }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Save</h3>
                        <p class="text-white/60">Save photos and videos that you want to see again. No one is notified, and only you can see what you've saved.</p>
                    </div>
                @endif
            </div>

            <!-- Tagged Content (placeholder) -->
            <div class="tagged-content hidden" id="tagged-grid">
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Photos of you</h3>
                    <p class="text-white/60">When people tag you in photos, they'll appear here.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for tab switching and dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF token setup
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Tab switching functionality
            const tabs = document.querySelectorAll('[data-tab]');
            const contents = {
                'posts': document.getElementById('posts-grid'),
                'saved': document.getElementById('saved-grid'),
                'tagged': document.getElementById('tagged-grid')
            };

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;
                    
                    // Remove active styles from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('text-white');
                        t.classList.add('text-white/60');
                    });
                    
                    // Add active styles to clicked tab
                    this.classList.remove('text-white/60');
                    this.classList.add('text-white');
                    
                    // Hide all content sections
                    Object.values(contents).forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show target content
                    contents[targetTab].classList.remove('hidden');
                });
            });

            // Dropdown menu functionality
            document.addEventListener('click', function(e) {
                // Close all dropdowns when clicking outside
                if (!e.target.closest('.post-menu-btn') && !e.target.closest('.post-menu')) {
                    document.querySelectorAll('.post-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }

                // Toggle dropdown when menu button is clicked
                if (e.target.closest('.post-menu-btn')) {
                    const button = e.target.closest('.post-menu-btn');
                    const postId = button.dataset.postId;
                    const menu = document.querySelector(`.post-menu[data-post-id="${postId}"]`);
                    
                    // Close all other dropdowns
                    document.querySelectorAll('.post-menu').forEach(otherMenu => {
                        if (otherMenu !== menu) {
                            otherMenu.classList.add('hidden');
                        }
                    });
                    
                    // Toggle current dropdown
                    menu.classList.toggle('hidden');
                    e.stopPropagation();
                }
            });

            // Save/Unsave functionality for saved posts
            document.querySelectorAll('.save-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.dataset.postId;
                    const saveButton = this;
                    const postElement = saveButton.closest('.group');

                    fetch(`/posts/${postId}/save`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.saved) {
                            // Post was unsaved, remove it from the saved grid
                            postElement.remove();
                            
                            // Check if saved grid is now empty
                            const savedGrid = document.getElementById('saved-grid');
                            const remainingPosts = savedGrid.querySelectorAll('.group').length;
                            
                            if (remainingPosts === 0) {
                                // Show empty state
                                savedGrid.innerHTML = `
                                    <div class="flex flex-col items-center justify-center py-12 text-center">
                                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-white mb-2">Save</h3>
                                        <p class="text-white/60">Save photos and videos that you want to see again. No one is notified, and only you can see what you've saved.</p>
                                    </div>
                                `;
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
</x-app-layout>
