<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Consultation - Dr. {{ $consultation->doctor->name }}</title>
    
    <!-- Stream Video SDK Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@stream-io/video-react-sdk/dist/css/styles.css" />
    <script>
        window.consultationId = @json($consultation->id);
        window.userType = 'student';
    </script>
    <?php
    $manifestPath = public_path('build/manifest.json');
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        // Include CSS entry if present
        if (isset($manifest['resources/css/app.css'])) {
            $cssFile = $manifest['resources/css/app.css']['file'];
            echo '<link rel="stylesheet" href="' . asset('build/' . $cssFile) . '" />';
        }
    }
    ?>
</head>
<body class="antialiased">
    <div id="video-call-root"></div>
    <script>
        window.streamVideoConfig = @json($streamConfig);
        window.streamVideoConfig.callId = "{{ $consultation->id }}";
        window.consultationData = @json($consultation);
    </script>
    <?php
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (isset($manifest['resources/js/video-call.jsx'])) {
            $jsFile = $manifest['resources/js/video-call.jsx']['file'];
            $assetPrimary = asset('build/' . $jsFile);
            $assetAlternate = asset('public/build/' . $jsFile);
            echo "<script>
                (function(){
                    var urls = [\"{$assetPrimary}\", \"{$assetAlternate}\"];
                    var i = 0;
                    console.log('Loader: attempting to load video-call bundle from', urls);
                    function loadNext(){
                        if(i>=urls.length){
                            console.error('video-call bundle not found after trying', urls);
                            var el = document.getElementById('video-call-root');
                            if(el){ el.innerHTML = '<div style=\"padding:1rem;background:#fff;color:#000;text-align:center\"><strong>Error:</strong> Video client failed to load. Check the Network tab for failed requests. Tried: ' + urls.join(', ') + '</div>'; }
                            return;
                        }
                        console.log('Loader: attempting URL', i + ':', urls[i]);
                        var s = document.createElement('script');
                        s.type = 'module';
                        s.src = urls[i];
                        s.onerror = function(){ console.log('Loader: failed to load', urls[i], ', trying next...'); i++; loadNext(); };
                        s.onload = function(){ 
                            console.log('Loader: successfully loaded', urls[i]);
                            // CRITICAL: Initialize the React app after bundle loads
                            if(typeof window.initializeVideoCallApp === 'function'){
                                console.log('Initializing video call app...');
                                console.log('Config:', window.streamVideoConfig);
                                window.initializeVideoCallApp(
                                    'video-call-root',
                                    window.streamVideoConfig,
                                    window.consultationData,
                                    'student'
                                );
                            } else {
                                console.error('initializeVideoCallApp function not found on window');
                            }
                        };
                        document.body.appendChild(s);
                    }
                    loadNext();
                })();
            </script>";
        } else {
            echo app('Illuminate\Foundation\Vite')(['resources/js/video-call.jsx']);
        }
    } else {
        echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/video-call.jsx']);
    }
    ?>
</body>
</html>