<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HrUsers extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'head_id',
        'team_head',
        'approver',
        'company_id',
        'group_name',
    ];

    public function companyItems()
    {
        return $this->hasMany(HRUserCompanyItems::class, 'hr_user_id', 'id');
    }

    public function locationItems()
    {
        return $this->hasMany(HRUserLocationItems::class, 'hr_user_id', 'id');
    }

    public function jobLevelItems()
    {
        return $this->hasMany(HrUserJobLevelItems::class, 'hr_user_id', 'id');
    }

    /**
     * Transfer store access employees from one HR user to another
     * 
     * @param int $fromHrUserId Source HR user ID
     * @param int $toHrUserId Target HR user ID
     * @param array $employeeIds Array of employee IDs to transfer
     * @return array Result with success status and message
     */
    public static function transferStoreAccessEmployees($fromHrUserId, $toHrUserId, $employeeIds)
    {
        DB::beginTransaction();
        try {
            if (empty($employeeIds)) {
                return [
                    'success' => false,
                    'message' => 'No employees selected for transfer'
                ];
            }

            // Verify both HR users exist
            $fromHrUser = self::find($fromHrUserId);
            $toHrUser = self::find($toHrUserId);

            if (!$fromHrUser || !$toHrUser) {
                return [
                    'success' => false,
                    'message' => 'Invalid HR user ID(s)'
                ];
            }

            // Verify both are store departments
            if ($fromHrUser->department_id != 8 || $toHrUser->department_id != 8) {
                return [
                    'success' => false,
                    'message' => 'Both HR users must be in the Store department'
                ];
            }

            // Get existing store accesses
            $existingAccesses = DB::table('hr_user_store_accesses')
                ->where('hr_user_id', $fromHrUserId)
                ->whereIn('employee_id', $employeeIds)
                ->get();

            if ($existingAccesses->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No matching store access records found'
                ];
            }

            $transferred = 0;
            $skipped = 0;

            foreach ($existingAccesses as $access) {
                // Check if employee already exists for target HR user
                $existing = DB::table('hr_user_store_accesses')
                    ->where('hr_user_id', $toHrUserId)
                    ->where('employee_id', $access->employee_id)
                    ->first();

                if ($existing) {
                    $skipped++;
                    continue;
                }

                // Transfer the access
                DB::table('hr_user_store_accesses')
                    ->where('id', $access->id)
                    ->update([
                        'hr_user_id' => $toHrUserId,
                        'updated_at' => now()
                    ]);

                $transferred++;
            }

            DB::commit();

            $message = "Successfully transferred {$transferred} employee(s)";
            if ($skipped > 0) {
                $message .= ". {$skipped} employee(s) already exist in target HR user and were skipped.";
            }

            return [
                'success' => true,
                'message' => $message,
                'transferred' => $transferred,
                'skipped' => $skipped
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('hruser')->error('Error transferring store access employees: ' . $e->getMessage(), [
                'from_hr_user_id' => $fromHrUserId,
                'to_hr_user_id' => $toHrUserId,
                'employee_ids' => $employeeIds,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to transfer employees: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get all HR users who have access to store department (department_id = 8)
     * 
     * @return array List of HR users with store department access
     */
    public static function getStoreDepartmentHrUsers()
    {
        return DB::table('hr_users')
            ->leftJoin('users', 'users.id', 'hr_users.user_id')
            ->leftJoin('departments', 'departments.id', 'hr_users.department_id')
            ->select(
                'hr_users.id as hr_user_id',
                'hr_users.user_id',
                'users.name',
                'users.job_title',
                'departments.name as department_name'
            )
            ->where('hr_users.department_id', 8)
            ->groupBy('hr_users.id', 'hr_users.user_id', 'users.name', 'users.job_title', 'departments.name')
            ->get()
            ->map(function ($user) {
                return [
                    'hr_user_id' => $user->hr_user_id,
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'job_title' => $user->job_title,
                    'department_name' => $user->department_name,
                    'display_text' => $user->name . ($user->job_title ? ' - ' . $user->job_title : '')
                ];
            })
            ->toArray();
    }
}
