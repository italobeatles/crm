<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Opportunity;
use Illuminate\Support\Facades\DB;

class LeadConversionService
{
    public function convert(Lead $lead, bool $createOpportunity = true): Customer
    {
        return DB::transaction(function () use ($lead, $createOpportunity) {
            $customer = Customer::create([
                'tipo' => 'pj',
                'nome' => $lead->nome,
                'documento' => null,
                'email' => $lead->email,
                'telefone' => $lead->telefone,
                'status' => 'ativo',
                'observacoes' => $lead->observacoes,
                'id_usuario_responsavel' => $lead->id_usuario_responsavel,
            ]);

            if ($createOpportunity) {
                Opportunity::create([
                    'id_cliente' => $customer->id,
                    'id_lead' => $lead->id,
                    'titulo' => 'Oportunidade inicial - '.$lead->nome,
                    'valor' => 0,
                    'etapa' => 'qualificacao',
                    'probabilidade' => 30,
                    'data_prevista_fechamento' => now()->addDays(30)->toDateString(),
                    'status' => 'aberta',
                    'id_usuario_responsavel' => $lead->id_usuario_responsavel,
                ]);
            }

            $lead->update([
                'status' => 'convertido',
                'id_cliente_convertido' => $customer->id,
                'convertido_em' => now(),
            ]);

            return $customer;
        });
    }
}
