<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    extrato: Object,
    resumo: Object
});

// Função para formatar dinheiro (R$)
const formatMoney = (value) => {
    return parseFloat(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
};

// Função para formatar data
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
};
</script>

<template>
    <Head title="Financeiro" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestão Financeira</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                        <div class="flex justify-between items-center">
                            <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Receitas (Vendas)</div>
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] px-2 py-1 rounded-full font-bold">Entradas</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-emerald-600">{{ formatMoney(resumo.receitas) }}</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                        <div class="flex justify-between items-center">
                            <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Despesas (Insumos)</div>
                            <span class="bg-red-100 text-red-800 text-[10px] px-2 py-1 rounded-full font-bold">Saídas</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-red-600">{{ formatMoney(resumo.despesas) }}</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4" :class="resumo.lucro >= 0 ? 'border-blue-500' : 'border-orange-500'">
                        <div class="flex justify-between items-center">
                            <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Resultado Operacional</div>
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-bold">Lucro/Prejuízo</span>
                        </div>
                        <div class="mt-2 text-3xl font-bold" :class="resumo.lucro >= 0 ? 'text-blue-600' : 'text-orange-600'">
                            {{ formatMoney(resumo.lucro) }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50 flex justify-between items-center">
                            <h3 class="font-bold text-emerald-800 flex items-center gap-2">
                                <span>📈</span> Histórico de Vendas
                            </h3>
                            <a href="/financeiro/exportar-vendas" class="bg-white border border-gray-200 text-gray-600 hover:bg-emerald-600 hover:text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase transition shadow-sm flex items-center gap-1">
                                📊 Imprimir Relatório
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-600">
                                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500">
                                    <tr>
                                        <th class="px-4 py-3">Data</th>
                                        <th class="px-4 py-3">Descrição</th>
                                        <th class="px-4 py-3 text-right">Valor</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="venda in extrato.vendas" :key="venda.id" class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ formatDate(venda.data_venda) }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-gray-900">Venda de {{ venda.cultura }}</div>
                                            <div class="text-[10px] text-gray-400 uppercase tracking-tight">{{ venda.fazenda.nome }} • {{ venda.comprador }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-emerald-600 whitespace-nowrap">+ {{ formatMoney(venda.valor_total) }}</td>
                                    </tr>
                                    <tr v-if="extrato.vendas.length === 0">
                                        <td colspan="3" class="px-4 py-6 text-center text-gray-400">Nenhuma venda registrada.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex justify-between items-center">
                            <h3 class="font-bold text-red-800 flex items-center gap-2">
                                <span>📉</span> Histórico de Despesas
                            </h3>
                            <a href="/financeiro/exportar-despesas" class="bg-white border border-gray-200 text-gray-600 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase transition shadow-sm flex items-center gap-1">
                                📊 Imprimir Relatório
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-600">
                                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500">
                                    <tr>
                                        <th class="px-4 py-3">Data Reg.</th>
                                        <th class="px-4 py-3">Insumo</th>
                                        <th class="px-4 py-3 text-right">Custo Total</th>
                                    </tr>
                                </thead>
                               <tbody class="divide-y divide-gray-100">
                                    <tr v-for="item in extrato.despesas" :key="item.id" class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ formatDate(item.data) }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-gray-900">{{ item.titulo }}</div>
                                            <div class="text-[10px] text-gray-400 flex items-center gap-1 uppercase tracking-tight">
                                                <span>{{ item.tipo === 'manutencao' ? '🔧' : '🧪' }}</span>
                                                {{ item.subtitulo }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-red-600 whitespace-nowrap">
                                            - {{ formatMoney(item.valor) }}
                                        </td>
                                    </tr>
                                    <tr v-if="extrato.despesas.length === 0">
                                        <td colspan="3" class="px-4 py-6 text-center text-gray-400">
                                            Nenhuma despesa registrada.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>