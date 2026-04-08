<div class="container-form">
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2.5rem; margin: 0;">
                Nuevo <span style="color: var(--text-main);">Usuario</span>
            </h2>
            <p class="text-cyan-tag">Control de acceso al sistema</p>
        </div>
        <a href="<?= base_url('admin/usuarios') ?>" hx-get="<?= base_url('admin/usuarios') ?>" hx-target="#main-content"
            hx-push-url="true" class="btn-back">
            <i data-lucide="arrow-left"></i>
            Volver al listado
        </a>
    </div>

    <form hx-post="<?= base_url('admin/usuarios/guardar') ?>" hx-target="#main-content" class="dark-card-form">

        <div class="grid-3">
            <div class="form-section">
                <h3 class="section-title">Acceso</h3>

                <div class="form-group">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="username" value="<?= old('username') ?>" required
                        placeholder="Ej: jdoe_admin">
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required placeholder="Mínimo 6 caracteres">
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Seguridad</h3>

                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" value="<?= old('email') ?>" required
                        placeholder="correo@ejemplo.com">
                </div>

                <div class="form-group">
                    <label>Rol de Usuario</label>
                    <select name="rol" class="neon-select" required>
                        <option value="">Seleccionar Rol...</option>
                        <option value="admin">Administrador (Acceso Total)</option>
                        <option value="delegado">Delegado (Gestión de Club)</option>
                        <option value="usuario">Usuario Estándar</option>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Vinculación</h3>

                <div class="form-group">
                    <label>Institución (Solo para Delegados)</label>
                    <select name="institucion_id" class="neon-select">
                        <option value="0">Ninguna / General</option>
                        <?php foreach ($instituciones as $inst): ?>
                            <option value="<?= $inst['id'] ?>"
                                <?= old('institucion_id') == $inst['id'] ? 'selected' : '' ?>>
                                <?= esc($inst['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <p class="text-id" style="margin-top: 1.5rem;">
                        <i data-lucide="info" style="width: 14px; vertical-align: middle;"></i>
                        Los delegados solo podrán gestionar noticias y jugadores de su club asignado.
                    </p>
                </div>
            </div>
        </div>

        <div class="form-footer mt-8">
            <button type="submit" class="btn-neon-save">
                <i data-lucide="user-plus"></i>
                Crear Usuario
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>

<style>
    .neon-select {
        width: 100%;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(188, 19, 254, 0.3);
        border-radius: 8px;
        color: white;
        outline: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23bc13fe' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.2em;
    }

    .neon-select:focus {
        border-color: var(--neon-purple);
        box-shadow: 0 0 10px rgba(188, 19, 254, 0.2);
    }
</style>