<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('resident.dashboard', [
            'user'           => $user,
            'docs_count'     => Document::where('user_id', $user->id)->count(),
            'docs_pending'   => Document::where('user_id', $user->id)->where('status', 'pending_review')->count(),
            'docs_ready'     => Document::where('user_id', $user->id)->where('status', 'ready_for_pickup')->count(),
            'complaints_count'=> Complaint::where('complainant_id', $user->id)->count(),
            'open_complaints'=> Complaint::where('complainant_id', $user->id)
                                ->whereNotIn('status', ['resolved', 'dismissed'])->count(),
            'appointments'   => Appointment::where('user_id', $user->id)
                                ->where('scheduled_at', '>=', now())
                                ->orderBy('scheduled_at')->limit(5)->get(),
            'announcements'  => Announcement::published()->latest('published_at')->limit(3)->get(),
        ]);
    }
}
