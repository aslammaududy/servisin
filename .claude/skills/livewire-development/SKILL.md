---
name: livewire-development
description: >-
  Develops reactive Livewire 4 components. Activates when creating, updating, or modifying
  Livewire components; working with wire:model, wire:click, wire:loading, or any wire: directives;
  adding real-time updates, loading states, or reactivity; debugging component behavior;
  writing Livewire tests; or when the user mentions Livewire, component, counter, or reactive UI.
---

# Livewire 4 Development

## When to Apply

Activate this skill when:
- Creating new Livewire components
- Modifying existing component state or behavior
- Debugging reactivity or lifecycle issues
- Writing Livewire component tests
- Adding Alpine.js interactivity to components
- Working with wire: directives

## Documentation

Use `search-docs` for detailed Livewire 4 patterns and documentation.

## Basic Usage

### Creating Components

Use the `php artisan make:livewire [Posts\CreatePost]` Artisan command to create new components.

### Volt Single-File Components

Livewire 4 uses Volt single-file components (SFC) by default. These combine PHP logic and Blade templates in one file.

- Single-file components use the `⚡` prefix in filenames (e.g., `⚡create.blade.php`).
- Components live in `resources/views/pages/` for page components.
- Namespaces like `pages::booking.create` are used to reference components.

<code-snippet name="Volt Single-File Component" lang="php">
<?php

use Livewire\Component;

new class extends Component {
    public string $name = '';

    public function save(): void
    {
        // action logic
    }
};
?>

<div>
    <form wire:submit="save">
        <x-ui.input wire:model="name" />
        <x-ui.button type="submit">Save</x-ui.button>
    </form>
</div>
</code-snippet>

### Fundamental Concepts

- State should live on the server, with the UI reflecting it.
- All Livewire requests hit the Laravel backend; they're like regular HTTP requests. Always validate form data and run authorization checks in Livewire actions.

## Livewire 4 Specifics

### Key Changes From Livewire 3

- `wire:model` is deferred by default. Use `wire:model.live` for real-time updates.
- Parallel request handling: `wire:model.live` requests run in parallel instead of sequentially, improving responsiveness.
- Components use the `App\Livewire` namespace.
- Use `$this->dispatch()` to dispatch events.
- This project uses Volt single-file components (SFC) with the `⚡` prefix. Use `php artisan make:volt` to create new SFCs.
- The Blaze rendering engine can pre-render static parts of Blade templates at compile time for performance gains.
- PHP 8.4 property hooks are supported for native getters/setters.

### New Directives

- `wire:sort` — Built-in drag-and-drop support for sortable lists.
- `wire:sort:handle` — Drag handles for sortable items.
- `wire:sort:group` — Dragging between multiple lists.
- `wire:transition` — Uses the browser's native View Transitions API (not Alpine wrappers).
- `wire:show` — Toggles visibility with CSS only (no DOM removal or network request).
- `wire:cloak`, `wire:offline`, `wire:target` — Additional UI control directives.
- `data-loading` — Automatically added to elements triggering network requests; use `data-loading:class` for styling.

### Form Objects

Form objects encapsulate form state and validation in a dedicated class extending `Livewire\Form`:

<code-snippet name="Livewire Form Object" lang="php">
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookingForm extends Form
{
    #[Validate('required', message: 'Name is required')]
    public string $name = '';

    #[Validate(['required', 'date'])]
    public string $date = '';

    public function store(): void
    {
        $this->validate();
        Model::create([...]);
    }
}
</code-snippet>

Bind form properties in templates with `wire:model="formName.property"`:

<code-snippet name="Form Object Binding" lang="blade">
<x-ui.input wire:model="bookingForm.name" />
<x-ui.error name="bookingForm.name" />
</code-snippet>

### File Uploads

Use the `WithFileUploads` trait on the component (not the form object):

<code-snippet name="File Upload" lang="php">
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public BookingForm $bookingForm;

    public function save(): void
    {
        $this->bookingForm->store();
    }
};
</code-snippet>

File inputs bind to the form with `wire:model="formName.photo"`.

### Alpine Integration

- Alpine.js is bundled with Livewire 4; don't manually include Alpine.
- Included plugins: persist, intersect, collapse, and focus.
- Use `$wire` in Alpine to access Livewire component state.
- `wire:transition` now uses browser View Transitions API instead of Alpine wrappers.

## Best Practices

### Component Structure

- Livewire components require a single root element.
- Use `wire:loading` and `wire:dirty` for delightful loading states.
- Use `data-loading` attributes for automatic loading indicators.

### Using Keys in Loops

<code-snippet name="Wire Key in Loops" lang="blade">
@foreach ($items as $item)
    <div wire:key="item-{{ $item->id }}">
        {{ $item->name }}
    </div>
@endforeach
</code-snippet>

### Lifecycle Hooks

Prefer lifecycle hooks like `mount()`, `updatedFoo()` for initialization and reactive side effects:

<code-snippet name="Lifecycle Hook Examples" lang="php">
public function mount(User $user): void { $this->user = $user; }
public function updatedSearch(): void { $this->resetPage(); }
</code-snippet>

### Events

Use targeted event dispatching:

<code-snippet name="Event Dispatching" lang="php">
$this->dispatch('event-name', data: $value);
$this->dispatch('event-name')->to(OtherComponent::class);
</code-snippet>

## JavaScript Hooks

<code-snippet name="Livewire Init Hook Example" lang="js">
document.addEventListener('livewire:init', function () {
    Livewire.hook('request', ({ fail }) => {
        if (fail && fail.status === 419) {
            alert('Your session expired');
        }
    });
});
</code-snippet>

## Testing

Test Volt single-file components using the `pages::` namespace:

<code-snippet name="Testing Volt Components" lang="php">
use Livewire\Livewire;

it('renders the create page', function () {
    Livewire::test('pages::booking.create')
        ->assertStatus(200);
});

it('validates required fields', function () {
    Livewire::test('pages::booking.create')
        ->set('bookingForm.name', '')
        ->call('save')
        ->assertHasErrors(['bookingForm.name']);
});
</code-snippet>

<code-snippet name="Testing Standard Components" lang="php">
Livewire::test(Counter::class)
    ->assertSet('count', 0)
    ->call('increment')
    ->assertSet('count', 1)
    ->assertSee(1)
    ->assertStatus(200);
</code-snippet>

## Common Pitfalls

- Forgetting `wire:key` in loops causes unexpected behavior when items change
- Using `wire:model` expecting real-time updates (use `wire:model.live` instead)
- Not validating/authorizing in Livewire actions (treat them like HTTP requests)
- Including Alpine.js separately when it's already bundled with Livewire 4
- Binding file inputs to the component instead of the form object (use `wire:model="formName.photo"`)
- Forgetting to add `use Livewire\WithFileUploads` on the component class for file uploads
- Passing empty string `''` instead of `null` for nullable integer database columns
