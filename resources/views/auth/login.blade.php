<x-guest-layout>
    <div class="flex flex-col items-center mb-6">
        <x-application-logo />
        <h1 class="mt-4 text-2xl font-bold">Tekrar Hoş Geldin</h1>
        <p class="text-sm text-slate-300">Hesabınıza giriş yapın</p>
    </div>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="E-posta" />
            <x-text-input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Parola" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center space-x-2">
                <input id="remember" type="checkbox" name="remember" class="rounded border-slate-600 bg-slate-800 text-amber-500 focus:ring-amber-400" />
                <span>Beni hatırla</span>
            </label>
            <a class="text-amber-400 hover:underline" href="{{ route('password.request') }}">Parolanızı mı unuttunuz?</a>
        </div>

        <x-primary-button>Giriş Yap</x-primary-button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-300">
        Hesabınız yok mu?
        <a href="{{ route('register') }}" class="text-amber-400 hover:underline">Kayıt Ol</a>
    </div>
</x-guest-layout>
