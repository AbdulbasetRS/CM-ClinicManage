<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'visit_id',
        'total_amount',
        'discount',
        'final_amount',
        'status',
        'payment_method',
        'invoice_date',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'payment_method' => PaymentMethod::class,
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function updateTotals(): void
    {
        // 1. حساب الإجمالي قبل الخصم
        $totalAmount = $this->items()->sum(DB::raw('amount * quantity'));

        // 2. حساب القيمة النهائية بعد الخصم
        $finalAmount = $totalAmount - $this->discount;

        // 3. تحديث الفاتورة (استخدام fill/save لضمان عدم حدوث حلقة لا نهائية مع Update)
        $this->fill([
            'total_amount' => $totalAmount,
            'final_amount' => max(0, $finalAmount),
        ]);
        $this->save();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
