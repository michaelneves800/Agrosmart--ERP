<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    fazenda: Object
});

const form = useForm({
    nome: props.fazenda.nome,
    cidade: props.fazenda.cidade,
    estado: props.fazenda.estado,
});

const submit = () => {
    form.put(route('fazendas.update', props.fazenda.id));
};
</script>

<template>
    <Head title="Editar Fazenda" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase tracking-tight">Editar Propriedade</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-gray-100">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nome da Fazenda</label>
                            <input type="text" v-model="form.nome" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 font-bold text-gray-700" required />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Cidade</label>
                                <input type="text" v-model="form.cidade" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 font-bold text-gray-700" required />
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Estado (UF)</label>
                                <input type="text" v-model="form.estado" maxlength="2" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 font-bold text-gray-700 uppercase" required />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit" :disabled="form.processing" class="flex-1 bg-emerald-600 text-white font-black py-4 rounded-xl hover:bg-emerald-700 transition-all uppercase tracking-widest text-xs shadow-lg shadow-emerald-100">
                                Salvar Alterações
                            </button>
                            <Link href="/dashboard" class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                                Cancelar
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>