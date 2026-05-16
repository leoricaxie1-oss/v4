<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\SkillService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $type     = $request->query('type', 'services'); // services | businesses
        $category = $request->query('category');
        $q        = trim((string) $request->query('q'));

        if ($type === 'businesses') {
            $items = Business::public()
                ->when($q, fn ($qq) => $qq->where('business_name', 'like', "%{$q}%"))
                ->paginate(12)->withQueryString();
        } else {
            $items = SkillService::public()
                ->when($category, fn ($qq) => $qq->where('category', $category))
                ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
                }))
                ->paginate(12)->withQueryString();
        }

        return view('landing.directory', [
            'type'       => $type,
            'category'   => $category,
            'q'          => $q,
            'items'      => $items,
            'categories' => config('panipone.skill_categories'),
        ]);
    }

    public function showService(SkillService $service): View
    {
        abort_unless($service->status === 'approved', 404);
        $service->increment('views_count');
        $service->load('user', 'reviews.user');
        return view('landing.service-show', compact('service'));
    }

    public function showBusiness(Business $business): View
    {
        abort_unless($business->status === 'approved', 404);
        $business->load('owner', 'reviews.user');
        return view('landing.business-show', compact('business'));
    }
}
