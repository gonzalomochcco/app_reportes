<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportOtherDebt extends Model
{

    protected $table = 'report_other_debts';

    protected $fillable = [
        'subscription_report_id',
        'entity',
        'currency',
        'amount',
        'expiration_days',
    ];

    public function subscriptionReport(): BelongsTo
    {
        return $this->belongsTo(SubscriptionReport::class);
    }

}
