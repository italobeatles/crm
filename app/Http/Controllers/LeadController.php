<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Models\Lead;
use App\Models\User;
use App\Services\LeadConversionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function __construct(
        private readonly LeadConversionService $leadConversionService,
    ) {
    }

    public function index(Request $request): View
    {
        $leads = Lead::query()
            ->with('responsavel', 'clienteConvertido')
            ->visibleTo($request->user())
            ->when($request->filled('nome'), fn ($query) => $query->where('nome', 'like', '%'.$request->string('nome').'%'))
            ->when($request->filled('origem'), fn ($query) => $query->where('origem', $request->string('origem')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('id_usuario_responsavel'), fn ($query) => $query->where('id_usuario_responsavel', $request->integer('id_usuario_responsavel')))
            ->orderByDesc('criado_em')
            ->paginate(10)
            ->withQueryString();

        return view('leads.index', [
            'leads' => $leads,
            'origins' => Lead::originOptions(),
            'statuses' => Lead::statusOptions(),
            'users' => User::query()->where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('leads.create', $this->formData());
    }

    public function store(LeadRequest $request): RedirectResponse
    {
        Lead::create($request->validated());

        return redirect()->route('leads.index')->with('success', 'Lead cadastrado com sucesso.');
    }

    public function show(Lead $lead): View
    {
        $this->authorizeRecord($lead);
        $lead->load('responsavel', 'clienteConvertido', 'opportunities.customer');

        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead): View
    {
        $this->authorizeRecord($lead);

        return view('leads.edit', array_merge($this->formData(), ['lead' => $lead]));
    }

    public function update(LeadRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorizeRecord($lead);
        $lead->update($request->validated());

        return redirect()->route('leads.index')->with('success', 'Lead atualizado com sucesso.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $this->authorizeRecord($lead);
        $lead->delete();

        return redirect()->route('leads.index')->with('success', 'Lead removido com sucesso.');
    }

    public function convert(Request $request, Lead $lead): RedirectResponse
    {
        $this->authorizeRecord($lead);

        if ($lead->status === 'convertido') {
            return back()->with('error', 'Este lead já foi convertido.');
        }

        $customer = $this->leadConversionService->convert(
            $lead,
            $request->boolean('criar_oportunidade', true),
        );

        return redirect()->route('customers.show', $customer)->with('success', 'Lead convertido em cliente com sucesso.');
    }

    private function formData(): array
    {
        return [
            'origins' => Lead::originOptions(),
            'statuses' => Lead::statusOptions(),
            'users' => User::query()->where('status', true)->orderBy('name')->get(),
        ];
    }

    private function authorizeRecord(Lead $lead): void
    {
        $user = auth()->user();

        abort_unless($user->canManageTeams() || $lead->id_usuario_responsavel === $user->id, 403);
    }
}
