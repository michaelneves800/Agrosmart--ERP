<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({ talhao: Object });

const form = useForm({
    cultura: '',
    variedade: '',
    data_plantio: new Date().toISOString().split('T')[0]
});

function salvarPlantio() {
    form.post(`/talhoes/${props.talhao.id}/plantar`, {
        onSuccess: () => alert('🌱 Plantio registrado com sucesso!')
    });
}
</script>

<template>
    <Head title="Registrar Plantio" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Novo Plantio: {{ talhao.nome }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <form @submit.prevent="salvarPlantio" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">O que você vai plantar?</label>
                            <select v-model="form.cultura" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                <option value="" disabled>Selecione...</option>
                                <option>Soja</option>
                                <option>Milho</option>
                                <option>Trigo</option>
                                <option>Café</option>
                                <option>Feijão</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Variedade / Semente</label>
                            <input type="text" v-model="form.variedade" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: Intacta RR2 PRO" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data do Plantio</label>
                            <input type="date" v-model="form.data_plantio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4">
                            <Link :href="`/fazendas/${talhao.fazenda_id}`" class="text-gray-600 hover:text-gray-900 font-bold text-sm">Cancelar</Link>
                            
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition transform active:scale-95">
                                🌱 Confirmar Plantio
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>