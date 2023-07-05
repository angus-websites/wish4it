import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                serif: ['Comic Neue', ...defaultTheme.fontFamily.serif],
            },

            colors: {
                primary: {
                    light2: "#745CB4",
                    light: "#6147A9",
                    DEFAULT: "#543E90",
                    dark: "#4A387A",
                    dark2: "#453964",
                },

                accent: {
                    light2: "#8EF645",
                    light: "#75E626",
                    DEFAULT: "#58D500",
                    dark: "#51BD04",
                    dark2: "#48A109",
                },

                dark: {
                    light2: "#4A6682",
                    light: "#344D66",
                    DEFAULT: "#24384C",
                    dark: "#253442",
                    dark2: "#24313D",
                },

                light: {
                    light2: "#FFFFFF",
                    light: "#FAFAFA",
                    DEFAULT: "#F3F3F3",
                    dark: "#DEDEDE",
                    dark2: "#D0D0D0",
                },

                bgc:{
                    DEFAULT: "#F3F4F6",
                    dark:  "#17202A",
                },

                txt: {
                    DEFAULT: "#94A2B8",
                    dark:  "#334255",
                }
            },
        },
    },

    plugins: [forms, typography],
};
