<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure</title>


    <!-- Security script to prevent browser dev tools -->
    <script src="https://cdn.jsdelivr.net/npm/disable-devtool@0.3.7/disable-devtool.min.js"></script>
    <script>
        // Initialize DisableDevtool with options
        DisableDevtool({
            ondevtoolopen: function() {
                // Redirect to a warning page or reload
                window.location.reload();
            },
            interval: 1000, // Check interval
            disableMenu: true, // Disable context menu
            url: "about:blank" // URL to redirect if detected
        });
    </script>

    <!-- JavaScript obfuscator from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/javascript-obfuscator@4.0.2/dist/index.browser.js"></script>

</head>

<body>
    <div class="container" style="display: none;z-index:-999;">

    </div>

    <!-- Add Content Security Policy META tag for extra protection -->
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self' https: 'unsafe-inline'; img-src 'self' https: data:; font-src 'self' https: data:;">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JQuery for security functions -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- Additional Security: js-sha256 for client-side hashing -->
    <script src="https://cdn.jsdelivr.net/npm/js-sha256@0.9.0/src/sha256.min.js"></script>

    <!-- Additional Security: UAParser for browser fingerprinting -->
    <script src="https://cdn.jsdelivr.net/npm/ua-parser-js@1.0.2/dist/ua-parser.min.js"></script>

    <!-- UglifyJS (terser) for runtime code minification -->
    <script src="https://cdn.jsdelivr.net/npm/terser@5.14.2/dist/bundle.min.js"></script>

    <!-- Security Script -->
    <script>
        // Obfuscated code will be generated and injected here by JavaScript Obfuscator
        var securityCode = function() {
            // Prevent right-click
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                return false;
            });

            // Disable keyboard shortcuts for developer tools
            document.addEventListener('keydown', function(e) {
                // F12 key
                if (e.keyCode === 123) {
                    e.preventDefault();
                    return false;
                }

                // Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C
                if (e.ctrlKey &&
                    (e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67))) {
                    e.preventDefault();
                    return false;
                }

                // Ctrl+U (View source)
                if (e.ctrlKey && e.keyCode === 85) {
                    e.preventDefault();
                    return false;
                }
            });

            // Detect if devtools is open
            function detectDevTools() {
                const widthThreshold = 160;
                const heightThreshold = 160;

                // Check if dimensions of the window differ significantly
                if (window.outerWidth - window.innerWidth > widthThreshold ||
                    window.outerHeight - window.innerHeight > heightThreshold) {
                    document.body.innerHTML = '';
                    window.location.href = 'about:blank';
                }
            }

            // Run detection periodically
            setInterval(detectDevTools, 1000);

            // Create browser fingerprint
            const parser = new UAParser();
            const result = parser.getResult();
            const fingerprint = sha256(JSON.stringify({
                browser: result.browser,
                os: result.os,
                device: result.device,
                cpu: result.cpu,
                screen: {
                    width: screen.width,
                    height: screen.height,
                    availWidth: screen.availWidth,
                    availHeight: screen.availHeight,
                    colorDepth: screen.colorDepth
                },
                timezone: new Date().getTimezoneOffset(),
                language: navigator.language
            }));

            // Store fingerprint in session storage
            sessionStorage.setItem('security_token', fingerprint);

            // Toggle password visibility
            document.getElementById('togglePassword').addEventListener('click', function() {
                const passwordField = document.getElementById('password');
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Toggle icon
                this.innerHTML = type === 'password' ?
                    '<i class="fas fa-eye"></i>' :
                    '<i class="fas fa-eye-slash"></i>';
            });

            // Add form submission security
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                // Add security token to the form
                const securityInput = document.createElement('input');
                securityInput.type = 'hidden';
                securityInput.name = 'security_token';
                securityInput.value = fingerprint;
                this.appendChild(securityInput);

                // Add timestamp
                const timestampInput = document.createElement('input');
                timestampInput.type = 'hidden';
                timestampInput.name = 'login_timestamp';
                timestampInput.value = new Date().toISOString();
                this.appendChild(timestampInput);

                // Hash the password client-side before sending (for extra protection in transit)
                // Note: Server must be configured to handle this!
                // const passwordField = document.getElementById('password');
                // const passwordHash = sha256(passwordField.value);
                // passwordField.value = passwordHash;
            });
        };

        // Obfuscate the security code
        window.onload = function() {
            try {
                // Run the security code
                securityCode();

                // Obfuscate it after execution for any future calls
                var obfuscatedCode = JavaScriptObfuscator.obfuscate(
                    securityCode.toString(), {
                        compact: true,
                        controlFlowFlattening: true,
                        controlFlowFlatteningThreshold: 1,
                        deadCodeInjection: true,
                        deadCodeInjectionThreshold: 1,
                        debugProtection: true,
                        debugProtectionInterval: true,
                        disableConsoleOutput: true,
                        identifierNamesGenerator: 'hexadecimal',
                        renameGlobals: false,
                        rotateStringArray: true,
                        selfDefending: true,
                        stringArray: true,
                        stringArrayEncoding: ['base64'],
                        stringArrayThreshold: 1,
                        transformObjectKeys: true,
                        unicodeEscapeSequence: true
                    }
                ).getObfuscatedCode();

                // Create a new obfuscated function
                eval('window._securityModule = function() {' + obfuscatedCode + '}');

                // Check periodically if the security module is still active
                setInterval(function() {
                    if (typeof window._securityModule === 'function') {
                        window._securityModule();
                    } else {
                        window.location.reload();
                    }
                }, 5000);
            } catch (e) {
                // Fail silently
            }
        };
    </script>

    <!-- Add webpack-like minification at runtime -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all inline scripts except this one
            const scripts = document.querySelectorAll('script:not([src])');
            for (let i = 0; i < scripts.length; i++) {
                if (scripts[i] !== document.currentScript) {
                    try {
                        // Minify using Terser
                        if (typeof Terser !== 'undefined') {
                            Terser.minify(scripts[i].innerHTML).then(function(result) {
                                if (result.code) {
                                    scripts[i].innerHTML = result.code;
                                }
                            });
                        }
                    } catch (e) {
                        // Fail silently
                    }
                }
            }
        });
    </script>
</body>

</html>
