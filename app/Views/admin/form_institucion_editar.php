<div class="container-form">
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2.5rem; margin: 0;">
                Editar <span style="color: var(--text-main);">regitución</span>
            </h2>
            <p class="text-cyan-tag">Modificar registro oficial del club</p>
        </div>
        <a href="<?= base_url('admin/instituciones') ?>" class="btn-back">
            <i data-lucide="arrow-left"></i> Volver al listado
        </a>
    </div>

    <form hx-post="<?= base_url('admin/instituciones/actualizar/' . $reg['id']) ?>" hx-target="#main-content"
        enctype="multipart/form-data" class="dark-card-form">

        <div class="grid-3">
            <div class="form-section">
                <h3 class="section-title">Identidad</h3>

                <div class="form-group">
                    <label>Nombre del Club</label>
                    <input type="text" name="nombre" value="<?= old('nombre', $reg['nombre']) ?>"
                        class="<?= (isset($validation) && $validation->hasError('nombre')) ? 'input-error' : '' ?>">
                    <?php if (isset($validation) && $validation->hasError('nombre')): ?>
                        <p class="error-text"><?= $validation->getError('nombre') ?></p>
                    <?php endif; ?>
                </div>

                <div x-data="{ 
                        imageUrl: null, 
                        originalUrl: '<?= (!empty($reg['logo_path'])) ? base_url('uploads/logos/' . $reg['logo_path']) : '' ?>' 
                     }" class="text-center">
                    <label class="label-left">Logo de la regitución</label>

                    <div
                        class="upload-zone <?= (isset($validation) && $validation->hasError('logo')) ? 'border-error' : '' ?>">

                        <template x-if="!imageUrl && !originalUrl">
                            <div class="upload-placeholder">
                                <i data-lucide="image-plus"></i>
                                <p>Click para subir logo</p>
                            </div>
                        </template>

                        <template x-if="originalUrl && !imageUrl">
                            <img :src="originalUrl" class="preview-img">
                        </template>

                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="preview-img" style="border: 2px solid var(--neon-cyan);">
                        </template>

                        <input type="file" name="logo" x-ref="logoInput" accept="image/*" class="input-file-hidden"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imageUrl = e.target.result; }; reader.readAsDataURL(file); }">
                    </div>

                    <div class="mt-2">
                        <button type="button" x-show="imageUrl" @click="imageUrl = null; $refs.logoInput.value = ''"
                            class="btn-remove-img">
                            Quitar cambios
                        </button>
                        <p x-show="originalUrl && !imageUrl" class="text-cyan-tag"
                            style="font-size: 9px; opacity: 0.6; margin-top: 8px;">
                            Click sobre la imagen para cambiarla
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Ubicación</h3>
                <div class="form-group">
                    <label>Estadio Principal</label>
                    <input type="text" name="estadio" value="<?= old('estadio', $reg['estadio']) ?>">
                </div>
                <div class="form-group">
                    <label>Link Google Maps</label>
                    <input type="text" name="maps" value="<?= old('maps', $reg['maps']) ?>"
                        placeholder="https://goo.gl/maps/...">
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Contacto</h3>
                <div class="form-group">
                    <label>Email Oficial</label>
                    <input type="email" name="email_contacto"
                        value="<?= old('email_contacto', $reg['email_contacto']) ?>"
                        class="<?= (isset($validation) && $validation->hasError('email_contacto')) ? 'input-error' : '' ?>">
                    <?php if (isset($validation) && $validation->hasError('email_contacto')): ?>
                        <p class="error-text"><?= $validation->getError('email_contacto') ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="<?= old('telefono', $reg['telefono']) ?>">
                </div>
            </div>
        </div>

        <div class="form-section mt-8">
            <label class="label-left">Historia / Descripción</label>
            <textarea name="descripcion" rows="3"
                class="neon-textarea"><?= old('descripcion', $reg['descripcion']) ?></textarea>
        </div>

        <div class="form-footer gap-4">
            <a href="<?= base_url('admin/instituciones') ?>" class="btn-back">
                Cancelar
            </a>

            <button type="submit" class="btn-neon-save">
                <i data-lucide="save"></i>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>