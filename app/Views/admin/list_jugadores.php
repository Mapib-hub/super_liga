<div class="container-fluid">
    <div class="flex-between mb-6">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2rem; margin: 0;">Jugadores</h2>
            <p class="text-cyan-tag">Registro de deportistas y estadísticas</p>
        </div>
        <button hx-get="<?= base_url('admin/jugadores/crear') ?>" hx-target="#main-content" hx-push-url="true"
            class="btn-neon">
            <i data-lucide="user-plus"></i> Nuevo Jugador
        </button>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Jugador</th>
                    <th>RUT / DNI</th>
                    <th>Institución</th>
                    <th>Posición</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jugadores as $j): ?>
                    <tr>
                        <td>
                            <div class="flex-row gap-4">
                                <div class="logo-circle overflow-hidden">
                                    <?php if ($j['foto_path']): ?>
                                        <img src="<?= base_url('uploads/jugadores/' . $j['foto_path']) ?>"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <i data-lucide="user" style="color: var(--neon-purple);"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-bold text-white"><?= esc($j['nombres'] . ' ' . $j['apellidos']) ?></p>
                                    <span class="text-id">ID #<?= $j['id'] ?></span>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-main"><?= esc($j['rut_dni']) ?></span></td>
                        <td><span class="badge-neon-cyan"><?= esc($j['institucion_nombre']) ?></span></td>
                        <td><span class="text-white"><?= esc($j['posicion']) ?></span></td>
                        <td class="text-center">
                            <span class="<?= $j['activo'] ? 'text-neon-cyan' : 'text-error' ?>">
                                <?= $j['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td>
                            <div class="flex-actions justify-center">
                                <button hx-get="<?= base_url('admin/jugadores/editar/' . $j['id']) ?>"
                                    hx-target="#main-content" class="btn-edit"><i data-lucide="edit-3"></i></button>
                                <button hx-delete="<?= base_url('admin/jugadores/eliminar/' . $j['id']) ?>"
                                    hx-confirm="¿Eliminar jugador?" hx-target="#main-content" class="btn-delete"><i
                                        data-lucide="trash-2"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>