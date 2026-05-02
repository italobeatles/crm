<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $activities = Activity::query()
            ->with('responsavel')
            ->visibleTo($request->user())
            ->when($request->filled('tipo'), fn ($query) => $query->where('tipo', $request->string('tipo')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('id_usuario_responsavel'), fn ($query) => $query->where('id_usuario_responsavel', $request->integer('id_usuario_responsavel')))
            ->orderBy('data_vencimento')
            ->paginate(10)
            ->withQueryString();

        return view('activities.index', [
            'activities' => $activities,
            'types' => Activity::typeOptions(),
            'relatedTypes' => Activity::relatedTypeOptions(),
            'users' => User::query()->where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('activities.create', $this->formData());
    }

    public function store(ActivityRequest $request): RedirectResponse
    {
        Activity::create($this->normalize($request->validated()));

        return redirect()->route('activities.index')->with('success', 'Atividade cadastrada com sucesso.');
    }

    public function show(Activity $activity): View
    {
        $this->authorizeRecord($activity);

        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity): View
    {
        $this->authorizeRecord($activity);

        return view('activities.edit', array_merge($this->formData(), ['activity' => $activity]));
    }

    public function update(ActivityRequest $request, Activity $activity): RedirectResponse
    {
        $this->authorizeRecord($activity);
        $activity->update($this->normalize($request->validated()));

        return redirect()->route('activities.index')->with('success', 'Atividade atualizada com sucesso.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $this->authorizeRecord($activity);
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Atividade removida com sucesso.');
    }

    public function complete(Activity $activity): RedirectResponse
    {
        $this->authorizeRecord($activity);

        $activity->update([
            'status' => 'concluida',
            'concluido_em' => now(),
        ]);

        return back()->with('success', 'Atividade concluída.');
    }

    private function formData(): array
    {
        return [
            'types' => Activity::typeOptions(),
            'relatedTypes' => Activity::relatedTypeOptions(),
            'users' => User::query()->where('status', true)->orderBy('name')->get(),
        ];
    }

    private function normalize(array $data): array
    {
        if ($data['status'] === 'concluida') {
            $data['concluido_em'] = now();
        }

        return $data;
    }

    private function authorizeRecord(Activity $activity): void
    {
        $user = auth()->user();

        abort_unless($user->canManageTeams() || $activity->id_usuario_responsavel === $user->id, 403);
    }
}
