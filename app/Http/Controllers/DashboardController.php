<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Opportunity;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $leadQuery = Lead::query()->visibleTo($user);
        $customerQuery = Customer::query()->visibleTo($user);
        $opportunityQuery = Opportunity::query()->visibleTo($user)->open();
        $activityQuery = Activity::query()->visibleTo($user);

        return view('dashboard', [
            'metrics' => [
                'leads' => (clone $leadQuery)->count(),
                'customers' => (clone $customerQuery)->count(),
                'opportunities' => (clone $opportunityQuery)->count(),
                'opportunityValue' => (clone $opportunityQuery)->sum('valor'),
                'lateActivities' => (clone $activityQuery)
                    ->where('status', 'pendente')
                    ->where('data_vencimento', '<', now())
                    ->count(),
            ],
            'pipeline' => Opportunity::query()
                ->visibleTo($user)
                ->open()
                ->selectRaw('etapa, COUNT(*) as total, COALESCE(SUM(valor), 0) as valor_total')
                ->groupBy('etapa')
                ->get(),
            'pendingActivities' => (clone $activityQuery)
                ->where('status', 'pendente')
                ->orderBy('data_vencimento')
                ->limit(8)
                ->get(),
        ]);
    }
}
