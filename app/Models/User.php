<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'email', 'password', 'role', 'status', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    public const CREATED_AT = 'criado_em';
    public const UPDATED_AT = 'atualizado_em';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_SALES = 'sales';
    public const ROLE_SUPPORT = 'support';
    public const DELETED_AT = 'deletado_em';

    protected $table = 'tbusers';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verificado_em' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    protected function emailVerifiedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->email_verificado_em,
            set: fn ($value) => ['email_verificado_em' => $value],
        );
    }

    public static function roleOptions(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_MANAGER => 'Gestor',
            self::ROLE_SALES => 'Vendedor',
            self::ROLE_SUPPORT => 'Atendimento',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function canManageTeams(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MANAGER], true);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'id_usuario_responsavel');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'id_usuario_responsavel');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'id_usuario_responsavel');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'id_usuario_responsavel');
    }
}
