<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionReport extends Model
{

    protected $table = 'subscription_reports';

    protected $fillable = [
        'subscription_id',
        'period',
        'created_at',
        'updated_at',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(ReportLoan::class);
    }

    public function otherDebts(): HasMany
    {
        return $this->hasMany(ReportOtherDebt::class);
    }

    public function creditCards(): HasMany
    {
        return $this->hasMany(ReportCreditCard::class);
    }

}
