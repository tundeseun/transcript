<x-guest-layout>
    <div id="form">
        <i class="fas fa-user"></i>
        @error('message')
            <div class="alert alert-danger">
                {{ $message }}
            </div>
        @enderror
        {{-- @if (session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif --}}
        <form method="post" action="{{ route('login.store') }}">
            @csrf
            <div class="form-group">
                <label for="matric">Matriculation Number:</label>
                <input type="text" placeholder="Matric Number" name="matric">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" placeholder="Password" name="password">
            </div>

            <input type='submit' value='Sign in' name='send' class='btn'>
        </form>

        <p class="noacc">
            If you do not have an account, click
            <a class="reg" href="register.php">Sign Up.</a>
        </p>
        <p class="noacc">

            <a class="reg" href="forget.php">Forget Password?</a>
        </p>
    </div>
</x-guest-layout>
