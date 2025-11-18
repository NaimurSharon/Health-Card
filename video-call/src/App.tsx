import React, { useState, useEffect } from 'react'
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
    SpeakerLayout,
    CallControls,
    ScreenShareButton,
    ToggleAudioPublishingButton,
    ToggleVideoPublishingButton,
} from '@stream-io/video-react-sdk'
import type { Call } from '@stream-io/video-react-sdk'

import '@stream-io/video-react-sdk/dist/css/styles.css'
import './video-call.css'

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
        const sessionId = call.state.session?.id || null;
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
            const sessionId = call.state.session?.id || null;
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

export default function App(props: { client: StreamVideoClient; call: Call }) {
    const { client, call } = props

    return (
        <StreamVideo client={ client }>
            <StreamCall call={ call }>
                <VideoCallUI />
            </StreamCall>
        </StreamVideo>
    )
}

const VideoCallUI = () => {
    const {
        useCallCallingState,
        useLocalParticipant,
        useRemoteParticipants,
        useParticipantCount,
    } = useCallStateHooks()

    const call = useCall()
    const callingState = useCallCallingState()
    const localParticipant = useLocalParticipant()
    const remoteParticipants = useRemoteParticipants()
    const participantCount = useParticipantCount()

    const [isLeaving, setIsLeaving] = useState(false)

    useEffect(() => {
        // Track call duration
        const startTime = Date.now()
        return () => {
            const duration = Math.floor((Date.now() - startTime) / 1000)
            console.log('Call duration:', duration, 'seconds')
        }
    }, [])

    const handleLeaveCall = async () => {
        setIsLeaving(true)
        try {
            await call?.leave()
            // Notify Laravel backend
            const consultationId = consultationIdFromWindow()
            if (consultationId) {
                await fetch(`/video-consultations/${consultationId}/ended`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
            }
            // Redirect back to Laravel
            window.location.href = consultationId
                ? `/video-consultations/${consultationId}`
                : '/dashboard'
        } catch (error) {
            console.error('Error leaving call:', error)
            setIsLeaving(false)
        }
    }

    // Loading state
    if (callingState === CallingState.JOINING) {
        return <LoadingScreen message="Joining call..." />
    }

    if (callingState === CallingState.RECONNECTING) {
        return <LoadingScreen message="Reconnecting..." />
    }

    if (callingState !== CallingState.JOINED) {
        return <LoadingScreen message="Connecting..." />
    }

    if (isLeaving) {
        return <LoadingScreen message="Leaving call..." />
    }

    return (
        <StreamTheme className="video-call-container">
            <div className="video-call-wrapper">
                {/* Header */ }
                <div className="call-header">
                    <div className="call-info">
                        <span className="call-title">Video Consultation</span>
                        <span className="participant-count">
                            { participantCount } { participantCount === 1 ? 'participant' : 'participants' }
                        </span>
                    </div>
                    <CallTimer />
                </div>

                {/* Main video area */ }
                <div className="video-content">
                    { remoteParticipants.length === 0 ? (
                        <WaitingForOthers localParticipant={ localParticipant } />
                    ) : (
                        <ParticipantGrid
                            localParticipant={ localParticipant }
                            remoteParticipants={ remoteParticipants }
                        />
                    ) }
                </div>

                {/* Controls */ }
                <div className="call-controls-wrapper">
                    <CustomCallControls onLeave={ handleLeaveCall } />
                </div>
            </div>
        </StreamTheme>
    )
}

const LoadingScreen = ({ message }: { message: string }) => {
    return (
        <div className="loading-screen">
            <div className="loading-content">
                <div className="spinner"></div>
                <p>{ message }</p>
            </div>
        </div>
    )
}

const CallTimer = () => {
    const [time, setTime] = useState(0)

    useEffect(() => {
        const interval = setInterval(() => {
            setTime(t => t + 1)
        }, 1000)
        return () => clearInterval(interval)
    }, [])

    const formatTime = (seconds: number) => {
        const hrs = Math.floor(seconds / 3600)
        const mins = Math.floor((seconds % 3600) / 60)
        const secs = seconds % 60
        if (hrs > 0) {
            return `${hrs}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
        }
        return `${mins}:${secs.toString().padStart(2, '0')}`
    }

    return (
        <div className="call-timer">
            <span className="recording-dot"></span>
            <span>{ formatTime(time) }</span>
        </div>
    )
}

const WaitingForOthers = ({ localParticipant }: { localParticipant?: StreamVideoParticipant }) => {
    return (
        <div className="waiting-room">
            <div className="waiting-message">
                <h2>Waiting for others to join...</h2>
                <p>You'll see them here when they arrive</p>
            </div>
            { localParticipant && (
                <div className="local-preview">
                    <ParticipantView participant={ localParticipant } />
                </div>
            ) }
        </div>
    )
}

const ParticipantGrid = ({
    localParticipant,
    remoteParticipants
}: {
    localParticipant?: StreamVideoParticipant
    remoteParticipants: StreamVideoParticipant[]
}) => {
    const totalParticipants = remoteParticipants.length + (localParticipant ? 1 : 0)

    // Use grid layout based on participant count
    const getGridClass = () => {
        if (totalParticipants === 1) return 'grid-1'
        if (totalParticipants === 2) return 'grid-2'
        if (totalParticipants <= 4) return 'grid-4'
        if (totalParticipants <= 6) return 'grid-6'
        return 'grid-9'
    }

    return (
        <div className={ `participant-grid ${getGridClass()}` }>
            { remoteParticipants.map((participant) => (
                <div key={ participant.sessionId } className="participant-tile">
                    <ParticipantView participant={ participant } />
                </div>
            )) }
            { localParticipant && (
                <div className="participant-tile local-participant">
                    <ParticipantView participant={ localParticipant } />
                    <div className="participant-label">You</div>
                </div>
            ) }
        </div>
    )
}

const CustomCallControls = ({ onLeave }: { onLeave: () => void }) => {
    const call = useCall()
    const { useMicrophoneState, useCameraState, useScreenShareState } = useCallStateHooks()

    const { microphone, isMute } = useMicrophoneState()
    const { camera, isMute: isCameraOff } = useCameraState()
    const { screenShare, status: screenShareStatus } = useScreenShareState()

    const isScreenSharing = screenShareStatus === 'enabled'

    const toggleMicrophone = async () => {
        if (isMute) {
            await microphone.enable()
        } else {
            await microphone.disable()
        }
    }

    const toggleCamera = async () => {
        if (isCameraOff) {
            await camera.enable()
        } else {
            await camera.disable()
        }
    }

    const toggleScreenShare = async () => {
        try {
            if (isScreenSharing) {
                await screenShare.disable()
            } else {
                await screenShare.enable()
            }
        } catch (error) {
            console.error('Screen share error:', error)
        }
    }

    return (
        <div className="custom-call-controls">
            <button
                className={ `control-button ${isMute ? 'active' : ''}` }
                onClick={ toggleMicrophone }
                title={ isMute ? 'Unmute' : 'Mute' }
            >
                { isMute ? (
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M19 11C19 12.1 18.7 13.2 18.2 14.1L19.5 15.4C20.4 14 21 12.5 21 11H19ZM15 11.2V11C15 7.7 12.3 5 9 5C8.6 5 8.2 5.1 7.8 5.1L9.1 6.4C11.2 6.6 13 8.5 13 11V11.2L15 13.2V11.2ZM4.3 3L3 4.3L7.7 9H7V11C7 14.3 9.7 17 13 17C13.9 17 14.7 16.7 15.4 16.3L17.3 18.2C16.1 19 14.6 19.5 13 19.5C8.7 19.5 5 16.1 5 11.8H3C3 17.4 7.2 22 12.5 22.5V26H15.5V22.5C16.9 22.3 18.2 21.9 19.4 21.2L20.7 22.5L22 21.2L4.3 3Z" fill="currentColor" />
                    </svg>
                ) : (
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 14C13.66 14 15 12.66 15 11V5C15 3.34 13.66 2 12 2C10.34 2 9 3.34 9 5V11C9 12.66 10.34 14 12 14Z" fill="currentColor" />
                        <path d="M17 11C17 14.3 14.3 17 11 17C7.7 17 5 14.3 5 11H3C3 15.1 6.1 18.4 10 18.9V22H14V18.9C17.9 18.4 21 15.1 21 11H19C19 11 19 11 17 11Z" fill="currentColor" />
                    </svg>
                ) }
            </button>

            <button
                className={ `control-button ${isCameraOff ? 'active' : ''}` }
                onClick={ toggleCamera }
                title={ isCameraOff ? 'Turn on camera' : 'Turn off camera' }
            >
                { isCameraOff ? (
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 6.5L17 10.5V7C17 5.9 16.1 5 15 5H9.6L21 16.4V6.5ZM3.4 1.7L2 3.1L4.9 6H4C2.9 6 2 6.9 2 8V18C2 19.1 2.9 20 4 20H15C15.3 20 15.6 19.9 15.9 19.8L19.9 23.8L21.3 22.4L3.4 1.7Z" fill="currentColor" />
                    </svg>
                ) : (
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M17 10.5V7C17 5.89 16.1 5 15 5H4C2.89 5 2 5.89 2 7V17C2 18.1 2.89 19 4 19H15C16.1 19 17 18.1 17 17V13.5L21 17.5V6.5L17 10.5Z" fill="currentColor" />
                    </svg>
                ) }
            </button>

            <button
                className={ `control-button ${isScreenSharing ? 'active' : ''}` }
                onClick={ toggleScreenShare }
                title={ isScreenSharing ? 'Stop sharing' : 'Share screen' }
            >
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M20 18C21.1 18 22 17.1 22 16V6C22 4.89 21.1 4 20 4H4C2.89 4 2 4.89 2 6V16C2 17.1 2.89 18 4 18H0V20H24V18H20ZM4 6H20V16H4V6Z" fill="currentColor" />
                </svg>
            </button>

            <button
                className="control-button leave-button"
                onClick={ onLeave }
                title="Leave call"
            >
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 9C10.4 9 8.85 9.25 7.4 9.72V12.82C7.4 13.22 7.17 13.56 6.84 13.72C5.86 14.21 4.97 14.84 4.17 15.57C3.95 15.76 3.66 15.86 3.37 15.86C3.16 15.86 2.95 15.81 2.75 15.7C2.29 15.45 2 14.95 2 14.4V9.72C2 8.91 2.54 8.2 3.34 7.97C5.36 7.37 7.68 7 10 7C12.32 7 14.64 7.37 16.66 7.97C17.46 8.2 18 8.91 18 9.72V12H16V9.72C14.15 9.25 13.6 9 12 9ZM20 18V22H4V18H6V16H4C2.9 16 2 16.9 2 18V22C2 23.1 2.9 24 4 24H20C21.1 24 22 23.1 22 22V18C22 16.9 21.1 16 20 16H18V18H20Z" fill="currentColor" />
                </svg>
                <span>Leave</span>
            </button>
        </div>
    )
}
