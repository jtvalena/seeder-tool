<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
class AdminFasPurchaseRequisition extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'fas_number',
        'department_id',
        'location_id',
        'status',
        'company_id',
        'store_branches_id',
        'admin_remarks',
        'justification_remarks',
        'total_amount',
        'requested_by',
        'endorse_by',
        'processed_by',
        'noted_by',
        'items_received_by',
        'budget_checked_by',
        'expected_date',
        'has_po',
        'type',
        'transaction_type',
        'reject_reason',
        'petty_cash',
        'deleted_by',
        'adjusted_by',
        'adjusted_at',
        'capital_vendor_id',
        'capital_company_id',
        'capital_store_branch_id',
        'admin_warehouse_id',
        'requestor_department_id',
        'confirmed_date',
        'approved_date',
        'admin_checked_date',
        'admin_received_date',
        'admin_approved_date',
        'admin_rejected_date',
        'received',
        'mass_created'
    ];

    public function AdminFasPurchaseRequisitionItems(){
        return $this->hasMany(AdminFasPurchaseRequisitionItems::class,'admin_fpr_id');
    }

    /**
     * When a PO becomes Completed, complete On Process requisitions whose linked POs are all done.
     */
    public static function completeFromCompletedPurchaseOrder(int $purchaseOrderId): void
    {
        $viaBasket = DB::table('admin_purchase_order_items as poi')
            ->join('admin_fas_basket_items as bi', 'bi.id', '=', 'poi.basket_item_id')
            ->join('admin_fas_purchase_requisition_items as ri', 'ri.id', '=', 'bi.admin_fas_requisition_item_id')
            ->where('poi.admin_purchase_order_id', $purchaseOrderId)
            ->whereNull('poi.deleted_at')
            ->whereNull('bi.deleted_at')
            ->whereNull('ri.deleted_at')
            ->pluck('ri.admin_fpr_id');

        $viaDirect = DB::table('admin_purchase_order_items as poi')
            ->join('admin_fas_purchase_requisition_items as ri', 'ri.admin_purchase_order_item_id', '=', 'poi.id')
            ->where('poi.admin_purchase_order_id', $purchaseOrderId)
            ->whereNull('poi.deleted_at')
            ->whereNull('ri.deleted_at')
            ->pluck('ri.admin_fpr_id');

        $requisitionIds = $viaBasket->merge($viaDirect)->unique()->filter();

        foreach ($requisitionIds as $requisitionId) {
            self::tryCompleteOnProcessRequisition((int) $requisitionId);
        }
    }

    /**
     * Mark On Process requisition as Completed when all linked POs are Completed (and none remain basket-only).
     */
    public static function tryCompleteOnProcessRequisition(int $requisitionId): void
    {
        $requisition = self::find($requisitionId);
        if (!$requisition || (int) $requisition->status !== 6) {
            return;
        }

        $hasCompletedPo = self::requisitionHasPurchaseOrderWithStatuses($requisitionId, [7]);
        if (!$hasCompletedPo) {
            return;
        }

        $hasIncompletePo = self::requisitionHasPurchaseOrderOutsideStatuses($requisitionId, [7, 10]);
        if ($hasIncompletePo) {
            return;
        }

        $hasBasketOnly = DB::table('admin_fas_purchase_requisition_items as ri')
            ->join('admin_fas_basket_items as bi', 'bi.admin_fas_requisition_item_id', '=', 'ri.id')
            ->leftJoin('admin_purchase_order_items as poi', function ($join) {
                $join->on('poi.basket_item_id', '=', 'bi.id')->whereNull('poi.deleted_at');
            })
            ->where('ri.admin_fpr_id', $requisitionId)
            ->whereNull('ri.deleted_at')
            ->whereNull('bi.deleted_at')
            ->whereNull('poi.id')
            ->where(function ($q) {
                $q->whereNull('ri.admin_purchase_order_item_id')
                    ->orWhere('ri.admin_purchase_order_item_id', 0);
            })
            ->exists();

        if ($hasBasketOnly) {
            return;
        }

        $requisition->status = 5;
        $requisition->saveQuietly();
    }

    private static function requisitionHasPurchaseOrderWithStatuses(int $requisitionId, array $statuses): bool
    {
        return DB::table('admin_fas_purchase_requisition_items as ri')
            ->leftJoin('admin_fas_basket_items as bi', function ($join) {
                $join->on('bi.admin_fas_requisition_item_id', '=', 'ri.id')->whereNull('bi.deleted_at');
            })
            ->leftJoin('admin_purchase_order_items as poi', function ($join) {
                $join->on('poi.basket_item_id', '=', 'bi.id')->whereNull('poi.deleted_at');
            })
            ->leftJoin('admin_purchase_orders as po', function ($join) {
                $join->on('po.id', '=', 'poi.admin_purchase_order_id')->whereNull('po.deleted_at');
            })
            ->leftJoin('admin_purchase_order_items as poi_direct', function ($join) {
                $join->on('poi_direct.id', '=', 'ri.admin_purchase_order_item_id')->whereNull('poi_direct.deleted_at');
            })
            ->leftJoin('admin_purchase_orders as po_direct', function ($join) {
                $join->on('po_direct.id', '=', 'poi_direct.admin_purchase_order_id')->whereNull('po_direct.deleted_at');
            })
            ->where('ri.admin_fpr_id', $requisitionId)
            ->whereNull('ri.deleted_at')
            ->where(function ($q) use ($statuses) {
                $q->whereIn('po.status', $statuses)
                    ->orWhereIn('po_direct.status', $statuses);
            })
            ->exists();
    }

    private static function requisitionHasPurchaseOrderOutsideStatuses(int $requisitionId, array $statuses): bool
    {
        return DB::table('admin_fas_purchase_requisition_items as ri')
            ->leftJoin('admin_fas_basket_items as bi', function ($join) {
                $join->on('bi.admin_fas_requisition_item_id', '=', 'ri.id')->whereNull('bi.deleted_at');
            })
            ->leftJoin('admin_purchase_order_items as poi', function ($join) {
                $join->on('poi.basket_item_id', '=', 'bi.id')->whereNull('poi.deleted_at');
            })
            ->leftJoin('admin_purchase_orders as po', function ($join) {
                $join->on('po.id', '=', 'poi.admin_purchase_order_id')->whereNull('po.deleted_at');
            })
            ->leftJoin('admin_purchase_order_items as poi_direct', function ($join) {
                $join->on('poi_direct.id', '=', 'ri.admin_purchase_order_item_id')->whereNull('poi_direct.deleted_at');
            })
            ->leftJoin('admin_purchase_orders as po_direct', function ($join) {
                $join->on('po_direct.id', '=', 'poi_direct.admin_purchase_order_id')->whereNull('po_direct.deleted_at');
            })
            ->where('ri.admin_fpr_id', $requisitionId)
            ->whereNull('ri.deleted_at')
            ->where(function ($q) use ($statuses) {
                $q->where(function ($inner) use ($statuses) {
                    $inner->whereNotNull('po.id')->whereNotIn('po.status', $statuses);
                })->orWhere(function ($inner) use ($statuses) {
                    $inner->whereNotNull('po_direct.id')->whereNotIn('po_direct.status', $statuses);
                });
            })
            ->exists();
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            DB::transaction(function () use ($model) {
                if ($model->transaction_type == 'prf') {
                    $department = Department::find(Auth::user()->department_id);

                    if (empty($department) || empty($department->admin_code)) {
                        $department = Department::find(request()->get('department_id', null));
                        if (empty($department) || empty($department->admin_code)) {
                            $year_now = date("Y");
                            if (!$model->fas_number) {
                                $model->fas_number = $year_now . "-0001";
                            }
                            return;
                        }
                    }

                    $code = $department->admin_code;
                    $currentYear = date("Y");

                    $previous_prf_num = AdminFasPurchaseRequisition::where('transaction_type', 'prf')
                        ->whereNotNull('fas_number')
                        ->where('fas_number', 'REGEXP', "^{$code}-{$currentYear}-\\d{4}$")
                        ->orderBy('fas_number', 'desc')
                        ->lockForUpdate()
                        ->value('fas_number');

                    if ($previous_prf_num) {
                        $newNumber = str_pad(((int) substr($previous_prf_num, -4)) + 1, 4, '0', STR_PAD_LEFT);
                        $paddedNum = preg_replace('/\d{4}$/', $newNumber, $previous_prf_num);
                    } else {
                        $paddedNum = "{$code}-{$currentYear}-0001";
                    }

                    if (!$model->fas_number) {
                        $model->fas_number = $paddedNum;
                    }
                } else {
                    $find_department = Department::find(request()->get('department_id', null));
                    $department_code = '';
                    if (!empty($find_department)) {
                        $department_code = strtoupper($find_department->admin_code);
                    }
                    $year_now = date("Y");
                    $month_now = date("m");

                    $previous_fas_num = AdminFasPurchaseRequisition::where('transaction_type', 'fas')
                        ->whereNotNull('fas_number')
                        ->where('fas_number', 'LIKE', '%v' . $month_now . '-' . $year_now)
                        ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(fas_number, "v", 1), "-", -1) AS UNSIGNED) DESC')
                        ->lockForUpdate()
                        ->value('fas_number');

                    $count = 1;
                    if ($previous_fas_num && preg_match('/(\d{4})v(\d{2})-(\d{4})$/', $previous_fas_num, $matches)) {
                        if ($matches[2] == $month_now && $matches[3] == $year_now) {
                            $count = intval($matches[1]) + 1;
                        }
                    }

                    $count = str_pad($count, 4, "0", STR_PAD_LEFT);

                    if (!empty($find_department)) {
                        $paddedNum = $department_code . "-" . $count . 'v' . $month_now . '-' . $year_now;
                    } else {
                        $paddedNum = $count . 'v' . $month_now . '-' . $year_now;
                    }

                    if (!$model->fas_number) {
                        $model->fas_number = $paddedNum;
                    }
                }
            });
        });
    }
}
