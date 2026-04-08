<div class="container-form">
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2.5rem; margin: 0;">
                Editar <span style="color: var(--text-main);">Jugador</span>
            </h2>
            <p class="text-cyan-tag">Ficha: <?= esc($jugador['nombres'] . ' ' . $jugador['apellidos']) ?></p>
        </div>
        <a href="<?= base_url('admin/jugadores') ?>" hx-get="<?= base_url('admin/jugadores') ?>"
            hx-target="#main-content" hx-push-url="true" class="btn-back">
            <i data-lucide="arrow-left"></i> Volver
        </a>
    </div>

    <form hx-post="<?= base_url('admin/jugadores/actualizar/' . $jugador['id']) ?>" hx-target="#main-content"
        hx-encoding="multipart/form-data" enctype="multipart/form-data" class="dark-card-form">

        <input type="hidden" name="id" value="<?= $jugador['id'] ?>">

        <div class="grid-3">
            <div class="form-section">
                <h3 class="section-title">Identidad</h3>
                <div class="form-group">
                    <label>Nombres</label>
                    <input type="text" name="nombres" value="<?= esc($jugador['nombres']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" value="<?= esc($jugador['apellidos']) ?>" required>
                </div>
                <div class="form-group">
                    <label>RUT / DNI</label>
                    <input type="text" name="rut_dni" value="<?= esc($jugador['rut_dni']) ?>" required>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Club y Posición</h3>
                <div class="form-group">
                    <label>Institución</label>
                    <select name="institucion_id" class="neon-select" required>
                        <?php foreach ($instituciones as $inst): ?>
                            <option value="<?= $inst['id'] ?>"
                                <?= ($jugador['institucion_id'] == $inst['id']) ? 'selected' : '' ?>>
                                <?= esc($inst['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Posición</label>
                    <input type="text" name="posicion" value="<?= esc($jugador['posicion']) ?>">
                </div>
                <div class="form-group" style="margin-top: 2rem;">
                    <label class="flex-row gap-2 cursor-pointer">
                        <input type="checkbox" name="activo" value="1" <?= $jugador['activo'] ? 'checked' : '' ?>>
                        <span class="text-white">Jugador Activo</span>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Imagen de Perfil</h3>
                <div x-data="{ imageUrl: '<?= !empty($jugador['foto_path']) ? base_url('uploads/jugadores/' . $jugador['foto_path']) : '' ?>' }"
                    class="text-center">
                    <div class="upload-zone">
                        <template x-if="!imageUrl">
                            <div class="upload-placeholder">
                                <i data-lucide="camera"></i>
                                <p>Nueva Foto</p>
                            </div>
                        </template>
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="preview-img" style="object-fit: cover;">
                        </template>
                        <input type="file" name="foto" accept="image/*" class="input-file-hidden"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imageUrl = e.target.result; }; reader.readAsDataURL(file); }">
                    </div>
                    <small class="text-id mt-2 block">Haz clic para reemplazar la foto</small>
                </div>
            </div>
        </div>

        <div class="form-footer mt-8">
            <button type="submit" class="btn-neon-save">
                <i data-lucide="refresh-cw"></i> Actualizar Ficha
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>