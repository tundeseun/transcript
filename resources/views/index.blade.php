<x-guest-layout :title="'Transcript Application | Sign In Page'">

    <div class="box lr">

        <div class="left">
            <div class="">
                <p class="display-2 text-bold text-primary head">Transcript Application</p>
            </div>
            <h1 class="bold">Read the following guidelines carefully before commencing a transcript application:
            </h1>
            <ul class="mb-1">
                <li>

                    Institutional (not personal/ individual) email address is to be submitted for official
                    transcripts.

                </li>
                <li>

                    Application and payment should only be made via tps.oauife.edu.ng. Payment of cash to any
                    individual, company or agent is prohibited.

                </li>
                <li>

                    Transcripts that are requested for personally will be marked "Student Copy"

                </li>
                <li>

                    All details are to be filled in correctly.

                </li>
            </ul>
            <p>

                For further enquires, kindly contact us using the following avenues:
            </p>
            <p>
                Email address: transcript@pgcollege.edu.ng
            </p>


        </div>
        <div>
            @if (session()->has('success'))
                <div>
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="right">
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
        </div>
    </div>
    
</x-guest-layout>
