<x-guest-layout>
    <div class="flex flex-col items-center mb-6">
        <x-application-logo />
        <h1 class="mt-4 text-2xl font-bold">Yeni Hesap Oluştur</h1>
        <p class="text-sm text-slate-300">SRS kartlarınıza hemen başlayın</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Ad Soyad" />
            <x-text-input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="E-posta" />
            <x-text-input id="email" type="email" name="email" value="{{ old('email') }}" required />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Parola" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Parola (Tekrar)" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required />
        </div>

        <x-primary-button>Kayıt Ol</x-primary-button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-300">
        Zaten hesabınız var mı?
        <a href="{{ route('login') }}" class="text-amber-400 hover:underline">Giriş Yap</a>
    </div>
</x-guest-layout>
