<x-slot:title>
    {{ $title ?? 'Sheaf UI' }}
</x-slot:title>

<x-layouts.base>
    <x-layouts.partials.nav />

    <div class="max-w-5xl mx-auto mt-30 px-4 lg:px-8">
        {{ $slot }}
    </div>
</x-layouts.base>
