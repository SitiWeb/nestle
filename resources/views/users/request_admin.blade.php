<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{route('user.request_login_action')}}">
        @csrf
        <div  class="flex  flex-row w-full mb-6 mx-1">
        <div style="width:100%">
        <x-application-logo width=""></x-application-logo>

        </div>
        </div>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif
        
        <!-- Email Address -->

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus  />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>
        
        <div>
            <x-input-label for="position" :value="__('Position')" />
            <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position')" required autofocus  />
            <x-input-error :messages="$errors->get('position')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="office" :value="__('Office location')" />
            <x-text-input id="office" class="block mt-1 w-full" type="text" name="office" :value="old('office')" required autofocus  />
            <x-input-error :messages="$errors->get('office')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-label for="password" :value="__('Confirm password')" />
            <x-text-input id="password_confirm" class="block mt-1 w-full"
                type="password"
                name="password_confirm"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password_confirm')" class="mt-2" />
        </div>

      

        <div class="flex mt-4 flex-col">
            <x-primary-button class="mb-3">
                {{ __('Request access') }}
            </x-primary-button>

            @if (Route::has('login'))
                <a class="underline py-2 text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Back to login') }}
                </a>
            @endif
         

            
        </div>
    </form>
</x-guest-layout>
