<?php

namespace App\Models;

use Database\Factories\OrganizationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    /** @use HasFactory<OrganizationFactory> */
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

//todo: uncomment when add classes below

//    public function units(): HasMany
//    {
//        return $this->hasMany(Unit::class);
//    }
//
//    public function teams(): HasMany
//    {
//        return $this->hasMany(Team::class);
//    }
}
