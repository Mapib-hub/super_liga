<div class="container-form" x-data="{ titulo: '<?= old('titulo') ?>', slug: '' }">
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2.5rem; margin: 0;">
                Nueva <span style="color: var(--text-main);">Noticia</span>
            </h2>
            <p class="text-cyan-tag">Publicación de novedades para el portal</p>
        </div>
        <a href="<?= base_url('admin/noticias') ?>" hx-get="<?= base_url('admin/noticias') ?>" hx-target="#main-content"
            hx-push-url="true" class="btn-back">
            <i data-lucide="arrow-left"></i>
            Volver al listado
        </a>
    </div>

    <form hx-post="<?= base_url('admin/noticias/guardar') ?>" hx-target="#main-content"
        hx-encoding="multipart/form-data" enctype="multipart/form-data" class="dark-card-form">

        <div class="grid-3">
            <div class="form-section">
                <h3 class="section-title">Encabezado</h3>

                <div class="form-group">
                    <label>Título de la Noticia</label>
                    <input type="text" name="titulo" x-model="titulo"
                        @input="slug = titulo.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-')"
                        class="<?= (isset($validation) && $validation->hasError('titulo')) ? 'input-error' : '' ?>"
                        placeholder="Ej: Gran victoria del equipo local">
                    <?php if (isset($validation) && $validation->hasError('titulo')): ?>
                        <p class="error-text"><?= $validation->getError('titulo') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>URL Amigable (Slug)</label>
                    <input type="text" name="slug" x-model="slug" readonly
                        style="background: rgba(0,0,0,0.2); color: var(--neon-cyan); cursor: not-allowed;"
                        placeholder="se-genera-solo">
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Imagen Destacada</h3>

                <div x-data="{ imageUrl: null }" class="text-center">
                    <div
                        class="upload-zone <?= (isset($validation) && $validation->hasError('imagen')) ? 'border-error' : '' ?>">
                        <template x-if="!imageUrl">
                            <div class="upload-placeholder">
                                <i data-lucide="image-plus"></i>
                                <p>Click para subir imagen</p>
                            </div>
                        </template>

                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="preview-img" style="object-fit: cover;">
                        </template>

                        <input type="file" name="imagen" accept="image/*" class="input-file-hidden"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imageUrl = e.target.result; }; reader.readAsDataURL(file); }">
                    </div>

                    <button type="button" x-show="imageUrl" @click="imageUrl = null" class="btn-remove-img">
                        Quitar imagen
                    </button>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Relación</h3>

                <div class="form-group">
                    <label>Vincular a Institución</label>
                    <select name="institucion_id" class="neon-select">
                        <option value="0">Noticia General (Liga)</option>
                        <?php foreach ($instituciones as $inst): ?>
                            <option value="<?= $inst['id'] ?>"
                                <?= old('institucion_id') == $inst['id'] ? 'selected' : '' ?>>
                                <?= esc($inst['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha de Publicación</label>
                    <input type="datetime-local" name="fecha_creacion"
                        value="<?= old('fecha_creacion', date('Y-m-d\TH:i')) ?>">
                </div>
            </div>
        </div>

        <div class="form-section mt-8">
            <label class="label-left">Cuerpo de la Noticia</label>
            <textarea name="descripcion" rows="8" class="neon-textarea"
                placeholder="Escribe aquí el contenido detallado..."><?= old('descripcion') ?></textarea>
            <?php if (isset($validation) && $validation->hasError('descripcion')): ?>
                <p class="error-text"><?= $validation->getError('descripcion') ?></p>
            <?php endif; ?>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-neon-save">
                <i data-lucide="send"></i>
                Publicar Noticia
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>

<style>
    /* Estilo para el select estilo neón que combine con tus inputs */
    .neon-select {
        width: 100%;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(188, 19, 254, 0.3);
        border-radius: 8px;
        color: white;
        outline: none;
        transition: 0.3s;
        appearance: none;
        /* Quita la flecha por defecto */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23bc13fe' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.2em;
    }

    .neon-select:focus {
        border-color: var(--neon-purple);
        box-shadow: 0 0 10px rgba(188, 19, 254, 0.2);
    }

    .neon-select option {
        background: #1a1a1a;
        color: white;
    }
</style>