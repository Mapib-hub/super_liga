<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Liga | Panel Neon</title>

    <!-- Librerías Externas -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="<?= base_url('assets/css/admin-custom.css') ?>">
    <?= $this->renderSection('styles') ?>
</head>

<body x-data="{ mobileMenuOpen: false }">

    <div class="admin-wrapper">
        <aside class="sidebar" :class="{ 'mobile-open': mobileMenuOpen }">
            <div class="sidebar-brand">
                <h1 class="font-sport text-neon-purple">SUPER LIGA</h1>
                <p class="sidebar-tagline">Panel Administrativo</p>
            </div>
            <nav class="sidebar-nav">
                <a href="<?= base_url('admin/dashboard') ?>" hx-get="<?= base_url('admin/dashboard') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="layout-dashboard"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/usuarios') ?>" hx-get="<?= base_url('admin/usuarios') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="users"></i>
                    <span>Usuarios</span>
                </a>

                <div class="nav-section-title">Gestión</div>

                <a href="<?= base_url('admin/instituciones') ?>" hx-get="<?= base_url('admin/instituciones') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="building-2"></i>
                    <span>Instituciones</span>
                </a>

                <a href="<?= base_url('admin/temporadas') ?>" hx-get="<?= base_url('admin/temporadas') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="calendar-range"></i>
                    <span>Temporadas</span>
                </a>

                <a href="<?= base_url('admin/fechas') ?>" hx-get="<?= base_url('admin/fechas') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="calendar-days"></i>
                    <span>Fechas</span>
                </a>

                <a href="<?= base_url('admin/series') ?>" hx-get="<?= base_url('admin/series') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="list-ordered"></i>
                    <span>Series</span>
                </a>

                <a href="<?= base_url('admin/noticias') ?>" hx-get="<?= base_url('admin/noticias') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="newspaper"></i>
                    <span>Noticias</span>
                </a>

                <a href="<?= base_url('admin/jugadores') ?>" hx-get="<?= base_url('admin/jugadores') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="users"></i>
                    <span>Jugadores</span>
                </a>

                <a href="<?= base_url('admin/goles') ?>" hx-get="<?= base_url('admin/goles') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="goal"></i>
                    <span>Goles</span>
                </a>

                <a href="<?= base_url('admin/tarjetas') ?>" hx-get="<?= base_url('admin/tarjetas') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false" class="nav-link">
                    <i data-lucide="copy-minus" style="color: var(--neon-cyan);"></i>
                    <span>Tarjetas</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="<?= base_url('logout') ?>" class="logout-link">
                    <i data-lucide="log-out"></i> <span>Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <div class="main-layout">
            <header class="top-bar">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="menu-toggle">
                    <i data-lucide="menu"></i>
                </button>

                <div class="htmx-indicator" id="loading-indicator">
                    <div class="spinner"></div>
                    <span>Cargando</span>
                </div>

                <div class="top-bar-info">
                    Temporada: <span class="text-neon-cyan"><?= $tempActual['nombre_temporada'] ?? '2024/25' ?></span>
                </div>
            </header>

            <main class="content-area" id="main-content">
                <?php if (session()->getFlashdata('success')): ?>
                    <script>
                        Swal.fire({
                            title: '¡Hecho!',
                            text: '<?= session()->getFlashdata('success') ?>',
                            icon: 'success',
                            timer: 3000
                        });
                    </script>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <script>
                        Swal.fire({
                            title: 'Atención',
                            text: '<?= session()->getFlashdata('error') ?>',
                            icon: 'error',
                            timer: 3000
                        });
                    </script>
                <?php endif; ?>

                <?php if (isset($view)): ?>
                    <?= view($view, $data ?? []) ?>
                <?php else: ?>
                    <?= $this->renderSection('content') ?>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Scripts Globales -->
    <script>
        // Inicializar iconos al cargar
        lucide.createIcons();

        // Re-inicializar iconos CADA VEZ que HTMX carga contenido nuevo
        document.body.addEventListener('htmx:afterSwap', () => {
            lucide.createIcons();

            // TRUCO SENIOR: Forzar a Alpine a re-escanear el DOM nuevo
            if (typeof Alpine !== 'undefined') {
                Alpine.initTree(document.getElementById('main-content'));
            }
        });
    </script>
</body>

</html>