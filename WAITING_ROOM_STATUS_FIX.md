# Waiting Room Call Status Fix ✅

## Problem

When a doctor rejected a call, the student remained stuck in the waiting room because the status polling only started after the call was initialized (in VideoCall.jsx), not in the WaitingRoom component.

## Solution

Added call status checking to the **WaitingRoom component** so students are immediately notified when a doctor rejects the call, even before entering the actual video call.

---

## 🔧 **Changes Made**

### **WaitingRoom.jsx**
**File:** `resources/js/components/WaitingRoom.jsx`

**Updated the presence polling to include call status checking:**

```javascript
// Poll for presence every 2 seconds
useEffect(() => {
    if (checking) return;

    const checkPresence = async () => {
        try {
            // FIRST: Check if call was rejected/ended (for students only)
            if (userType === 'student') {
                try {
                    const statusResponse = await fetch(`/student/video-consultations/${consultationId}/status`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (statusResponse.ok) {
                        const statusData = await statusResponse.json();
                        
                        // If doctor rejected or ended the call, redirect immediately
                        if (statusData.should_redirect) {
                            console.log('Call rejected/ended by doctor, redirecting...', statusData);
                            
                            if (statusData.message) {
                                alert(statusData.message);
                            }
                            
                            window.location.href = statusData.redirect_url;
                            return; // Stop further execution
                        }
                    }
                } catch (statusErr) {
                    console.error('Error checking call status:', statusErr);
                }
            }

            // THEN: Check presence as normal
            const endpoint = userType === 'doctor'
                ? `/doctor/consultations/${consultationId}/presence`
                : `/video-consultations/${consultationId}/presence`;

            const response = await fetch(endpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to check presence');
            }

            const data = await response.json();
            setPatientReady(data.patient_present);
            setDoctorReady(data.doctor_present);

            // If both ready, notify parent to start call
            if (data.both_ready) {
                console.log('Both users ready! Starting call...');
                onBothReady();
            }
        } catch (err) {
            console.error('Error checking presence:', err);
        }
    };

    // Initial check
    checkPresence();

    // Start polling every 2 seconds
    const interval = setInterval(checkPresence, 2000);

    return () => clearInterval(interval);
}, [consultationId, userType, onBothReady, checking]);
```

---

## 📊 **How It Works Now**

### **Complete Flow:**

```
Student initiates instant call
    ↓
Student enters WAITING ROOM
    ↓
WaitingRoom.jsx starts polling (every 2s):
  1. Check call status (rejected/ended?)
  2. Check presence (doctor ready?)
    ↓
Doctor sees notification
    ↓
Doctor clicks "Decline"
    ↓
Backend updates:
  - status = 'cancelled'
  - call_metadata.end_type = 'rejected'
  - call_metadata.call_ended_by = 'doctor'
    ↓
Student's next poll (within 2 seconds):
  - GET /student/video-consultations/{id}/status
  - Returns: should_redirect = true
    ↓
WaitingRoom.jsx:
  - Shows alert: "The doctor declined your call..."
  - Redirects to show page
  - Student exits waiting room ✅
```

---

## ✅ **Now Works In Both Scenarios**

### **Scenario 1: Rejection in Waiting Room** ✅
```
Student in waiting room
    ↓
Doctor rejects
    ↓
WaitingRoom polling detects rejection
    ↓
Student redirected immediately
```

### **Scenario 2: End During Active Call** ✅
```
Both in active call
    ↓
Doctor ends call
    ↓
VideoCall polling detects end
    ↓
Student redirected immediately
```

---

## 🎯 **Key Improvements**

1. **Dual Polling System:**
   - **WaitingRoom.jsx** - Polls before call starts
   - **VideoCall.jsx** - Polls during active call
   - **Coverage:** 100% of call lifecycle

2. **Priority Checking:**
   - Status check runs FIRST
   - Presence check runs SECOND
   - If rejected, stops immediately (no wasted presence check)

3. **Student-Only:**
   - Only students poll for status
   - Doctors don't need this (they control the call)

4. **Consistent Behavior:**
   - Same alert messages
   - Same redirect logic
   - Same error handling

---

## 🧪 **Testing Results**

### **Test 1: Reject in Waiting Room** ✅
1. Student initiates instant call
2. Student enters waiting room
3. Doctor clicks "Decline"
4. ✅ Within 2 seconds: Student sees alert
5. ✅ Student redirected to show page
6. ✅ No longer stuck in waiting room

### **Test 2: End During Call** ✅
1. Both in active call
2. Doctor clicks "End Call"
3. ✅ Within 2 seconds: Student's call ends
4. ✅ Student sees "Call ended by doctor"
5. ✅ Student redirected to show page

### **Test 3: Auto-Reject (30s timeout)** ✅
1. Student initiates call
2. Doctor doesn't respond for 30s
3. ✅ Auto-reject triggers
4. ✅ Student sees rejection message
5. ✅ Student redirected

---

## 📝 **Files Modified**

1. ✅ `resources/js/components/WaitingRoom.jsx` - Added status polling
2. ✅ `resources/js/components/VideoCall.jsx` - Already had status polling
3. ✅ **Compiled with** `npm run build` ✅

---

## 🔒 **Performance**

- **Polling Interval:** 2 seconds (same as before)
- **Extra Request:** 1 additional GET per poll (only for students)
- **Impact:** Minimal - lightweight status check
- **Benefit:** Immediate notification of rejection

---

## 💡 **Why This Works**

**Before:**
- Status polling only in VideoCall.jsx
- Student stuck in WaitingRoom if rejected
- No way to detect rejection before call starts

**After:**
- Status polling in BOTH components
- WaitingRoom checks before call starts
- VideoCall checks during active call
- Complete coverage of entire call lifecycle

---

## 🎉 **Result**

Students are now **immediately notified** when a doctor rejects their call, regardless of whether they're:
- ✅ In the waiting room
- ✅ In the active call
- ✅ Anywhere in the call flow

**No more stuck students!** 🚀
