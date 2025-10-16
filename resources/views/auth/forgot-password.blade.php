<x-guest-layout>
    <div class="text-center mb-6">
        <x-application-logo class="mx-auto" />
        <h1 class="mt-4 text-2xl font-bold">Parolanızı mı unuttunuz?</h1>
        <p class="text-sm text-slate-300">E-posta adresinizi girin, size sıfırlama bağlantısı gönderelim.</p>
    </div>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="E-posta" />
            <x-text-input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button>Bağlantı Gönder</x-primary-button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-300">
        <a href="{{ route('login') }}" class="text-amber-400 hover:underline">Giriş ekranına dön</a>
    </div>
</x-guest-layout>
