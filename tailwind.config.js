/** @type {import('tailwindcss').Config} */
module.exports = {
    // Scan every Blade view + the design_1 front-end JS so the JIT build
    // includes the same classes the Play CDN generated at runtime.
    content: [
        './resources/views/**/*.blade.php',
        './resources/views/**/*.php',
        './public/assets/design_1/js/**/*.js',
    ],
    theme: {
        extend: {},
    },
    // The legacy design_1 theme (app.min.css) already ships a full set of
    // pixel-based numeric utilities (e.g. `.size-80` = 80px). Tailwind's
    // rem-based `size-*` scale collides with those and — because the theme's
    // `.size-*` rules have no `!important` while tailwind-built.css loads
    // last — Tailwind wins, inflating avatars/icons (size-80 -> 20rem ≈ 280px)
    // and breaking layouts on phones. Disable Tailwind's `size` utility so the
    // theme's pixel definitions are the single source of truth. (All other
    // numeric collisions like mx-*/py-*/gap-* are safe: the theme marks those
    // `!important`, so it already wins them.)
    corePlugins: {
        size: false,
    },
    plugins: [],
};
