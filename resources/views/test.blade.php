<html>

<head>
    <title>Test Streaming</title>
    <link rel="stylesheet" href="{{ mix('css/frontend.css') }}">
</head>

<body class="bg-light">
    <div class="container">
        <div id="video"></div>
    </div>
    <script src="{{ mix('js/frontend.js') }}"></script>
    <script src="https://cdn.jwplayer.com/libraries/jEt7sw4C.js"></script>
    <script>
        jwplayer('video').setup({
            'playlist': [{
                'file': '{{ $video_url }}'
            }]
        });

    </script>
</body>

</html>
