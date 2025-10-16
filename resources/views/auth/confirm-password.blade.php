<x-guest-layout>
    <div class="text-center mb-6">
        <x-application-logo class="mx-auto" />
        <h1 class="mt-4 text-2xl font-bold">Parolanızı Onaylayın</h1>
        <p class="text-sm text-slate-300">Bu işlemi tamamlamak için mevcut parolanızı girin.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" value="Parola" />
            <x-text-input id="password" type="password" name="password" required autofocus />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button>Onayla</x-primary-button>
    </form>
</x-guest-layout>
