<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{

    protected $table = 'subscriptions';

    protected $fillable = [
        'full_name',
        'document',
        'email',
        'phone',
    ];

    public function subscriptionReports(): HasMany
    {
        return $this->hasMany(SubscriptionReport::class);
    }

}
