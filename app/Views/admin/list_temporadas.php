<style>
/* Contenedor Principal */
#contenedor-temporadas {
    padding-top: 20px;
    display: flex;
    flex-wrap: wrap;
}

.tarjeta_cont {
    width: 33.33%;
    /* Ajustado para que entren 3 perfecto */
    padding: 10px;
    box-sizing: border-box;
}

.tarjeta_cont .card {
    background-color: #1a1a1a;
    border: 1px solid #333;
    border-radius: 15px;
    color: #eee;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    min-height: 240px;
    overflow: hidden;
}

/* Hover de la Tarjeta */
.tarjeta_cont .card:hover {
    transform: translateY(-10px);
    border-color: #00d4ff;
    box-shadow: 0 0 20px rgba(0, 212, 255, 0.4);
}

/* Tarjeta Actual (Fucsia) */
.tarjeta_cont .card.border-success {
    border: 2px solid #ff00ff !important;
    background: linear-gradient(145deg, #1a1a1a, #2d0221);
    box-shadow: 0 0 15px rgba(255, 0, 255, 0.3);
}

/* Status Actual Badge */
.btn-actual-status {
    display: inline-block;
    background-color: rgba(255, 0, 255, 0.1);
    color: #ff00ff;
    border: 1px solid #ff00ff;
    border-radius: 50px;
    padding: 5px 20px;
    font-size: 0.75rem;
    font-weight: 800;
    animation: pulse-status 2s infinite;
}

@keyframes pulse-status {

    0%,
    100% {
        box-shadow: 0 0 5px #ff00ff;
    }

    50% {
        box-shadow: 0 0 20px #ff00ff;
        color: #fff;
    }
}

/* --- ESTILO DE BOTONES NEON --- */

.tarjeta_cont .btn-sm {
    border-radius: 6px;
    text-transform: uppercase;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1px;
    padding: 6px 12px;
    transition: all 0.3s ease;
    background: transparent;
}

/* Activar (Verde) */
.btn-outline-success {
    border: 1px solid #00ff88 !important;
    color: #00ff88 !important;
}

.btn-outline-success:hover {
    background: #00ff88 !important;
    color: #000 !important;
    box-shadow: 0 0 15px #00ff88, 0 0 30px rgba(0, 255, 136, 0.5) !important;
}

/* Editar (Azul) */
.btn-outline-primary {
    border: 1px solid #00d4ff !important;
    color: #00d4ff !important;
}

.btn-outline-primary:hover {
    background: #00d4ff !important;
    color: #000 !important;
    box-shadow: 0 0 15px #00d4ff, 0 0 30px rgba(0, 212, 255, 0.5) !important;
}

/* Borrar (Rojo) */
.btn-outline-danger {
    border: 1px solid #ff4b2b !important;
    color: #ff4b2b !important;
}

.btn-outline-danger:hover {
    background: #ff4b2b !important;
    color: #fff !important;
    box-shadow: 0 0 15px #ff4b2b, 0 0 30px rgba(255, 75, 43, 0.5) !important;
}

.mt-auto {
    margin-top: auto !important;
}
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-alt"></i> <?= $titulo ?></h2>
        <button hx-get="<?= base_url('admin/temporadas/crear') ?>" hx-target="#main-content" hx-push-url="true"
            class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Temporada
        </button>
    </div>

    <div class="row" id="contenedor-temporadas">
        <?php if (empty($temporadas)): ?>
        <div class="col-12 text-center">
            <div class="alert alert-info">No hay temporadas.</div>
        </div>
        <?php else: ?>
        <?php foreach ($temporadas as $t): ?>
        <div class="tarjeta_cont">
            <div class="card h-100 <?= $t['actual'] ? 'border-success shadow-sm' : '' ?>">
                <div class="card-body d-flex flex-column text-center">
                    <?php if ($t['actual']): ?>
                    <div class="mb-3">
                        <span class="btn-actual-status">
                            <i class="fas fa-circle-notch fa-spin me-1"></i> ACTUAL
                        </span>
                    </div>
                    <?php endif; ?>

                    <h4 class="fw-bold"><?= esc($t['nombre_temporada']) ?></h4>
                    <p class="text-muted mb-4">ID: <?= $t['id'] ?></p>

                    <div class="mt-auto d-flex justify-content-center gap-2">
                        <?php if (!$t['actual']): ?>
                        <button hx-get="<?= base_url('admin/temporadas/activar/' . $t['id']) ?>"
                            hx-target="#main-content" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-bolt"></i> Activar
                        </button>
                        <?php endif; ?>

                        <button hx-get="<?= base_url('admin/temporadas/editar/' . $t['id']) ?>"
                            hx-target="#main-content" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Editar
                        </button>

                        <button hx-delete="<?= base_url('admin/temporadas/eliminar/' . $t['id']) ?>"
                            hx-target="#main-content" hx-confirm="¿Borrar temporada?"
                            class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i> Borrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>