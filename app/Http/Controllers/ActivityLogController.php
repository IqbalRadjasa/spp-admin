<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::query()
            ->with('user')
            ->latest()
            ->paginate(5);

        return view('activity-logs', compact('activityLogs'));
    }
}
