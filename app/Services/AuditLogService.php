<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log an activity to the audit logs
     */
    public static function log($action, $module, $recordId = null, $recordName = null, $description = null, $oldValues = null, $newValues = null)
    {
        try {
            $user = Auth::user();
            
            AuditLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->first_name . ' ' . $user?->last_name ?? 'System',
                'action' => $action,
                'module' => $module,
                'record_id' => $recordId,
                'record_name' => $recordName,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail to not interrupt the main application flow
            \Log::error('Audit log error: ' . $e->getMessage());
        }
    }
}