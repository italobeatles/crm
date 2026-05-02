<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends CrmModel
{
    use HasFactory;

    protected $table = 'tbatividades';

    protected function casts(): array
    {
        return [
            'data_vencimento' => 'datetime',
            'concluido_em' => 'datetime',
        ];
    }

    public static function typeOptions(): array
    {
        return [
            'tarefa' => 'Tarefa',
            'ligacao' => 'Ligação',
            'reuniao' => 'Reunião',
            'email' => 'E-mail',
            'observacao' => 'Observação',
        ];
    }

    public static function relatedTypeOptions(): array
    {
        return [
            'lead' => 'Lead',
            'customer' => 'Cliente',
            'opportunity' => 'Oportunidade',
        ];
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
}
