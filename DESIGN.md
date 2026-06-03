---
name: Earth & Utility
colors:
  surface: '#fff8f3'
  surface-dim: '#e0d9d2'
  surface-bright: '#fff8f3'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#faf2ec'
  surface-container: '#f4ece6'
  surface-container-high: '#efe7e0'
  surface-container-highest: '#e9e1db'
  on-surface: '#1e1b17'
  on-surface-variant: '#4e453d'
  inverse-surface: '#33302c'
  inverse-on-surface: '#f7efe9'
  outline: '#80756b'
  outline-variant: '#d2c4b9'
  surface-tint: '#735a3e'
  primary: '#735a3e'
  on-primary: '#ffffff'
  primary-container: '#c4a484'
  on-primary-container: '#503a21'
  inverse-primary: '#e3c19f'
  secondary: '#635d5a'
  on-secondary: '#ffffff'
  secondary-container: '#e6ded9'
  on-secondary-container: '#67625e'
  tertiary: '#5f5e5b'
  on-tertiary: '#ffffff'
  tertiary-container: '#aba9a6'
  on-tertiary-container: '#3e3e3c'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffddbb'
  primary-fixed-dim: '#e3c19f'
  on-primary-fixed: '#291803'
  on-primary-fixed-variant: '#5a4229'
  secondary-fixed: '#e9e1dc'
  secondary-fixed-dim: '#cdc5c0'
  on-secondary-fixed: '#1e1b18'
  on-secondary-fixed-variant: '#4b4642'
  tertiary-fixed: '#e5e2de'
  tertiary-fixed-dim: '#c8c6c2'
  on-tertiary-fixed: '#1c1c19'
  on-tertiary-fixed-variant: '#474744'
  background: '#fff8f3'
  on-background: '#1e1b17'
  surface-variant: '#e9e1db'
typography:
  headline-lg:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '600'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: '1.2'
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '500'
    lineHeight: '1.2'
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '500'
    lineHeight: '1.3'
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1.4'
    letterSpacing: 0.05em
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: '1.4'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 48px
  xl: 80px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 64px
---

## Brand & Style

This design system is built upon a foundation of organic sophistication and functional clarity. It is designed for professional environments that value a sense of calm, reliability, and tactile quality. By pairing the utilitarian precision of modernist typography with an earthy, natural color palette, the system evokes a "Premium Professional" emotional response—authoritative yet approachable.

The visual style leans into **Minimalism with Tonal Layering**. It avoids excessive decoration in favor of structural integrity, using the warmth of the primary brand color to soften the coldness often found in systematic interfaces. The aesthetic is focused on high-quality spacing, deliberate "ink-trap" legibility, and a hierarchy that feels both architectural and human.

## Colors

The color palette for the design system is anchored by a warm, sophisticated beige extracted from organic textures. This primary tan (`#C4A484`) serves as the core brand identifier, used for key actions and distinctive UI elements. 

The primary color is supported by a deep charcoal (`#2D2926`) for high-contrast typography and primary buttons, ensuring accessibility and a grounded feel. A lighter tertiary cream (`#F4F1ED`) is used for large background surfaces and container fills to create a soft, low-fatigue viewing experience. The neutral palette consists of muted greys with warm undertones to maintain harmony with the primary tan, avoiding the harshness of pure black or clinical blue-greys.

## Typography

The design system utilizes **Inter** exclusively to maintain a clean, systematic, and highly legible interface. The type scale is optimized for information density and clarity. 

Headlines utilize tighter tracking and heavier weights to create a strong visual anchor against the expansive layouts. Body text is set with generous line-heights to promote readability in data-heavy environments. Labels utilize a slight increase in letter-spacing and uppercase styling where necessary to distinguish functional metadata from narrative content. On mobile devices, headline sizes scale down aggressively to ensure no more than three words per line, maintaining the vertical rhythm of the layout.

## Layout & Spacing

This design system follows a **12-column fluid grid** for desktop and a **4-column fluid grid** for mobile. The spacing philosophy is based on an 8px square-grid rhythm, ensuring that every element—from the height of a button to the padding of a card—aligns to a consistent mathematical scale.

Layouts should prioritize generous white space (using `lg` and `xl` tokens) to separate major content sections, reflecting the minimalist brand personality. Gutters are fixed at 24px to provide a breathable air-gap between columns, while outer margins expand dynamically based on screen size to keep content centered and readable. In data-dense views, the `sm` spacing unit is preferred to maintain a compact, "pro-tool" feel.

## Elevation & Depth

Hierarchy in the design system is conveyed through **Tonal Layers** and **Soft Ambient Shadows**. Rather than using heavy drop shadows, depth is created by stacking surfaces of slightly different luminance.

1.  **Base Layer:** The tertiary cream color serves as the canvas.
2.  **Mid Layer:** White or primary-tinted containers sit on the base, using a very soft, diffused shadow (15% opacity of the secondary color) to suggest a slight lift.
3.  **Top Layer:** Interactive elements like modals or floating action buttons use a more pronounced shadow with a larger blur radius to indicate maximum priority.

Outlines are used sparingly, primarily as low-contrast "ghost borders" (1px solid with 10% opacity) to define boundaries on containers without adding visual noise.

## Shapes

The design system employs a **Rounded** shape language to balance the technical nature of the Inter typeface with the warmth of the color palette. 

Standard components like buttons and input fields utilize a 0.5rem (8px) corner radius. For larger structural elements, such as cards or modal containers, the `rounded-lg` (16px) or `rounded-xl` (24px) tokens are applied to create a softer, more modern silhouette. This consistent curvature ensures that even complex layouts feel unified and intentional.

## Components

### Buttons
Primary buttons should use the dark secondary color with white text for maximum impact, or the primary beige with dark text for secondary prominence. They feature 0.5rem rounded corners and medium-weight labels.

### Input Fields
Inputs are defined by a subtle 1px border in a neutral-light shade. Upon focus, the border transitions to the primary tan with a soft outer glow. The background remains white to ensure text contrast.

### Cards
Cards use a white background against the tertiary cream page fill. They should feature `rounded-lg` corners and the "Mid Layer" ambient shadow. Padding inside cards should strictly follow the `md` (24px) spacing unit.

### Chips & Tags
Chips are pill-shaped (using the max-roundedness token) and use a light tint of the primary color with dark text. They are used for filtering or indicating categories without demanding the attention of a full button.

### Lists
Lists utilize the `base` (8px) spacing unit for vertical separation and include a subtle horizontal divider in the neutral-light color to guide the eye across rows.