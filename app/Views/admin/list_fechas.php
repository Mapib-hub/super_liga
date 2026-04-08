<div class="content-area">
    <div class="flex-between mb-8">
        <div>
            <p class="sidebar-tagline">Calendario Activo</p>
            <h2 style="color: var(--neon-cyan); margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                <i class="fas fa-calendar-day"></i> <?= $titulo ?>
            </h2>
        </div>
        <button hx-get="<?= base_url('admin/fechas/crear') ?>" hx-target="#main-content" hx-push-url="true"
            class="btn-neon">
            <i class="fas fa-plus"></i> Añadir Fecha
        </button>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Nombre de la Fecha</th>
                    <th>Temporada Actual</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($fechas)): ?>
                <tr>
                    <td colspan="4" class="text-center" style="padding: 40px; color: var(--text-muted);">
                        <i class="fas fa-info-circle mb-2" style="font-size: 2rem; display: block;"></i>
                        No hay fechas registradas en esta temporada.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($fechas as $f): ?>
                <tr>
                    <td>
                        <?php
                                $color = match ($f['estado']) {
                                    'pendiente' => 'var(--neon-cyan)',
                                    'suspendida' => 'var(--neon-pink)',
                                    default => 'var(--text-muted)'
                                };
                                ?>
                        <span
                            style="color: <?= $color ?>; font-weight: 800; font-size: 10px; border: 1px solid <?= $color ?>; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; box-shadow: 0 0 5px <?= $color ?>;">
                            <?= esc($f['estado']) ?>
                        </span>
                    </td>
                    <td style="font-weight: 600; letter-spacing: 1px; color: var(--text-main);">
                        <?= esc($f['nombre_fecha']) ?>
                    </td>
                    <td>
                        <span class="text-cyan-tag" style="opacity: 0.8;"><?= esc($f['nombre_temporada']) ?></span>
                    </td>
                    <td>
                        <div class="flex-actions justify-center">
                            <button hx-get="<?= base_url('admin/fechas/editar/' . $f['id']) ?>"
                                hx-target="#main-content" hx-push-url="true" class="btn-edit" title="Editar">
                                <i data-lucide="edit-3"></i>
                            </button>

                            <button hx-delete="<?= base_url('admin/fechas/eliminar/' . $f['id']) ?>"
                                hx-confirm="¿Eliminar la fecha <?= esc($f['nombre_fecha']) ?>?"
                                hx-target="#main-content" class="btn-delete" title="Eliminar">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
// Vital: Reiniciar Lucide cada vez que HTMX inyecta este contenido
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>