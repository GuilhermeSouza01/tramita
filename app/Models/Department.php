<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function requests() :HasMany
    {
        return $this->hasMany(Request::class);
    }

    // Generate slug from name
    protected static function booted()
    {
        static::creating(function (Department $department) {
            if (empty($department->slug)) {
                $department->slug = str($department->name)->slug();
            }
        });
    }
}
