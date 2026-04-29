<?php

namespace App\Http\Controllers;

use App\Models\AlumniIdRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\IdRequestApprovedMail;
use App\Mail\IdRequestRejectedMail;
use Illuminate\Support\Facades\Log;

class AlumniIDController extends Controller
{
    public function showRequestForm()
    {
        $existingRequest = AlumniIdRequest::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();
            
        return view('alumni-id.request', compact('existingRequest'));
    }

    public function submitRequest(Request $request)
    {
        $existingRequest = AlumniIdRequest::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();
            
        if ($existingRequest) {
            return back()->with('error', 'You already have a pending or approved request.');
        }

        $idRequest = AlumniIdRequest::create([
            'user_id' => Auth::id(),
            'request_date' => now(),
            'status' => 'pending'
        ]);

        // Notify admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'id_request',
                'message' => 'New Alumni ID request from ' . Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'is_read' => false
            ]);
        }

        return back()->with('success', 'ID request submitted successfully.');
    }

    public function pendingRequests()
    {
        $requests = AlumniIdRequest::with('user.profile')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.id-requests.pending', compact('requests'));
    }

    public function approveRequest(AlumniIdRequest $idRequest)
    {
        $alumniId = 'ALU-' . strtoupper(uniqid()) . '-' . date('Y');
        
        $idRequest->update([
            'status' => 'approved',
            'alumni_id_number' => $alumniId
        ]);
        
        // Send email notification
        try {
            Mail::to($idRequest->user->email)->send(new IdRequestApprovedMail($idRequest->user, $idRequest));
        } catch (\Exception $e) {
            Log::error('Failed to send ID approval email: ' . $e->getMessage());
        }
        
        Notification::create([
            'user_id' => $idRequest->user_id,
            'type' => 'id_request_approved',
            'message' => 'Your Alumni ID request has been approved! Your ID Number is: ' . $alumniId . '. You may now claim your ID at the Alumni Office.',
            'is_read' => false
        ]);
        
        return back()->with('success', 'ID request approved. Alumni ID: ' . $alumniId);
    }

    public function rejectRequest(Request $request, AlumniIdRequest $idRequest)
    {
        $request->validate([
            'remarks' => 'required|string'
        ]);

        $idRequest->update([
            'status' => 'rejected',
            'remarks' => $request->remarks
        ]);
        
        // Send email notification
        try {
            Mail::to($idRequest->user->email)->send(new IdRequestRejectedMail($idRequest->user, $request->remarks));
        } catch (\Exception $e) {
            Log::error('Failed to send ID rejection email: ' . $e->getMessage());
        }

        Notification::create([
            'user_id' => $idRequest->user_id,
            'type' => 'id_request_rejected',
            'message' => 'Your Alumni ID request was rejected. Reason: ' . $request->remarks,
            'is_read' => false
        ]);

        return back()->with('success', 'ID request rejected.');
    }

    public function allRequests()
    {
        $requests = AlumniIdRequest::with('user.profile')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.id-requests.index', compact('requests'));
    }

    // ========== NEW METHODS ==========

    public function markIdAsClaimed(AlumniIdRequest $idRequest)
    {
        if ($idRequest->status !== 'approved') {
            return back()->with('error', 'Only approved ID requests can be marked as claimed.');
        }
        
        $idRequest->update([
            'claimed' => true,
            'claimed_at' => now()
        ]);
        
        // Notify the user
        Notification::create([
            'user_id' => $idRequest->user_id,
            'type' => 'id_claimed',
            'message' => 'Your Alumni ID has been marked as claimed. Thank you for visiting the Alumni Office!',
            'is_read' => false
        ]);
        
        return back()->with('success', 'ID marked as claimed successfully.');
    }

    public function markIdAsUnclaimed(AlumniIdRequest $idRequest)
    {
        if ($idRequest->status !== 'approved') {
            return back()->with('error', 'Only approved ID requests can be marked as unclaimed.');
        }
        
        $idRequest->update([
            'claimed' => false,
            'claimed_at' => null
        ]);
        
        // Notify the user
        Notification::create([
            'user_id' => $idRequest->user_id,
            'type' => 'id_unclaimed',
            'message' => 'Your Alumni ID status has been updated to "Not Claimed". Please visit the Alumni Office to claim your ID.',
            'is_read' => false
        ]);
        
        return back()->with('success', 'ID marked as unclaimed successfully.');
    }

    public function requestNewId(Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
            'other_reason' => 'required_if:reason,Other|string|nullable',
            'old_request_id' => 'required|exists:alumni_id_requests,id'
        ]);
        
        $oldRequest = AlumniIdRequest::findOrFail($request->old_request_id);
        
        // Check if the old request is approved and claimed
        if ($oldRequest->status !== 'approved' || !$oldRequest->claimed) {
            return back()->with('error', 'You can only request a new ID if your current ID has been claimed.');
        }
        
        $reason = $request->reason;
        if ($reason === 'Other') {
            $reason = $request->other_reason;
        }
        
        // Create new ID request
        $newRequest = AlumniIdRequest::create([
            'user_id' => Auth::id(),
            'request_date' => now(),
            'status' => 'pending',
            'remarks' => 'Replacement request. Reason: ' . $reason . ' | Old ID: ' . $oldRequest->alumni_id_number
        ]);
        
        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'id_replacement_request',
                'message' => '🔄 Replacement Alumni ID request from ' . Auth::user()->first_name . ' ' . Auth::user()->last_name . '. Reason: ' . $reason,
                'is_read' => false
            ]);
        }
        
        // Notify user
        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'id_replacement_submitted',
            'message' => 'Your replacement ID request has been submitted. Please wait for admin approval.',
            'is_read' => false
        ]);
        
        return redirect()->route('alumni-id.request')->with('success', 'Your replacement ID request has been submitted. Please wait for admin approval.');
    }
}