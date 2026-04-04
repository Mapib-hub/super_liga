<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Instituciones</h2>
        <p class="text-slate-500 text-sm">Configuración y listado de clubes</p>
    </div>
    <a href="<?= base_url('admin/instituciones/crear') ?>" hx-get="<?= base_url('admin/instituciones/crear') ?>"
        hx-target="#main-content" hx-push-url="true"
        class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black uppercase text-xs tracking-widest transition-all shadow-lg shadow-indigo-100 active:scale-95">
        Nuevo Club
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr class="text-[10px] uppercase tracking-widest text-slate-400 font-black">
                <th class="px-6 py-4">Club</th>
                <th class="px-6 py-4">Estadio</th>
                <th class="px-6 py-4">Contacto</th>
                <th class="px-6 py-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            <?php foreach ($instituciones as $inst): ?>
            <tr class="hover:bg-slate-50/50 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <img src="<?= base_url('uploads/logos/' . ($inst['logo_path'] ?: 'default.png')) ?>"
                            class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm">
                        <div>
                            <p class="font-bold text-slate-800 leading-tight"><?= esc($inst['nombre']) ?></p>
                            <p class="text-[10px] text-slate-400 font-medium"><?= esc($inst['razon_social']) ?></p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm font-semibold text-slate-600"><?= esc($inst['estadio']) ?></span>
                </td>
                <td class="px-6 py-4 text-xs text-slate-500">
                    <?= esc($inst['nombre_contacto']) ?><br>
                    <span class="text-indigo-500 font-bold"><?= esc($inst['telefono']) ?></span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="<?= base_url('admin/instituciones/editar/' . $inst['id']) ?>"
                            hx-get="<?= base_url('admin/instituciones/editar/' . $inst['id']) ?>"
                            hx-target="#main-content" hx-push-url="true"
                            class="p-2 hover:bg-white rounded-lg text-blue-600 shadow-sm border border-transparent hover:border-blue-100 transition">
                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                        </a>

                        <a hx-get="<?= base_url('admin/instituciones/eliminar/' . $inst['id']) ?>"
                            hx-confirm="Vas a eliminar a <?= $inst['nombre'] ?>. Esta acción no se puede deshacer."
                            hx-target="#main-content"
                            class="p-2 hover:bg-white rounded-lg text-red-500 shadow-sm border border-transparent hover:border-red-100 transition cursor-pointer">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </a>
                        <div id="delete-trigger" hx-target="#main-content"></div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
lucide.createIcons();
</script>