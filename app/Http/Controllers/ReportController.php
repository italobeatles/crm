<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $opportunities = Opportunity::query()
            ->with('customer', 'responsavel')
            ->visibleTo($request->user())
            ->when($request->filled('responsavel_opportunity'), fn ($query) => $query->where('id_usuario_responsavel', $request->integer('responsavel_opportunity')))
            ->when($request->filled('etapa'), fn ($query) => $query->where('etapa', $request->string('etapa')))
            ->when($request->filled('data_inicio'), fn ($query) => $query->whereDate('data_prevista_fechamento', '>=', $request->date('data_inicio')))
            ->when($request->filled('data_fim'), fn ($query) => $query->whereDate('data_prevista_fechamento', '<=', $request->date('data_fim')))
            ->get();

        $activities = Activity::query()
            ->with('responsavel')
            ->visibleTo($request->user())
            ->when($request->filled('responsavel_activity'), fn ($query) => $query->where('id_usuario_responsavel', $request->integer('responsavel_activity')))
            ->when($request->filled('tipo_atividade'), fn ($query) => $query->where('tipo', $request->string('tipo_atividade')))
            ->when($request->filled('status_atividade'), fn ($query) => $query->where('status', $request->string('status_atividade')))
            ->get();

        return view('reports.index', [
            'opportunities' => $opportunities,
            'activities' => $activities,
            'users' => User::query()->where('status', true)->orderBy('name')->get(),
            'opportunityStages' => Opportunity::stageOptions(),
            'activityTypes' => Activity::typeOptions(),
        ]);
    }

    public function export(Request $request, string $type): StreamedResponse
    {
        abort_unless(in_array($type, ['opportunities', 'activities'], true), 404);

        return response()->streamDownload(function () use ($request, $type) {
            $handle = fopen('php://output', 'wb');

            if ($type === 'opportunities') {
                fputcsv($handle, ['Titulo', 'Cliente', 'Etapa', 'Status', 'Valor', 'Responsavel']);
                Opportunity::query()
                    ->with('customer', 'responsavel')
                    ->visibleTo($request->user())
                    ->get()
                    ->each(fn ($item) => fputcsv($handle, [
                        $item->titulo,
                        $item->customer?->nome,
                        $item->etapa,
                        $item->status,
                        $item->valor,
                        $item->responsavel?->name,
                    ]));
            } else {
                fputcsv($handle, ['Titulo', 'Tipo', 'Status', 'Vencimento', 'Responsavel']);
                Activity::query()
                    ->with('responsavel')
                    ->visibleTo($request->user())
                    ->get()
                    ->each(fn ($item) => fputcsv($handle, [
                        $item->titulo,
                        $item->tipo,
                        $item->status,
                        optional($item->data_vencimento)?->format('d/m/Y H:i'),
                        $item->responsavel?->name,
                    ]));
            }

            fclose($handle);
        }, sprintf('%s-%s.csv', $type, now()->format('YmdHis')), [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
