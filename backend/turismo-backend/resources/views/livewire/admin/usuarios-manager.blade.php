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
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o email…">
    </div>
    <button class="btn btn-primary" wire:click="openCreate">
        ＋ Nuevo Usuario
    </button>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $u)
                <tr wire:key="usuario-{{ $u->id }}">
                    <td>
                        <div class="admin-avatar" style="width: 32px; height: 32px; font-size: 14px;">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $u->name }}</div>
                    </td>
                    <td class="muted">{{ $u->email }}</td>
                    <td>
                        @if(($u->rol ?? 'turista') === 'admin')
                            <span class="badge badge-purple">🛠️ Admin</span>
                        @else
                            <span class="badge badge-blue">👤 Turista</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-success btn-sm" wire:click="openEdit({{ $u->id }})">✏️ Editar</button>
                            @if(session('admin_user.id') !== $u->id)
                                <button class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $u->id }}, '{{ addslashes($u->name) }}')">🗑️</button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled style="cursor:not-allowed;" title="No podés eliminarte a vos mismo">🗑️</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-icon">👥</div>
                            <div class="empty-text">No hay usuarios{{ $search ? ' que coincidan con la búsqueda' : '' }}</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($usuarios->hasPages())
    <div class="pagination-wrap">
        <span>Mostrando {{ $usuarios->firstItem() }}–{{ $usuarios->lastItem() }} de {{ $usuarios->total() }}</span>
        <div>{{ $usuarios->links('vendor.pagination.custom') }}</div>
    </div>
    @endif
</div>

{{-- Create / Edit Modal --}}
@if($showModal)
<div class="modal-overlay" wire:click.self="$set('showModal', false)">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">{{ $isEditing ? '✏️ Editar Usuario' : '＋ Nuevo Usuario' }}</span>
            <button class="modal-close" wire:click="$set('showModal', false)">×</button>
        </div>
        <div class="modal-body">
            <div class="form-grid form-grid-1">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" wire:model="name" placeholder="Ej: Juan Pérez">
                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" wire:model="email" placeholder="Ej: juan@ejemplo.com">
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Contraseña {{ $isEditing ? '(dejar en blanco para no cambiar)' : '*' }}</label>
                    <input type="password" wire:model="password" placeholder="••••••••">
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Rol *</label>
                    <select wire:model="rol">
                        <option value="turista">Turista</option>
                        <option value="admin">Administrador</option>
                    </select>
                    @error('rol') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" wire:click="$set('showModal', false)">Cancelar</button>
            <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $isEditing ? 'Guardar cambios' : 'Crear usuario' }}</span>
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
            <div class="modal-title" style="margin-bottom:10px">Eliminar usuario</div>
            <div class="confirm-text">
                ¿Seguro que querés eliminar a <span class="confirm-name">{{ $confirmName }}</span>?
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
