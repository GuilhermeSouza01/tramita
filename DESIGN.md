# Tramita — Design System

This document covers the two visual themes of Tramita:

- **Tramita Core (Dark)** — admin panel (Filament)
- **Tramita Core Light** — public and authentication screens (Login, Sign Up, Password Recovery)

---

## 1. Tramita Core (Dark) — Admin Panel

### Colors

| Token | Value | Usage |
|---|---|---|
| `surface` | `#0b1326` | Base application background |
| `surface-dim` | `#0b1326` | Background in "dim" state |
| `surface-bright` | `#31394d` | Background in "bright" state |
| `surface-container-lowest` | `#060e20` | Deepest container (e.g. sidebar, background modals) |
| `surface-container-low` | `#131b2e` | Low container |
| `surface-container` | `#1b243b` | Default container (cards) |
| `surface-container-high` | `#252e46` | Elevated container (dropdowns, popovers) |
| `surface-container-highest` | `#31394d` | Most elevated container (tooltips, floating elements) |
| `on-surface` | `#e2e2e9` | Primary text over surface |
| `on-surface-variant` | `#c5c6d0` | Secondary text over surface |
| `outline` | `#8f9099` | Emphasized borders |
| `outline-variant` | `#45464f` | Subtle borders/dividers |
| `primary` | `#4167cf` | Main action color (buttons, links) |
| `on-primary` | `#ffffff` | Text/icon over `primary` |
| `primary-container` | `#314681` | Background for elements with primary emphasis (badges, chips) |
| `on-primary-container` | `#dce1ff` | Text over `primary-container` |
| `secondary` | `#bfc6dc` | Secondary action color |
| `on-secondary` | `#293042` | Text/icon over `secondary` |
| `secondary-container` | `#3f4759` | Background for elements with secondary emphasis |
| `on-secondary-container` | `#dce2f9` | Text over `secondary-container` |
| `error` | `#ffb4ab` | Error states |
| `on-error` | `#690005` | Text/icon over `error` |

### Typography

- **Font family:** `Inter`, sans-serif

| Scale | Size |
|---|---|
| Display | `3.5rem` |
| Headline | `2rem` |
| Title | `1.25rem` |
| Body | `1rem` |
| Label | `0.875rem` |

### Roundness

- `8px` (token: `ROUND_EIGHT`)

---

## 2. Tramita Core Light — Authentication / Public

### Colors

| Token | Value | Usage |
|---|---|---|
| `surface` | `#faf8ff` | Base application background |
| `surface-dim` | `#d2d9f4` | Background in "dim" state |
| `surface-bright` | `#faf8ff` | Background in "bright" state |
| `surface-container-lowest` | `#ffffff` | Deepest container (e.g. login cards) |
| `surface-container-low` | `#f2f3ff` | Low container |
| `surface-container` | `#eceeff` | Default container |
| `surface-container-high` | `#e6e8ff` | Elevated container |
| `surface-container-highest` | `#e1e2f1` | Most elevated container |
| `on-surface` | `#1a1b21` | Primary text over surface |
| `on-surface-variant` | `#45464f` | Secondary text over surface |
| `outline` | `#757780` | Emphasized borders |
| `outline-variant` | `#c5c6d0` | Subtle borders/dividers |
| `primary` | `#4167cf` | Main action color (buttons, links) |
| `on-primary` | `#ffffff` | Text/icon over `primary` |
| `primary-container` | `#dce1ff` | Background for elements with primary emphasis |
| `on-primary-container` | `#00164e` | Text over `primary-container` |

> Note: the Light theme doesn't define `secondary`, `secondary-container`, or `error` — it inherits those values from the Core (Dark) theme when needed, or they should be defined later if the design calls for specific variations on public screens.

### Typography

- **Font family:** `Inter`, sans-serif (same scale as the Dark theme, unless stated otherwise)

### Roundness

- `4px` (token: `ROUND_FOUR`)

---

## 3. Usage Notes

- `primary` (`#4167cf`) is the only brand color shared between both themes — it keeps Tramita's identity consistent between admin and public.
- The Dark theme uses greater roundness (`8px`) than the Light theme (`4px`) — softer corners in the admin panel, more formal/straight corners on authentication screens.
- All `on-*` pairs are defined to ensure proper text contrast over their respective background — always use the correct pair (e.g. text over `primary-container` should use `on-primary-container`, never `on-primary`).
