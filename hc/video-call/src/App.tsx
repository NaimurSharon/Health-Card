import React from 'react'
import {
  CallingState,
  StreamCall,
  StreamVideo,
  StreamVideoClient,
  StreamVideoParticipant,
  useCall,
  useCallStateHooks,
  StreamTheme,
  ParticipantView,
} from '@stream-io/video-react-sdk'

import '@stream-io/video-react-sdk/dist/css/styles.css'

/**
 * Create Stream client and join call using server-provided configuration.
 * Expects the Laravel endpoint `/api/video-call/config/{consultationId}` to
 * return JSON with `streamConfig` containing `apiKey`, `token`, `user`, and `callId`.
 */
export async function createClientAndJoin() {
  // Determine consultation id from global injected by blade or from URL
  const consultationId = (window as any).consultationId || (() => {
    try {
      const parts = window.location.pathname.split('/').filter(Boolean)
      if (parts.length >= 2 && parts[parts.length - 1] === 'video-call') {
        return parts[parts.length - 2]
      }
      return parts[parts.length - 1]
    } catch (e) {
      return null
    }
  })()

  if (!consultationId) {
    throw new Error('No consultationId found. Open via Laravel route that injects consultationId.')
  }

  const res = await fetch(`/api/video-call/config/${consultationId}`, {
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    credentials: 'same-origin'
  })

  if (!res.ok) {
    const text = await res.text()
    throw new Error('Failed to fetch stream config: ' + res.status + ' ' + text)
  }

  const payload = await res.json()
  const streamConfig = payload.streamConfig || payload

  const { apiKey, token, user, callId } = streamConfig

  if (!apiKey || !token || !user || !callId) {
    throw new Error('Invalid stream configuration received from server')
  }

  const client = new StreamVideoClient({ apiKey, user, token })
  const call = client.call('default', callId)
  await call.join({ create: true })

  // notify backend that participant joined (works when served same-origin)
  try {
    const consultationId = consultationIdFromWindow() || null;
    const sessionId = (call.localParticipant && (call.localParticipant as any).sessionId) || (call as any).sessionId || null;
    if (consultationId && sessionId) {
      await fetch(`/video-consultations/${consultationId}/joined`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify({ participant: { sessionId, user: user } })
      });
    }
  } catch (e) {
    console.warn('Failed to POST participantJoined', e);
  }

  // ensure we notify server when the page unloads
  const onUnload = () => {
    try {
      const consultationId = consultationIdFromWindow() || null;
      const sessionId = (call.localParticipant && (call.localParticipant as any).sessionId) || (call as any).sessionId || null;
      if (consultationId && sessionId) {
        navigator.sendBeacon(`/video-consultations/${consultationId}/left`, JSON.stringify({ participant: { sessionId } }));
      }
    } catch (err) {
      // ignore
    }
  }
  window.addEventListener('beforeunload', onUnload);

  return { client, call }
}

function consultationIdFromWindow() {
  // get consultation id injected by blade or from URL
  try {
    // @ts-ignore
    if ((window as any).consultationId) return (window as any).consultationId;
    const parts = window.location.pathname.split('/').filter(Boolean);
    if (parts.length >= 2 && parts[parts.length - 1] === 'video-call') return parts[parts.length - 2];
    return parts[parts.length - 1] || null;
  } catch (e) {
    return null;
  }
}

export default function App(props: { client: StreamVideoClient; call: StreamCall }) {
  const { client, call } = props

  return (
    <StreamVideo client={client}>
      <StreamCall call={call}>
        <MyUILayout />
      </StreamCall>
    </StreamVideo>
  )
}

export const MyUILayout = () => {
  const {
    useCallCallingState,
    useLocalParticipant,
    useRemoteParticipants
  } = useCallStateHooks()

  const callingState = useCallCallingState()
  const localParticipant = useLocalParticipant()
  const remoteParticipants = useRemoteParticipants()

  if (callingState !== CallingState.JOINED) {
    return <div style={{ padding: 16 }}>Loading...</div>
  }

  return (
    <StreamTheme>
      <div style={{ padding: 12 }}>
        <MyParticipantList participants={remoteParticipants} />
        <MyFloatingLocalParticipant participant={localParticipant} />
      </div>
    </StreamTheme>
  )
}

export const MyParticipantList = (props: { participants: StreamVideoParticipant[] }) => {
  const { participants } = props
  return (
    <div style={{ display: 'flex', flexDirection: 'row', gap: '8px' }}>
      {participants.map((participant) => (
        <ParticipantView participant={participant} key={participant.sessionId} />
      ))}
    </div>
  )
}

export const MyFloatingLocalParticipant = (props: { participant?: StreamVideoParticipant }) => {
  const { participant } = props
  if (!participant) {
    return <p>Error: No local participant</p>
  }

  return (
    <div
      style={{
        position: 'absolute',
        top: '15px',
        left: '15px',
        width: '240px',
        height: '135px',
        boxShadow: 'rgba(0, 0, 0, 0.1) 0px 0px 10px 3px',
        borderRadius: '12px'
      }}
    >
      <ParticipantView participant={participant} />
    </div>
  )
}
