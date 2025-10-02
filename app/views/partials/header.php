<!-- Ionic Framework -->
<script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
<script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />

<!-- Ionicons -->
<script type="module">
    import { defineCustomElements } from 'https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js';
    defineCustomElements(window);
</script>

<!-- PWA Meta Tags -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="theme-color" content="#667eea">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

<style>
:root {
    --ion-color-primary: #667eea;
    --ion-color-primary-rgb: 102, 126, 234;
    --ion-color-primary-contrast: #ffffff;
    --ion-color-primary-contrast-rgb: 255, 255, 255;
    --ion-color-primary-shade: #5a6fce;
    --ion-color-primary-tint: #758dec;
    
    --ion-color-secondary: #764ba2;
    --ion-color-secondary-rgb: 118, 75, 162;
    --ion-color-secondary-contrast: #ffffff;
    --ion-color-secondary-contrast-rgb: 255, 255, 255;
    --ion-color-secondary-shade: #68428f;
    --ion-color-secondary-tint: #845dab;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background: #f4f5f8;
    padding-bottom: calc(70px + env(safe-area-inset-bottom));
}
</style>
