<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ApprovalStep extends Model
{
     use HasFactory;

    protected $fillable = [
        'request_id',
        'approver_id',
        'order',
        'status',
        'notes',
        'decided_at',
    ];

    protected $casts = [
        'decided_at' => 'datetime',
        'order'      => 'integer',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDecided(): bool
    {
        return in_array($this->status, ['approved', 'rejected']);
    }
}
