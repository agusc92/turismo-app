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
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre…">
    </div>
    <button class="btn btn-primary" wire:click="openCreate">
        ＋ Nuevo Complejo
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
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complejos as $c)
                <tr wire:key="complejo-{{ $c->id }}">
                    <td>
                        @if($c->imagen)
                            <img src="{{ $c->imagen }}" class="table-img" alt="{{ $c->nombre }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="table-img-placeholder" style="display:none">🏢</div>
                        @else
                            <div class="table-img-placeholder">🏢</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $c->nombre }}</div>
                        @if($c->servicio)
                            <div class="muted" style="margin-top:2px;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">Servicios: {{ $c->servicio }}</div>
                        @endif
                    </td>
                    <td class="muted">{{ $c->direccion }}</td>
                    <td class="muted">{{ $c->telefono ?: '—' }}</td>
                    <td class="muted">{{ $c->mail ?: '—' }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-success btn-sm" wire:click="openEdit({{ $c->id }})">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $c->id }}, '{{ addslashes($c->nombre) }}')">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon">🏢</div>
                            <div class="empty-text">No hay complejos{{ $search ? ' que coincidan con la búsqueda' : '' }}</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($complejos->hasPages())
    <div class="pagination-wrap">
        <span>Mostrando {{ $complejos->firstItem() }}–{{ $complejos->lastItem() }} de {{ $complejos->total() }}</span>
        <div>{{ $complejos->links('vendor.pagination.custom') }}</div>
    </div>
    @endif
</div>

{{-- Create / Edit Modal --}}
@if($showModal)
<div class="modal-overlay" wire:click.self="$set('showModal', false)">
    <div class="modal modal-lg">
        <div class="modal-header">
            <span class="modal-title">{{ $isEditing ? '✏️ Editar Complejo' : '＋ Nuevo Complejo' }}</span>
            <button class="modal-close" wire:click="$set('showModal', false)">×</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" wire:model="nombre" placeholder="Ej: Complejo Las Dunas">
                    @error('nombre') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Dirección *</label>
                    <input type="text" wire:model="direccion" placeholder="Ej: Av. Costanera 1500">
                    @error('direccion') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" wire:model="mail" placeholder="contacto@complejo.com">
                    @error('mail') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" wire:model="telefono" placeholder="Ej: 2262-123456">
                    @error('telefono') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Servicios</label>
                    <textarea wire:model="servicio" placeholder="Ej: Wi-Fi, Piscina, Estacionamiento…"></textarea>
                    @error('servicio') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Información Adicional</label>
                    <textarea wire:model="adicional" placeholder="Ej: Abierto todo el año, se aceptan mascotas…"></textarea>
                    @error('adicional') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                    <label class="form-group span-2">
                        <label>Facebook (URL)</label>
                <div class="form-group span-2">
                    <label>Facebook (URL)</label>
                    <input type="url" wire:model="facebook" placeholder="https://facebook.com/..." />
                </div>
                <div class="form-group span-2">
                    <label>Instagram (URL)</label>
                    <input type="url" wire:model="instagram" placeholder="https://instagram.com/..." />
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
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" wire:click="$set('showModal', false)">Cancelar</button>
            <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $isEditing ? 'Guardar cambios' : 'Crear complejo' }}</span>
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
            <div class="modal-title" style="margin-bottom:10px">Eliminar complejo</div>
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
