<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends CrmModel
{
    use HasFactory;

    protected $table = 'tbnotes';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
