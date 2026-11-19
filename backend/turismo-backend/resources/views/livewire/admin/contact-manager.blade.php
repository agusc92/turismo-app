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
                <div class="card-title">Información de contacto</div>
                <div class="muted" style="margin-top: 5px;">Estos datos se muestran en los canales oficiales de TurismoApp.</div>
            </div>
            <span class="badge badge-blue">Configuración global</span>
        </div>

        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label for="contact-telefono">Teléfono</label>
                    <input id="contact-telefono" type="text" wire:model="telefono" placeholder="Ej: +54 9 2262 123456">
                    @error('telefono') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="contact-whatsapp">WhatsApp</label>
                    <input id="contact-whatsapp" type="text" wire:model="whatsapp" placeholder="Ej: +54 9 2262 123456">
                    @error('whatsapp') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="contact-email">Email</label>
                    <input id="contact-email" type="email" wire:model="email" placeholder="contacto@turismo.com">
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="contact-web">Sitio web</label>
                    <input id="contact-web" type="text" wire:model="web" placeholder="https://turismo.com">
                    @error('web') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-section-title" style="margin: 26px 0 14px;">Redes sociales</div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="contact-instagram">Instagram</label>
                    <input id="contact-instagram" type="text" wire:model="instagram" placeholder="@turismo_oficial">
                    @error('instagram') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="contact-facebook">Facebook</label>
                    <input id="contact-facebook" type="text" wire:model="facebook" placeholder="facebook.com/turismo">
                    @error('facebook') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="contact-youtube">YouTube</label>
                    <input id="contact-youtube" type="text" wire:model="youtube" placeholder="youtube.com/@turismo">
                    @error('youtube') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="contact-tiktok">TikTok</label>
                    <input id="contact-tiktok" type="text" wire:model="tiktok" placeholder="@turismo_tiktok">
                    @error('tiktok') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 18px;">
                <label for="contact-direccion">Dirección</label>
                <input id="contact-direccion" type="text" wire:model="direccion" placeholder="Av. 2 4321, Necochea">
                @error('direccion') <span class="error-msg">{{ $message }}</span> @enderror
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
