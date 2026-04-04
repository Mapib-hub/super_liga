<div class="mb-8">
    <h2 class="text-3xl font-black text-slate-800">Estadísticas Generales</h2>
    <p class="text-slate-500 italic">Resumen de la <?= $tempActual['nombre_temporada'] ?></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
        <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center shadow-inner">
            <i data-lucide="zap" class="w-8 h-8"></i>
        </div>
        <div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Goles</p>
            <p class="text-3xl font-black text-slate-800"><?= $totalGoles ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
        <div
            class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner">
            <i data-lucide="calendar-check" class="w-8 h-8"></i>
        </div>
        <div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Avance</p>
            <p class="text-3xl font-black text-slate-800"><?= $porcentaje ?>%</p>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-slate-800">Tabla General de Instituciones</h3>
            <button hx-get="/admin/tablas/general" hx-target="#tabla-container"
                class="text-indigo-600 text-sm font-bold">Actualizar</button>
        </div>

        <div id="tabla-container">
            <p class="text-slate-400 text-sm">Selecciona una serie para ver detalles...</p>
        </div>
    </div>

    <div class="bg-indigo-900 p-8 rounded-3xl shadow-xl text-white">
        <h3 class="text-xl font-bold mb-6 italic underline decoration-indigo-400">Goles por Serie</h3>
        <ul class="space-y-4">
            <?php foreach ($desgloseGoles as $g): ?>
            <li class="flex justify-between items-center border-b border-indigo-800 pb-2">
                <span class="opacity-80"><?= $g['nombre_serie'] ?></span>
                <span class="font-black text-lg"><?= $g['goles'] ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>