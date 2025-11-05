<x-app-layout>
    <x-slot name="title">Create Post</x-slot>
    
    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-lg rounded-lg p-6">
                
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">Create New Post</h2>
                    <a href="{{ route('home') }}" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                <!-- Post Form -->
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- User Info -->
                    <div class="flex items-center space-x-4 pb-4 border-b border-white/20">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full overflow-hidden">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                     alt="{{ Auth::user()->first_name }}'s profile picture"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <span class="text-white font-semibold text-lg">
                                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">{{ Auth::user()->username }}</h3>
                            <p class="text-white/60">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div>
                        <textarea 
                            name="content" 
                            id="contentTextarea"
                            rows="4" 
                            placeholder="What's on your mind? Use @username to mention someone and #hashtags for topics" 
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm resize-none"
                            required
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Mention suggestions dropdown -->
                        <div id="mentionSuggestions" class="hidden absolute z-50 bg-white/10 backdrop-blur-md border border-white/20 rounded-lg mt-1 max-h-40 overflow-y-auto">
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Upload Image (optional)</label>
                        <div class="relative">
                            <input 
                                type="file" 
                                name="image" 
                                accept="image/*" 
                                id="imageInput"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm"
                            >
                        </div>
                        <p class="text-white/60 text-sm mt-2">JPG, PNG or GIF (max. 2MB)</p>
                        @error('image')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <div class="relative inline-block">
                                <img id="previewImg" src="" alt="Preview" class="max-w-full h-auto max-h-64 rounded-lg shadow-lg">
                                <button type="button" id="removeImage" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Tags (optional)</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="tags" 
                                id="tagsInput"
                                placeholder="Add tags separated by commas (e.g., #nature, travel, photography)" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm"
                                value="{{ old('tags') }}"
                            >
                        </div>
                        <p class="text-white/60 text-sm mt-2">Use hashtags or separate with commas</p>
                        
                        <!-- Tag Preview -->
                        <div id="tagPreview" class="flex flex-wrap gap-2 mt-3"></div>
                        
                        <!-- Popular Tags -->
                        <div class="mt-3">
                            <p class="text-white/60 text-xs mb-2">Popular tags:</p>
                            <div class="flex flex-wrap gap-1" id="popularTags">
                                <button type="button" class="popular-tag px-2 py-1 bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 text-xs rounded-full transition-colors" data-tag="photography">
                                    #photography
                                </button>
                                <button type="button" class="popular-tag px-2 py-1 bg-green-600/20 hover:bg-green-600/40 text-green-300 text-xs rounded-full transition-colors" data-tag="travel">
                                    #travel
                                </button>
                                <button type="button" class="popular-tag px-2 py-1 bg-purple-600/20 hover:bg-purple-600/40 text-purple-300 text-xs rounded-full transition-colors" data-tag="art">
                                    #art
                                </button>
                                <button type="button" class="popular-tag px-2 py-1 bg-yellow-600/20 hover:bg-yellow-600/40 text-yellow-300 text-xs rounded-full transition-colors" data-tag="food">
                                    #food
                                </button>
                                <button type="button" class="popular-tag px-2 py-1 bg-red-600/20 hover:bg-red-600/40 text-red-300 text-xs rounded-full transition-colors" data-tag="fitness">
                                    #fitness
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6">
                        <a href="{{ route('home') }}" 
                            class="px-6 py-3 text-white/80 hover:text-white transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg">
                            Share Post
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image Preview Functionality
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const removeImageBtn = document.getElementById('removeImage');

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeImageBtn.addEventListener('click', function() {
                imageInput.value = '';
                imagePreview.classList.add('hidden');
                previewImg.src = '';
            });

            // Tag Functionality
            const tagsInput = document.getElementById('tagsInput');
            const tagPreview = document.getElementById('tagPreview');
            const popularTagButtons = document.querySelectorAll('.popular-tag');

            function updateTagPreview() {
                const tagsValue = tagsInput.value;
                const tags = tagsValue.split(',').map(tag => tag.trim().replace('#', '')).filter(tag => tag);
                
                tagPreview.innerHTML = '';
                tags.forEach(tag => {
                    if (tag) {
                        const tagElement = document.createElement('span');
                        tagElement.className = 'inline-flex items-center px-2 py-1 bg-indigo-600/30 text-indigo-300 text-xs rounded-full';
                        tagElement.innerHTML = `
                            #${tag}
                            <button type="button" class="ml-1 text-indigo-400 hover:text-indigo-200" onclick="removeTag('${tag}')">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        `;
                        tagPreview.appendChild(tagElement);
                    }
                });
            }

            function addTag(tagName) {
                const currentTags = tagsInput.value.split(',').map(tag => tag.trim().replace('#', '')).filter(tag => tag);
                if (!currentTags.includes(tagName)) {
                    currentTags.push(tagName);
                    tagsInput.value = currentTags.join(', ');
                    updateTagPreview();
                }
            }

            window.removeTag = function(tagToRemove) {
                const currentTags = tagsInput.value.split(',').map(tag => tag.trim().replace('#', '')).filter(tag => tag && tag !== tagToRemove);
                tagsInput.value = currentTags.join(', ');
                updateTagPreview();
            };

            tagsInput.addEventListener('input', updateTagPreview);

            popularTagButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tagName = this.getAttribute('data-tag');
                    addTag(tagName);
                });
            });

            // Mention Functionality
            const contentTextarea = document.getElementById('contentTextarea');
            const mentionSuggestions = document.getElementById('mentionSuggestions');
            let currentMentionQuery = '';
            let mentionStartPos = -1;
            let selectedSuggestionIndex = -1;

            contentTextarea.addEventListener('input', function(e) {
                const text = e.target.value;
                const cursorPos = e.target.selectionStart;
                
                // Find @ symbol before cursor
                let atPos = -1;
                for (let i = cursorPos - 1; i >= 0; i--) {
                    if (text[i] === '@') {
                        atPos = i;
                        break;
                    } else if (text[i] === ' ' || text[i] === '\n') {
                        break;
                    }
                }
                
                if (atPos !== -1) {
                    const query = text.substring(atPos + 1, cursorPos);
                    if (query.length >= 1 && !query.includes(' ')) {
                        currentMentionQuery = query;
                        mentionStartPos = atPos;
                        searchUsers(query);
                    } else {
                        hideMentionSuggestions();
                    }
                } else {
                    hideMentionSuggestions();
                }
            });

            contentTextarea.addEventListener('keydown', function(e) {
                if (mentionSuggestions.classList.contains('hidden')) return;

                const suggestions = mentionSuggestions.querySelectorAll('.mention-suggestion');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedSuggestionIndex = Math.min(selectedSuggestionIndex + 1, suggestions.length - 1);
                    updateSuggestionSelection(suggestions);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedSuggestionIndex = Math.max(selectedSuggestionIndex - 1, 0);
                    updateSuggestionSelection(suggestions);
                } else if (e.key === 'Enter' || e.key === 'Tab') {
                    if (selectedSuggestionIndex >= 0) {
                        e.preventDefault();
                        suggestions[selectedSuggestionIndex].click();
                    }
                } else if (e.key === 'Escape') {
                    hideMentionSuggestions();
                }
            });

            function searchUsers(query) {
                fetch(`/users/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => {
                        showMentionSuggestions(users);
                    })
                    .catch(error => {
                        console.error('Error searching users:', error);
                    });
            }

            function showMentionSuggestions(users) {
                mentionSuggestions.innerHTML = '';
                selectedSuggestionIndex = -1;

                if (users.length === 0) {
                    hideMentionSuggestions();
                    return;
                }

                users.forEach((user, index) => {
                    const div = document.createElement('div');
                    div.className = 'mention-suggestion px-3 py-2 hover:bg-white/20 cursor-pointer flex items-center space-x-2';
                    div.innerHTML = `
                        <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                            ${user.profile_photo_path 
                                ? `<img src="/storage/${user.profile_photo_path}" alt="${user.first_name}'s profile" class="w-full h-full object-cover">`
                                : `<div class="w-full h-full bg-white/20 flex items-center justify-center text-white text-xs">${user.first_name.charAt(0).toUpperCase()}</div>`
                            }
                        </div>
                        <div>
                            <div class="text-white text-sm font-medium">@${user.username}</div>
                            <div class="text-white/60 text-xs">${user.first_name} ${user.last_name}</div>
                        </div>
                    `;
                    
                    div.addEventListener('click', function() {
                        insertMention(user.username);
                    });
                    
                    mentionSuggestions.appendChild(div);
                });

                mentionSuggestions.classList.remove('hidden');
            }

            function hideMentionSuggestions() {
                mentionSuggestions.classList.add('hidden');
                selectedSuggestionIndex = -1;
            }

            function updateSuggestionSelection(suggestions) {
                suggestions.forEach((suggestion, index) => {
                    if (index === selectedSuggestionIndex) {
                        suggestion.classList.add('bg-white/20');
                    } else {
                        suggestion.classList.remove('bg-white/20');
                    }
                });
            }

            function insertMention(username) {
                const text = contentTextarea.value;
                const beforeMention = text.substring(0, mentionStartPos);
                const afterMention = text.substring(contentTextarea.selectionStart);
                const newText = beforeMention + '@' + username + ' ' + afterMention;
                
                contentTextarea.value = newText;
                contentTextarea.focus();
                
                const newCursorPos = mentionStartPos + username.length + 2;
                contentTextarea.setSelectionRange(newCursorPos, newCursorPos);
                
                hideMentionSuggestions();
            }

            // Initial tag preview update
            updateTagPreview();
        });
    </script>
</x-app-layout>
