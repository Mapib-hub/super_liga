<div class="content-area">
    <div class="container-form">
        <div class="dark-card-form">
            <div class="section-title">
                <i class="fas fa-plus-circle"></i> <?= $titulo ?>
            </div>

            <form hx-post="<?= base_url('admin/temporadas/guardar') ?>" hx-target="#main-content" hx-push-url="true">

                <div class="form-group">
                    <label>Nombre de la Temporada</label>
                    <input type="text" name="nombre_temporada" placeholder="EJ: TEMPORADA VERANO 2026" required
                        autofocus>
                    <p class="sidebar-tagline" style="margin-top: 8px;">Usa nombres claros para reportes
                        institucionales.</p>
                </div>

                <div class="form-group"
                    style="background: rgba(34, 5, 77, 0.2); padding: 20px; border-radius: 15px; border: 1px solid var(--dark-border);">
                    <div class="flex-between">
                        <div>
                            <label style="color: var(--neon-cyan); margin-bottom: 2px;">Temporada Activa</label>
                            <span class="text-muted" style="font-size: 10px;">Si se activa, el resto pasará a modo
                                histórico.</span>
                        </div>
                        <div style="position: relative;">
                            <input type="checkbox" name="actual" value="1" id="checkActual"
                                style="width: 25px; height: 25px; accent-color: var(--neon-purple); cursor: pointer;">
                        </div>
                    </div>
                </div>

                <div class="flex-between mt-8">
                    <button type="button" hx-get="<?= base_url('admin/temporadas') ?>" hx-target="#main-content"
                        hx-push-url="true" class="btn-edit"
                        style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                        <i class="fas fa-chevron-left"></i> Volver a la lista
                    </button>

                    <button type="submit" class="btn-neon-save">
                        <i class="fas fa-save"></i> Guardar Temporada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>