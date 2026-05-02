<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpportunityRequest;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpportunityController extends Controller
{
    public function index(Request $request): View
    {
        $opportunities = Opportunity::query()
            ->with('customer', 'lead', 'responsavel')
            ->visibleTo($request->user())
            ->when($request->filled('etapa'), fn ($query) => $query->where('etapa', $request->string('etapa')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('id_usuario_responsavel'), fn ($query) => $query->where('id_usuario_responsavel', $request->integer('id_usuario_responsavel')))
            ->orderByDesc('criado_em')
            ->paginate(10)
            ->withQueryString();

        return view('opportunities.index', [
            'opportunities' => $opportunities,
            'stages' => Opportunity::stageOptions(),
            'statuses' => Opportunity::statusOptions(),
            'users' => User::query()->where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('opportunities.create', $this->formData());
    }

    public function store(OpportunityRequest $request): RedirectResponse
    {
        Opportunity::create($this->normalizeStatus($request->validated()));

        return redirect()->route('opportunities.index')->with('success', 'Oportunidade cadastrada com sucesso.');
    }

    public function show(Opportunity $opportunity): View
    {
        $this->authorizeRecord($opportunity);
        $opportunity->load('customer', 'lead', 'responsavel');

        return view('opportunities.show', compact('opportunity'));
    }

    public function edit(Opportunity $opportunity): View
    {
        $this->authorizeRecord($opportunity);

        return view('opportunities.edit', array_merge($this->formData(), ['opportunity' => $opportunity]));
    }

    public function update(OpportunityRequest $request, Opportunity $opportunity): RedirectResponse
    {
        $this->authorizeRecord($opportunity);
        $opportunity->update($this->normalizeStatus($request->validated()));

        return redirect()->route('opportunities.index')->with('success', 'Oportunidade atualizada com sucesso.');
    }

    public function destroy(Opportunity $opportunity): RedirectResponse
    {
        $this->authorizeRecord($opportunity);
        $opportunity->delete();

        return redirect()->route('opportunities.index')->with('success', 'Oportunidade removida com sucesso.');
    }

    public function pipeline(Request $request): View
    {
        $user = $request->user();
        $stages = Opportunity::stageOptions();
        $pipeline = [];

        foreach (array_keys($stages) as $stage) {
            $pipeline[$stage] = Opportunity::query()
                ->with('customer', 'responsavel')
                ->visibleTo($user)
                ->where('etapa', $stage)
                ->where('status', $stage === 'fechado_ganho' ? 'ganha' : ($stage === 'fechado_perdido' ? 'perdida' : 'aberta'))
                ->orderByDesc('valor')
                ->get();
        }

        return view('opportunities.pipeline', compact('pipeline', 'stages'));
    }

    public function updateStage(Request $request, Opportunity $opportunity): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $this->authorizeRecord($opportunity);

        $validated = $request->validate([
            'etapa' => ['required', 'in:'.implode(',', array_keys(Opportunity::stageOptions()))],
        ]);

        $payload = $this->normalizeStatus([
            'id_cliente' => $opportunity->id_cliente,
            'id_lead' => $opportunity->id_lead,
            'titulo' => $opportunity->titulo,
            'valor' => $opportunity->valor,
            'etapa' => $validated['etapa'],
            'probabilidade' => $opportunity->probabilidade,
            'data_prevista_fechamento' => optional($opportunity->data_prevista_fechamento)?->toDateString(),
            'status' => $opportunity->status,
            'observacoes' => $opportunity->observacoes,
            'id_usuario_responsavel' => $opportunity->id_usuario_responsavel,
        ]);

        $opportunity->update($payload);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Etapa atualizada com sucesso.',
                'opportunity' => [
                    'id' => $opportunity->id,
                    'etapa' => $opportunity->etapa,
                    'status' => $opportunity->status,
                ],
            ]);
        }

        return back()->with('success', 'Etapa atualizada com sucesso.');
    }

    private function formData(): array
    {
        return [
            'customers' => Customer::query()->orderBy('nome')->get(),
            'leads' => Lead::query()->orderByDesc('criado_em')->get(),
            'users' => User::query()->where('status', true)->orderBy('name')->get(),
            'stages' => Opportunity::stageOptions(),
            'statuses' => Opportunity::statusOptions(),
        ];
    }

    private function normalizeStatus(array $data): array
    {
        if ($data['etapa'] === 'fechado_ganho') {
            $data['status'] = 'ganha';
            $data['ganho_em'] = now();
            $data['perdido_em'] = null;
        } elseif ($data['etapa'] === 'fechado_perdido') {
            $data['status'] = 'perdida';
            $data['perdido_em'] = now();
            $data['ganho_em'] = null;
        } else {
            $data['status'] = 'aberta';
            $data['ganho_em'] = null;
            $data['perdido_em'] = null;
        }

        return $data;
    }

    private function authorizeRecord(Opportunity $opportunity): void
    {
        $user = auth()->user();

        abort_unless($user->canManageTeams() || $opportunity->id_usuario_responsavel === $user->id, 403);
    }
}
