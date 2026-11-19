<div>
    @if($toastMsg)
        <div class="toast-container" id="toast-container">
            <div class="toast toast-{{ $toastType }}" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)">
                {{ $toastType === 'success' ? '✅' : '❌' }} {{ $toastMsg }}
            </div>
        </div>
    @endif

    <div class="card" style="max-width: 980px;">
        <div class="card-header">
            <div>
                <div class="card-title">Imágenes por sección</div>
                <div class="muted" style="margin-top: 5px;">Configurá la imagen específica de cada categoría.</div>
            </div>
            <span class="badge badge-blue">Configuración global</span>
        </div>

        <div class="card-body">
            <div class="form-section-title" style="margin: 0 0 14px;">Imágenes por categoría</div>
            <div class="form-grid">
                @foreach([
                    'actividades' => 'Actividades',
                    'gastronomicos' => 'Gastronómicos',
                    'eventos' => 'Eventos',
                    'alojamientos' => 'Alojamientos',
                    'complejos' => 'Complejos',
                    'balnearios' => 'Balnearios',
                ] as $field => $label)
                    <div class="form-group">
                        <label for="imagen-{{ $field }}">{{ $label }}</label>
                        <input id="imagen-{{ $field }}" type="url" wire:model="{{ $field }}" placeholder="https://ejemplo.com/{{ $field }}.jpg">
                        @if($this->{$field})
                            <img src="{{ $this->{$field} }}" alt="Vista previa de {{ strtolower($label) }}" style="width:100%;height:120px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--border);margin-top:4px;" onerror="this.style.display='none'">
                        @endif
                        @error($field) <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card-footer" style="display:flex; justify-content:flex-end;">
            <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove>Guardar cambios</span>
                <span wire:loading><span class="spinner"></span> Guardando…</span>
            </button>
        </div>
    </div>
</div>
