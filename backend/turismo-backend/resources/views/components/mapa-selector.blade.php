<div class="form-group span-2" 
    x-data="{
        map: null,
        marker: null,
        initMap() {
            setTimeout(() => {
                // Forzamos a que si viene vacío o no es un número válido, use una por defecto
                let rawLat = @js($this->latitud);
                let rawLng = @js($this->longitud);

                let lat = (rawLat && !isNaN(rawLat)) ? parseFloat(rawLat) : -38.554;
                let lng = (rawLng && !isNaN(rawLng)) ? parseFloat(rawLng) : -58.739;

                // Inicializamos el mapa de forma segura
                this.map = L.map('map-admin').setView([lat, lng], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(this.map);

                // Solo clavamos el marcador inicial si realmente había coordenadas guardadas (Caso Editar)
                if (rawLat && rawLng && !isNaN(rawLat) && !isNaN(rawLng)) {
                    this.marker = L.marker([lat, lng]).addTo(this.map);
                }

                // Escuchamos el click en el mapa
                this.map.on('click', (e) => {
                    let clickLat = e.latlng.lat.toFixed(6);
                    let clickLng = e.latlng.lng.toFixed(6);

                    @this.set('latitud', clickLat);
                    @this.set('longitud', clickLng);

                    if (this.marker) {
                        this.marker.setLatLng(e.latlng);
                    } else {
                        this.marker = L.marker(e.latlng).addTo(this.map);
                    }
                });
            }, 300); // Subimos a 300ms para asegurar que el modal CSS abrió del todo
        }
    }" 
    x-init="initMap()">
    
    <label>Ubicación en el mapa (Haz clic para marcar)</label>
    <div wire:ignore id="map-admin" style="height: 250px; width: 100%; border-radius: 8px; margin-bottom: 15px; border: 1px solid var(--border); z-index: 1;"></div>

    <div style="display: flex; gap: 10px;">
        <div style="flex: 1;">
            <label>Latitud</label>
            <input type="text" wire:model="latitud" readonly style="background: var(--bg-card); opacity: 0.8;">
        </div>
        <div style="flex: 1;">
            <label>Longitud</label>
            <input type="text" wire:model="longitud" readonly style="background: var(--bg-card); opacity: 0.8;">
        </div>
    </div>
</div>