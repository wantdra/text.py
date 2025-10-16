<x-guest-layout>
    <div class="text-center mb-6">
        <x-application-logo class="mx-auto" />
        <h1 class="mt-4 text-2xl font-bold">E-posta Doğrulaması</h1>
        <p class="text-sm text-slate-300">Başlamak için lütfen e-postanıza gönderilen doğrulama bağlantısını onaylayın.</p>
    </div>

    <x-auth-session-status :status="session('status')" />

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
            @csrf
            <x-primary-button>Doğrulama E-postasını Yeniden Gönder</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-secondary-button type="submit" class="w-full">Çıkış Yap</x-secondary-button>
        </form>
    </div>
</x-guest-layout>
