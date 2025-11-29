# Waiting Room Issue - Fix Summary

## Problem
Students were not getting the doctor's ready status and were being kept waiting in the waiting room indefinitely, even when the doctor was ready.

## Root Cause
There were TWO critical bugs:

### Bug #1: Backend Method Signature Error (CRITICAL)
The `markReady()` method in both controllers had incorrect parameter signatures:
- **StudentConsultationController**: `public function markReady(Request $id)` 
- **DoctorConsultationController**: `public function markReady(Request $request, $id)`

The first one was trying to use the Request object as the ID, and both were incorrect. The route passes the consultation ID as a parameter, so the signature should simply be `public function markReady($id)`.

**This bug prevented the waiting room from working AT ALL** - participants couldn't mark themselves as ready, so the other participant would never see them.

### Bug #2: Timestamp Not Being Refreshed
Even if Bug #1 was fixed, there was a second issue in the frontend (`resources/js/hooks/useCallState.js`):

1. When a participant enters the waiting room, the `markPresent()` function is called to set a timestamp in the backend (`doctor_ready_at` or `student_ready_at`)
2. The backend's `checkPresence()` method validates these timestamps and only considers a participant "present" if their timestamp is within the last 10 seconds
3. **The bug**: `markPresent()` was only called ONCE when entering the waiting room, so after 10 seconds, the backend would consider the participant as "not present" anymore
4. This would cause the other participant to never see them as ready after 10 seconds

## The Fixes

### Fix #1: Backend Controller Method Signatures
Fixed both `StudentConsultationController.php` and `DoctorConsultationController.php`:

**Before:**
```php
// StudentConsultationController.php
public function markReady(Request $id) { ... }

// DoctorConsultationController.php  
public function markReady(Request $request, $id) { ... }
```

**After:**
```php
// Both controllers now have:
public function markReady($id) { ... }
```

### Fix #2: Frontend Timestamp Refresh
Modified `resources/js/hooks/useCallState.js` line 93-105:

**Before:**
```javascript
const enterWaitingRoom = () => {
    setCallState('waiting');
    markPresent();

    // Poll every 2 seconds
    pollingIntervalRef.current = setInterval(checkParticipantPresence, 2000);
    checkParticipantPresence(); // Immediate check
};
```

**After:**
```javascript
const enterWaitingRoom = () => {
    console.log(`[WaitingRoom] Entering waiting room for ${userType}`);
    setCallState('waiting');
    markPresent();

    // Poll every 2 seconds for presence check
    pollingIntervalRef.current = setInterval(() => {
        checkParticipantPresence();
        markPresent(); // Keep refreshing our presence timestamp
    }, 2000);
    checkParticipantPresence(); // Immediate check
};
```

## What Changed
- **Backend**: Fixed method signatures to correctly receive the consultation ID from the route parameter
- **Frontend**: Now `markPresent()` is called every 2 seconds (along with `checkParticipantPresence()`)
- This keeps the timestamp fresh in the backend
- The backend will now correctly recognize both participants as present
- Both participants will see each other as "ready" and the call will proceed
- Added comprehensive debug logging to track the waiting room flow

## Files Modified
1. `app/Http/Controllers/Student/StudentConsultationController.php` - Fixed `markReady()` method signature
2. `app/Http/Controllers/Doctor/DoctorConsultationController.php` - Fixed `markReady()` method signature  
3. `resources/js/hooks/useCallState.js` - Fixed polling interval to refresh presence timestamp + added debug logging
4. Assets rebuilt with `npm run build`

## Testing
To verify the fix:
1. Have a student initiate a video consultation
2. Have the doctor join the consultation
3. Both should see each other as "Connected" and "Ready" in the waiting room
4. The call should automatically proceed after both are ready
5. Neither participant should be stuck waiting indefinitely

## Technical Details
- **Backend validation**: `checkPresence()` in both `StudentConsultationController.php` and `DoctorConsultationController.php` check if timestamps are within 10 seconds
- **Frontend polling**: Runs every 2 seconds to check presence AND refresh the timestamp
- **Timestamp format**: ISO 8601 format stored in `call_metadata` JSON field
