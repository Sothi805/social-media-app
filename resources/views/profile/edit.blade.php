<x-app-layout>
    <x-slot name="title">Edit Profile</x-slot>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-lg rounded-lg p-8">
                
                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-white">Edit Profile</h2>
                    <a href="{{ route('profile.index') }}" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                <!-- Profile Form -->
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('patch')

                    <!-- Profile Picture Section -->
                    <div class="flex flex-col items-center space-y-4 pb-6 border-b border-white/20">
                        <div class="relative">
                            <div class="flex-shrink-0 w-32 h-32 rounded-full overflow-hidden" id="profileImageContainer">
                                @if(Auth::user()->profile_photo_path)
                                    <img id="profileImg" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                         alt="{{ Auth::user()->first_name }}'s profile picture"
                                         class="w-full h-full object-cover">
                                @else
                                    <div id="profilePlaceholder" class="w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                        <span class="text-white font-semibold text-4xl">
                                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="removeProfileImage" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-8 h-8 items-center justify-center transition-colors hidden">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="text-center">
                            <label for="profile_photo_path" class="cursor-pointer bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-all duration-200 inline-block">
                                Change Profile Picture
                            </label>
                            <input 
                                id="profile_photo_path" 
                                name="profile_photo_path" 
                                type="file" 
                                accept="image/*" 
                                class="hidden"
                            >
                            <p class="text-white/60 text-sm mt-2">JPG, PNG or GIF (max. 2MB)</p>
                            @error('profile_photo_path')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- First Name -->
                        <div>
                            <label class="block text-white text-sm font-medium mb-2">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm">
                            @error('first_name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-white text-sm font-medium mb-2">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm">
                            @error('last_name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label class="block text-white text-sm font-medium mb-2">Middle Name <span class="text-white/60">(optional)</span></label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', Auth::user()->middle_name) }}" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm">
                            @error('middle_name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block text-white text-sm font-medium mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm">
                            @error('username')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label class="block text-white text-sm font-medium mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm">
                            @error('email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bio Section -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Bio</label>
                        <textarea name="bio" rows="4" placeholder="Tell us about yourself..." 
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm resize-none">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                        @error('bio')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Occupation -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Occupation</label>
                        <input type="text" name="occupation" value="{{ old('occupation', Auth::user()->occupation ?? '') }}" 
                            placeholder="What do you do?" 
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-sm">
                        @error('occupation')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6">
                        <a href="{{ route('profile.index') }}" 
                            class="px-6 py-3 text-white/80 hover:text-white transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileInput = document.getElementById('profile_photo_path');
            const profileImg = document.getElementById('profileImg');
            const profilePlaceholder = document.getElementById('profilePlaceholder');
            const profileContainer = document.getElementById('profileImageContainer');
            const removeButton = document.getElementById('removeProfileImage');
            const originalSrc = profileImg ? profileImg.src : null;

            profileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Remove placeholder if it exists
                        if (profilePlaceholder) {
                            profilePlaceholder.remove();
                        }

                        // Create or update profile image
                        if (profileImg) {
                            profileImg.src = e.target.result;
                        } else {
                            const newImg = document.createElement('img');
                            newImg.id = 'profileImg';
                            newImg.src = e.target.result;
                            newImg.alt = 'Profile picture preview';
                            newImg.className = 'w-full h-full object-cover';
                            profileContainer.appendChild(newImg);
                        }

                        // Show remove button
                        removeButton.classList.remove('hidden');
                        removeButton.classList.add('flex');
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeButton.addEventListener('click', function() {
                profileInput.value = '';
                
                // Hide remove button
                removeButton.classList.add('hidden');
                removeButton.classList.remove('flex');

                // Restore original image or placeholder
                if (originalSrc && profileImg) {
                    profileImg.src = originalSrc;
                } else {
                    // Remove image and restore placeholder
                    if (profileImg) {
                        profileImg.remove();
                    }
                    
                    const placeholder = document.createElement('div');
                    placeholder.id = 'profilePlaceholder';
                    placeholder.className = 'w-full h-full bg-white/20 backdrop-blur-sm flex items-center justify-center';
                    placeholder.innerHTML = `
                        <span class="text-white font-semibold text-4xl">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                        </span>
                    `;
                    profileContainer.appendChild(placeholder);
                }
            });
        });
    </script>
</x-app-layout>