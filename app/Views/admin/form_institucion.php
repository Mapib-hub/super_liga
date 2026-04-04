<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Nueva <span
                    class="text-indigo-600">Institución</span></h2>
            <p class="text-slate-500 text-sm font-medium">Registro oficial de clubes</p>
        </div>
        <a href="<?= base_url('admin/instituciones') ?>" hx-get="<?= base_url('admin/instituciones') ?>"
            hx-target="#main-content" hx-push-url="true"
            class="flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold transition cursor-pointer">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Volver al listado
        </a>
    </div>

    <form hx-post="<?= base_url('admin/instituciones/guardar') ?>" hx-target="#main-content"
        hx-encoding="multipart/form-data" enctype="multipart/form-data"
        class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="space-y-6">
                <h3 class="text-indigo-600 font-black text-xs uppercase tracking-widest border-b pb-2">Identidad</h3>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Nombre del Club</label>
                    <input type="text" name="nombre" value="<?= old('nombre') ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition <?= (isset($validation) && $validation->hasError('nombre')) ? 'ring-2 ring-red-500' : '' ?>">
                    <?php if (isset($validation) && $validation->hasError('nombre')): ?>
                    <p class="text-red-500 text-[10px] mt-1 font-bold italic uppercase">
                        <?= $validation->getError('nombre') ?></p>
                    <?php endif; ?>
                </div>

                <div class="space-y-6 text-center" x-data="{ imageUrl: null }">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 text-left">
                        Logo de la Institución
                    </label>

                    <div
                        class="bg-slate-50 border-2 border-dashed <?= (isset($validation) && $validation->hasError('logo')) ? 'border-red-300' : 'border-slate-200' ?> rounded-3xl p-8 relative overflow-hidden group">
                        <template x-if="!imageUrl">
                            <div class="space-y-2">
                                <i data-lucide="image-plus" class="w-10 h-10 mx-auto text-slate-300"></i>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Click para subir logo</p>
                            </div>
                        </template>

                        <template x-if="imageUrl">
                            <img :src="imageUrl"
                                class="mx-auto h-32 w-32 object-contain rounded-xl shadow-lg transition-all group-hover:scale-105">
                        </template>

                        <input type="file" name="logo" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imageUrl = e.target.result; }; reader.readAsDataURL(file); }">
                    </div>
                    <?php if (isset($validation) && $validation->hasError('logo')): ?>
                    <p class="text-red-500 text-[10px] mt-1 font-bold italic uppercase text-left">
                        <?= $validation->getError('logo') ?></p>
                    <?php endif; ?>

                    <button type="button" x-show="imageUrl" @click="imageUrl = null"
                        class="text-[10px] font-black text-red-500 uppercase tracking-tighter hover:underline">
                        Quitar imagen
                    </button>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-indigo-600 font-black text-xs uppercase tracking-widest border-b pb-2">Ubicación</h3>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Estadio Principal</label>
                    <input type="text" name="estadio" value="<?= old('estadio') ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Link Google Maps</label>
                    <input type="text" name="maps" placeholder="https://goo.gl/maps/..." value="<?= old('maps') ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition">
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-indigo-600 font-black text-xs uppercase tracking-widest border-b pb-2">Contacto</h3>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Email Oficial</label>
                    <input type="email" name="email_contacto" value="<?= old('email_contacto') ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition <?= (isset($validation) && $validation->hasError('email_contacto')) ? 'ring-2 ring-red-500' : '' ?>">
                    <?php if (isset($validation) && $validation->hasError('email_contacto')): ?>
                    <p class="text-red-500 text-[10px] mt-1 font-bold italic uppercase">
                        <?= $validation->getError('email_contacto') ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Teléfono</label>
                    <input type="text" name="telefono" value="<?= old('telefono') ?>"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition">
                </div>
            </div>

        </div>

        <div class="mt-8">
            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Historia / Descripción</label>
            <textarea name="descripcion" rows="3"
                class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition"><?= old('descripcion') ?></textarea>
        </div>

        <div class="mt-10 flex justify-end">
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-tighter transition shadow-xl flex items-center gap-3">
                <i data-lucide="save" class="w-5 h-5"></i>
                Guardar Institución
            </button>
        </div>
    </form>
</div>

<script>
lucide.createIcons();
</script>