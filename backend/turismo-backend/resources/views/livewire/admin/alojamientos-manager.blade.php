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
        <input name="search" style="padding-left: 35px;" type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre…">
    </div>
    <button class="btn btn-primary" wire:click="openCreate">＋ Nuevo Alojamiento</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Imagen</th><th>Nombre</th><th>Tipo</th><th>Mascotas</th><th>Período</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($alojamientos as $item)
                <tr wire:key="aloj-{{ $item->id }}">
                    <td>
                        @if($item->imagen)
                            <img src="{{ $item->imagen }}" class="table-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="table-img-placeholder" style="display:none">🏨</div>
                        @else
                            <div class="table-img-placeholder">🏨</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $item->nombre }}</div>
                        <div class="muted">{{ $item->direccion }}</div>
                    </td>
                    <td><span class="badge badge-purple">{{ $item->tipo }}</span></td>
                    <td>
                        @if($item->mascotas)
                            <span class="badge badge-green">🐾 Sí</span>
                        @else
                            <span class="badge badge-red">No</span>
                        @endif
                    </td>
                    <td class="muted">{{ $item->periodoApertura ?: '—' }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-success btn-sm" wire:click="openEdit({{ $item->id }})">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $item->id }}, '{{ addslashes($item->nombre) }}')">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><div class="empty-icon">🏨</div><div class="empty-text">No hay alojamientos</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($alojamientos->hasPages())
    <div class="pagination-wrap">
        <span>{{ $alojamientos->firstItem() }}–{{ $alojamientos->lastItem() }} de {{ $alojamientos->total() }}</span>
        <div>{{ $alojamientos->links('vendor.pagination.custom') }}</div>
    </div>
    @endif
</div>

@if($showModal)
<div class="modal-overlay" wire:click.self="$set('showModal', false)">
    <div class="modal modal-lg">
        <div class="modal-header">
            <span class="modal-title">{{ $isEditing ? '✏️ Editar Alojamiento' : '＋ Nuevo Alojamiento' }}</span>
            <button class="modal-close" wire:click="$set('showModal', false)">×</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" wire:model="nombre" placeholder="Hotel Central">
                    @error('nombre') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Tipo *</label>
                    <select class="form-select" wire:model="tipo">
                    <option value="">Seleccionar tipo</option>
                    @foreach($tipos as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
                @error('tipo') <span class="error-msg">{{ $message }}</span> @enderror

                </div>
                <div class="form-group span-2">
                    <label>Dirección *</label>
                    <input type="text" wire:model="direccion" placeholder="Calle 10 Nº 500">
                    @error('direccion') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" wire:model="telefono" placeholder="2262554433">
                </div>
                <div class="form-group">
                    <label>Mail</label>
                    <input type="email" wire:model="mail" placeholder="reservas@hotel.com">
                    @error('mail') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Facebook (URL)</label>
                    <input type="url" wire:model="facebook" placeholder="https://facebook.com/..." />
                    @error('facebook') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Instagram (URL)</label>
                    <input type="url" wire:model="instagram" placeholder="https://instagram.com/..." />
                    @error('instagram') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Página Web (URL)</label>
                    <input type="url" wire:model="paginaWeb" placeholder="https://…">
                </div>
                <div class="form-group">
                    <label>Período de Apertura</label>
                    <input type="text" wire:model="periodoApertura" placeholder="Todo el año">
                </div>
                <div class="form-group">
                    <label>Mascotas permitidas</label>
                    <div class="toggle-wrap">
                        <label class="toggle">
                            <input type="checkbox" wire:model="mascotas">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group span-2">
                    <label>URL de Imagen</label>
                    <input type="url" wire:model="imagen" placeholder="https://…">
                </div>

                <x-mapa-selector />

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

@if($showConfirm)
<div class="modal-overlay" wire:click.self="$set('showConfirm', false)">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:32px;text-align:center">
            <div class="confirm-icon">🗑️</div>
            <div class="modal-title" style="margin-bottom:10px">Eliminar alojamiento</div>
            <div class="confirm-text">¿Seguro que querés eliminar <span class="confirm-name">{{ $confirmName }}</span>?</div>
        </div>
        <div class="modal-footer" style="justify-content:center">
            <button class="btn btn-secondary" wire:click="$set('showConfirm', false)">Cancelar</button>
            <button class="btn btn-danger" wire:click="delete">Sí, eliminar</button>
        </div>
    </div>
</div>
@endif
</div>
