import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import containerQueries from '@tailwindcss/container-queries';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                "on-tertiary": "#ffffff",
                "on-surface-variant": "#45464d",
                "surface-tint": "#565e74",
                "primary-fixed": "#dae2fd",
                "inverse-on-surface": "#eff1f3",
                "surface-container-lowest": "#ffffff",
                "on-primary-fixed": "#131b2e",
                "on-surface": "#191c1e",
                "surface-container-highest": "#e0e3e5",
                "on-error-container": "#93000a",
                "secondary-fixed-dim": "#b4c5ff",
                "error": "#ba1a1a",
                "surface-container-low": "#f2f4f6",
                "surface-dim": "#d8dadc",
                "primary": "#000000",
                "error-container": "#ffdad6",
                "on-tertiary-fixed": "#0b1c30",
                "secondary-container": "#316bf3",
                "on-secondary": "#ffffff",
                "on-background": "#191c1e",
                "on-tertiary-fixed-variant": "#38485d",
                "primary-fixed-dim": "#bec6e0",
                "tertiary-container": "#0b1c30",
                "primary-container": "#131b2e",
                "surface": "#f7f9fb",
                "on-error": "#ffffff",
                "surface-container-high": "#e6e8ea",
                "on-secondary-fixed-variant": "#003ea8",
                "on-tertiary-container": "#75859d",
                "tertiary": "#000000",
                "outline-variant": "#c6c6cd",
                "surface-bright": "#f7f9fb",
                "secondary": "#0051d5",
                "surface-container": "#eceef0",
                "secondary-fixed": "#dbe1ff",
                "background": "#f7f9fb",
                "inverse-primary": "#bec6e0",
                "on-secondary-fixed": "#00174b",
                "inverse-surface": "#2d3133",
                "on-primary-fixed-variant": "#3f465c",
                "tertiary-fixed": "#d3e4fe",
                "on-secondary-container": "#fefcff",
                "on-primary-container": "#7c839b",
                "on-primary": "#ffffff",
                "outline": "#76777d",
                "tertiary-fixed-dim": "#b7c8e1",
                "surface-variant": "#e0e3e5"
            },
            borderRadius: {
                "DEFAULT": "0.125rem",
                "lg": "0.25rem",
                "xl": "0.5rem",
                "full": "0.75rem"
            },
            spacing: {
                "base": "8px",
                "xs": "4px",
                "gutter": "24px",
                "sm": "12px",
                "lg": "40px",
                "md": "24px",
                "xl": "64px",
                "container-max": "1440px"
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                "h3": ["Public Sans"],
                "label-caps": ["Public Sans"],
                "h2": ["Public Sans"],
                "h1": ["Public Sans"],
                "data-tabular": ["Public Sans"],
                "body-base": ["Public Sans"],
                "body-sm": ["Public Sans"]
            },
            fontSize: {
                "h3": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                "label-caps": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700" }],
                "h2": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                "h1": ["30px", { "lineHeight": "38px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                "data-tabular": ["14px", { "lineHeight": "20px", "fontWeight": "500" }],
                "body-base": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }]
            }
        },
    },

    plugins: [forms, containerQueries],
};
