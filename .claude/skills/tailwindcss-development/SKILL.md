---
name: tailwindcss-development
description: >-
  Styles applications using Tailwind CSS v4 utilities. Activates when adding styles, restyling components,
  working with gradients, spacing, layout, flex, grid, responsive design, dark mode, colors,
  typography, or borders; or when the user mentions CSS, styling, classes, Tailwind, restyle,
  hero section, cards, buttons, or any visual/UI changes.
---

# Tailwind CSS v4 Development

## When to Apply

Activate this skill when:

- Adding styles to components or pages
- Working with responsive design
- Implementing dark mode
- Extracting repeated patterns into components
- Debugging spacing or layout issues

## Documentation

Use `search-docs` for detailed Tailwind CSS v4 patterns and documentation.

## Basic Usage

- Use Tailwind CSS classes to style HTML. Check and follow existing Tailwind conventions in the project before introducing new patterns.
- Offer to extract repeated patterns into components that match the project's conventions (e.g., Blade, JSX, Vue).
- Consider class placement, order, priority, and defaults. Remove redundant classes, add classes to parent or child elements carefully to reduce repetition, and group elements logically.

## Tailwind CSS v4 Specifics

- Always use Tailwind CSS v4 and avoid deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.
- `tailwind.config.js` is no longer needed — configuration is CSS-first.

### CSS-First Configuration

In Tailwind v4, configuration is CSS-first using the `@theme` directive — no separate `tailwind.config.js` file is needed:

<code-snippet name="CSS-First Config" lang="css">
@theme {
  --color-brand: oklch(0.72 0.11 178);
  --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
}
</code-snippet>

All design tokens use native CSS custom properties, enabling dynamic theming.

### Import Syntax

In Tailwind v4, import Tailwind with a regular CSS `@import` statement instead of the `@tailwind` directives used in v3:

<code-snippet name="v4 Import Syntax" lang="diff">
- @tailwind base;
- @tailwind components;
- @tailwind utilities;
+ @import "tailwindcss";
</code-snippet>

### Content Detection

Tailwind v4 uses automatic content detection — no `content` array in config needed. Use `@source` directives for additional paths:

<code-snippet name="Source Directives" lang="css">
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../**/*.blade.php';
</code-snippet>

### Dark Mode

This project uses a custom dark mode variant:

<code-snippet name="Custom Dark Mode" lang="css">
@custom-variant dark (&:where(.dark, .dark *));
</code-snippet>

If existing pages and components support dark mode, new pages and components must support it the same way using the `dark:` variant:

<code-snippet name="Dark Mode" lang="html">
<div class="bg-white dark:bg-neutral-800/10 text-neutral-900 dark:text-neutral-50">
    Content adapts to color scheme
</div>
</code-snippet>

### Replaced Utilities

Tailwind v4 removed deprecated utilities. Use the replacements shown below:

| Deprecated | Replacement |
|------------|-------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |

### Important Modifier

The `!` important modifier should be placed at the end of the class name:

<code-snippet name="Important Modifier" lang="diff">
- !flex
+ flex!
</code-snippet>

### New Features in v4

- **Oxide Engine:** Rust-based engine providing 5x faster full builds and 100x faster incremental builds.
- **Native CSS Variables:** All design tokens use native CSS variables for dynamic theming.
- **Dynamic Spacing:** Spacing utilities derived from a base spacing variable; accept any value without arbitrary syntax.
- **Built-in Container Queries:** Container queries supported natively (no plugin needed).
- **3D Transform Utilities:** `rotate-x-45`, `rotate-y-12`, `perspective-500`.
- **Arbitrary Value Syntax:** Uses parentheses for CSS variables: `bg-(--brand-color)` instead of `bg-[--brand-color]`.
- **Variants Order:** Stacked variants apply left to right instead of right to left.

## Spacing

Use `gap` utilities instead of margins for spacing between siblings:

<code-snippet name="Gap Utilities" lang="html">
<div class="flex gap-8">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
</code-snippet>

## Common Patterns

### Flexbox Layout

<code-snippet name="Flexbox Layout" lang="html">
<div class="flex items-center justify-between gap-4">
    <div>Left content</div>
    <div>Right content</div>
</div>
</code-snippet>

### Grid Layout

<code-snippet name="Grid Layout" lang="html">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div>Card 1</div>
    <div>Card 2</div>
    <div>Card 3</div>
</div>
</code-snippet>

## Common Pitfalls

- Using deprecated v3 utilities (bg-opacity-*, flex-shrink-*, etc.)
- Using `@tailwind` directives instead of `@import "tailwindcss"`
- Trying to use `tailwind.config.js` instead of CSS `@theme` directive
- Using margins for spacing between siblings instead of gap utilities
- Forgetting to add dark mode variants when the project uses dark mode
- Using `!flex` instead of `flex!` for the important modifier
- Using square brackets for CSS variables instead of parentheses: `bg-(--brand-color)`
