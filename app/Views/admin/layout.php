<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Liga | Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    body {
        background-color: #baad9b;
    }

    .htmx-indicator {
        opacity: 0;
        transition: opacity 200ms ease-in;
        pointer-events: none;
    }

    .htmx-request .htmx-indicator {
        opacity: 1;
    }

    .htmx-request.htmx-indicator {
        opacity: 1;
    }

    /* Deshabilitar botones mientras carga */
    button:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    </style>
</head>

<body class="font-sans antialiased" x-data="{ mobileMenuOpen: false }">
    <div class="flex items-center gap-4">
        <div class="htmx-indicator">
            <div class="flex items-center gap-2 text-indigo-600">
                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                        fill="none"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Cargando...</span>
            </div>
        </div>
    </div>
    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-indigo-950 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6">
                <h1 class="text-2xl font-black italic tracking-tighter text-indigo-400">SUPER LIGA</h1>
                <p class="text-[10px] uppercase tracking-widest opacity-50 font-bold">Panel Administrativo</p>
            </div>

            <nav class="flex-1 px-4 space-y-2 py-4">
                <a href="<?= base_url('admin/dashboard') ?>" hx-get="<?= base_url('admin/dashboard') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-800 transition">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-semibold">Dashboard</span>
                </a>

                <div class="pt-4 pb-2 px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Gestión
                </div>

                <a href="<?= base_url('admin/instituciones') ?>" hx-get="<?= base_url('admin/instituciones') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-800 transition">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                    <span>Instituciones</span>
                </a>
                <a href="<?= base_url('admin/temporadas') ?>" hx-get="<?= base_url('admin/temporadas') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-800 transition">
                    <i data-lucide="calendar-range" class="w-5 h-5"></i>
                    <span>Temporadas</span>
                </a>
                <a href="<?= base_url('admin/fechas') ?>" hx-get="<?= base_url('admin/fechas') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-800 transition">
                    <i data-lucide="calendar-days" class="w-5 h-5"></i>
                    <span>Fechas</span>
                </a>
                <a href="<?= base_url('admin/series') ?>" hx-get="<?= base_url('admin/series') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-800 transition">
                    <i data-lucide="list-ordered" class="w-5 h-5"></i>
                    <span>Series</span>
                </a>
                <a href="<?= base_url('admin/noticias') ?>" hx-get="<?= base_url('admin/noticias') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-800 transition">
                    <i data-lucide="newspaper" class="w-5 h-5"></i>
                    <span>Noticias</span>
                </a>

                <a href="<?= base_url('admin/jugadores') ?>" hx-get="<?= base_url('admin/jugadores') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-800 transition">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Jugadores</span>
                </a>
                <a href="<?= base_url('admin/goles') ?>" hx-get="<?= base_url('admin/goles') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-800 transition">
                    <i data-lucide="goal" class="w-5 h-5"></i>
                    <span>Goles</span>
                </a>
                <a href="<?= base_url('admin/tarjetas') ?>" hx-get="<?= base_url('admin/tarjetas') ?>"
                    hx-target="#main-content" hx-push-url="true" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-800 transition">
                    <i data-lucide="copy-minus" class="w-5 h-5 text-yellow-500"></i>
                    <span>Tarjetas</span>
                </a>
            </nav>

            <div class="p-4 border-t border-indigo-900">
                <a href="<?= base_url('logout') ?>"
                    class="flex items-center gap-3 p-3 text-red-400 hover:bg-red-950/30 rounded-xl transition">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden"
            x-transition:enter="transition opacity-100 duration-300"
            x-transition:leave="transition opacity-0 duration-300">
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 z-10">
                <div class="flex items-center gap-4">
                    <button @click="mobileMenuOpen = true"
                        class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <span
                        class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        <?= $tempActual['nombre_temporada'] ?? 'Sin Temporada' ?>
                    </span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto md:p-8" id="main-content">
                <?php if (isset($view)): ?>
                <?= view($view, $data ?? []) ?> <?php else: ?>
                <?= $this->renderSection('content') ?>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
    // Inicializar iconos de Lucide
    lucide.createIcons();

    // IMPORTANTE: Reiniciar iconos después de cada intercambio de HTMX
    document.body.addEventListener('htmx:afterSwap', function(evt) {
        if (evt.detail.target.id === "main-content") {
            lucide.createIcons();
        }
    });

    document.body.addEventListener('swal:fire', function(evt) {
        Swal.fire({
            title: evt.detail.title || 'Atención',
            text: evt.detail.text || '',
            icon: evt.detail.icon || 'info',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#4F46E5' // Color Indigo de tu diseño
        });
    });

    document.addEventListener('htmx:confirm', function(e) {
        // Si el elemento no tiene hx-confirm, no hacemos nada
        if (!e.detail.question) return;

        // Detenemos la petición de HTMX momentáneamente
        e.preventDefault();

        Swal.fire({
            title: '¿Estás seguro?',
            text: e.detail.question,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4F46E5', // Indigo
            cancelButtonColor: '#64748b', // Slate
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            // Esto hace que el alert se vea pro y coincida con tu interfaz
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl px-6 py-3 font-black uppercase text-xs tracking-widest',
                cancelButton: 'rounded-xl px-6 py-3 font-black uppercase text-xs tracking-widest'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, le decimos a HTMX que continúe la petición
                e.detail.issueRequest(true);
            }
        });
    });
    // Configuración global de HTMX para subcarpetas
    document.addEventListener("htmx:configRequest", (config) => {
        // Aseguramos que todas las peticiones AJAX lleven el prefijo correcto
        // si es que no lo tienen ya.
        if (!config.detail.path.startsWith('/htmx/') && !config.detail.path.startsWith('http')) {
            config.detail.path = '/htmx/' + config.detail.path.replace(/^\//, '');
        }
    });

    // Este es el truco para el botón "Atrás"
    window.onpopstate = function(event) {
        if (event.state && event.state.path) {
            // Si la ruta del historial perdió el /htmx/, se lo reinyectamos
            if (!event.state.path.startsWith('/htmx/')) {
                const newPath = '/htmx/' + event.state.path.replace(/^\//, '');
                window.history.replaceState(event.state, "", newPath);
            }
            htmx.ajax('GET', event.state.path, '#main-content');
        }
    };
    </script>
</body>

</html>