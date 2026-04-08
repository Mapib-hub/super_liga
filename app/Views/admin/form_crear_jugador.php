<div class="container-form">
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2.5rem; margin: 0;">
                Nuevo <span style="color: var(--text-main);">Jugador</span>
            </h2>
            <p class="text-cyan-tag">Ficha técnica del deportista</p>
        </div>
        <a href="<?= base_url('admin/jugadores') ?>" hx-get="<?= base_url('admin/jugadores') ?>"
            hx-target="#main-content" hx-push-url="true" class="btn-back">
            <i data-lucide="arrow-left"></i> Volver
        </a>
    </div>

    <form hx-post="<?= base_url('admin/jugadores/guardar') ?>" hx-target="#main-content"
        hx-encoding="multipart/form-data" enctype="multipart/form-data" class="dark-card-form">

        <div class="grid-3">
            <div class="form-section">
                <h3 class="section-title">Datos Personales</h3>
                <div class="form-group">
                    <label>Nombres</label>
                    <input type="text" name="nombres" required placeholder="Ej: Juan Andrés">
                </div>
                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" required placeholder="Ej: Pérez Silva">
                </div>
                <div class="form-group">
                    <label>RUT / DNI</label>
                    <input type="text" name="rut_dni" required placeholder="12.345.678-9">
                </div>
                <div class="form-group">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento">
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Información Deportiva</h3>
                <div class="form-group">
                    <label>Institución / Club</label>
                    <select name="institucion_id" class="neon-select" required>
                        <option value="">Seleccionar Club...</option>
                        <?php foreach ($instituciones as $inst): ?>
                            <option value="<?= $inst['id'] ?>"><?= esc($inst['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Posición</label>
                    <select name="posicion" class="neon-select">
                        <option value="Arquero">Arquero</option>
                        <option value="Defensa">Defensa</option>
                        <option value="Mediocampista">Mediocampista</option>
                        <option value="Delantero">Delantero</option>
                    </select>
                </div>
                <div class="form-group" style="margin-top: 2rem;">
                    <label class="flex-row gap-2 cursor-pointer">
                        <input type="checkbox" name="activo" value="1" checked>
                        <span class="text-white">Jugador Habilitado</span>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Foto de Perfil</h3>
                <div x-data="{ imageUrl: null }" class="text-center">
                    <div class="upload-zone">
                        <template x-if="!imageUrl">
                            <div class="upload-placeholder">
                                <i data-lucide="camera"></i>
                                <p>Subir Foto</p>
                            </div>
                        </template>
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="preview-img" style="object-fit: cover;">
                        </template>
                        <input type="file" name="foto" accept="image/*" class="input-file-hidden"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imageUrl = e.target.result; }; reader.readAsDataURL(file); }">
                    </div>
                    <button type="button" x-show="imageUrl" @click="imageUrl = null"
                        class="btn-remove-img">Quitar</button>
                </div>
            </div>
        </div>

        <div class="form-footer mt-8">
            <button type="submit" class="btn-neon-save">
                <i data-lucide="save"></i> Registrar Jugador
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>