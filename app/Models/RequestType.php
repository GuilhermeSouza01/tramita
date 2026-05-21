<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'sla_hours',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sla_hours' => 'integer',
    ];

    public function requests():HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function approvers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'request_type_approvers')
            ->withPivot('order')
            ->orderByPivot('order');
    }

    protected static function booted(): void
    {
        static::creating(function (RequestType $type) {
            if (empty($type->slug)) {
                $type->slug = str($type->name)->slug();
            }
        });
    }
}
