<div>
{{-- Toast --}}
<div class="toast-container" id="toast-container">
    @if($toastMsg)
    <div class="toast toast-{{ $toastType }}" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)">
        {{ $toastType === 'success' ? '✅' : '❌' }} {{ $toastMsg }}
    </div>
    @endif
</div>

{{-- Header --}}
<div class="card-header" style="background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--border);margin-bottom:20px;">
    <div class="search-bar">
        <span class="search-icon">🔍</span>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o lugar…">
    </div>
    <button class="btn btn-primary" wire:click="openCreate">
        ＋ Nuevo Evento
    </button>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Lugar</th>
                    <th>Fecha</th>
                    <th>Destacado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($eventos as $evento)
                <tr wire:key="evento-{{ $evento->id }}">
                    <td>
                        @if($evento->imagen)
                            <img src="{{ $evento->imagen }}" class="table-img" alt="{{ $evento->nombre }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="table-img-placeholder" style="display:none">🎉</div>
                        @else
                            <div class="table-img-placeholder">🎉</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $evento->nombre }}</div>
                        @if($evento->descripcion)
                            <div class="muted" style="margin-top:2px;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $evento->descripcion }}</div>
                        @endif
                    </td>
                    <td class="muted">{{ $evento->lugar }}</td>
                    <td class="muted">{{ $evento->fecha ? $evento->fecha->format('d/m/Y') : '—' }}</td>
                    <td>
                        @if($evento->destacado)
                            <span class="badge badge-yellow">⭐ Destacado</span>
                        @else
                            <span class="badge" style="background:var(--bg-hover);color:var(--text-faint)">Normal</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-success btn-sm" wire:click="openEdit({{ $evento->id }})">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $evento->id }}, '{{ addslashes($evento->nombre) }}')">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon">🎉</div>
                            <div class="empty-text">No hay eventos{{ $search ? ' que coincidan con la búsqueda' : '' }}</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($eventos->hasPages())
    <div class="pagination-wrap">
        <span>Mostrando {{ $eventos->firstItem() }}–{{ $eventos->lastItem() }} de {{ $eventos->total() }}</span>
        <div>{{ $eventos->links() }}</div>
    </div>
    @endif
</div>

{{-- Create / Edit Modal --}}
@if($showModal)
<div class="modal-overlay" wire:click.self="$set('showModal', false)">
    <div class="modal modal-lg">
        <div class="modal-header">
            <span class="modal-title">{{ $isEditing ? '✏️ Editar Evento' : '＋ Nuevo Evento' }}</span>
            <button class="modal-close" wire:click="$set('showModal', false)">×</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" wire:model="nombre" placeholder="Ej: Festival de Jazz">
                    @error('nombre') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Lugar *</label>
                    <input type="text" wire:model="lugar" placeholder="Ej: Anfiteatro Municipal">
                    @error('lugar') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Dirección *</label>
                    <input type="text" wire:model="direccion" placeholder="Ej: Av. Costanera 1000">
                    @error('direccion') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Fecha *</label>
                    <input type="date" wire:model="fecha">
                    @error('fecha') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Descripción</label>
                    <textarea wire:model="descripcion" placeholder="Descripción del evento…"></textarea>
                    @error('descripcion') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>URL de Imagen</label>
                    <input type="url" wire:model="imagen" placeholder="https://…">
                    @error('imagen') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Latitud</label>
                    <input type="number" step="0.000001" wire:model="latitud" placeholder="-38.555">
                    @error('latitud') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Longitud</label>
                    <input type="number" step="0.000001" wire:model="longitud" placeholder="-58.777">
                    @error('longitud') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Destacado</label>
                    <div class="toggle-wrap">
                        <label class="toggle">
                            <input type="checkbox" wire:model="destacado">
                            <span class="toggle-slider"></span>
                        </label>
                        <span style="font-size:13px;color:var(--text-muted)">{{ $destacado ? 'Sí, es destacado' : 'No destacado' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" wire:click="$set('showModal', false)">Cancelar</button>
            <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $isEditing ? 'Guardar cambios' : 'Crear evento' }}</span>
                <span wire:loading><span class="spinner"></span> Guardando…</span>
            </button>
        </div>
    </div>
</div>
@endif

{{-- Delete Confirm --}}
@if($showConfirm)
<div class="modal-overlay" wire:click.self="$set('showConfirm', false)">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:32px;text-align:center">
            <div class="confirm-icon">🗑️</div>
            <div class="modal-title" style="margin-bottom:10px">Eliminar evento</div>
            <div class="confirm-text">
                ¿Seguro que querés eliminar <span class="confirm-name">{{ $confirmName }}</span>?
                <br>Esta acción no se puede deshacer.
            </div>
        </div>
        <div class="modal-footer" style="justify-content:center">
            <button class="btn btn-secondary" wire:click="$set('showConfirm', false)">Cancelar</button>
            <button class="btn btn-danger" wire:click="delete">Sí, eliminar</button>
        </div>
    </div>
</div>
@endif
</div>
