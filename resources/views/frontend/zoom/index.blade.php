@php
    $VERSION_NUMBER = '2.0.1';
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lớp học trực tuyến</title>
    <meta>
        <!-- import Web Meeting SDK CSS -->
        <link type="text/css" rel="stylesheet" href="https://source.zoom.us/{{$VERSION_NUMBER}}/css/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="https://source.zoom.us/{{$VERSION_NUMBER}}/css/react-select.css" />
    </meta>
    <style>
        html, body {
            min-width: 0 !important;
        }

        #zmmtg-root {
            display: none;
        }

    </style>
</head>
<body>
    <!-- added on import -->
    <div style="position: fixed; top: 0; left: 0; z-index: 1; width: 180px;">
        <a href="{{ route('home') }}">
            <img src="{{ config('settings.logo') ?? asset('tomato/assets/img/logo.png') }}" style="width: 100%">
        </a>
    </div>
    <div id="zmmtg-root"></div>
    <div id="aria-notify-area"></div>

    <!-- import Web Meeting SDK JS dependencies -->
	<script src="https://source.zoom.us/{{$VERSION_NUMBER}}/lib/vendor/react.min.js"></script>
	<script src="https://source.zoom.us/{{$VERSION_NUMBER}}/lib/vendor/react-dom.min.js"></script>
	<script src="https://source.zoom.us/{{$VERSION_NUMBER}}/lib/vendor/redux.min.js"></script>
	<script src="https://source.zoom.us/{{$VERSION_NUMBER}}/lib/vendor/redux-thunk.min.js"></script>
	<script src="https://source.zoom.us/{{$VERSION_NUMBER}}/lib/vendor/lodash.min.js"></script>

	<!-- import Web Meeting SDK -->
	<script src="https://source.zoom.us/zoom-meeting-{{$VERSION_NUMBER}}.min.js"></script>
    <script>
        ZoomMtg.setZoomJSLib('https://source.zoom.us/{{$VERSION_NUMBER}}/lib', '/av');
        ZoomMtg.preLoadWasm();
        ZoomMtg.prepareWebSDK();
        // loads language files, also passes any error messages to the ui
        ZoomMtg.i18n.load('vi-VN');
        ZoomMtg.i18n.reload('vi-VN');
        let apiKey = "{{ env('ZOOM_API_KEY', '') }}";
        let meetingNumber = '{{ $meetingId }}';
        let role = {{ $role }};
        let leaveUrl = '{{ route("user.my_zoom") }}';
        let userName = '{{ $fullName }}';
        let userEmail = '{{ $email }}';
        let passWord = '{{ $password }}';
        let signature = '{{ $signature }}'
        let registrantToken = ''
        const zoomMeetingSDK = document.getElementById("zmmtg-root")
        // To hide
        zoomMeetingSDK.style.display = 'block';
        ZoomMtg.init({
            disableInvite: true,
            disableRecord: true,
            disableCallOut: true,
            isSupportPolling: false,
            isSupportBreakout: false,
            leaveUrl: leaveUrl,
            success: (success) => {
                console.log(success)
                ZoomMtg.join({
                signature: signature,
                meetingNumber: meetingNumber,
                userName: userName,
                apiKey: apiKey,
                userEmail: userEmail,
                passWord: passWord,
                tk: registrantToken,
                success: (success) => {
                    console.log(success)
                },
                error: (error) => {
                    console.log('Error')
                    console.log(error)
                }
                });

            },
            error: (error) => {
                console.log(error)
            }
            })
    </script>
</body>
</html>
