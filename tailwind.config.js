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
            'body': '1.6rem',
            'small': '1.3rem',
            'medium': '1.4rem',
            'h1': '3.2rem',
            'h2': '2.6rem',
            'h3': '2.2rem',
            'h4': '2rem',
            'h5': '1.8rem',
        },
        colors: {
            'white': '#ffffff',
            'black': '#000000',
            'grey': '#eeeeee',
            'primary': '#333',
            'secondary': '#ae6364',
            'transparent': 'transparent',
            'danger': 'red',
            'success': 'green',
            'gray': {
                50: '#A8A9AA',
                80: '#25282B',
                100: '#131416'
            }
        },
        transitionProperty: {
            'height': 'height',
            'all': 'all',
        },
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins: [],
}
