<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Property;
use App\Models\PropertyLead;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'leadsCount' => PropertyLead::count(),
            'propertiesCount' => Property::count(),
            'postsCount' => Post::count(),
            'propertiesPublished' => Property::where('status', 'published')->count(),
            'propertiesDraft' => Property::where('status', 'draft')->count(),
            'propertiesViews' => (int) Property::sum('views'),
            'postsPublished' => Post::where('status', 'published')->count(),
            'postsDraft' => Post::where('status', 'draft')->count(),
            'postsViews' => (int) Post::sum('views'),
        ]);
    }
}
