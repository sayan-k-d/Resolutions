<x-auth>
    <x-slot:title>
        Register
    </x-slot:title>
    <x-slot:content>
        <div class="background-design"></div>
        <div class="login-container">
            <div class="image-container"></div>
            <div class="form-container">
                <h2>Sign Up</h2>
                <form class="w-100" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Name" required
                            id="floatingInput">
                        <label for="floatingInput">Name</label>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required
                            id="floatingInput">
                        <label for="floatingInput">Email</label>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="password" name="password" class="form-control" id="floatingPassword"
                            placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn">Sign Up</button>
                    <a class="btn btn-outline-dark py-2" href="{{ route('login') }}">Login</a>
                </form>
            </div>
        </div>
        @if (session('error'))
            <script>
                Swal.fire({
                    icon: "error",
                    text: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        @endif
    </x-slot:content>
</x-auth>
