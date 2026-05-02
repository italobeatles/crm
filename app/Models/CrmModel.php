<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class CrmModel extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'criado_em';
    public const UPDATED_AT = 'atualizado_em';
    public const DELETED_AT = 'deletado_em';

    protected $guarded = [];
}
