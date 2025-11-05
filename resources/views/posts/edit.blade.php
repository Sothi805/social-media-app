<x-app-layout>
    <x-slot name="title">Edit Post</x-slot>
    
    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <a href="{{ route('profile.index') }}" class="text-white hover:text-white/80 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-white">Edit Post</h1>
                </div>

                <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Content Input -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-white mb-2">
                            Caption
                        </label>
                        <textarea 
                            id="content" 
                            name="content" 
                            rows="4" 
                            required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                            placeholder="Write a caption...">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Image Display -->
                    @if($post->image_path)
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">
                                Current Image
                            </label>
                            <div class="relative w-full max-w-md">
                                <img src="{{ asset('storage/' . $post->image_path) }}" 
                                     alt="Current post image" 
                                     class="w-full h-auto rounded-lg">
                            </div>
                        </div>
                    @endif

                    <!-- New Image Upload -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-white mb-2">
                            {{ $post->image_path ? 'Replace Image (optional)' : 'Add Image (optional)' }}
                        </label>
                        <div class="relative">
                            <input 
                                type="file" 
                                id="imageInput" 
                                name="image" 
                                accept="image/*"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm">
                        </div>
                        <p class="text-white/60 text-sm mt-2">JPG, PNG or GIF (max. 2MB)</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
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
                                value="{{ old('tags', $post->tags->pluck('name')->join(', ')) }}"
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
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('profile.index') }}" 
                           class="px-6 py-2 text-white/70 hover:text-white transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-8 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-transparent">
                            Update Post
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

            // Initial tag preview update
            updateTagPreview();
        });
    </script>
</x-app-layout>
