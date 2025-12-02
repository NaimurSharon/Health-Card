# Call End & Rejection Handling ✅

## Overview

Implemented proper handling for when doctor ends or rejects a call, ensuring the student is immediately notified and redirected.

---

## 🎯 **Features Implemented**

### 1. **Doctor Rejects Call** ✅
- Student gets redirected to consultation show page
- Alert message: "The doctor declined your call. Please try again later."
- Call status set to `'cancelled'`
- Metadata records rejection details

### 2. **Doctor Ends Call** ✅
- Student's call automatically ends
- Student redirected to consultation show page
- Alert message: "Call ended by doctor"
- Call status set to `'completed'`

### 3. **Student Ends Call** ✅
- Doctor's call continues (no automatic end)
- Metadata marks `ended_by = 'student'`
- Student redirected to show page

---

## 🔧 **How It Works**

### **Backend Changes:**

#### 1. **DoctorCallController::rejectCall()**
**File:** `app/Http/Controllers/Doctor/DoctorCallController.php`

```php
public function rejectCall(Request $request)
{
    // ... validation ...

    // Update metadata to record rejection
    $metadata = $consultation->call_metadata ?? [];
    $metadata['doctor_rejected_at'] = now()->toISOString();
    $metadata['rejection_reason'] = 'Doctor declined the call';
    $metadata['call_ended_by'] = 'doctor';
    $metadata['end_type'] = 'rejected';  // ← Key for student notification

    $consultation->update([
        'status' => 'cancelled',
        'ended_at' => now(),
        'call_metadata' => $metadata
    ]);

    // Broadcast event to notify student
    broadcast(new \App\Events\CallStatusChanged($consultation->id, 'rejected', $doctor->id))->toOthers();

    return response()->json([
        'success' => true,
        'message' => 'Call rejected successfully'
    ]);
}
```

**Key Points:**
- Sets `end_type = 'rejected'` in metadata
- Sets `call_ended_by = 'doctor'`
- Broadcasts event (optional, polling is primary method)

---

#### 2. **DoctorConsultationController::endCall()**
**File:** `app/Http/Controllers/Doctor/DoctorConsultationController.php`

```php
public function endCall(Request $request, $id)
{
    // ... validation ...

    // Update metadata
    $metadata = $consultation->call_metadata ?? [];
    $metadata['call_ended_by'] = 'doctor';  // ← Marks who ended it
    $metadata['ended_at'] = now()->toISOString();

    $consultation->update([
        'status' => 'completed',
        'ended_at' => now(),
        'duration' => $request->duration ?? 0,
        'call_metadata' => $metadata
    ]);

    // Broadcast event to notify other participant
    broadcast(new \App\Events\CallStatusChanged($consultation->id, 'ended', $doctor->id))->toOthers();

    return response()->json(['success' => true]);
}
```

**Key Points:**
- Sets `call_ended_by = 'doctor'`
- Broadcasts event
- Status changes to `'completed'`

---

#### 3. **StudentConsultationController::checkCallStatus()** (NEW)
**File:** `app/Http/Controllers/Student/StudentConsultationController.php`

```php
public function checkCallStatus($id)
{
    $student = Auth::user();
    
    $consultation = VideoConsultation::where('id', $id)
        ->where('user_id', $student->id)
        ->firstOrFail();

    $metadata = $consultation->call_metadata ?? [];
    $endedBy = $metadata['call_ended_by'] ?? null;
    $endType = $metadata['end_type'] ?? null;

    return response()->json([
        'status' => $consultation->status,
        'ended_by' => $endedBy,
        'end_type' => $endType,
        'should_redirect' => in_array($consultation->status, ['cancelled', 'completed']) && $endedBy !== 'student',
        'redirect_url' => route('video-consultation.show', $id),
        'message' => $consultation->status === 'cancelled' && $endType === 'rejected' 
            ? 'The doctor declined your call. Please try again later.'
            : ($consultation->status === 'completed' ? 'Call ended by doctor' : null)
    ]);
}
```

**Key Logic:**
- `should_redirect = true` if:
  - Status is `'cancelled'` or `'completed'` AND
  - Call was NOT ended by student (`ended_by !== 'student'`)
- Returns appropriate message based on `end_type`

---

### **Frontend Changes:**

#### **VideoCall.jsx - Status Polling**
**File:** `resources/js/components/VideoCall.jsx`

Added polling mechanism for students:

```javascript
// Poll call status (for students) to detect if doctor ended/rejected call
useEffect(() => {
    // Only poll for students
    if (userType !== 'student' || !consultation?.id) return;

    const checkCallStatus = async () => {
        try {
            const response = await fetch(`/student/video-consultations/${consultation.id}/status`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                
                // If doctor ended or rejected the call, redirect student
                if (data.should_redirect) {
                    console.log('Call ended by doctor, redirecting...', data);
                    
                    // Stop polling
                    if (statusCheckIntervalRef.current) {
                        clearInterval(statusCheckIntervalRef.current);
                    }

                    // Leave the call gracefully
                    if (call && !callLeftRef.current) {
                        callLeftRef.current = true;
                        await call.leave().catch(() => {});
                    }

                    // Show message and redirect
                    if (data.message) {
                        alert(data.message);
                    }

                    window.location.href = data.redirect_url;
                }
            }
        } catch (error) {
            console.error('Status check error:', error);
        }
    };

    // Start polling every 2 seconds
    statusCheckIntervalRef.current = setInterval(checkCallStatus, 2000);

    // Cleanup
    return () => {
        if (statusCheckIntervalRef.current) {
            clearInterval(statusCheckIntervalRef.current);
        }
    };
}, [consultation?.id, userType, call]);
```

**Key Features:**
- **Only runs for students** (`userType === 'student'`)
- **Polls every 2 seconds** to check call status
- **Gracefully leaves call** before redirecting
- **Shows alert message** to user
- **Redirects to show page** automatically
- **Cleans up interval** on unmount

---

## 📊 **Flow Diagrams**

### **Doctor Rejects Call:**

```
Doctor clicks "Decline" in notification panel
    ↓
POST /api/video-calls/reject
    ↓
DoctorCallController::rejectCall()
    ↓
Update consultation:
  - status = 'cancelled'
  - call_metadata.end_type = 'rejected'
  - call_metadata.call_ended_by = 'doctor'
    ↓
Broadcast CallStatusChanged event (optional)
    ↓
Student polling detects status change (every 2s)
    ↓
checkCallStatus() returns:
  - should_redirect = true
  - message = "The doctor declined your call..."
    ↓
Student's VideoCall.jsx:
  - Stops polling
  - Leaves Stream call
  - Shows alert
  - Redirects to /student/video-consultations/{id}
```

---

### **Doctor Ends Call:**

```
Doctor clicks "End Call" button
    ↓
POST /doctor/video-consultations/{id}/end
    ↓
DoctorConsultationController::endCall()
    ↓
Update consultation:
  - status = 'completed'
  - call_metadata.call_ended_by = 'doctor'
    ↓
Broadcast CallStatusChanged event
    ↓
Student polling detects status change
    ↓
checkCallStatus() returns:
  - should_redirect = true
  - message = "Call ended by doctor"
    ↓
Student's VideoCall.jsx:
  - Stops polling
  - Leaves Stream call
  - Shows alert
  - Redirects to show page
```

---

### **Student Ends Call:**

```
Student clicks "End Call" button
    ↓
POST /student/video-consultations/{id}/end
    ↓
StudentConsultationController::endCall()
    ↓
Update consultation:
  - status = 'completed'
  - call_metadata.ended_by = 'student'
    ↓
Student redirected to show page
    ↓
Doctor's call continues (no automatic end)
```

---

## 🛣️ **Routes Added**

**File:** `routes/student.php`

```php
Route::get('/video-consultations/{id}/status', [StudentConsultationController::class, 'checkCallStatus'])
    ->name('video-consultation.status');
```

---

## 📝 **Database Schema**

### **call_metadata Structure:**

```json
{
  "instant_call": true,
  "initiated_at": "2025-12-02T12:04:05.000Z",
  "call_ended_by": "doctor",
  "end_type": "rejected",
  "doctor_rejected_at": "2025-12-02T12:05:30.000Z",
  "rejection_reason": "Doctor declined the call"
}
```

**Key Fields:**
- `call_ended_by`: "doctor" | "student"
- `end_type`: "rejected" | null
- `doctor_rejected_at`: ISO timestamp
- `rejection_reason`: Human-readable reason

---

## ✅ **Testing Scenarios**

### **Scenario 1: Doctor Rejects Instant Call**
1. Student initiates instant call
2. Student enters waiting room
3. Doctor sees notification
4. Doctor clicks "Decline"
5. ✅ Student sees alert: "The doctor declined your call. Please try again later."
6. ✅ Student redirected to `/student/video-consultations/{id}`
7. ✅ Consultation status = 'cancelled'

### **Scenario 2: Doctor Ends Ongoing Call**
1. Both in call
2. Doctor clicks "End Call"
3. ✅ Student sees alert: "Call ended by doctor"
4. ✅ Student's call ends automatically
5. ✅ Student redirected to show page
6. ✅ Consultation status = 'completed'

### **Scenario 3: Student Ends Call**
1. Both in call
2. Student clicks "End Call"
3. ✅ Student redirected to show page
4. ✅ Doctor's call continues (no automatic end)
5. ✅ Metadata shows `ended_by = 'student'`

---

## 🔒 **Security & Performance**

### **Polling Interval:**
- **2 seconds** - Good balance between responsiveness and server load
- Only runs for students
- Automatically stops when call ends
- Cleans up on component unmount

### **Authorization:**
- `checkCallStatus()` verifies student owns the consultation
- Uses `firstOrFail()` to prevent unauthorized access

### **Error Handling:**
- Try-catch around polling
- Graceful fallback if API fails
- Logs errors to console

---

## 🎉 **Benefits**

1. ✅ **Immediate Notification** - Student knows within 2 seconds
2. ✅ **Clean UX** - No orphaned calls
3. ✅ **Clear Messages** - User knows what happened
4. ✅ **Graceful Cleanup** - Properly leaves Stream call
5. ✅ **No Confusion** - Student can't stay in ended call
6. ✅ **Audit Trail** - Metadata records who ended call and why

---

## 📦 **Files Modified**

1. ✅ `app/Http/Controllers/Doctor/DoctorCallController.php`
2. ✅ `app/Http/Controllers/Doctor/DoctorConsultationController.php`
3. ✅ `app/Http/Controllers/Student/StudentConsultationController.php`
4. ✅ `app/Events/CallStatusChanged.php` (NEW)
5. ✅ `resources/js/components/VideoCall.jsx`
6. ✅ `routes/student.php`

---

## 🚀 **Ready to Test!**

The system now properly handles call endings from both sides:
- Doctor rejects → Student notified and redirected
- Doctor ends → Student's call ends automatically
- Student ends → Doctor continues (as expected)

All changes compiled with `npm run build`! 🎊
