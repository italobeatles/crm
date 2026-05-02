<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Opportunity extends CrmModel
{
    use HasFactory;

    protected $table = 'tboportunidades';

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'data_prevista_fechamento' => 'date',
            'ganho_em' => 'datetime',
            'perdido_em' => 'datetime',
        ];
    }

    public static function stageOptions(): array
    {
        return [
            'prospeccao' => 'Prospecção',
            'qualificacao' => 'Qualificação',
            'proposta' => 'Proposta',
            'negociacao' => 'Negociação',
            'fechado_ganho' => 'Fechado/Ganho',
            'fechado_perdido' => 'Fechado/Perdido',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'aberta' => 'Aberta',
            'ganha' => 'Ganha',
            'perdida' => 'Perdida',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_cliente');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'id_lead');
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_responsavel');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->canManageTeams()) {
            return $query;
        }

        return $query->where('id_usuario_responsavel', $user->id);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'aberta');
    }
}
