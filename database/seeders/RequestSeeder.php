<?php

namespace Database\Seeders;

use App\Enums\RequestStatus;
use App\Models\ApprovalStep;
use App\Models\Comment as ModelsComment;
use App\Models\Department;
use App\Models\Request;
use App\Models\RequestType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $solicitantes = User::role('requester')->get();
        $types        = RequestType::with('approvers')->get();
        $departments  = Department::all();

        $scenarios = [
            // Aprovadas
            ['status' => RequestStatus::Approved,    'count' => 8],
            // Rejeitadas
            ['status' => RequestStatus::Rejected,    'count' => 3],
            // Em análise
            ['status' => RequestStatus::UnderReview, 'count' => 5],
            // Pendentes
            ['status' => RequestStatus::Pending,     'count' => 4],
            // Rascunho
            ['status' => RequestStatus::Draft,       'count' => 3],
            // Ajuste solicitado
            ['status' => RequestStatus::AdjustmentRequested, 'count' => 2],
        ];

        foreach ($scenarios as $scenario) {
            for ($i = 0; $i < $scenario['count']; $i++) {
                /** @var RequestType $type */
                $type        = $types->random();
                /** @var User $solicitante */
                $solicitante = $solicitantes->random();
                $department  = $departments->random();

                $createdAt   = now()->subDays(rand(1, 60));
                $submittedAt = in_array($scenario['status'], [RequestStatus::Draft])
                    ? null
                    : $createdAt->copy()->addHours(rand(1, 4));

                $resolvedAt = in_array($scenario['status'], [RequestStatus::Approved, RequestStatus::Rejected])
                    ? $submittedAt?->copy()->addHours(rand(2, 72))
                    : null;

                $dueAt = $type->sla_hours && $submittedAt
                    ? $submittedAt->copy()->addHours($type->sla_hours)
                    : null;

                $request = Request::create([
                    'code'            => $this->generateCode(),
                    'request_type_id' => $type->id,
                    'requester_id'    => $solicitante->id,
                    'department_id'   => $department->id,
                    'title'           => $this->fakeTitle($type->name),
                    'description'     => $this->fakeDescription(),
                    'status'          => $scenario['status'],
                    'submitted_at'    => $submittedAt,
                    'resolved_at'     => $resolvedAt,
                    'due_at'          => $dueAt,
                    'created_at'      => $createdAt,
                    'updated_at'      => $createdAt,
                ]);

                // Criar etapas de aprovação
                $this->createApprovalSteps($request, $type, $scenario['status']);

                // Criar comentários
                $this->createComments($request, $solicitante);
            }
        }
    }

    private function createApprovalSteps(Request $request, RequestType $type, RequestStatus $status): void
    {
        foreach ($type->approvers as $approver) {
            $stepStatus = match(true) {
                $status === RequestStatus::Approved    => 'approved',
                $status === RequestStatus::Rejected && $approver->pivot->order === 1 => 'rejected',
                $status === RequestStatus::UnderReview && $approver->pivot->order === 1 => 'pending',
                $status === RequestStatus::Pending     => 'pending',
                default => 'pending',
            };

            $decidedAt = in_array($stepStatus, ['approved', 'rejected'])
                ? $request->submitted_at?->copy()->addHours(rand(1, 48))
                : null;

            ApprovalStep::create([
                'request_id'  => $request->id,
                'approver_id' => $approver->id,
                'order'       => $approver->pivot->order,
                'status'      => $stepStatus,
                'notes'       => $stepStatus === 'rejected' ? 'Solicitação não atende aos critérios estabelecidos.' : null,
                'decided_at'  => $decidedAt,
                'created_at'  => $request->created_at,
                'updated_at'  => $request->created_at,
            ]);
        }
    }

    private function createComments(Request $request, User $solicitante): void
    {
        if (rand(0, 1)) {
            ModelsComment::create([
                'request_id'  => $request->id,
                'user_id'     => $solicitante->id,
                'body'        => $this->fakeComment(),
                'is_internal' => false,
                'created_at'  => $request->created_at->copy()->addHours(rand(1, 5)),
            ]);
        }
    }

    private function fakeTitle(string $type): string
    {
        $titles = [
            'Solicitação de Compra'        => ['Compra de notebook Dell', 'Aquisição de cadeiras ergonômicas', 'Compra de licenças de software', 'Material de escritório Q2'],
            'Reembolso de Despesas'        => ['Reembolso viagem São Paulo', 'Despesas com cliente - jantar', 'Uber para reunião externa', 'Reembolso material treinamento'],
            'Férias'                       => ['Férias julho 2025', 'Férias dezembro 2025', 'Férias coletivas agosto', 'Recesso fim de ano'],
            'Acesso a Sistema'             => ['Acesso ao ERP Omie', 'Permissão no Google Analytics', 'Acesso VPN corporativa', 'Liberação sistema de RH'],
            'Contratação de Fornecedor'    => ['Fornecedor de limpeza', 'Agência de marketing digital', 'Consultoria jurídica', 'Empresa de segurança'],
        ];

        $options = $titles[$type] ?? ['Solicitação interna'];
        return $options[array_rand($options)];
    }

    private function fakeDescription(): string
    {
        $descriptions = [
            'Solicito aprovação conforme necessidade identificada no setor. Documentação de suporte em anexo.',
            'Esta solicitação é necessária para manter a continuidade das operações do departamento.',
            'Conforme alinhado com a gestão, solicito a aprovação para prosseguir com o processo.',
            'Segue solicitação formal para análise e aprovação da liderança responsável.',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function fakeComment(): string
    {
        $comments = [
            'Segue documentação complementar conforme solicitado.',
            'Gostaria de agilizar essa aprovação pois temos prazo até o fim do mês.',
            'Qualquer dúvida estou à disposição.',
            'Atualizo que a situação permanece pendente de aprovação.',
        ];

        return $comments[array_rand($comments)];
    }

    private function generateCode(): string
    {
        $year  = now()->year;
        $count = Request::whereYear('created_at', $year)->count() + 1;

        return 'REQ-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

}
