<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends CrmModel
{
    use HasFactory;

    protected $table = 'tbleads';

    protected function casts(): array
    {
        return [
            'convertido_em' => 'datetime',
        ];
    }

    public static function originOptions(): array
    {
        return [
            'site' => 'Site',
            'whatsapp' => 'WhatsApp',
            'indicacao' => 'Indicação',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'outro' => 'Outro',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'novo' => 'Novo',
            'contato' => 'Em contato',
            'qualificado' => 'Qualificado',
            'convertido' => 'Convertido',
            'perdido' => 'Perdido',
        ];
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_responsavel');
    }

    public function clienteConvertido(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_cliente_convertido');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'id_lead');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->canManageTeams()) {
            return $query;
        }

        return $query->where('id_usuario_responsavel', $user->id);
    }
}
