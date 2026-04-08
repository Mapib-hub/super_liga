<div class="container-fluid">
    <div class="flex-between mb-6">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2rem; margin: 0;">Instituciones</h2>
            <p class="text-cyan-tag">Configuración y listado de clubes</p>
        </div>
        <button hx-get="<?= base_url('admin/instituciones/crear') ?>" hx-target="#main-content" hx-push-url="true"
            class="btn-neon">
            <i data-lucide="plus-circle" style="width:16px; height:16px; vertical-align:middle;"></i>
            Nuevo Club
        </button>

    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Club</th>
                    <th>Estadio</th>
                    <th>Contacto</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($instituciones as $inst): ?>
                    <tr>
                        <td>
                            <div class="flex-row gap-4">
                                <img src="<?= base_url('uploads/logos/' . ($inst['logo_path'] ?: 'default.png')) ?>"
                                    class="logo-circle">
                                <div>
                                    <p class="font-bold text-white"><?= esc($inst['nombre']) ?></p>
                                    <span class="text-id">ID #<?= $inst['id'] ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-semibold" style="color: var(--neon-cyan);"><?= esc($inst['estadio']) ?></span>
                        </td>
                        <td>
                            <div class="contact-info">
                                <span class="text-main"><?= esc($inst['nombre_contacto']) ?></span><br>
                                <span class="text-neon-purple font-bold"><?= esc($inst['telefono']) ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="flex-actions justify-center">
                                <button hx-get="<?= base_url('admin/instituciones/editar/' . $inst['id']) ?>"
                                    hx-target="#main-content" hx-push-url="true" class="btn-edit" title="Editar">
                                    <i data-lucide="edit-3"></i>
                                </button>

                                <button hx-delete="<?= base_url('admin/instituciones/eliminar/' . $inst['id']) ?>"
                                    hx-confirm="¿Vas a eliminar a <?= $inst['nombre'] ?>?" hx-target="#main-content"
                                    class="btn-delete" title="Eliminar">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    lucide.createIcons();
</script>