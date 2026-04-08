<div class="container-form" style="max-width: 600px;">
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2rem; margin: 0;">Editar <span
                    style="color: var(--text-main);">Serie</span></h2>
            <p class="text-cyan-tag">ID #<?= $reg['id'] ?></p>
        </div>
        <a href="<?= base_url('admin/series') ?>" hx-get="<?= base_url('admin/series') ?>" hx-target="#main-content"
            hx-push-url="true" class="btn-back">
            <i data-lucide="arrow-left"></i> Volver
        </a>
    </div>

    <form hx-post="<?= base_url('admin/series/actualizar/' . $reg['id']) ?>" hx-target="#main-content"
        class="dark-card-form">

        <div class="form-group">
            <label>Nombre de la Serie</label>
            <input type="text" name="nombre" value="<?= esc($reg['nombre']) ?>" required>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="4" class="neon-textarea"><?= esc($reg['descripcion']) ?></textarea>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-neon-save">
                <i data-lucide="save"></i> Actualizar Serie
            </button>
        </div>
    </form>
</div>
<script>
lucide.createIcons();
</script>