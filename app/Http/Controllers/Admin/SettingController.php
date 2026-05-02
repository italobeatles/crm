<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index', [
            'settings' => Setting::query()->orderBy('chave')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'chave' => ['required', 'string', 'max:80', 'unique:tbsettings,chave'],
            'valor' => ['nullable', 'string'],
            'descricao' => ['nullable', 'string', 'max:180'],
        ]);

        Setting::create($data);

        return redirect()->route('admin.settings.index')->with('success', 'Parâmetro cadastrado com sucesso.');
    }

    public function edit(Setting $setting): View
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting): RedirectResponse
    {
        $data = $request->validate([
            'chave' => ['required', 'string', 'max:80', 'unique:tbsettings,chave,'.$setting->id],
            'valor' => ['nullable', 'string'],
            'descricao' => ['nullable', 'string', 'max:180'],
        ]);

        $setting->update($data);

        return redirect()->route('admin.settings.index')->with('success', 'Parâmetro atualizado com sucesso.');
    }

    public function destroy(Setting $setting): RedirectResponse
    {
        $setting->delete();

        return redirect()->route('admin.settings.index')->with('success', 'Parâmetro removido com sucesso.');
    }
}
