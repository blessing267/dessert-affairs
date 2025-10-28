# Dessert Affairs Website (ACF + Timber/Twig + SCSS)

A small custom WordPress theme demonstrating preferred stack:
- WordPress with **Timber/Twig** templating
- **ACF** for flexible content (Hero section, Project meta)
- **SCSS** compiled to CSS and enqueued with cache-busting

## Features
- Front page hero managed via ACF (headline, subheadline, background image, CTA)
- Custom Post Type: **Projects** (client name, URL, thumbnail)
- Responsive layout with a small SCSS architecture

## Structure
- `views/front-page.twig` — homepage hero using ACF
- `views/archive-project.twig` & `views/single-project.twig` — CPT templates
- `assets/scss` → compiled to `assets/css/main.css` via Live Sass Compiler

## Why this stack
- **Twig** keeps templates clean and maintainable
- **ACF** gives editors flexible content without breaking layouts
- **SCSS** organizes styles and scales as features grow
