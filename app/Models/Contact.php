<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends CrmModel
{
    use HasFactory;

    protected $table = 'tbcontacts';

    protected function casts(): array
    {
        return [
            'principal' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_cliente');
    }
}
