import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/views/website/*.blade.php",
        "./resources/views/website/includes/*.blade.php",
        "./resources/views/website/pages/**/*.blade.php",
        "./resources/views/user/**/*.blade.php",
        "./resources/views/user/pages/**/*.blade.php",
        "./database/seeders/*.php"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        require('flowbite/plugin'),
    ],
};
