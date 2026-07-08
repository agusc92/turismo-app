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
        <input name="search" style="padding-left: 35px;" type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o tipo…">
    </div>
    <button class="btn btn-primary" wire:click="openCreate">＋ Nueva Actividad</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Imagen</th><th>Nombre</th><th>Tipo</th><th>Teléfono</th><th>Horarios</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($actividades as $actividad)
                <tr wire:key="act-{{ $actividad->id }}">
                    <td>
                        @if($actividad->imagen)
                            <img src="{{ $actividad->imagen }}" class="table-img" alt="{{ $actividad->nombre }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="table-img-placeholder" style="display:none">🏄</div>
                        @else
                            <div class="table-img-placeholder">🏄</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $actividad->nombre }}</div>
                        <div class="muted">{{ $actividad->direccion }}</div>
                    </td>
                    <td>
                        @if($actividad->tipo)
                            <span class="badge badge-blue">{{ $actividad->tipo->tipo }}</span>
                        @else <span class="muted">—</span> @endif
                    </td>
                    <td class="muted">{{ $actividad->telefono ?: '—' }}</td>
                    <td class="muted" style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $actividad->dias_y_horarios ?: '—' }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-success btn-sm" wire:click="openEdit({{ $actividad->id }})">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $actividad->id }}, '{{ addslashes($actividad->nombre) }}')">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><div class="empty-icon">🏄</div><div class="empty-text">No hay actividades</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($actividades->hasPages())
    <div class="pagination-wrap">
        <span>Mostrando {{ $actividades->firstItem() }}–{{ $actividades->lastItem() }} de {{ $actividades->total() }}</span>
        <div>{{ $actividades->links('vendor.pagination.custom') }}</div>
    </div>
    @endif
</div>

@if($showModal)
<div class="modal-overlay" wire:click.self="$set('showModal', false)">
    <div class="modal modal-lg">
        <div class="modal-header">
            <span class="modal-title">{{ $isEditing ? '✏️ Editar Actividad' : '＋ Nueva Actividad' }}</span>
            <button class="modal-close" wire:click="$set('showModal', false)">×</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" wire:model="nombre" placeholder="Clases de Surf">
                    @error('nombre') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Tipo *</label>
                    <select wire:model="tipo_id">
                        <option value="">— Seleccionar tipo —</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t->id }}">{{ $t->tipo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_id') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Dirección *</label>
                    <input type="text" wire:model="direccion" placeholder="Playa Grande">
                    @error('direccion') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Descripción</label>
                    <textarea wire:model="descripcion" placeholder="Descripción de la actividad…"></textarea>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" wire:model="telefono" placeholder="2262123456">
                </div>
                <div class="form-group">
                    <label>Mail</label>
                    <input type="email" wire:model="mail" placeholder="info@actividad.com">
                    @error('mail') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Facebook</label>
                    <input type="url" wire:model="facebook" placeholder="https://facebook.com/…">
                    @error('facebook') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Instagram</label>
                    <input type="url" wire:model="instagram" placeholder="https://instagram.com/…">
                    @error('instagram') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Sitio Web (URL)</label>
                    <input type="url" wire:model="web" placeholder="https://…">
                    @error('web') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Días y Horarios</label>
                    <input type="text" wire:model="dias_y_horarios" placeholder="L-V 10:00-18:00">
                </div>
                <div class="form-group span-2">
                    <label>URL de Imagen</label>
                    <input type="url" wire:model="imagen" placeholder="https://…">
                    @error('imagen') <span class="error-msg">{{ $message }}</span> @enderror
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
            <div class="modal-title" style="margin-bottom:10px">Eliminar actividad</div>
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
