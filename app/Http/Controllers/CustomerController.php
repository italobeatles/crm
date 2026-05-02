<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\CustomerRequest;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->with('responsavel')
            ->visibleTo($request->user())
            ->when($request->filled('nome'), fn ($query) => $query->where('nome', 'like', '%'.$request->string('nome').'%'))
            ->when($request->filled('tipo'), fn ($query) => $query->where('tipo', $request->string('tipo')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('id_usuario_responsavel'), fn ($query) => $query->where('id_usuario_responsavel', $request->integer('id_usuario_responsavel')))
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', [
            'customers' => $customers,
            'types' => Customer::typeOptions(),
            'statuses' => Customer::statusOptions(),
            'users' => User::query()->where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('customers.create', $this->formData());
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create($request->validated());

        return redirect()->route('customers.show', $customer)->with('success', 'Cliente cadastrado com sucesso.');
    }

    public function show(Customer $customer): View
    {
        $this->authorizeRecord($customer);
        $customer->load('responsavel', 'contacts', 'opportunities.responsavel');

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $this->authorizeRecord($customer);

        return view('customers.edit', array_merge($this->formData(), ['customer' => $customer]));
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->authorizeRecord($customer);
        $customer->update($request->validated());

        return redirect()->route('customers.index')->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->authorizeRecord($customer);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Cliente removido com sucesso.');
    }

    public function storeContact(ContactRequest $request, Customer $customer): RedirectResponse
    {
        $this->authorizeRecord($customer);

        if ($request->boolean('principal')) {
            $customer->contacts()->update(['principal' => false]);
        }

        $customer->contacts()->create($request->validated());

        return redirect()->route('customers.show', $customer)->with('success', 'Contato cadastrado com sucesso.');
    }

    public function destroyContact(Customer $customer, Contact $contact): RedirectResponse
    {
        $this->authorizeRecord($customer);
        abort_unless($contact->id_cliente === $customer->id, 404);

        $contact->delete();

        return redirect()->route('customers.show', $customer)->with('success', 'Contato removido com sucesso.');
    }

    private function formData(): array
    {
        return [
            'types' => Customer::typeOptions(),
            'statuses' => Customer::statusOptions(),
            'users' => User::query()->where('status', true)->orderBy('name')->get(),
        ];
    }

    private function authorizeRecord(Customer $customer): void
    {
        $user = auth()->user();

        abort_unless($user->canManageTeams() || $customer->id_usuario_responsavel === $user->id, 403);
    }
}
