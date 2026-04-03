@php
    $type = $type ?? 'custom';
    $size = $size ?? 24;
    $className = $className ?? 'icons text-gray-500';
@endphp

@switch($type)
    @case('linkedin')
        <x-linkedin-bol-icon class="{{ $className }} text-linkedin" width="{{ $size }}px" height="{{ $size }}px"/>
        @break

    @case('twitter')
        <x-twitter-bol-icon class="{{ $className }} text-twitter" width="{{ $size }}px" height="{{ $size }}px"/>
        @break

    @case('facebook')
        <x-facebook-bol-icon class="{{ $className }} text-facebook" width="{{ $size }}px" height="{{ $size }}px"/>
        @break

    @case('github')
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="currentColor" class="{{ $className }} text-dark">
            <path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.57.1.78-.25.78-.55v-1.94c-3.2.7-3.88-1.37-3.88-1.37-.53-1.33-1.29-1.68-1.29-1.68-1.05-.72.08-.71.08-.71 1.16.08 1.77 1.19 1.77 1.19 1.03 1.76 2.71 1.25 3.37.96.1-.75.4-1.25.73-1.53-2.55-.29-5.22-1.27-5.22-5.66 0-1.25.45-2.27 1.18-3.07-.12-.29-.51-1.46.11-3.05 0 0 .97-.31 3.17 1.17a10.9 10.9 0 0 1 5.78 0c2.2-1.48 3.17-1.17 3.17-1.17.62 1.59.23 2.76.11 3.05.74.8 1.18 1.82 1.18 3.07 0 4.4-2.68 5.37-5.24 5.65.42.36.79 1.04.79 2.11v3.12c0 .3.2.66.79.55A11.5 11.5 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"/>
        </svg>
        @break

    @case('youtube')
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="currentColor" class="{{ $className }} text-danger">
            <path d="M23.5 7.2a3.4 3.4 0 0 0-2.4-2.4C19 4.2 12 4.2 12 4.2s-7 0-9.1.6A3.4 3.4 0 0 0 .5 7.2 35 35 0 0 0 0 12a35 35 0 0 0 .5 4.8 3.4 3.4 0 0 0 2.4 2.4c2.1.6 9.1.6 9.1.6s7 0 9.1-.6a3.4 3.4 0 0 0 2.4-2.4A35 35 0 0 0 24 12a35 35 0 0 0-.5-4.8ZM9.6 15.5V8.5L16 12l-6.4 3.5Z"/>
        </svg>
        @break

    @case('instagram')
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" class="{{ $className }}">
            <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="2"/>
            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
            <circle cx="17.2" cy="6.8" r="1.2" fill="currentColor"/>
        </svg>
        @break

    @case('behance')
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" class="{{ $className }}">
            <rect x="2" y="2" width="20" height="20" rx="4" fill="currentColor" fill-opacity="0.12"/>
            <path d="M7 8h4c1.5 0 2.5.8 2.5 2s-.7 1.6-1.2 1.8c.8.2 1.7.8 1.7 2.1 0 1.7-1.3 2.6-3 2.6H7V8Zm2 3h1.8c.5 0 .8-.3.8-.8 0-.6-.4-.8-.9-.8H9V11Zm0 3.8h2c.7 0 1.1-.3 1.1-1 0-.6-.4-.9-1.1-.9H9v1.9Z" fill="currentColor"/>
            <path d="M16 9h5v1h-5V9Zm2.5 7.7c-1.8 0-3.1-1.2-3.1-3.2s1.3-3.2 3-3.2c2 0 3.1 1.5 3.1 3.4H17.5c.1.8.6 1.3 1.4 1.3.6 0 1-.2 1.2-.7h1.8c-.3 1.4-1.5 2.4-3.4 2.4Zm-1-4h2.2c-.1-.7-.5-1.1-1.1-1.1-.6 0-1 .4-1.1 1.1Z" fill="currentColor"/>
        </svg>
        @break

    @case('dribbble')
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" class="{{ $className }}">
            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
            <path d="M7 6.8c2.7 2.4 4.8 6 6 10.4" stroke="currentColor" stroke-width="1.8"/>
            <path d="M4.2 11.8c3-.7 8-.9 13.6.7" stroke="currentColor" stroke-width="1.8"/>
            <path d="M10.5 3.8c2.8 3.6 5.2 8.7 5.8 13.6" stroke="currentColor" stroke-width="1.8"/>
        </svg>
        @break

    @case('medium')
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" class="{{ $className }}">
            <rect x="2" y="2" width="20" height="20" rx="4" fill="currentColor" fill-opacity="0.12"/>
            <circle cx="8.5" cy="12" r="3.5" fill="currentColor"/>
            <ellipse cx="14.8" cy="12" rx="2.2" ry="3.2" fill="currentColor" fill-opacity="0.85"/>
            <ellipse cx="19" cy="12" rx="1" ry="2.5" fill="currentColor" fill-opacity="0.65"/>
        </svg>
        @break

    @case('portfolio')
        <x-iconsax-lin-global class="{{ $className }} text-primary" width="{{ $size }}px" height="{{ $size }}px"/>
        @break

    @case('website')
        <x-iconsax-lin-link class="{{ $className }} text-primary" width="{{ $size }}px" height="{{ $size }}px"/>
        @break

    @default
        <x-iconsax-lin-link class="{{ $className }} text-gray-500" width="{{ $size }}px" height="{{ $size }}px"/>
@endswitch
