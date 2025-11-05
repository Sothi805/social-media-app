<x-app-layout>
    <x-slot name="title">Home</x-slot>
    <div class="py-6 ">
        <div class="flex">
            <div class="w-2/3 flex justify-center">
                <div class="flex flex-col">
                    @forelse ($posts as $post)
                        <div class="post w-128 mb-4 flex flex-col space-y-2 pb-4 border-b border-gray-300/20">
                            <div class="flex">
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
                                <div class="w-full ml-2 flex justify-between items-center">
                                    <div>
                                        <h3 class="text-white text-sm font-medium">
                                            <a href="{{ route('profile.show', $post->user) }}" class="hover:text-white/80">
                                                {{ $post->user->username }}
                                            </a>
                                            <span class="text-gray-400">·</span> 
                                            <span class="text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                        </h3>
                                        @if($post->user->occupation)
                                            <p class="text-gray-300 text-sm font-light">{{ $post->user->occupation }}</p>
                                        @endif
                                    </div>
                                    @if($post->user_id === Auth::id())
                                        <form method="POST" action="{{ route('posts.destroy', $post) }}" 
                                              onsubmit="return confirm('Are you sure you want to delete this post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-4 text-red-400 hover:text-red-300">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Post Image -->
                            @if($post->image_path)
                                <img class="w-full h-auto mb-2 rounded-sm" src="{{ asset('storage/' . $post->image_path) }}" alt="Post image">
                            @endif

                            <!-- Post Tags -->
                            @if($post->tags->count() > 0)
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($post->tags->take(5) as $tag)
                                        <a href="{{ route('tags.posts', $tag) }}" 
                                           class="inline-block px-2 py-1 text-xs rounded-full transition-colors"
                                           style="background-color: {{ $tag->color }}20; color: {{ $tag->color }};"
                                           onmouseover="this.style.backgroundColor='{{ $tag->color }}40'"
                                           onmouseout="this.style.backgroundColor='{{ $tag->color }}20'">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                    @if($post->tags->count() > 5)
                                        <span class="text-white/60 text-xs px-2 py-1">+{{ $post->tags->count() - 5 }} more</span>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Post Actions -->
                            <div class="flex justify-between">
                                <div class="flex space-x-4 text-white">
                                    <!-- Like Button -->
                                    <button type="button" class="like-btn cursor-pointer active:scale-125 w-8 h-8 flex items-center justify-center transition-colors" data-post-id="{{ $post->id }}" data-url="{{ route('posts.like', $post) }}">
                                        <svg viewBox="-1.12 -1.12 18.24 18.24" fill="{{ $post->likes->contains('user_id', Auth::id()) ? '#ff0000' : 'none' }}" stroke="{{ $post->likes->contains('user_id', Auth::id()) ? 'none' : '#ff0000' }}" xmlns="http://www.w3.org/2000/svg" stroke-width="1">
                                            <path d="M1.24264 8.24264L8 15L14.7574 8.24264C15.553 7.44699 16 6.36786 16 5.24264V5.05234C16 2.8143 14.1857 1 11.9477 1C10.7166 1 9.55233 1.55959 8.78331 2.52086L8 3.5L7.21669 2.52086C6.44767 1.55959 5.28338 1 4.05234 1C1.8143 1 0 2.8143 0 5.05234V5.24264C0 6.36786 0.44699 7.44699 1.24264 8.24264Z" />
                                        </svg>
                                    </button>
                                    <!-- Comment Button (scroll to comment input) -->
                                    <button type="button" class="w-8 h-8 cursor-pointer active:scale-125 flex items-center justify-center hover:text-blue-500 transition-colors comment-modal-btn" onclick="this.closest('.post').querySelector('input[name=comment]').focus()">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" transform="matrix(-1, 0, 0, 1, 0, 0)">
                                            <g clip-path="url(#clip0_429_11233)">
                                                <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 13.4876 3.36093 14.891 4 16.1272L3 21L7.8728 20C9.10904 20.6391 10.5124 21 12 21Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                        </svg>
                                    </button>
                                    <!-- Save Button -->
                                    <button type="button" class="save-btn cursor-pointer active:scale-125 w-8 h-8 flex items-center justify-center transition-colors" data-post-id="{{ $post->id }}" data-url="{{ route('posts.save.toggle', $post) }}">
                                        <svg viewBox="0 0 24 24" fill="{{ $post->saves->contains('user_id', Auth::id()) ? '#00ffff' : 'none' }}" stroke="{{ $post->saves->contains('user_id', Auth::id()) ? 'none' : '#00ffff' }}" xmlns="http://www.w3.org/2000/svg" stroke-width="1.5">
                                            <path d="M5 6c0-1.4 0-2.1.272-2.635a2.5 2.5 0 0 1 1.093-1.093C6.9 2 7.6 2 9 2h6c1.4 0 2.1 0 2.635.272a2.5 2.5 0 0 1 1.092 1.093C19 3.9 19 4.6 19 6v13.208c0 1.056 0 1.583-.217 1.856a1 1 0 0 1-.778.378c-.349.002-.764-.324-1.593-.976L12 17l-4.411 3.466c-.83.652-1.245.978-1.594.976a1 1 0 0 1-.778-.378C5 20.791 5 20.264 5 19.208V6z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Post Stats and Content -->
                            <div class="space-y-2">
                                <p class="font-semibold text-white text-xs like-count">{{ $post->likes_count }} likes</p>
                                <div class="text-white text-xs">
                                    <span class="font-semibold">{{ $post->user->username }}</span>
                                    <span class="font-light ml-2">{{ $post->content }}</span>
                                </div>
                                
                                <!-- Comments List -->
                                <div class="comments-list">
                                    {{-- Optionally render initial comments here if desired --}}
                                </div>
                                <form method="POST" action="{{ route('posts.comment', $post) }}" class="flex w-full comment-form" data-post-id="{{ $post->id }}" data-url="{{ route('posts.comment', $post) }}">
                                    @csrf
                                    <input name="comment" class="bg-transparent text-white text-xs placeholder-white/50 border-none outline-none flex-1" 
                                        type="text" placeholder="Add a comment...">
                                    <button type="submit" class="ml-2 text-blue-400 hover:text-blue-300 text-xs font-semibold">Post</button>
                                </form>
                            </div>
                        </div>
                    @empty
                    <!-- Comment Modal (hidden by default) -->
                    <div id="commentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg relative">
                            <button id="closeCommentModal" class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
                            <div id="modalPostContent" class="p-4 border-b"></div>
                            <div id="modalComments" class="p-4 max-h-64 overflow-y-auto"></div>
                            <form id="modalCommentForm" class="flex items-center border-t p-4">
                                <input type="text" id="modalCommentInput" class="flex-1 border rounded px-3 py-2 mr-2" placeholder="Add a comment..." required />
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Post</button>
                            </form>
                        </div>
                    </div>
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-2">No posts yet</h3>
                            <p class="text-white/60 mb-4">Start following people or create your first post to see content here.</p>
                            <a href="{{ route('posts.create') }}" 
                               class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-all duration-200">
                                Create Post
                            </a>
                        </div>
                    @endforelse
                    
                    @if($posts->hasPages())
                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-1/3">
                <div class="flex flex-col w-4/5 sticky top-8">
                    <div class="flex justify-between items-center">
                        <div class="flex ">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full overflow-hidden">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                         alt="{{ Auth::user()->first_name }}'s profile picture"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-2">
                                <h1 class="text-white font-semibold">{{ Auth::user()->username }}</h1>
                                <h2 class="text-white/80 font-light">{{ Auth::user()->first_name }}
                                    {{ Auth::user()->middle_name }}
                                    {{ Auth::user()->last_name }}
                                </h2>
                            </div>
                        </div>
                        <a href="{{ route('posts.create') }}" class="text-blue-400 hover:text-blue-300 text-sm font-semibold">
                            Create Post
                        </a>
                    </div>
                    <div class="flex justify-between items-center py-6">
                        <h3 class="text-white/75 font-semibold">
                            Suggested for you
                        </h3>
                        <a href="{{ route('search') }}" class="text-white text-md font-semibold hover:text-white/80">See all</a>
                    </div>
                    <p class="font-light text-white/50 text-sm text-left mb-4">
                        <span><a href="#" class="hover:text-white/70">About</a></span> <span class="text-gray-400"> . </span> 
                        <span><a href="#" class="hover:text-white/70">Help</a></span> <span class="text-gray-400"> . </span> 
                        <span><a href="#" class="hover:text-white/70"> Press </a></span> <span class="text-gray-400"> . </span> 
                        <span><a href="#" class="hover:text-white/70">API</a></span> <span class="text-gray-400"> . </span> 
                        <span><a href="#" class="hover:text-white/70">Jobs</a></span> <span class="text-gray-400"> . </span> 
                        <span><a href="#" class="hover:text-white/70">Privacy</a></span> <span class="text-gray-400"> . </span> 
                        <span><a href="#" class="hover:text-white/70">Terms</a></span> <span class="text-gray-400"> . </span> 
                        <span><a href="#" class="hover:text-white/70">Locations</a></span> <span class="text-gray-400"> . </span> 
                        <span><a href="#" class="hover:text-white/70">Language</a></span> <span class="text-gray-400"> . </span> 
                        <span><a href="#" class="hover:text-white/70">Meta Verified</a></span> 
                    </p>
                    <p class="font-light text-white/50 text-sm">© 2025 CATMEDIA FROM NPIT TEAM 4</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Comment Modal logic
        const modal = document.getElementById('commentModal');
        const closeModalBtn = document.getElementById('closeCommentModal');
        const modalPostContent = document.getElementById('modalPostContent');
        const modalComments = document.getElementById('modalComments');
        const modalCommentForm = document.getElementById('modalCommentForm');
        const modalCommentInput = document.getElementById('modalCommentInput');
        let currentPostId = null;

        // Open modal and load comments
        document.querySelectorAll('.comment-modal-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const postElem = this.closest('.post');
                currentPostId = postElem.querySelector('.comment-form').dataset.postId;
                fetch(`/posts/${currentPostId}/comments`)
                    .then(res => res.json())
                    .then(data => {
                        // Show post content (customize as needed)
                        modalPostContent.innerHTML = `
                            <div class=\"flex items-center mb-2\">
                                <img src=\"${data.post.user_avatar}\" class=\"w-8 h-8 rounded-full mr-2\" />
                                <span class=\"font-semibold mr-2\">${data.post.user_name}</span>
                                <span class=\"text-gray-500 text-xs\">${data.post.created_at}</span>
                            </div>
                            <div class=\"mb-2\">${data.post.caption}</div>
                            <img src=\"${data.post.image_url}\" class=\"w-full rounded\" />
                        `;
                        // Show comments or a friendly message
                        if (data.comments.length === 0) {
                            modalComments.innerHTML = `<div class=\"text-gray-400 text-center py-4\">No comments yet. Be the first to comment!</div>`;
                        } else {
                            modalComments.innerHTML = data.comments.map(c => `
                                <div class=\"mb-2\">
                                    <span class=\"font-semibold\">${c.user_name}</span>
                                    <span>${c.body}</span>
                                    <span class=\"text-xs text-gray-400 ml-2\">${c.created_at}</span>
                                </div>
                            `).join('');
                        }
                        modal.classList.remove('hidden');
                    });
            });
        });

        // Close modal
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            modalPostContent.innerHTML = '';
            modalComments.innerHTML = '';
            modalCommentInput.value = '';
        });

        // Submit new comment via AJAX
        modalCommentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(`/posts/${currentPostId}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                },
                body: JSON.stringify({ body: modalCommentInput.value })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Prepend new comment
                    modalComments.innerHTML = `
                        <div class=\"mb-2\"><span class=\"font-semibold\">${data.comment.user_name}</span> <span>${data.comment.body}</span> <span class=\"text-xs text-gray-400 ml-2\">${data.comment.created_at}</span></div>
                    ` + modalComments.innerHTML;
                    modalCommentInput.value = '';
                }
            });
        });
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Like button AJAX
        document.querySelectorAll('.like-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const postId = this.dataset.postId;
                const url = this.dataset.url;
                const svg = this.querySelector('svg');
                const likeCountElem = this.closest('.post').querySelector('.like-count');
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.liked) {
                        svg.setAttribute('fill', '#ff0000');
                        svg.setAttribute('stroke', 'none');
                    } else {
                        svg.setAttribute('fill', 'none');
                        svg.setAttribute('stroke', '#ff0000');
                    }
                    if (likeCountElem && typeof data.likes_count !== 'undefined') {
                        likeCountElem.textContent = data.likes_count + ' likes';
                    }
                });
            });
        });
        // Save button AJAX
        document.querySelectorAll('.save-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const postId = this.dataset.postId;
                const url = this.dataset.url;
                const svg = this.querySelector('svg');
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.saved) {
                        svg.setAttribute('fill', '#00ffff');
                        svg.setAttribute('stroke', 'none');
                    } else {
                        svg.setAttribute('fill', 'none');
                        svg.setAttribute('stroke', '#00ffff');
                    }
                });
            });
        });
        // Comment form AJAX
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const url = this.dataset.url;
                const postId = this.dataset.postId;
                const input = this.querySelector('input[name="comment"]');
                const commentText = input.value.trim();
                if (!commentText) return;
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comment: commentText })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.comment) {
                        const commentsList = this.closest('.post').querySelector('.comments-list');
                        const commentElem = document.createElement('div');
                        commentElem.className = 'text-white text-xs mt-1';
                        commentElem.innerHTML = `<span class="font-semibold">${data.comment.user.username}</span> <span class="font-light ml-2">${data.comment.content}</span>`;
                        commentsList.appendChild(commentElem);
                        input.value = '';
                    }
                });
            });
        });
    });
    </script>
    @endpush
</x-app-layout>
