import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
// import withMT from "@material-tailwind/html/utils/withMT";

// /** @type {import('tailwindcss').Config} */
// export default withMT( {
//     content: [
//         './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
//         './storage/framework/views/*.php',
//         './resources/views/**/*.blade.php',
//         './resources/js/**/*.vue',
//         'node_modules/flowbite-vue/**/*.{js,jsx,ts,tsx,vue}',
//         'node_modules/flowbite/**/*.{js,jsx,ts,tsx}',
//         './index.html',
//         './src/**/*.{vue,js,ts,jsx,tsx}',
        
//     ],

//     theme: {
//         extend: {
//             fontFamily: {
//                 sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                
//             },
//             width: {
//                 '1/8': '12.5%',
//                 '2/8': '25%',
//                 '3/8': '37.5%',
//                 '4/8': '50%',
//                 '5/8': '62.5%',
//                 '6/8': '75%',
//                 '7/8': '87.5%',
//                 // その他の必要な比率
//             },
//         },
//     },

//     plugins: [
//         forms,
//         require('@tailwindcss/forms'),
//         require('flowbite/plugin'),
//         // require('@vue-swatches/plugin'),
//     ],
// });

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        'node_modules/flowbite-vue/**/*.{js,jsx,ts,tsx,vue}',
        'node_modules/flowbite/**/*.{js,jsx,ts,tsx}',
        './index.html',
        './src/**/*.{vue,js,ts,jsx,tsx}',
        
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                
            },
            width: {
                '1/8': '12.5%',
                '2/8': '25%',
                '3/8': '37.5%',
                '4/8': '50%',
                '5/8': '62.5%',
                '6/8': '75%',
                '7/8': '87.5%',
                // その他の必要な比率
            },
        },
    },

    plugins: [
        forms,
        require('@tailwindcss/forms'),
        // require('flowbite/plugin'),
        // require('@vue-swatches/plugin'),
    ],
};

