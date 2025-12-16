<nav class="-mx-3 flex flex-1 justify-end">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
        >
            Log in
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
            >
                Register
            </a>
        @endif
    @endauth
</nav>
