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
        <input name="search" style="padding-left: 35px;" type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, tipo o menú…">
    </div>
    <button class="btn btn-primary" wire:click="openCreate">＋ Nuevo Gastronómico</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Imagen</th><th>Nombre</th><th>Tipos</th><th>Horario</th><th>Menús</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($gastronomicos as $item)
                <tr wire:key="gas-{{ $item->id }}">
                    <td>
                        @if($item->imagen)
                            <img src="{{ $item->imagen }}" class="table-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="table-img-placeholder" style="display:none">🍽️</div>
                        @else
                            <div class="table-img-placeholder">🍽️</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $item->nombre }}</div>
                        <div class="muted">{{ $item->direccion }}</div>
                    </td>
                    <td>
                        @foreach($item->tipos->take(2) as $t)
                            <span class="badge badge-blue" style="margin-right:3px">{{ $t->tipo }}</span>
                        @endforeach
                        @if($item->tipos->count() > 2) <span class="muted">+{{ $item->tipos->count() - 2 }}</span> @endif
                    </td>
                    <td class="muted">{{ $item->horario ?: '—' }}</td>
                    <td>
                        @foreach($item->menus->take(2) as $m)
                            <span class="badge badge-purple" style="margin-right:3px">{{ $m->tipo }}</span>
                        @endforeach

                        @if($item->menus->count() > 2) 
                            <span class="muted">+{{ $item->menus->count() - 2 }}</span> 
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-success btn-sm" wire:click="openEdit({{ $item->id }})">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $item->id }}, '{{ addslashes($item->nombre) }}')">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><div class="empty-icon">🍽️</div><div class="empty-text">No hay gastronómicos</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($gastronomicos->hasPages())
    <div class="pagination-wrap">
        <span>{{ $gastronomicos->firstItem() }}–{{ $gastronomicos->lastItem() }} de {{ $gastronomicos->total() }}</span>
        <div>{{ $gastronomicos->links('vendor.pagination.custom') }}</div>
    </div>
    @endif
</div>

@if($showModal)
<div class="modal-overlay" wire:click.self="$set('showModal', false)">
    <div class="modal modal-lg">
        <div class="modal-header">
            <span class="modal-title">{{ $isEditing ? '✏️ Editar Gastronómico' : '＋ Nuevo Gastronómico' }}</span>
            <button class="modal-close" wire:click="$set('showModal', false)">×</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-group span-2">
                    <label>Nombre *</label>
                    <input type="text" wire:model="nombre" placeholder="Restaurante Mar del Sur">
                    @error('nombre') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group span-2">
                    <label>Dirección *</label>
                    <input type="text" wire:model="direccion" placeholder="Av. Principal 789">
                    @error('direccion') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" wire:model="telefono" placeholder="1122334455">
                </div>
                <div class="form-group">
                    <label>Horario</label>
                    <input type="text" wire:model="horario" placeholder="L-V 08:00-20:00">
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
                    <label>Tienda Online (URL)</label>
                    <input type="url" wire:model="tiendaOnline" placeholder="https://…">
                </div>
                <div class="form-group span-2">
                    <label>Extras</label>
                    <textarea wire:model="extras" placeholder="Zona de juegos, terraza…"></textarea>
                </div>
                <div class="form-group span-2">
                    <label>URL de Imagen</label>
                    <input type="url" wire:model="imagen" placeholder="https://…">
                </div>

                <x-mapa-selector />

                <div class="form-group span-2">
                    <label>Tipos Gastronómicos</label>
                    <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px">
                        @foreach($tipo_ids as $idx => $selectedId)
                        <div style="display:flex;align-items:center;gap:6px">
                            <select wire:model="tipo_ids.{{ $idx }}" wire:change="maybeAddSelect({{ $idx }})" style="padding:4px 8px;border-radius:4px;border:1px solid var(--border);background:var(--bg-input);color:var(--text-primary)">
                                <option value="">-- Seleccione un tipo --</option>
                                @foreach($tiposGastronomicos as $tg)
                                    <option value="{{ $tg->id }}" {{ $tg->id == $selectedId ? 'selected' : '' }}>{{ $tg->tipo }}</option>
                                @endforeach
                            </select>
                            @if(count($tipo_ids) > 1)
                                <button type="button" class="btn btn-danger btn-sm" wire:click="removeTipoSelect({{ $idx }})" style="padding:2px 6px">✖️</button>
                            @endif
                        </div>
                        @endforeach

                    </div>
                    @error('tipo_ids') <span class="error-msg">{{ $message }}</span> @enderror
                    @error('tipo_ids.*') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group span-2">
                    <label>Menús disponibles</label>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px">
                        @foreach($menus as $menu)
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text-primary);cursor:pointer">
                            <input type="checkbox" wire:model="menu_ids" value="{{ $menu->id }}" style="width:auto">
                            {{ $menu->tipo }}
                        </label>
                        @endforeach
                    </div>
                    @error('menu_ids') <span class="error-msg">{{ $message }}</span> @enderror
                    @error('menu_ids.*') <span class="error-msg">{{ $message }}</span> @enderror
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

@if($showConfirm)
<div class="modal-overlay" wire:click.self="$set('showConfirm', false)">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:32px;text-align:center">
            <div class="confirm-icon">🗑️</div>
            <div class="modal-title" style="margin-bottom:10px">Eliminar gastronómico</div>
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
