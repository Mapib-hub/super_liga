<div class="container-fluid">
    <div class="flex-between mb-6">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2rem; margin: 0;">Gestión de Series</h2>
            <p class="text-cyan-tag">Categorías y divisiones del torneo</p>
        </div>
        <button hx-get="<?= base_url('admin/series/crear') ?>" hx-target="#main-content" hx-push-url="true"
            class="btn-neon">
            <i data-lucide="plus-circle" style="width:16px; height:16px; vertical-align:middle;"></i>
            Nueva Serie
        </button>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nombre de la Serie</th>
                    <th>Descripción</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($series)): ?>
                <?php foreach ($series as $s): ?>
                <tr>
                    <td>
                        <span class="text-id">#<?= $s['id'] ?></span>
                    </td>
                    <td>
                        <p class="font-bold text-white" style="font-size: 1.1rem;"><?= esc($s['nombre']) ?></p>
                    </td>
                    <td>
                        <span style="opacity: 0.7; font-size: 0.9rem;">
                            <?= esc($s['descripcion'] ?: 'Sin descripción') ?>
                        </span>
                    </td>
                    <td>
                        <div class="flex-actions justify-center">
                            <button hx-get="<?= base_url('admin/series/editar/' . $s['id']) ?>"
                                hx-target="#main-content" hx-push-url="true" class="btn-edit" title="Editar">
                                <i data-lucide="edit-3"></i>
                            </button>

                            <button hx-delete="<?= base_url('admin/series/eliminar/' . $s['id']) ?>"
                                hx-confirm="¿Eliminar la serie <?= esc($s['nombre']) ?>?" hx-target="#main-content"
                                class="btn-delete" title="Eliminar">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center" style="padding: 40px; opacity: 0.5;">
                        No hay series registradas aún.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Esto es vital para que los iconos de Lucide se rendericen tras la carga de HTMX
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>