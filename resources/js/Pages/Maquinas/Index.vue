<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ maquinas: Array, fazendas: Array });
const modalAberto = ref(false);

const form = useForm({
    fazenda_id: props.fazendas[0]?.id, // Seleciona a primeira auto
    nome: '',
    tipo: 'Trator',
    modelo: '',
    horimetro_atual: ''
});

function salvarMaquina() {
    form.post('/maquinas', {
        onSuccess: () => {
            modalAberto.value = false;
            form.reset();
        }
    });
}
</script>

<template>
    <Head title="Frota de Máquinas" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestão de Maquinário</h2>
                <button @click="modalAberto = true" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-emerald-700 transition">
                    + Nova Máquina
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="mq in maquinas" :key="mq.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-gray-800 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-gray-100 p-3 rounded-full text-2xl">
                                <span v-if="mq.tipo == 'Trator'">🚜</span>
                                <span v-else-if="mq.tipo == 'Colheitadeira'">🌾</span>
                                <span v-else>🚚</span>
                            </div>
                            <span class="px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-800 uppercase">{{ mq.status }}</span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900">{{ mq.nome }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ mq.tipo }} - {{ mq.modelo }}</p>
                        
                        <div class="border-t pt-4 flex justify-between items-center">
                            <span class="text-gray-400 text-xs uppercase font-bold">Horímetro</span>
                            <span class="text-xl font-bold text-gray-800">{{ mq.horimetro_atual }} <span class="text-sm font-normal">hrs</span></span>
                        </div>
                    </div>

                    <div v-if="maquinas.length === 0" class="col-span-3 text-center py-10 bg-white rounded-lg border-2 border-dashed border-gray-300">
                        <p class="text-gray-400 text-lg">Nenhuma máquina cadastrada.</p>
                        <button @click="modalAberto = true" class="text-emerald-600 font-bold mt-2 hover:underline">Cadastre seu primeiro trator</button>
                    </div>
                </div>

            </div>
        </div>

        <div v-if="modalAberto" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Nova Máquina</h3>
                    <button @click="modalAberto = false" class="text-gray-400">✕</button>
                </div>
                <form @submit.prevent="salvarMaquina" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Nome / Apelido</label>
                        <input type="text" v-model="form.nome" class="w-full border-gray-300 rounded-md" placeholder="Ex: John Deere 7J" required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Tipo</label>
                            <select v-model="form.tipo" class="w-full border-gray-300 rounded-md">
                                <option>Trator</option><option>Colheitadeira</option><option>Pulverizador</option><option>Caminhão</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Modelo</label>
                            <input type="text" v-model="form.modelo" class="w-full border-gray-300 rounded-md" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Horímetro Atual (Horas)</label>
                        <input type="number" step="0.1" v-model="form.horimetro_atual" class="w-full border-gray-300 rounded-md" required />
                    </div>
                    
                    <div v-if="fazendas.length > 1">
                        <label class="block text-sm font-medium">Fazenda</label>
                        <select v-model="form.fazenda_id" class="w-full border-gray-300 rounded-md">
                            <option v-for="f in fazendas" :key="f.id" :value="f.id">{{ f.nome }}</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-lg hover:bg-emerald-700">Salvar Frota</button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>