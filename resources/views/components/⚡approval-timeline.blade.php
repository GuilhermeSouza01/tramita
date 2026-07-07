<?php

use Livewire\Component;
use App\Models\Request;

new class extends Component
{
    public Request $record;

    public function with(): array
    {
        $steps = $this->record->approvalSteps()
            ->with('approver')
            ->orderBy('order')
            ->get();
        $currentStep = $steps->firstWhere('status', 'pending');
        return [
            'steps' => $steps,
            'currentStep' => $currentStep,
            'totalSteps' => $steps->count(),
            'completedSteps' => $steps->whereIn('status', ['approved'])->count(),
        ];
    }
};
?>

<div class="py-6">
    <div class="relative">
        @forelse ($steps as $step)
            @php
                $isApproved = $step->status === 'approved';
                $isRejected = $step->status === 'rejected';
                $isCurrent  = $step->is($currentStep);
                $isPending  = $step->status === 'pending' && ! $isCurrent;
                $deptName   = $step->approver?->department?->name;
                $subtitle = $step->approver?->name;
                if ($deptName) {
                    $subtitle .= ' (' . $deptName . ')';
                }
                if ($step->decided_at) {
                    $subtitle .= ' • ' . $step->decided_at->format('d/m/Y H:i');
                }
            @endphp
            <div @class([
                'relative flex gap-7 pb-10 last:pb-0 transition-all duration-200',
                'opacity-50' => $isPending,
            ])>
                {{-- Indicator --}}
                <div class="relative flex w-11 flex-col items-center">
                    <div @class([
                        'relative z-10 flex h-11 w-11 items-center justify-center rounded-full transition-all duration-200',
                        'bg-success-500 text-white shadow-sm shadow-success-500/30' => $isApproved,
                        'bg-danger-500 text-white shadow-sm shadow-danger-500/30' => $isRejected,
                        'bg-primary-600 text-white shadow-lg shadow-primary-500/30 ring-[3px] ring-primary-500/25' => $isCurrent,
                        'bg-gray-200 dark:bg-gray-700 text-gray-400' => $isPending,
                    ])>
                        @if ($isApproved)
                            <x-filament::icon icon="heroicon-m-check" class="h-5 w-5" />
                        @elseif ($isRejected)
                            <x-filament::icon icon="heroicon-m-x-mark" class="h-5 w-5" />
                        @elseif ($isCurrent)
                            <span class="h-3 w-3 animate-pulse rounded-full bg-white"></span>
                        @else
                            <span class="h-2.5 w-2.5 rounded-full bg-current opacity-30"></span>
                        @endif
                    </div>
                    @if (! $loop->last)
                        <div @class([
                            'absolute top-[22px] w-0.5 transition-all',
                            'h-[calc(100%+4px)]',
                            'bg-success-300 dark:bg-success-700' => $isApproved,
                            'bg-gray-300 dark:bg-gray-600' => $isPending || $isCurrent,
                            'bg-danger-300 dark:bg-danger-700' => $isRejected,
                        ]) style="left: 50%; margin-left: -0.5px;"></div>
                    @endif
                </div>
                {{-- Content --}}
                <div @class([
                    'flex-1 min-w-0 rounded-xl border p-5 transition-all duration-200',
                    'hover:bg-white/[0.04]',
                    'border-gray-200 dark:border-gray-700' => ! $isCurrent,
                    'border-primary-500/30 bg-primary-500/[0.03] dark:bg-primary-500/[0.04]' => $isCurrent,
                ])>
                    <div class="flex items-start justify-between gap-6">
                        <div class="min-w-0 flex-1">
                            <h4 @class([
                                'text-sm font-semibold leading-tight',
                                'text-gray-900 dark:text-white' => ! $isPending,
                                'text-gray-500 dark:text-gray-400' => $isPending,
                            ])>
                                {{ $step->approver?->name ?? '—' }}
                            </h4>
                            <p @class([
                                'mt-1 text-sm leading-relaxed',
                                'text-gray-500 dark:text-gray-400' => ! $isPending,
                                'text-gray-400 dark:text-gray-500' => $isPending,
                            ])>
                                {{ $subtitle }}
                            </p>
                            @if ($step->notes)
                                <p class="mt-2 text-xs italic text-gray-400 dark:text-gray-500">
                                    "{{ $step->notes }}"
                                </p>
                            @endif
                            @if ($isCurrent)
                                <div class="mt-3 h-1 w-full max-w-[280px] overflow-hidden rounded-full bg-gray-100 dark:bg-white/5">
                                    <div class="h-full rounded-full bg-primary-500 transition-all duration-700"
                                         style="width: {{ $completedSteps > 0 ? min(($completedSteps / max($totalSteps, 1)) * 100, 95) : 15 }}%">
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="shrink-0 pt-0.5">
                            <x-filament::badge
                                :color="match(true) {
                                    $isApproved => 'success',
                                    $isRejected => 'danger',
                                    $isCurrent => 'primary',
                                    default => 'gray',
                                }"
                                :icon="match(true) {
                                    $isApproved => 'heroicon-m-check-circle',
                                    $isRejected => 'heroicon-m-x-circle',
                                    $isCurrent => 'heroicon-m-arrow-path',
                                    default => 'heroicon-m-clock',
                                }"
                            >
                                {{ match(true) {
                                    $isApproved => 'CONCLUÍDO',
                                    $isRejected => 'REJEITADO',
                                    $isCurrent => 'EM ANDAMENTO',
                                    default => 'PENDENTE',
                                } }}
                            </x-filament::badge>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-white/5">
                    <x-filament::icon icon="heroicon-m-clock" class="h-6 w-6 text-gray-400 dark:text-gray-500" />
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Nenhuma etapa de aprovação
                </p>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                    Esta solicitação não possui etapas configuradas.
                </p>
            </div>
        @endforelse
    </div>
</div>
