<div class="container-fluid">
    <div class="flex-between mb-6">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2rem; margin: 0;">Noticias</h2>
            <p class="text-cyan-tag">Gestión de novedades y comunicados</p>
        </div>
        <button hx-get="<?= base_url('admin/noticias/crear') ?>" hx-target="#main-content" hx-push-url="true"
            class="btn-neon">
            <i data-lucide="plus-circle" style="width:16px; height:16px; vertical-align:middle;"></i>
            Nueva Noticia
        </button>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Noticia</th>
                    <th>Institución</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($noticias)): ?>
                    <?php foreach ($noticias as $noti): ?>
                        <tr>
                            <td>
                                <div class="flex-row gap-4">
                                    <div class="img-wrapper-neon">
                                        <?php if (!empty($noti['imagen'])): ?>
                                            <img src="<?= base_url('uploads/noticias/' . $noti['imagen']) ?>" class="logo-circle">
                                        <?php else: ?>
                                            <div class="logo-circle flex-center" style="background: #1a1a1a;">
                                                <i data-lucide="image-off" style="width:20px; color:#444;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white"><?= esc($noti['titulo']) ?></p>
                                        <span class="text-id">ID #<?= $noti['id'] ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-semibold" style="color: var(--neon-cyan);">
                                    <?= esc($noti['institucion_id'] ?: 'General') ?>
                                </span>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <span class="text-main"><?= date('d M, Y', strtotime($noti['fecha_creacion'])) ?></span><br>
                                    <span
                                        class="text-neon-purple font-bold"><?= date('H:i', strtotime($noti['fecha_creacion'])) ?>
                                        hrs</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex-actions justify-center">
                                    <button hx-get="<?= base_url('admin/noticias/editar/' . $noti['id']) ?>"
                                        hx-target="#main-content" hx-push-url="true" class="btn-edit" title="Editar">
                                        <i data-lucide="edit-3"></i>
                                    </button>

                                    <button hx-delete="<?= base_url('admin/noticias/eliminar/' . $noti['id']) ?>"
                                        hx-confirm="¿Vas a eliminar esta noticia?" hx-target="#main-content" class="btn-delete"
                                        title="Eliminar">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 40px; color: #666;">
                            No hay noticias publicadas actualmente.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Estilo adicional para que la imagen de la noticia no se vea deforme si es rectangular */
    .img-wrapper-neon .logo-circle {
        object-fit: cover;
        border: 2px solid var(--neon-purple);
        box-shadow: 0 0 10px rgba(188, 19, 254, 0.2);
    }

    .flex-center {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
    lucide.createIcons();
</script>