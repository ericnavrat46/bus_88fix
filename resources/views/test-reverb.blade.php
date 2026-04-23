<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Reverb</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <h1>Reverb Test</h1>
    <div id="messages">
        <p>Waiting for messages...</p>
    </div>

    <script type="module">
        window.addEventListener('load', () => {
            window.Echo.channel('test-channel')
                .listen('.test.event', (e) => {
                    console.log('Event received:', e);
                    const messages = document.getElementById('messages');
                    const p = document.createElement('p');
                    p.innerText = 'New message: ' + e.message;
                    messages.appendChild(p);
                });
        });
    </script>
</body>
</html>
