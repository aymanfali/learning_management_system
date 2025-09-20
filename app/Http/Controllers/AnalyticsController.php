<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    public function index()
    {
        try {
            // Monthly registrations (grouped by role)
            $monthlyStats = User::selectRaw('MONTH(created_at) as month, role, COUNT(*) as total')
                ->groupBy('month', 'role')
                ->orderBy('month')
                ->get()
                ->groupBy('month');


            // Daily registrations (last 7 days, grouped by role)
            $dailyStats = User::selectRaw('DATE(created_at) as date, role, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date', 'role')
                ->orderBy('date')
                ->get()
                ->groupBy('date');

            // Weekly courses (last 8 weeks)
            $weeklyCourses = Course::selectRaw('YEARWEEK(created_at, 1) as week, COUNT(*) as total')
                ->where('created_at', '>=', now()->subWeeks(8))
                ->groupBy('week')
                ->orderBy('week')
                ->pluck('total', 'week');

            $courseEnrollments = Enrollment::select('course_id', DB::raw('COUNT(*) as total'))
                ->groupBy('course_id')
                ->with('course:id,name')
                ->orderByDesc('total')
                ->take(5)
                ->get();


            return view('dashboard.index', compact(
                'monthlyStats',
                'dailyStats',
                'weeklyCourses',
                'courseEnrollments'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading analytics: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load dashboard analytics.');
        }
    }
}
