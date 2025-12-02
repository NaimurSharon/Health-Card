# Mutual Call End Detection Fix ✅

## Problem

When one participant ended a call, the other participant remained in the call without being notified or redirected. This created orphaned call sessions.

**Issues:**
- Student ends call → Doctor stays in call ❌
- Doctor ends call → Student stays in call ❌ (partially working)
- No mutual awareness of call termination

---

## Solution

Implemented **bidirectional call status polling** so both participants detect when the other person ends the call.

---

## 🔧 **Changes Made**

### **1. Fixed Student's endCall Metadata** ✅
**File:** `app/Http/Controllers/Student/StudentConsultationController.php`

**Before:**
```php
'call_metadata' => array_merge($meta, ['ended_by' => 'student', ...])
```

**After:**
```php
$meta['call_ended_by'] = 'student';  // ← Consistent key
$meta['ended_at'] = now()->toISOString();

$consultation->update([
    'status' => 'completed',
    'ended_at' => now(),
    'duration' => $request->duration ?? ...,
    'call_metadata' => $meta
]);

// Broadcast status change to notify other participant
broadcast(new \App\Events\CallStatusChanged($consultation->id, 'ended', Auth::id()))->toOthers();
```

**Key Changes:**
- ✅ Uses `call_ended_by` (consistent with doctor's implementation)
- ✅ Broadcasts CallStatusChanged event
- ✅ Sets proper metadata

---

### **2. Added Doctor's checkCallStatus Method** ✅
**File:** `app/Http/Controllers/Doctor/DoctorConsultationController.php`

```php
public function checkCallStatus($id)
{
    $doctor = Auth::user();
    
    $consultation = VideoConsultation::where('id', $id)
        ->where('doctor_id', $doctor->id)
        ->firstOrFail();

    $metadata = $consultation->call_metadata ?? [];
    $endedBy = $metadata['call_ended_by'] ?? null;
    $endType = $metadata['end_type'] ?? null;

    return response()->json([
        'status' => $consultation->status,
        'ended_by' => $endedBy,
        'end_type' => $endType,
        'should_redirect' => in_array($consultation->status, ['cancelled', 'completed']) && $endedBy !== 'doctor',
        'redirect_url' => route('doctor.video-consultation.show', $id),
        'message' => $consultation->status === 'completed' && $endedBy === 'student' 
            ? 'Call ended by patient'
            : null
    ]);
}
```

**Logic:**
- Returns `should_redirect = true` if:
  - Status is `completed` or `cancelled` AND
  - Call was NOT ended by the doctor (`ended_by !== 'doctor'`)
- Returns appropriate message

---

### **3. Added Doctor Route** ✅
**File:** `routes/doctor.php`

```php
Route::get('/consultations/{id}/status', [DoctorConsultationController::class, 'checkCallStatus'])
    ->name('consultations.status');
```

---

### **4. Updated VideoCall.jsx Polling** ✅
**File:** `resources/js/components/VideoCall.jsx`

**Before:**
```javascript
// Only poll for students
if (userType !== 'student' || !consultation?.id) return;

const response = await fetch(`/student/video-consultations/${consultation.id}/status`, {
```

**After:**
```javascript
// Poll for BOTH doctors and students
if (!consultation?.id) return;

const endpoint = userType === 'doctor'
    ? `/doctor/consultations/${consultation.id}/status`
    : `/student/video-consultations/${consultation.id}/status`;

const response = await fetch(endpoint, {
```

**Key Changes:**
- ✅ Removed student-only restriction
- ✅ Dynamic endpoint based on user type
- ✅ Works for both participants

---

## 📊 **How It Works Now**

### **Complete Flow:**

```
Participant A ends call
    ↓
Backend updates:
  - status = 'completed'
  - call_metadata.call_ended_by = 'student' (or 'doctor')
    ↓
Participant B's polling (every 2s):
  GET /[role]/consultations/{id}/status
    ↓
Backend returns:
  - should_redirect = true
  - message = "Call ended by [patient/doctor]"
    ↓
Participant B's VideoCall.jsx:
  - Stops polling
  - Leaves Stream call gracefully
  - Shows alert message
  - Redirects to show page
    ↓
Both participants exit cleanly ✅
```

---

## 🎯 **Scenarios Covered**

### **Scenario 1: Student Ends Call** ✅
```
Student clicks "End Call"
    ↓
POST /student/video-consultations/{id}/end
    ↓
Updates: status='completed', call_ended_by='student'
    ↓
Doctor's polling detects change (within 2s)
    ↓
Doctor sees: "Call ended by patient"
    ↓
Doctor redirected to show page
```

### **Scenario 2: Doctor Ends Call** ✅
```
Doctor clicks "End Call"
    ↓
POST /doctor/video-consultations/{id}/end
    ↓
Updates: status='completed', call_ended_by='doctor'
    ↓
Student's polling detects change (within 2s)
    ↓
Student sees: "Call ended by doctor"
    ↓
Student redirected to show page
```

### **Scenario 3: Doctor Rejects Call** ✅
```
Doctor clicks "Decline"
    ↓
POST /api/video-calls/reject
    ↓
Updates: status='cancelled', end_type='rejected'
    ↓
Student's polling detects (in waiting room or call)
    ↓
Student sees: "The doctor declined your call..."
    ↓
Student redirected to show page
```

---

## ✅ **Metadata Structure**

### **When Student Ends:**
```json
{
  "call_ended_by": "student",
  "ended_at": "2025-12-02T13:18:24.000Z"
}
```

### **When Doctor Ends:**
```json
{
  "call_ended_by": "doctor",
  "ended_at": "2025-12-02T13:18:24.000Z"
}
```

### **When Doctor Rejects:**
```json
{
  "call_ended_by": "doctor",
  "end_type": "rejected",
  "doctor_rejected_at": "2025-12-02T13:18:24.000Z",
  "rejection_reason": "Doctor declined the call"
}
```

---

## 🔒 **Key Logic**

### **checkCallStatus Response:**

**For Students:**
```javascript
should_redirect = (status IN ['cancelled', 'completed']) 
                  AND (ended_by !== 'student')
```

**For Doctors:**
```javascript
should_redirect = (status IN ['cancelled', 'completed']) 
                  AND (ended_by !== 'doctor')
```

**Result:**
- ✅ Don't redirect the person who ended the call (they already redirected via handleEndCall)
- ✅ DO redirect the other participant (they need to be notified)

---

## 📝 **Files Modified**

1. ✅ `app/Http/Controllers/Student/StudentConsultationController.php`
   - Fixed metadata key to `call_ended_by`
   - Added broadcast event

2. ✅ `app/Http/Controllers/Doctor/DoctorConsultationController.php`
   - Added `checkCallStatus()` method

3. ✅ `routes/doctor.php`
   - Added status check route

4. ✅ `resources/js/components/VideoCall.jsx`
   - Updated polling to work for both user types
   - Dynamic endpoint selection

5. ✅ **Compiled with** `npm run build` ✅

---

## 🧪 **Testing**

### **Test 1: Student Ends Call** ✅
1. Both in active call
2. Student clicks "End Call"
3. ✅ Student redirected immediately
4. ✅ Within 2s: Doctor sees "Call ended by patient"
5. ✅ Doctor redirected to show page
6. ✅ No orphaned sessions

### **Test 2: Doctor Ends Call** ✅
1. Both in active call
2. Doctor clicks "End Call"
3. ✅ Doctor redirected immediately
4. ✅ Within 2s: Student sees "Call ended by doctor"
5. ✅ Student redirected to show page
6. ✅ No orphaned sessions

### **Test 3: Doctor Rejects** ✅
1. Student in waiting room
2. Doctor clicks "Decline"
3. ✅ Within 2s: Student sees "The doctor declined your call..."
4. ✅ Student redirected to show page

---

## 🎉 **Benefits**

1. ✅ **Mutual Awareness** - Both participants know when call ends
2. ✅ **No Orphaned Calls** - No one stays in ended calls
3. ✅ **Clear Messages** - Users know who ended the call
4. ✅ **Graceful Cleanup** - Proper Stream call cleanup
5. ✅ **Consistent Behavior** - Works the same for both roles
6. ✅ **Fast Detection** - 2-second polling interval

---

## 📋 **Summary**

### **Before:**
- ❌ Student ends → Doctor stays in call
- ❌ Doctor ends → Student might stay in call
- ❌ No mutual awareness
- ❌ Orphaned sessions

### **After:**
- ✅ Student ends → Doctor notified & redirected
- ✅ Doctor ends → Student notified & redirected
- ✅ Both participants synchronized
- ✅ No orphaned sessions
- ✅ Clear communication

---

**Both participants now properly detect when the other person ends the call and are redirected within 2 seconds!** 🚀🎊
