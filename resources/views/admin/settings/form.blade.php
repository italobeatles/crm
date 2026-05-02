@csrf
@if(isset($setting)) @method('PUT') @endif
<div class="mb-3"><label class="form-label">Chave</label><input type="text" name="chave" class="form-control" value="{{ old('chave', $setting->chave ?? '') }}" required></div>
<div class="mb-3"><label class="form-label">Valor</label><textarea name="valor" class="form-control" rows="4">{{ old('valor', $setting->valor ?? '') }}</textarea></div>
<div class="mb-3"><label class="form-label">Descrição</label><input type="text" name="descricao" class="form-control" value="{{ old('descricao', $setting->descricao ?? '') }}"></div>
<button class="btn btn-primary">Salvar</button> <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">Cancelar</a>
