<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends CrmModel
{
    use HasFactory;

    protected $table = 'tbcustomers';

    public static function typeOptions(): array
    {
        return [
            'pf' => 'Pessoa Física',
            'pj' => 'Pessoa Jurídica',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'ativo' => 'Ativo',
            'inativo' => 'Inativo',
        ];
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_responsavel');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'id_cliente');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'id_cliente');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->canManageTeams()) {
            return $query;
        }

        return $query->where('id_usuario_responsavel', $user->id);
    }
}
