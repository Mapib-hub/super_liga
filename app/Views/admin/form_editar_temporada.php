<div class="content-area">
    <div class="container-form">
        <div class="dark-card-form">
            <div class="section-title">
                <i class="fas fa-edit"></i> Editar Temporada: <span
                    style="color: var(--text-main);"><?= esc($temporada['nombre_temporada']) ?></span>
            </div>

            <form hx-post="<?= base_url('admin/temporadas/actualizar/' . $temporada['id']) ?>" hx-target="#main-content"
                hx-push-url="true">

                <div class="form-group">
                    <label>Nombre de la Temporada</label>
                    <input type="text" name="nombre_temporada" value="<?= esc($temporada['nombre_temporada']) ?>"
                        placeholder="EJ: TEMPORADA INVIERNO 2026" required autofocus>
                    <p class="sidebar-tagline" style="margin-top: 8px;">ID de Registro: #<?= $temporada['id'] ?></p>
                </div>

                <div class="form-group"
                    style="background: rgba(34, 5, 77, 0.2); padding: 20px; border-radius: 15px; border: 1px solid var(--dark-border);">
                    <div class="flex-between">
                        <div>
                            <label style="color: var(--neon-cyan); margin-bottom: 2px;">Temporada Activa</label>
                            <span class="text-muted" style="font-size: 10px;">Activar esta opción marcará todas las
                                demás como históricas.</span>
                        </div>
                        <div style="position: relative;">
                            <input type="checkbox" name="actual" value="1" id="checkActual"
                                <?= $temporada['actual'] ? 'checked' : '' ?>
                                style="width: 25px; height: 25px; accent-color: var(--neon-purple); cursor: pointer;">
                        </div>
                    </div>
                </div>

                <div class="flex-between mt-8">
                    <button type="button" hx-get="<?= base_url('admin/temporadas') ?>" hx-target="#main-content"
                        hx-push-url="true" class="btn-edit"
                        style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px;">
                        <i class="fas fa-arrow-left"></i> Cancelar cambios
                    </button>

                    <button type="submit" class="btn-neon-save">
                        <i class="fas fa-sync-alt"></i> Actualizar Temporada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>