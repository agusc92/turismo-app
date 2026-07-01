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
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar tipo gastronómico…">
    </div>
    <button class="btn btn-primary" wire:click="openCreate">
        ＋ Nuevo Tipo Gastronómico
    </button>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Tipo</th>
                    <th>Gastronómicos Relacionados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $t)
                <tr wire:key="tipo-g-{{ $t->id }}">
                    <td class="muted">{{ $t->id }}</td>
                    <td>
                        <div style="font-weight:600">{{ $t->tipo }}</div>
                    </td>
                    <td>
                        <span class="badge badge-blue">{{ $t->gastronomicos_count }} locales</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-success btn-sm" wire:click="openEdit({{ $t->id }})">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $t->id }}, '{{ addslashes($t->tipo) }}')">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <div class="empty-icon">🍴</div>
                            <div class="empty-text">No hay tipos gastronómicos{{ $search ? ' que coincidan con la búsqueda' : '' }}</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tipos->hasPages())
    <div class="pagination-wrap">
        <span>Mostrando {{ $tipos->firstItem() }}–{{ $tipos->lastItem() }} de {{ $tipos->total() }}</span>
        <div>{{ $tipos->links('vendor.pagination.custom') }}</div>
    </div>
    @endif
</div>

{{-- Create / Edit Modal --}}
@if($showModal)
<div class="modal-overlay" wire:click.self="$set('showModal', false)">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">{{ $isEditing ? '✏️ Editar Tipo Gastronómico' : '＋ Nuevo Tipo Gastronómico' }}</span>
            <button class="modal-close" wire:click="$set('showModal', false)">×</button>
        </div>
        <div class="modal-body">
            <div class="form-grid form-grid-1">
                <div class="form-group">
                    <label>Nombre del Tipo *</label>
                    <input type="text" wire:model="tipo" placeholder="Ej: Cervecería, Parrilla…">
                    @error('tipo') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" wire:click="$set('showModal', false)">Cancelar</button>
            <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $isEditing ? 'Guardar cambios' : 'Crear tipo' }}</span>
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
            <div class="modal-title" style="margin-bottom:10px">Eliminar tipo gastronómico</div>
            <div class="confirm-text">
                ¿Seguro que querés eliminar el tipo <span class="confirm-name">{{ $confirmName }}</span>?
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
