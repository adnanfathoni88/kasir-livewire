<div class="w-50">
    <!-- form login -->
    <div class="text-align-center bg-white p-4 shadow rounded">
        <div>
            <h2>Register</h2>
        </div>
        <div>
            @if (session()->has('logout'))
            <div class="alert alert-danger">{{ session('logout') }}</div>
            @endif
            @if (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
        <form wire:submit.prevent="register">
            <div>
                <label for="name">Nama</label>
                <input type="text" class="form-control" id="name" wire:model="name">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" wire:model="email">
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" wire:model="password">
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4 d-flex" style="gap: 8px;">
                <button type="submit" class="btn btn-primary w-50">Register</button>
                <a href="/login" class="btn btn-outline-primary w-50">Login</a>
            </div>
        </form>
    </div>
</div>