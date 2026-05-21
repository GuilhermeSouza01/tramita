<?php

namespace App\Models;

use App\Enums\RequestStatus;
use App\Models\ApprovalStep;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Department;
use App\Models\RequestType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Override;
use Spatie\Activitylog\Traits\LogsActivity;

class Request extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $fillable = [
        'code',
        'request_type_id',
        'requester_id',
        'department_id',
        'title',
        'description',
        'status',
        'form_data',
        'submitted_at',
        'resolved_at',
        'due_at'
    ];

    protected $casts = [
        'status' => RequestStatus::class,
        'due_at' => 'datetime',
        'submitted_at' => 'datetime',
        'resolved_at' => 'datetime',
        'form_data' => 'array',
    ];

    // ActivityLog

    #[Override]
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('request');
    }


    public function requestType()
    {
        return $this->belongsTo(RequestType::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function approvalSteps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class)->orderBy('order');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    // Scopes

    public function scopePending($query)
    {
        return $query->whereIn('status', [
            RequestStatus::Pending,
            RequestStatus::UnderReview
        ]);
    }

    public function scopeForApprover($query, User $user)
    {
        return $query->whereHas('approvalSteps', fn($q) =>
            $q->where('approver_id', $user->id)
                ->where('status', 'pending')
        );
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_at')
                     ->where('due_at', '<', now())
                     ->whereNotIn('status', [
                         RequestStatus::Approved->value,
                         RequestStatus::Rejected->value,
                         RequestStatus::Cancelled->value,
                     ]);
    }

     public function currentStep(): ?ApprovalStep
    {
        return $this->approvalSteps()
                    ->where('status', 'pending')
                    ->orderBy('order')
                    ->first();
    }

    public function isOverdue(): bool
    {
        return $this->due_at
            && $this->due_at->isPast()
            && ! $this->status->isTerminal();
    }

    // --- Random code generation ---

    protected static function booted(): void
    {
        static::creating(function (Request $request) {
            $year  = now()->year;
            $count = static::whereYear('created_at', $year)->count() + 1;
            $request->code = 'REQ-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        });
    }
}
