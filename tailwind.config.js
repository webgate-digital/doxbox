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
            'lg': '10px',
            'md': '9px',
            'default': '9px',
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
                10: '#E9EAEA',
                20: '#D3D4D5',
                40: '#A8A9AA',
                50: '#A8A9AA',
                60: '#8C8D8E',
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
                'heading-2xl': ['4.8rem', {lineHeight: 'auto'}],
                'heading-xl': ['3.8rem', {lineHeight: 'auto'}],
                'heading-l': ['3.4rem', {lineHeight: 'auto'}],
                'heading-m': ['3rem', {lineHeight: 'auto'}],
                'heading-s': ['2.6rem', {lineHeight: 'auto'}],
                'heading-xs': ['2.2rem', {lineHeight: 'auto'}],
                'heading-2xs': ['2rem', {lineHeight: 'auto'}],
                'subheading-xl': '1.7rem',
                'subheading-l': '1.5rem',
                'subheading-m': '1.2rem',
                'subheading-s': '1.2rem',
                'subheading-xs': '1.2rem',
                'body-xl': '1.8rem',
                'body-l': '1.6rem',
                'body-m': ['1.4rem', {lineHeight: '2.2rem'}],
                'body-s': '1.3rem',
                'body-xs': '1.2rem',
                
                'md-heading-2xl': ['12rem', {lineHeight: '14.063rem'}],
                'md-heading-xl': ['9.6rem', {lineHeight: '11.25rem'}],
                'md-heading-l': ['8.4rem', {lineHeight: '9.844rem'}],
                'md-heading-m': ['7.2rem', {lineHeight: '8.438rem'}],
                'md-heading-s': ['6rem', {lineHeight: '7.031rem'}],
                'md-heading-xs': ['4.8rem', {lineHeight: '5.625rem'}],
                'md-heading-2xs': ['3.6rem', {lineHeight: '4.219rem'}],
                'md-subheading-xl': '2.4rem',
                'md-subheading-l': '2rem',
                'md-subheading-m': '1.6rem',
                'md-subheading-s': '1.4rem',
                'md-subheading-xs': '1.2rem',
                'md-body-xl': '2.4rem',
                'md-body-l': '2.0rem',
                'md-body-m': ['1.6rem', {lineHeight: '2.2rem'}],
                'md-body-s': '1.4rem',
                'md-body-xs': '1.2rem',
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
            screens: {
                '-sm': {'max': '639px'},
            }
        },
    },
    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },
    plugins: [],
    content: [
        './resources/**/*.vue',
    ]
}
