module.exports = {
    mode: 'jit',
    purge: [
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        container: {
            center: true,
            padding: '2rem',
        },
        fontFamily: {
            'body': ['"Roboto", sans-serif']
        },
        fontSize: {
            'default': '10px',
        },
        fontWeight: {
            'body-xl': 400,
            'body-l': 400,
            'body-m': 400,
            'body-s': 400,
            'body-xs': 400,
            'heading-2xl': 800,
            'heading-xl': 800,
            'heading-lg': 800,
            'heading-md': 800,
            'heading-sm': 800,
            'heading-xs': 800,
            'heading-2xs': 800,
            'subheading-xl': 800,
            'subheading-l': 800,
            'subheading-m': 800,
            'subheading-s': 800,
            'subheading-xs': 800,
        },
        colors: {
            'white': '#ffffff',
            'black': '#000000',
            'grey': '#eeeeee',
            'primary': '#4263EB',
            'primary-10': '#E3E7F7',
            'primary-80': '#6680EE',
            'secondary': '#25282B',
            'transparent': 'transparent',
            'danger': '#E93C3C',
            'success': '#1AB759',
            'gray': {
                5: '#F0F0F0',
                10: '#D9D9D9',
                40: '#A8A9AA',
                50: '#A8A9AA',
                80: '#25282B',
                100: '#131416'
            }
        },
        transitionProperty: {
            'height': 'height',
            'all': 'all',
        },
        extend: {
            fontSize: {
                'heading-2xl': ['12rem', {lineHeight: '14.063rem'}],
                'heading-xl': ['9.6rem', {lineHeight: '11.25rem'}],
                'heading-l': ['8.4rem', {lineHeight: '9.844rem'}],
                'heading-m': ['7.2rem', {lineHeight: '8.438rem'}],
                'heading-s': ['6rem', {lineHeight: '7.031rem'}],
                'heading-xs': ['4.8rem', {lineHeight: '5.625rem'}],
                'heading-2xs': ['3.6rem', {lineHeight: '4.219rem'}],
                'subheading-xl': '2.4rem',
                'subheading-l': '2rem',
                'subheading-m': '1.6rem',
                'subheading-s': '1.4rem',
                'subheading-xs': '1.2rem',
                'body-xl': '2.4rem',
                'body-l': '2.0rem',
                'body-m': ['1.6rem', {lineHeight: '2.2rem'}],
                'body-s': '1.4rem',
                'body-xs': '1.2rem',
            }
        },
    },
    variants: {
        extend: {},
    },
    plugins: [],
}
