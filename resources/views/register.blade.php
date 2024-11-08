
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white p-8 border rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6">Register</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="w-full px-3 py-2 border rounded-lg @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full px-3 py-2 border rounded-lg @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-3 py-2 border rounded-lg @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full px-3 py-2 border rounded-lg">
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                    Register
                </button>
            </form>

            <p class="mt-4 text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login here</a>
            </p>
        </div>
    </div>

