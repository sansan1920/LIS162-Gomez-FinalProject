<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $user_types_id = '';


    public function with(): array
    {
        $allowedTypes = ['Undergraduate', 'Graduate', 'Faculty', 'Guest'];
        $userTypes = \App\Models\UserType::whereIn('user_type', $allowedTypes)
                                ->select('user_types_id', 'user_type')
                                ->get()
                                ->toArray();
        return [
            'userTypes' => $userTypes,
        ];
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'user_types_id' => ['required', 'integer', 'exists:user_types,user_types_id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }


}; ?>

<div>
    <form wire:submit="register">

     <div class="mt-4">
        <div class="flex space-x-4"> 
        <!-- First Name -->
        <div class="flex-1">
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text" name="first_name" required autofocus autocomplete="given-name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>
                
        <!-- Last Name -->
        <div class="flex-1">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text" name="last_name" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>
    </div>
</div>

        <!-- User Type -->        
        <div class="mt-4">
            <x-input-label for="user_types_id" :value="__('User Type')" />
            
            <select wire:model="user_types_id" id="user_types_id" name="user_types_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                <option value="" disabled selected>{{ __('Select your user type') }}</option>
                
                {{-- $userTypes is fetched via the with() method --}}
                @foreach ($userTypes as $userType)
                    <option value="{{ $userType['user_types_id'] }}">
                        {{ $userType['user_type'] }}
                    </option>
                @endforeach
            </select>
            
            <x-input-error :messages="$errors->get('user_types_id')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
