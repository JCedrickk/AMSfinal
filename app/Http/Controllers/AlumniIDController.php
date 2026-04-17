<?php

namespace App\Http\Controllers;

use App\Models\AlumniIdRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Updated notification message
        Notification::create([
            'user_id' => $idRequest->user_id,
            'type' => 'id_request_approved',
            'message' => 'Your Alumni ID request has been approved! Your ID Number is: ' . $alumniId . '. You may now claim your Alumni ID at the Alumni Office of the school. Please proceed there with the payment of ₱100.00 for the ID processing fee. Bring a valid ID for verification.',
            'is_read' => false
        ]);

        return back()->with('success', 'ID request approved. Alumni ID: ' . $alumniId);
    }

    public function rejectRequest(AlumniIdRequest $idRequest, Request $request)
    {
        $request->validate([
            'remarks' => 'required|string'
        ]);

        $idRequest->update([
            'status' => 'rejected',
            'remarks' => $request->remarks
        ]);

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
}