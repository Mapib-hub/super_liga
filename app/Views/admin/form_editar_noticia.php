<div class="container-form" x-data="{ titulo: '<?= esc($reg['titulo']) ?>', slug: '<?= esc($reg['slug']) ?>' }">
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2.5rem; margin: 0;">
                Editar <span style="color: var(--text-main);">Noticia</span>
            </h2>
            <p class="text-cyan-tag">Actualizando: <?= esc($reg['titulo']) ?></p>
        </div>
        <a href="<?= base_url('admin/noticias') ?>" hx-get="<?= base_url('admin/noticias') ?>" hx-target="#main-content"
            hx-push-url="true" class="btn-back">
            <i data-lucide="arrow-left"></i>
            Volver al listado
        </a>
    </div>

    <form hx-post="<?= base_url('admin/noticias/actualizar/' . $reg['id']) ?>" hx-target="#main-content"
        hx-encoding="multipart/form-data" enctype="multipart/form-data" class="dark-card-form">

        <div class="grid-3">
            <div class="form-section">
                <h3 class="section-title">Encabezado</h3>
                <div class="form-group">
                    <label>Título de la Noticia</label>
                    <input type="text" name="titulo" x-model="titulo"
                        @input="slug = titulo.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-')"
                        class="<?= (isset($validation) && $validation->hasError('titulo')) ? 'input-error' : '' ?>">
                </div>
                <div class="form-group">
                    <label>URL Amigable (Slug)</label>
                    <input type="text" name="slug" x-model="slug" readonly
                        style="background: rgba(0,0,0,0.2); color: var(--neon-cyan); cursor: not-allowed;">
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Imagen Destacada</h3>
                <div x-data="{ imageUrl: '<?= !empty($reg['imagen']) ? base_url('uploads/noticias/' . $reg['imagen']) : '' ?>' }"
                    class="text-center">
                    <div class="upload-zone">
                        <template x-if="!imageUrl">
                            <div class="upload-placeholder">
                                <i data-lucide="image-plus"></i>
                                <p>Nueva imagen</p>
                            </div>
                        </template>
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="preview-img" style="object-fit: cover;">
                        </template>
                        <input type="file" name="imagen" accept="image/*" class="input-file-hidden"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imageUrl = e.target.result; }; reader.readAsDataURL(file); }">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Relación y Fecha</h3>
                <div class="form-group">
                    <label>Vincular a Institución</label>
                    <select name="institucion_id" class="neon-select">
                        <option value="0" <?= $reg['institucion_id'] == 0 ? 'selected' : '' ?>>Noticia General (Liga)
                        </option>
                        <?php foreach ($instituciones as $inst): ?>
                            <option value="<?= $inst['id'] ?>"
                                <?= $reg['institucion_id'] == $inst['id'] ? 'selected' : '' ?>>
                                <?= esc($inst['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha Original</label>
                    <input type="datetime-local" name="fecha_creacion"
                        value="<?= date('Y-m-d\TH:i', strtotime($reg['fecha_creacion'])) ?>">
                </div>
            </div>
        </div>
        <div class="form-section mt-6 mb-6">
            <div class="flex-between p-4 border border-purple-900/30 bg-purple-900/10 rounded-lg">
                <div>
                    <h4 class="text-white font-sport mb-1">Galería de Imágenes</h4>
                    <p class="text-id">Fotos adicionales de la jornada o evento.</p>
                </div>
                <button type="button" class="btn-neon-cyan"
                    onclick="document.getElementById('modal-galeria').classList.add('active')">
                    <i data-lucide="images"></i> Gestionar Galería (<?= count($imagenesExtra) ?>)
                </button>
            </div>
        </div>
        <div class="form-section mt-8">
            <label class="label-left">Cuerpo de la Noticia</label>
            <textarea name="descripcion" rows="10" class="neon-textarea"><?= esc($reg['descripcion']) ?></textarea>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-neon-save">
                <i data-lucide="refresh-cw"></i>
                Actualizar Noticia
            </button>
        </div>
    </form>
</div>
<div id="modal-galeria" class="modal-overlay">
    <div class="modal-content dark-card-form" style="max-width: 800px; width: 90%;">
        <div class="flex-between mb-6 border-b border-white/10 pb-4">
            <h3 class="text-neon-purple font-sport">Galería de la Jornada</h3>
            <button type="button" class="text-white"
                onclick="document.getElementById('modal-galeria').classList.remove('active')">
                <i data-lucide="x"></i>
            </button>
        </div>

        <form hx-post="<?= base_url('admin/noticias/subir-galeria/' . $reg['id']) ?>" hx-target="#main-content"
            hx-encoding="multipart/form-data" class="mb-8">
            <div class="upload-zone" style="height: 100px; border-style: dashed;">
                <p class="text-cyan-tag">Click para seleccionar múltiples fotos</p>
                <input type="file" name="imagenes[]" multiple accept="image/*" class="input-file-hidden"
                    onchange="this.form.requestSubmit()">
            </div>
            <small class="text-id mt-2 block text-center italic">La subida comenzará automáticamente al seleccionar los
                archivos.</small>
        </form>

        <div class="grid grid-cols-4 gap-4" style="max-height: 300px; overflow-y: auto;">
            <?php foreach ($imagenesExtra as $img): ?>
                <div class="relative group rounded border border-white/10 overflow-hidden">
                    <img src="<?= $img['imagen_path'] ?>" class="w-full h-24 object-cover">
                    <button type="button"
                        hx-delete="<?= base_url('admin/noticias/eliminar-foto/' . $img['id'] . '/' . $reg['id']) ?>"
                        hx-target="#main-content" hx-confirm="¿Eliminar esta imagen?"
                        class="absolute top-1 right-1 bg-red-600 p-1 rounded opacity-0 group-hover:opacity-100 transition-all">
                        <i data-lucide="trash-2" style="width: 14px;"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-8 pt-4 border-t border-white/10 text-right">
            <button type="button" class="btn-back"
                onclick="document.getElementById('modal-galeria').classList.remove('active')">
                Cerrar Galería
            </button>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .modal-overlay.active {
        display: flex;
    }
</style>
<script>
    lucide.createIcons();
</script>