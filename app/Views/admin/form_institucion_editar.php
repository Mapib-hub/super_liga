<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Editar <span
                    class="text-indigo-600">Institución</span></h2>
            <p class="text-slate-500 text-sm font-medium">Modificar registro oficial del club</p>
        </div>
        <a href="<?= base_url('admin/instituciones') ?>" hx-get="<?= base_url('admin/instituciones') ?>"
            hx-target="#main-content" hx-push-url="true"
            class="flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold transition cursor-pointer">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Volver al listado
        </a>
    </div>

    <form hx-post="<?= base_url('admin/instituciones/actualizar/' . $inst['id']) ?>" hx-target="#main-content"
        hx-encoding="multipart/form-data" enctype="multipart/form-data"
        class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="space-y-6">
                <h3 class="text-indigo-600 font-black text-xs uppercase tracking-widest border-b pb-2">Identidad</h3>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Nombre del Club</label>
                    <input type="text" name="nombre" value="<?= old('nombre', $inst['nombre']) ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition <?= (isset($validation) && $validation->hasError('nombre')) ? 'ring-2 ring-red-500' : '' ?>">
                    <?php if (isset($validation) && $validation->hasError('nombre')): ?>
                    <p class="text-red-500 text-[10px] mt-1 font-bold italic uppercase">
                        <?= $validation->getError('nombre') ?></p>
                    <?php endif; ?>
                </div>

                <div class="space-y-6 text-center" x-data="{ 
                        imageUrl: null, 
                        originalUrl: '<?= (!empty($inst['logo_path'])) ? base_url('uploads/logos/' . $inst['logo_path']) : '' ?>' 
                     }">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 text-left">
                        Logo de la Institución
                    </label>

                    <div
                        class="bg-slate-50 border-2 border-dashed <?= (isset($validation) && $validation->hasError('logo')) ? 'border-red-300' : 'border-slate-200' ?> rounded-3xl p-8 relative overflow-hidden group min-h-[160px] flex items-center justify-center">

                        <template x-if="!imageUrl && !originalUrl">
                            <div class="space-y-2">
                                <i data-lucide="image-plus" class="w-10 h-10 mx-auto text-slate-300"></i>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Click para subir logo</p>
                            </div>
                        </template>

                        <template x-if="originalUrl && !imageUrl">
                            <img :src="originalUrl"
                                class="mx-auto h-32 w-32 object-contain rounded-xl shadow-lg transition-all group-hover:scale-105">
                        </template>

                        <template x-if="imageUrl">
                            <img :src="imageUrl"
                                class="mx-auto h-32 w-32 object-contain rounded-xl shadow-lg transition-all group-hover:scale-105 border-4 border-indigo-200">
                        </template>

                        <input type="file" name="logo" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imageUrl = e.target.result; }; reader.readAsDataURL(file); }">
                    </div>
                    <?php if (isset($validation) && $validation->hasError('logo')): ?>
                    <p class="text-red-500 text-[10px] mt-1 font-bold italic uppercase text-left">
                        <?= $validation->getError('logo') ?></p>
                    <?php endif; ?>

                    <button type="button" x-show="imageUrl" @click="imageUrl = null; $refs.logoInput.value = ''"
                        class="text-[10px] font-black text-red-500 uppercase tracking-tighter hover:underline">
                        Quitar cambios
                    </button>
                    <p x-show="originalUrl && !imageUrl" class="text-[10px] text-slate-400 italic font-medium">Click
                        sobre la imagen para cambiarla.</p>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-indigo-600 font-black text-xs uppercase tracking-widest border-b pb-2">Ubicación</h3>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Estadio Principal</label>
                    <input type="text" name="estadio" value="<?= old('estadio', $inst['estadio']) ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Link Google Maps</label>
                    <input type="text" name="maps" placeholder="https://goo.gl/maps/..."
                        value="<?= old('maps', $inst['maps']) ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-indigo-600 font-black text-xs uppercase tracking-widest border-b pb-2">Contacto</h3>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Email Oficial</label>
                    <input type="email" name="email_contacto"
                        value="<?= old('email_contacto', $inst['email_contacto']) ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition <?= (isset($validation) && $validation->hasError('email_contacto')) ? 'ring-2 ring-red-500' : '' ?>">
                    <?php if (isset($validation) && $validation->hasError('email_contacto')): ?>
                    <p class="text-red-500 text-[10px] mt-1 font-bold italic uppercase">
                        <?= $validation->getError('email_contacto') ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Teléfono</label>
                    <input type="text" name="telefono" value="<?= old('telefono', $inst['telefono']) ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition">
                </div>
            </div>

        </div>

        <div class="mt-8">
            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Historia / Descripción</label>
            <textarea name="descripcion" rows="3"
                class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition"><?= old('descripcion', $inst['descripcion']) ?></textarea>
        </div>

        <div class="mt-10 flex justify-end gap-3">
            <a href="<?= base_url('admin/instituciones') ?>" hx-get="<?= base_url('admin/instituciones') ?>"
                hx-target="#main-content"
                class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-8 py-4 rounded-2xl font-black uppercase tracking-tighter transition cursor-pointer flex items-center gap-3">
                Cancelar
            </a>

            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-tighter transition shadow-xl flex items-center gap-3 shadow-indigo-200">
                <i data-lucide="save" class="w-5 h-5"></i>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
lucide.createIcons();
</script>