<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ manutencoes: Array, maquinas: Array });
const modalAberto = ref(false);

const form = useForm({
    maquina_id: props.maquinas[0]?.id,
    descricao: '',
    data_manutencao: new Date().toISOString().split('T')[0],
    custo: '',
    tipo: 'preventiva'
});

function salvar() {
    form.post('/manutencoes', {
        onSuccess: () => {
            modalAberto.value = false;
            form.reset();
        }
    });
}

const formatMoney = (val) => parseFloat(val).toLocaleString('pt-BR', {style:'currency', currency:'BRL'});
const formatDate = (date) => new Date(date).toLocaleDateString('pt-BR');
</script>

<template>
    <Head title="Manutenções" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Histórico de Manutenções</h2>
                <button @click="modalAberto = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                    + Registrar Manutenção
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Data</th>
                                <th class="px-6 py-3">Máquina</th>
                                <th class="px-6 py-3">Serviço</th>
                                <th class="px-6 py-3">Tipo</th>
                                <th class="px-6 py-3 text-right">Custo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="m in manutencoes" :key="m.id" class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4">{{ formatDate(m.data_manutencao) }}</td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ m.maquina.nome }}</td>
                                <td class="px-6 py-4">{{ m.descricao }}</td>
                                <td class="px-6 py-4">
                                    <span :class="m.tipo == 'preventiva' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 rounded-full text-xs font-bold uppercase">
                                        {{ m.tipo }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">{{ formatMoney(m.custo) }}</td>
                            </tr>
                            <tr v-if="manutencoes.length === 0">
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                    Nenhuma manutenção registrada no histórico.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="modalAberto" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Lançar Manutenção</h3>
                    <button @click="modalAberto = false" class="text-gray-400">✕</button>
                </div>
                <form @submit.prevent="salvar" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Máquina</label>
                        <select v-model="form.maquina_id" class="w-full border-gray-300 rounded-md">
                            <option v-for="mq in maquinas" :key="mq.id" :value="mq.id">{{ mq.nome }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">O que foi feito?</label>
                        <input type="text" v-model="form.descricao" class="w-full border-gray-300 rounded-md" placeholder="Ex: Troca de filtros" required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Data</label>
                            <input type="date" v-model="form.data_manutencao" class="w-full border-gray-300 rounded-md" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Tipo</label>
                            <select v-model="form.tipo" class="w-full border-gray-300 rounded-md">
                                <option value="preventiva">Preventiva (Revisão)</option>
                                <option value="corretiva">Corretiva (Quebrou)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Custo Total (R$)</label>
                        <input type="number" step="0.01" v-model="form.custo" class="w-full border-gray-300 rounded-md" required />
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700">Salvar Registro</button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>