<x-guest-layout>
    <x-slot name="title">Login</x-slot>
    <div class="flex flex-col lg:flex-row shadow-md min-h-[75vh] lg:h-[75vh] rounded-lg overflow-hidden max-w-5xl mx-auto">
        <div class="image-side w-full lg:w-1/2 h-48 lg:h-full p-0 m-0">
            <img class="w-full h-full object-cover" src="{{ asset('images/elements/image1.jpeg') }}" alt="">
        </div>
        <div class="form-side login-box w-full lg:w-1/2 m-0 px-6 py-8 lg:py-0 flex flex-col justify-center
                    bg-white/10 backdrop-blur-md border-0 border-white/20 shadow-lg">
            <div class="mx-auto mb-6">
                <a href="/">
                    <img class="w-16 sm:w-20" src="{{ asset('images/logo.png') }}" alt="logo">
                </a>
            </div>
             <div class="typing-container mb-6">
                    <h2 class="typing-text text-center text-lg sm:text-xl font-bold">Login to stay connected ...</h2>
            </div>       
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="user-box">
                    <input class="focus:outline-none focus:ring-0 focus:shadow-none" required name="email" type="text">
                    <label>Email</label>
                </div>
                                <div class="user-box relative">
                    <input id="loginPassword" class="focus:outline-none focus:ring-0 focus:shadow-none pr-10" required name="password" type="password">
                    <label>Password</label>
                    <button type="button" class="absolute right-3 top-3 text-white hover:text-gray-300 focus:outline-none z-10" onclick="togglePassword('loginPassword', 'loginEyeIcon')">
                        <svg id="loginEyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-white text-sm sm:text-base mb-4">Don't have an account? <a href="{{ route('register') }}" class="a2 text-white underline">Sign up!</a></p>
                <button class="py-3 mt-2 cursor-pointer text-white text-sm sm:text-base font-medium text-center w-full rounded-md bg-[#310e68] hover:bg-[#1e014e] duration-300 transition-all"
                    type="submit">Login</button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Change to eye-slash icon (hidden)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                // Change back to eye icon (visible)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
</x-guest-layout>
