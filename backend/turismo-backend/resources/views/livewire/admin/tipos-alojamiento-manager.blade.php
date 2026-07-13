<div>
<div class="toast-container">
    @if($toastMsg)
    <div class="toast toast-{{ $toastType }}" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)">
        {{ $toastType === 'success' ? '✅' : '❌' }} {{ $toastMsg }}
    </div>
    @endif
</div>

<div class="card-header" style="background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--border);margin-bottom:20px;">
    <div class="search-bar">
        <span class="search-icon">🔍</span>
        <input name="search" style="padding-left: 35px;" type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar tipo de alojamiento…">
    </div>
    <button class="btn btn-primary" wire:click="openCreate">＋ Nuevo Tipo</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Tipo</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $item)
                <tr wire:key="tipo-aloj-{{ $item->id }}">
                    <td style="width: 80px;" class="muted">#{{ $item->id }}</td>
                    <td>
                        <div style="font-weight:600">{{ $item->tipo }}</div>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content: flex-end;">
                            <button class="btn btn-success btn-sm" wire:click="openEdit({{ $item->id }})">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $item->id }}, '{{ addslashes($item->tipo) }}')">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">
                        <div class="empty-state">
                            <div class="empty-icon">🏨</div>
                            <div class="empty-text">No hay tipos de alojamiento registrados</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tipos->hasPages())
    <div class="pagination-wrap">
        <span>{{ $tipos->firstItem() }}–{{ $tipos->lastItem() }} de {{ $tipos->total() }}</span>
        <div>{{ $tipos->links('vendor.pagination.custom') }}</div>
    </div>
    @endif
</div>

{{-- MODAL DE CREACIÓN / EDICIÓN --}}
@if($showModal)
<div class="modal-overlay" wire:click.self="$set('showModal', false)">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">{{ $isEditing ? '✏️ Editar Tipo' : '＋ Nuevo Tipo' }}</span>
            <button class="modal-close" wire:click="$set('showModal', false)">×</button>
        </div>
        <div class="modal-body">
            <div class="form-grid" style="display: block;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Nombre del Tipo *</label>
                    <input type="text" wire:model="tipo" placeholder="Ej: Hotel, Cabaña, Apart Hotel...">
                    @error('tipo') <span class="error-msg" style="display:block; margin-top:5px;">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" wire:click="$set('showModal', false)">Cancelar</button>
            <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $isEditing ? 'Guardar' : 'Crear' }}</span>
                <span wire:loading><span class="spinner"></span> Guardando…</span>
            </button>
        </div>
    </div>
</div>
@endif

{{-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN --}}
@if($showConfirm)
<div class="modal-overlay" wire:click.self="$set('showConfirm', false)">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:32px;text-align:center">
            <div class="confirm-icon">🗑️</div>
            <div class="modal-title" style="margin-bottom:10px">Eliminar tipo</div>
            <div class="confirm-text">¿Seguro que querés eliminar <span class="confirm-name">{{ $confirmName }}</span>? Al hacerlo se desvinculará de todos los alojamientos.</div>
        </div>
        <div class="modal-footer" style="justify-content:center">
            <button class="btn btn-secondary" wire:click="$set('showConfirm', false)">Cancelar</button>
            <button class="btn btn-danger" wire:click="delete">Sí, eliminar</button>
        </div>
    </div>
</div>
@endif
</div>