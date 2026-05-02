@props(['actions' => []])

@if(count($actions))
    <div class="card card-outline card-light mb-3">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap align-items-center gap-3 small text-muted">
                <span class="fw-semibold text-dark">Legenda:</span>
                @foreach($actions as $action)
                    <span class="d-inline-flex align-items-center gap-2">
                        <span class="btn btn-xs {{ $action['class'] ?? 'btn-outline-secondary' }} disabled">
                            <i class="{{ $action['icon'] }}"></i>
                        </span>
                        <span>{{ $action['label'] }}</span>
                    </span>
                @endforeach
            </div>
        </div>
    </div>
@endif
