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
    plugins: [],
};
