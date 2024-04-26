/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "node_modules/preline/dist/*.js",
    ],
    plugins: [require("preline/plugin")],
    theme: {
        colors: {
            navy: {
                // navy color
                100: "#9FA8DA",
                200: "#7986CB",
                300: "#5C6BC0",
                400: "#3F51B5",
                500: "#3949AB",
                600: "#303F9F",
                700: "#283593",
                800: "#1A237E",
                900: "#0D1A52",
            },
            blue: {
                // blue color
                100: "#BBDEFB",
                200: "#90CAF9",
                300: "#64B5F6",
                400: "#42A5F5",
                500: "#2196F3",
                600: "#1E88E5",
                700: "#1976D2",
                800: "#1565C0",
                900: "#0D47A1",
            },
            yellow: {
                100: "#FFFDE7",
                200: "#FFF9C4",
                300: "#FFF59D",
                400: "#FFF176",
                500: "#FFEE58",
                600: "#FFEB3B",
                700: "#FDD835",
                800: "#FBC02D",
                900: "#F9A825",
            },
            light: {
                100: "#FFFFFF",
                200: "#f9fbfe",
                300: "#f2f7ff",
                400: "#e9f1ff",
                500: "#e1e9ff",
                600: "#d8e2ff",
                700: "#d0d9ff",
                800: "#c7d1ff",
                900: "#c0c9ff",
            },
            dark: {
                100: "#2F2F2F",
                200: "#2D2D2D",
                300: "#2B2B2B",
                400: "#1B1B1B",
                500: "#141414",
                600: "#0F0F0F",
                700: "#0A0A0A",
                800: "#050505",
                900: "#000000",
            },
            gray: {
                100: "#f8f9fa",
                200: "#e9ecef",
                300: "#dee2e6",
                400: "#ced4da",
                500: "#adb5bd",
                600: "#6c757d",
                700: "#495057",
                800: "#343a40",
                900: "#212529",
            },
            transparent: "transparent",
        },
        fontFamily: {
            display: ["Oswald", "sans-serif"],
            body: ["Source Sans Pro", "sans-serif"],
        },
        screens: {
            sm: "640px",
            md: "768px",
            lg: "1024px",
            xl: "1280px",
            "2xl": "1536px",
        },
        extend: {},
    },
};
