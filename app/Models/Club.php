<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Club extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'status',
    ];

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

}
