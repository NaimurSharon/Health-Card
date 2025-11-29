# Video Call (React + Vite)

This is a small standalone Vite + React TypeScript app that uses `@stream-io/video-react-sdk` to join a call.

Placement in repository:

- Dev: run the Vite dev server from the `video-call/` folder and visit `http://localhost:5173`
- Production: run `yarn build` inside `video-call/` and the build output will be placed into `public/video-call`.

Quick start

1. cd to the project folder and install dependencies:

```powershell
cd video-call; yarn install
```

2. Development server (keeps hot reload):

```powershell
yarn dev
```

3. Production build (outputs to `public/video-call`):

```powershell
yarn build
```

Notes
- The Laravel Blade `resources/views/video-call.blade.php` will iframe the dev server when `APP_ENV` is `local`, otherwise it will iframe `/video-call/index.html` (the built app).
- You can customize the API key, token and call id in `video-call/src/App.tsx` or change the initialization to fetch server-side generated tokens.
