<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display audit logs
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter by user
        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('record_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(20);
        $modules = AuditLog::distinct('module')->pluck('module');
        $actions = AuditLog::distinct('action')->pluck('action');
        $users = AuditLog::distinct('user_id', 'user_name')
                         ->select('user_id', 'user_name')
                         ->get()
                         ->unique('user_name');

        return view('admin.audit-logs', compact('auditLogs', 'modules', 'actions', 'users'));
    }

    /**
     * Export audit logs to CSV
     */
    public function export(Request $request)
    {
        $query = AuditLog::query();

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=audit-logs-" . now()->format('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0"
        );

        $callback = function() use($logs) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Date', 'Time', 'User', 'Action', 'Module', 'Record', 'Description', 'IP Address']);
            
            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d'),
                    $log->created_at->format('H:i:s'),
                    $log->user_name,
                    $log->action,
                    $log->module,
                    $log->record_name ?? 'N/A',
                    $log->description,
                    $log->ip_address,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}