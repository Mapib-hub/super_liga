<div class="container-small">
    <div class="form-header">
        <h2>Editar Serie: <?= esc($reg['nombre_serie']) ?></h2>
    </div>

    <form hx-post="<?= base_url('admin/series/actualizar/' . $reg['id']) ?>" hx-target="#main-content"
        class="admin-form">

        <div class="form-group">
            <label>Nombre de la Serie</label>
            <input type="text" name="nombre_serie" value="<?= esc($reg['nombre_serie']) ?>" required>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="4"><?= esc($reg['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">Actualizar Datos</button>
            <button type="button" hx-get="<?= base_url('admin/series') ?>" hx-target="#main-content"
                class="btn-cancel">Volver</button>
        </div>
    </form>
</div>