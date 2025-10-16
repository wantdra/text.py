<x-guest-layout>
    <div class="text-center mb-6">
        <x-application-logo class="mx-auto" />
        <h1 class="mt-4 text-2xl font-bold">Yeni Parola Belirle</h1>
        <p class="text-sm text-slate-300">Yeni parolanızı girin ve onaylayın.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="E-posta" />
            <x-text-input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Yeni Parola" />
            <x-text-input id="password" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Parola (Tekrar)" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required />
        </div>

        <x-primary-button>Parolamı Sıfırla</x-primary-button>
    </form>
</x-guest-layout>
