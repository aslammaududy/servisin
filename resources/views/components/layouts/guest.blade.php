<x-slot:title>
    {{ $title ?? 'Sheaf UI' }}
</x-slot:title>

<x-layouts.base>

    <x-layouts.partials.header />
    
    <div class="pt-24">
        {{ $slot }}
    </div>

    <x-layouts.partials.footer />
</x-layouts.base>
