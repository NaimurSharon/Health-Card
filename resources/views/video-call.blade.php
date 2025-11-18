<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Video Call</title>
    <style>
      html,body,#app-wrapper { height:100%; margin:0; }
      iframe { border:0; width:100%; height:100%; }
    </style>
  </head>
  <body>
    <div id="app-wrapper">
      @if (app()->environment('local'))
        {{-- Dev: iframe the Vite dev server --}} 
        <iframe src="http://localhost:5173/" title="Video Call (dev)"></iframe>
      @else
        {{-- Production: serve the built app from public/video-call/index.html --}}
        <iframe src="/video-call/index.html" title="Video Call"></iframe>
      @endif
    </div>
  </body>
</html>
