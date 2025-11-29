import React from 'react'
import { createRoot } from 'react-dom/client'
import App from './App'
import './index.css'

// The user's tutorial used top-level await. We'll initialize the client and call
// before rendering so the app receives ready client/call props.
async function bootstrap() {
  try {
    // Import client creation from App which exports `createClientAndJoin` helper
    const mod = await import('./App')
    const { createClientAndJoin } = mod
    const { client, call } = await createClientAndJoin()

    const container = document.getElementById('root')!
    const root = createRoot(container)
    root.render(<App client={client} call={call} />)
  } catch (err) {
    console.error('Failed to start video-call app', err)
    const container = document.getElementById('root')!
    container.innerHTML = '<h2>Failed to start video call app. Check console.</h2>'
  }
}

bootstrap()
