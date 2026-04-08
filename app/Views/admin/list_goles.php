<div class="container-admin" id="goles-container" x-data="{ 
        modalAbierto: false, 
        fechaId: '', 
        serieId: '', 
        partidoId: '', 
        series: [], 
        partidos: [], 
        jugadoresLocal: [], 
        jugadoresVisita: [], 
        cargandoSeries: false, 
        cargandoPartidos: false,
        cargandoJugadores: false,
        
        // Funciones de carga de datos
        async cargarSeries() {
            if (!this.fechaId) return;
            this.cargandoSeries = true;
            this.serieId = ''; 
            this.partidoId = '';
            this.series = [];
            this.partidos = [];
            
            try {
                const resp = await fetch(`<?= base_url('admin/goles/getSeriesPorFecha') ?>/${this.fechaId}`);
                const data = await resp.json();
                this.series = data;
            } catch (e) { console.error(e); }
            finally { this.cargandoSeries = false; }
        },

        async cargarPartidos() {
            if (!this.serieId) return;
            this.cargandoPartidos = true;
            this.partidoId = '';
            this.partidos = [];
            
            try {
                const resp = await fetch(`<?= base_url('admin/goles/getPartidos') ?>/${this.fechaId}/${this.serieId}`);
                const data = await resp.json();
                this.partidos = data;
            } catch (e) { console.error(e); }
            finally { this.cargandoPartidos = false; }
        },

        async cargarJugadores() {
            if (!this.partidoId) return;
            this.cargandoJugadores = true;
            this.jugadoresLocal = [];
            this.jugadoresVisita = [];
            
            try {
                const resp = await fetch(`<?= base_url('admin/fixture/get-jugadores-partido') ?>/${this.partidoId}`);
                const data = await resp.json();
                this.jugadoresLocal = data.local;
                this.jugadoresVisita = data.visita;
                
                // Agregar un input vacío por defecto para cada equipo
                this.agregarInputGoleador('local');
                this.agregarInputGoleador('visita');
            } catch (e) { console.error(e); }
            finally { this.cargandoJugadores = false; }
        },

        // Gestión dinámica de inputs de goles
        inputsLocal: [],
        inputsVisita: [],

        agregarInputGoleador(equipo) {
            const nuevoInput = { id: Date.now(), jugadorId: '', cantidad: 1 };
            if (equipo === 'local') this.inputsLocal.push(nuevoInput);
            else this.inputsVisita.push(nuevoInput);
        },

        eliminarInputGoleador(equipo, id) {
            if (equipo === 'local') this.inputsLocal = this.inputsLocal.filter(i => i.id !== id);
            else this.inputsVisita = this.inputsVisita.filter(i => i.id !== id);
        }
     }">

    <!-- CABECERA Y BOTÓN -->
    <div class="flex-between mb-8">
        <div>
            <h2 class="text-neon-purple font-sport" style="font-size: 2.5rem; margin: 0;">
                Control de <span style="color: var(--text-main);">Goleadores</span>
            </h2>
            <p class="text-cyan-tag">Registro histórico de anotaciones por fecha</p>
        </div>

        <button class="btn-neon" @click="modalAbierto = true">
            <i data-lucide="plus-circle"></i> Registrar Goles
        </button>
    </div>

    <!-- TABLA DE DATOS (Sin cambios, ya funciona bien) -->
    <div class="dark-card-form">
        <table class="neon-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Jugador</th>
                    <th>Club</th>
                    <th>Serie</th>
                    <th class="text-center">Goles</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($goleadores)): ?>
                    <?php foreach ($goleadores as $gol): ?>
                        <tr id="gol-row-<?= $gol['id'] ?>">
                            <td class="text-id"><?= date('d/m/Y', strtotime($gol['fecha_calendario'])) ?></td>
                            <td><span class="text-white font-bold"><?= esc($gol['apellidos']) ?></span>,
                                <?= esc($gol['nombres']) ?></td>
                            <td>
                                <div class="flex-align gap-2">
                                    <?php if ($gol['club_logo']): ?>
                                        <img src="<?= base_url('uploads/instituciones/' . $gol['club_logo']) ?>"
                                            style="width: 24px; height: 24px; object-fit: contain;" alt="Logo">
                                    <?php endif; ?>
                                    <span><?= esc($gol['club']) ?></span>
                                </div>
                            </td>
                            <td><span class="badge-serie"><?= esc($gol['serie']) ?></span></td>
                            <td class="text-center"><span class="text-neon-cyan font-sport"
                                    style="font-size: 1.2rem;"><?= $gol['cantidad'] ?></span></td>
                            <td class="text-right">
                                <button class="btn-icon-delete"
                                    hx-delete="<?= base_url('admin/registro/borrarGol/' . $gol['id']) ?>"
                                    hx-confirm="¿Estás seguro?" hx-target="#gol-row-<?= $gol['id'] ?>"
                                    hx-swap="outerHTML swap:1s">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-id italic">No hay goles registrados todavía.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- MODAL COMPLETO CON ALPINE -->
    <div x-show="modalAbierto" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;"
        @keydown.escape.window="modalAbierto = false">
        <div x-show="modalAbierto" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-80 transition-opacity" @click="modalAbierto = false"></div>

        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="modalAbierto" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-lg text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl"
                style="background-color: #1f1f1f; border: 1px solid #444; color: white; z-index: 10000;" @click.stop>

                <!-- Cabecera -->
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-neon-purple font-sport text-xl"><i class="fa-solid fa-futbol me-2"></i> Registro de
                        Planilla</h3>
                    <button @click="modalAbierto = false" class="text-gray-400 hover:text-white"><i
                            data-lucide="x"></i></button>
                </div>

                <!-- Cuerpo del Formulario -->
                <div class="px-4 py-5 sm:p-6 bg-[#1a1a1a]">
                    <form action="<?= base_url('admin/registro/guardarGolesPartido') ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- SELECTS EN CASCADA -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <!-- 1. Fecha -->
                            <div>
                                <label class="block text-sm font-bold text-cyan-tag mb-1">1. Fecha</label>
                                <select name="fecha_id" x-model="fechaId" @change="cargarSeries()"
                                    class="w-full p-2 bg-dark border border-secondary rounded text-white focus:border-neon-purple outline-none">
                                    <option value="">Seleccionar Fecha...</option>
                                    <?php foreach ($fechas as $f): ?>
                                        <option value="<?= $f['id'] ?>"><?= esc($f['nombre_fecha']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- 2. Serie -->
                            <div>
                                <label class="block text-sm font-bold text-cyan-tag mb-1">2. Serie</label>
                                <select name="serie_id" x-model="serieId" :disabled="!fechaId || cargandoSeries"
                                    @change="cargarPartidos()"
                                    class="w-full p-2 bg-dark border border-secondary rounded text-white focus:border-neon-purple outline-none disabled:opacity-50">
                                    <option value="" x-text="cargandoSeries ? 'Cargando...' : 'Seleccionar Serie...'">
                                    </option>
                                    <template x-for="serie in series" :key="serie.id">
                                        <option :value="serie.id" x-text="serie.nombre"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- 3. Partido -->
                            <div>
                                <label class="block text-sm font-bold text-cyan-tag mb-1">3. Partido</label>
                                <select name="partido_id" x-model="partidoId" :disabled="!serieId || cargandoPartidos"
                                    @change="cargarJugadores()"
                                    class="w-full p-2 bg-dark border border-secondary rounded text-white focus:border-neon-purple outline-none disabled:opacity-50">
                                    <option value=""
                                        x-text="cargandoPartidos ? 'Cargando...' : 'Seleccionar Partido...'"></option>
                                    <template x-for="partido in partidos" :key="partido.id">
                                        <option :value="partido.id"
                                            x-text="partido.local_nombre + ' vs ' + partido.visitante_nombre"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- ÁREA DE JUGADORES (Solo visible si hay partido seleccionado) -->
                        <div x-show="partidoId" x-transition class="border-t border-gray-700 pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                <!-- Equipo Local -->
                                <div>
                                    <h4 class="text-neon-cyan font-bold mb-2 border-b border-gray-700 pb-2">Equipo Local
                                    </h4>
                                    <template x-for="(input, index) in inputsLocal" :key="input.id">
                                        <div class="flex gap-2 mb-2">
                                            <select :name="'goles_local_ids[]'" x-model="input.jugadorId"
                                                class="flex-1 p-2 bg-dark border border-secondary rounded text-white text-sm">
                                                <option value="">Seleccionar Jugador...</option>
                                                <template x-for="jugador in jugadoresLocal" :key="jugador.id">
                                                    <option :value="jugador.id"
                                                        x-text="jugador.nombres + ' ' + jugador.apellidos"></option>
                                                </template>
                                            </select>
                                            <input type="number" :name="'cant_local_goles[]'" x-model="input.cantidad"
                                                min="1"
                                                class="w-16 p-2 bg-dark border border-secondary rounded text-white text-center">
                                            <button type="button" @click="eliminarInputGoleador('local', input.id)"
                                                class="text-red-500 hover:text-red-400"><i data-lucide="trash-2"
                                                    class="w-5 h-5"></i></button>
                                        </div>
                                    </template>
                                    <button type="button" @click="agregarInputGoleador('local')"
                                        class="mt-2 text-xs text-neon-cyan hover:underline">+ Añadir otro goleador
                                        local</button>
                                </div>

                                <!-- Equipo Visita -->
                                <div>
                                    <h4 class="text-red-400 font-bold mb-2 border-b border-gray-700 pb-2">Equipo Visita
                                    </h4>
                                    <template x-for="(input, index) in inputsVisita" :key="input.id">
                                        <div class="flex gap-2 mb-2">
                                            <select :name="'goles_visita_ids[]'" x-model="input.jugadorId"
                                                class="flex-1 p-2 bg-dark border border-secondary rounded text-white text-sm">
                                                <option value="">Seleccionar Jugador...</option>
                                                <template x-for="jugador in jugadoresVisita" :key="jugador.id">
                                                    <option :value="jugador.id"
                                                        x-text="jugador.nombres + ' ' + jugador.apellidos"></option>
                                                </template>
                                            </select>
                                            <input type="number" :name="'cant_visita_goles[]'" x-model="input.cantidad"
                                                min="1"
                                                class="w-16 p-2 bg-dark border border-secondary rounded text-white text-center">
                                            <button type="button" @click="eliminarInputGoleador('visita', input.id)"
                                                class="text-red-500 hover:text-red-400"><i data-lucide="trash-2"
                                                    class="w-5 h-5"></i></button>
                                        </div>
                                    </template>
                                    <button type="button" @click="agregarInputGoleador('visita')"
                                        class="mt-2 text-xs text-red-400 hover:underline">+ Añadir otro goleador
                                        visita</button>
                                </div>

                            </div>
                        </div>

                        <!-- Footer del Modal -->
                        <div class="flex justify-end gap-2 mt-6 pt-4 border-t border-gray-700">
                            <button type="button" @click="modalAbierto = false"
                                class="px-4 py-2 text-gray-300 hover:text-white border border-gray-600 rounded hover:bg-gray-800 transition">Cancelar</button>
                            <button type="submit"
                                class="btn-neon px-4 py-2 rounded shadow-lg shadow-purple-500/20">Guardar
                                Planilla</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    /* Estilos Base (Mismos que antes) */
    .neon-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--text-main);
    }

    .neon-table th {
        text-align: left;
        padding: 1rem;
        border-bottom: 2px solid rgba(139, 92, 246, 0.3);
        color: var(--neon-purple);
        font-family: 'SportFont', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .neon-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .neon-table tr:hover {
        background: rgba(139, 92, 246, 0.05);
    }

    .badge-serie {
        background: rgba(0, 255, 242, 0.1);
        color: var(--neon-cyan);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        border: 1px solid rgba(0, 255, 242, 0.3);
    }

    .btn-icon-delete {
        background: none;
        border: none;
        color: #ff4444;
        cursor: pointer;
        padding: 5px;
        transition: all 0.3s;
    }

    .btn-icon-delete:hover {
        color: #ff0000;
        transform: scale(1.2);
        filter: drop-shadow(0 0 5px #ff0000);
    }

    /* Utilidades Tailwind-like para el modal si no las tienes en tu CSS global */
    .grid {
        display: grid;
    }

    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .gap-4 {
        gap: 1rem;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-8 {
        gap: 2rem;
    }

    .flex-1 {
        flex: 1 1 0%;
    }

    .w-16 {
        width: 4rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .border-t {
        border-top-width: 1px;
    }

    .border-b {
        border-bottom-width: 1px;
    }

    .pt-4 {
        padding-top: 1rem;
    }

    .pb-2 {
        padding-bottom: 0.5rem;
    }

    .mt-2 {
        margin-top: 0.5rem;
    }

    .mt-6 {
        margin-top: 1.5rem;
    }

    .mb-6 {
        margin-bottom: 1.5rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-1 {
        margin-bottom: 0.25rem;
    }

    .hover\:underline:hover {
        text-decoration: underline;
    }

    @media (min-width: 768px) {
        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>