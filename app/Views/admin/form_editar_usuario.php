<div class="container-form">
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2.5rem; margin: 0;">
                Editar <span style="color: var(--text-main);">Usuario</span>
            </h2>
            <p class="text-cyan-tag">Modificando perfil de: <?= esc($usuario->username) ?></p>
        </div>
        <a href="<?= base_url('admin/usuarios') ?>" hx-get="<?= base_url('admin/usuarios') ?>" hx-target="#main-content"
            hx-push-url="true" class="btn-back">
            <i data-lucide="arrow-left"></i>
            Volver
        </a>
    </div>

    <form hx-post="<?= base_url('admin/usuarios/actualizar/' . $usuario->id) ?>" hx-target="#main-content"
        class="dark-card-form">
        <input type="hidden" name="id" value="<?= $usuario->id ?>">

        <div class="grid-3">
            <div class="form-section">
                <h3 class="section-title">Identidad</h3>

                <div class="form-group">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="nombre_usuario" value="<?= esc($usuario->username) ?>" required>
                </div>

                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="password" placeholder="Dejar en blanco para no cambiar">
                    <small class="text-id">Solo si deseas actualizarla</small>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Credenciales</h3>

                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" value="<?= esc($email) ?>" required>
                </div>

                <div class="form-group">
                    <label>Rol asignado</label>
                    <select name="rol" class="neon-select">
                        <?php
                        // El primer rol del array de grupos actual
                        $currentRole = !empty($groups) ? $groups[0] : 'usuario';
                        ?>
                        <option value="admin" <?= $currentRole == 'admin' ? 'selected' : '' ?>>Administrador</option>
                        <option value="delegado" <?= $currentRole == 'delegado' ? 'selected' : '' ?>>Delegado</option>
                        <option value="usuario" <?= $currentRole == 'usuario' ? 'selected' : '' ?>>Usuario</option>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Configuración</h3>

                <div class="form-group">
                    <label>Institución Vinculada</label>
                    <select name="institucion_id" class="neon-select">
                        <option value="0">Ninguna / General</option>
                        <?php foreach ($instituciones as $inst): ?>
                            <option value="<?= $inst['id'] ?>"
                                <?= ($usuario->institucion_id == $inst['id']) ? 'selected' : '' ?>>
                                <?= esc($inst['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-top: 2rem;">
                    <label class="flex-row gap-2 cursor-pointer">
                        <input type="checkbox" name="activo" value="1" <?= $usuario->active ? 'checked' : '' ?>>
                        <span class="text-white">Usuario Activo</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-footer mt-8">
            <button type="submit" class="btn-neon-save">
                <i data-lucide="save"></i>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>