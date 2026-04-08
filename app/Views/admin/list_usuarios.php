<div class="container-fluid">
    <div class="flex-between mb-6">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2rem; margin: 0;">Usuarios</h2>
            <p class="text-cyan-tag">Control de acceso y roles</p>
        </div>
        <button hx-get="<?= base_url('admin/usuarios/crear') ?>" hx-target="#main-content" hx-push-url="true"
            class="btn-neon">
            <i data-lucide="user-plus"></i> Nuevo Usuario
        </button>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td>
                            <div class="flex-row gap-4">
                                <div class="logo-circle flex-center" style="background: var(--neon-purple); color: white;">
                                    <?= strtoupper(substr($user->username, 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="font-bold text-white"><?= esc($user->username) ?></p>
                                    <span class="text-id">ID #<?= $user->id ?></span>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-main"><?= esc($user->email ?? 'S/E') ?></span></td>
                        <td>
                            <?php foreach ($user->groups as $group): ?>
                                <span class="badge-neon-purple"><?= esc($group) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <div class="flex-actions justify-center">
                                <button hx-get="<?= base_url('admin/usuarios/editar/' . $user->id) ?>"
                                    hx-target="#main-content" class="btn-edit"><i data-lucide="edit-3"></i></button>
                                <button hx-delete="<?= base_url('admin/usuarios/eliminar/' . $user->id) ?>"
                                    hx-confirm="¿Eliminar usuario?" hx-target="#main-content" class="btn-delete"><i
                                        data-lucide="trash-2"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>