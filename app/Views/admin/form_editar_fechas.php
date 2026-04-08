<div class="container-form" style="margin-top: 2rem;">
    <div class="dark-card-form">
        <div class="section-title">
            <i data-lucide="calendar" style="width:18px; color: var(--neon-purple); margin-right: 8px;"></i>
            <?= $titulo ?>: <span style="color: #fff;"><?= esc($reg['nombre_fecha']) ?></span>
        </div>

        <form hx-post="<?= base_url('admin/fechas/actualizar/' . $reg['id']) ?>" hx-target="#main-content"
            hx-push-url="true">

            <div class="grid-3">
                <div class="form-group">
                    <label>Temporada</label>
                    <select name="temporada_id" class="neon-textarea" required
                        style="height: 52px; appearance: none; background: #000 url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2220%22 height=%2220%22 fill=%22%23bd00ff%22><path d=%22M5 7l5 5 5-5H5z%22/></svg>') no-repeat right 15px center;">
                        <?php foreach ($temporadas as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ($t['id'] == $reg['temporada_id']) ? 'selected' : '' ?>
                            <?= $t['actual'] ? 'style="color: var(--neon-cyan); font-weight: bold;"' : '' ?>>
                            <?= esc($t['nombre_temporada']) ?> <?= $t['actual'] ? '(ACTUAL)' : '' ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nombre de la Fecha</label>
                    <input type="text" name="nombre_fecha" value="<?= esc($reg['nombre_fecha']) ?>"
                        placeholder="Ej: Fecha 01" required>
                </div>

                <div class="form-group">
                    <label>Fecha Calendario</label>
                    <input type="date" name="fecha_calendario" value="<?= $reg['fecha_calendario'] ?>" required
                        style="background: #000; color: white; border: 1px solid var(--dark-border); padding: 12px; border-radius: 15px; width: 100%;">
                </div>
            </div>

            <div class="grid-3 mt-4">
                <div class="form-group">
                    <label>Estado de la Fecha</label>
                    <select name="estado" class="neon-textarea"
                        style="height: 52px; appearance: none; background: #000 url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2220%22 height=%2220%22 fill=%22%2300ffff%22><path d=%22M5 7l5 5 5-5H5z%22/></svg>') no-repeat right 15px center;">
                        <option value="pendiente" <?= ($reg['estado'] == 'pendiente') ? 'selected' : '' ?>>0 -
                            PENDIENTE</option>
                        <option value="jugada" <?= ($reg['estado'] == 'jugada') ? 'selected' : '' ?>>1 - JUGADO
                        </option>
                        <option value="suspendida" <?= ($reg['estado'] == 'suspendida') ? 'selected' : '' ?>>2 -
                            SUSPENDIDO</option>
                    </select>
                </div>
            </div>

            <div class="flex-between mt-8" style="border-top: 1px solid var(--dark-border); padding-top: 2rem;">
                <button type="button" hx-get="<?= base_url('admin/fechas') ?>" hx-target="#main-content"
                    hx-push-url="true" class="btn-edit">
                    <i data-lucide="chevron-left"></i> CANCELAR
                </button>

                <button type="submit" class="btn-neon-save" style="background: var(--neon-purple);">
                    <i data-lucide="save"></i> ACTUALIZAR FECHA
                </button>
            </div>
        </form>
    </div>
</div>

<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>