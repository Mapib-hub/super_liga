<div class="container-form">
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2.5rem; margin: 0;">
                Nueva <span style="color: var(--text-main);">Institución</span>
            </h2>
            <p class="text-cyan-tag">Registro oficial de clubes</p>
        </div>
        <a href="<?= base_url('admin/instituciones') ?>" hx-get="<?= base_url('admin/instituciones') ?>"
            hx-target="#main-content" hx-push-url="true" class="btn-back">
            <i data-lucide="arrow-left"></i>
            Volver al listado
        </a>
    </div>

    <form hx-post="<?= base_url('admin/instituciones/guardar') ?>" hx-target="#main-content"
        hx-encoding="multipart/form-data" enctype="multipart/form-data" class="dark-card-form">

        <div class="grid-3">
            <div class="form-section">
                <h3 class="section-title">Identidad</h3>

                <div class="form-group">
                    <label>Nombre del Club</label>
                    <input type="text" name="nombre" value="<?= old('nombre') ?>"
                        class="<?= (isset($validation) && $validation->hasError('nombre')) ? 'input-error' : '' ?>"
                        placeholder="Ej: Club Deportivo Neon">
                    <?php if (isset($validation) && $validation->hasError('nombre')): ?>
                    <p class="error-text"><?= $validation->getError('nombre') ?></p>
                    <?php endif; ?>
                </div>

                <div x-data="{ imageUrl: null }" class="text-center">
                    <label class="label-left">Logo de la Institución</label>
                    <div
                        class="upload-zone <?= (isset($validation) && $validation->hasError('logo')) ? 'border-error' : '' ?>">
                        <template x-if="!imageUrl">
                            <div class="upload-placeholder">
                                <i data-lucide="image-plus"></i>
                                <p>Click para subir logo</p>
                            </div>
                        </template>

                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="preview-img">
                        </template>

                        <input type="file" name="logo" accept="image/*" class="input-file-hidden"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imageUrl = e.target.result; }; reader.readAsDataURL(file); }">
                    </div>

                    <button type="button" x-show="imageUrl" @click="imageUrl = null" class="btn-remove-img">
                        Quitar imagen
                    </button>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Ubicación</h3>
                <div class="form-group">
                    <label>Estadio Principal</label>
                    <input type="text" name="estadio" value="<?= old('estadio') ?>" placeholder="Nombre del estadio">
                </div>
                <div class="form-group">
                    <label>Link Google Maps</label>
                    <input type="text" name="maps" placeholder="https://goo.gl/maps/..." value="<?= old('maps') ?>">
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Contacto</h3>
                <div class="form-group">
                    <label>Email Oficial</label>
                    <input type="email" name="email_contacto" value="<?= old('email_contacto') ?>"
                        class="<?= (isset($validation) && $validation->hasError('email_contacto')) ? 'input-error' : '' ?>">
                    <?php if (isset($validation) && $validation->hasError('email_contacto')): ?>
                    <p class="error-text"><?= $validation->getError('email_contacto') ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="<?= old('telefono') ?>" placeholder="+56 9 ...">
                </div>
            </div>
        </div>

        <div class="form-section mt-8">
            <label class="label-left">Historia / Descripción</label>
            <textarea name="descripcion" rows="3" class="neon-textarea"><?= old('descripcion') ?></textarea>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-neon-save">
                <i data-lucide="save"></i>
                Guardar Institución
            </button>
        </div>
    </form>
</div>

<script>
lucide.createIcons();
</script>