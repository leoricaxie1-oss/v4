<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\Resident;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        // Population
        $populationByPurok = Resident::select('purok_id', DB::raw('count(*) as total'))
            ->groupBy('purok_id')->with('purok:id,name')->get();

        $genderDistribution = Resident::select('sex', DB::raw('count(*) as total'))
            ->whereNotNull('sex')->groupBy('sex')->get();

        $ageBuckets = [
            '0-12'  => Resident::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 0 AND 12')->count(),
            '13-17' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 13 AND 17')->count(),
            '18-29' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 18 AND 29')->count(),
            '30-44' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 30 AND 44')->count(),
            '45-59' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 45 AND 59')->count(),
            '60+'   => Resident::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 60')->count(),
        ];

        $monthlyDocuments = Document::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
                DB::raw('count(*) as total')
            )->where('created_at', '>=', now()->subYear())
             ->groupBy('ym')->orderBy('ym')->get();

        $complaintTrends = Complaint::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
                DB::raw('count(*) as total')
            )->where('created_at', '>=', now()->subYear())
             ->groupBy('ym')->orderBy('ym')->get();

        $incidentsPerPurok = Complaint::join('users', 'complaints.complainant_id', '=', 'users.id')
            ->join('residents', 'residents.user_id', '=', 'users.id')
            ->select('residents.purok_id', DB::raw('count(*) as total'))
            ->groupBy('residents.purok_id')->get();

        $businessCount  = Business::where('status', 'approved')->count();
        $serviceCount   = \App\Models\SkillService::where('status', 'approved')->count();

        return view('analytics.index', compact(
            'populationByPurok', 'genderDistribution', 'ageBuckets',
            'monthlyDocuments', 'complaintTrends', 'incidentsPerPurok',
            'businessCount', 'serviceCount'
        ));
    }
}
