<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportCreditCard extends Model
{

    protected $table = 'report_credit_cards';

    protected $fillable = [
        'subscription_report_id',
        'bank',
        'currency',
        'line',
        'used',
    ];

    public function subscriptionReport(): BelongsTo
    {
        return $this->belongsTo(SubscriptionReport::class);
    }

}
