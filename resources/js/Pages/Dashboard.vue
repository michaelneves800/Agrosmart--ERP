<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Doughnut } from 'vue-chartjs';
import { ref, computed } from 'vue';

ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
    kpis: Object,
    grafico: Object,
    lista_fazendas: Array,
    estoque: Array,
    clima_inicial: Object
});

// --- FUNÇÃO PARA FORMATAR MOEDA (R$) ---
const formatarDinheiro = (valor) => {
    return Number(valor).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });
};

// --- CONTROLE DE VISUALIZAÇÃO DO ESTOQUE ---
const mostrarTodosEstoques = ref(false);

const estoquesVisiveis = computed(() => {
    if (mostrarTodosEstoques.value) {
        return props.estoque;
    }
    return props.estoque.slice(0, 2);
});

// --- CONTROLE DE VENDAS ---
const modalVendaAberto = ref(false);
const itemVendaSelecionado = ref(null); 
const formVenda = useForm({
    fazenda_id: null,
    cultura: '',
    comprador: '',
    quantidade: '',
    preco_unitario: '',
    data_venda: new Date().toISOString().split('T')[0]
});

function abrirVenda(item) {
    if(props.lista_fazendas.length > 0) {
        formVenda.fazenda_id = props.lista_fazendas[0].id; 
    }
    itemVendaSelecionado.value = item;
    formVenda.cultura = item.cultura;
    modalVendaAberto.value = true;
}

function realizarVenda() {
    formVenda.post('/vendas', {
        onSuccess: () => {
            modalVendaAberto.value = false;
            formVenda.reset();
        },
        onError: (errors) => {
            if(errors.erro) alert(errors.erro);
        }
    });
}

// --- CONFIGURAÇÃO DO GRÁFICO ---
const chartData = {
    labels: props.grafico?.labels || [],
    datasets: [{
        backgroundColor: ['#10B981', '#F59E0B', '#3B82F6', '#EF4444'], 
        data: props.grafico?.data || []
    }]
};

const chartOptions = { 
    responsive: true, 
    maintainAspectRatio: false, 
    plugins: { 
        legend: { 
            position: 'bottom',
            labels: { font: { weight: 'bold', size: 10 } }
        } 
    } 
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl text-gray-800 leading-tight uppercase tracking-tight">Visão Geral da Operação</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg rounded-xl p-5 text-white flex flex-col justify-between h-32 relative">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest opacity-80">Clima Agora</p>
                                <p class="text-[10px] font-bold uppercase tracking-tighter">{{ lista_fazendas[0]?.cidade || 'Localização' }}</p>
                            </div>
                            <span class="text-3xl animate-pulse">☀️</span>
                        </div>
                        <div class="flex items-end justify-between">
                            <div class="text-3xl font-black tracking-tighter">24°C</div>
                            <div class="text-right">
                                <p class="text-[8px] font-black uppercase tracking-tighter opacity-90">Umidade 45%</p>
                                <p class="text-[8px] font-black uppercase opacity-70 font-mono">Dados via Satélite</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg rounded-xl p-5 text-white flex flex-col justify-between h-32">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest opacity-80">Mercado Hoje</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-[9px] font-black uppercase font-mono">SOJA (CBOT)</span>
                                <span class="text-[10px] font-black">R$ 134,20</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] font-black uppercase font-mono">MILHO (B3)</span>
                                <span class="text-[10px] font-black">R$ 58,15</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-end border-t border-white/20 pt-1">
                            <span class="text-[9px] font-black uppercase tracking-widest">DÓLAR</span>
                            <span class="text-lg font-black tracking-tighter">R$ 5,92</span>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-xl p-5 border-l-4 border-emerald-500 h-32 flex flex-col justify-between">
                        <p class="text-gray-400 text-[9px] font-black uppercase tracking-wider">Área Total</p>
                        <p class="text-2xl font-black text-gray-900 leading-none">{{ kpis?.hectares || 0 }} <span class="text-[10px] text-gray-300 uppercase">ha</span></p>
                        <p class="text-[8px] font-black text-emerald-600 uppercase tracking-tighter">Produção Ativa</p>
                    </div>

                    <div class="bg-white shadow-sm rounded-xl p-5 border-l-4 border-blue-400 h-32 flex flex-col justify-between">
                        <p class="text-gray-400 text-[9px] font-black uppercase tracking-wider">Propriedades</p>
                        <p class="text-2xl font-black text-gray-900 leading-none">{{ kpis?.fazendas || 0 }}</p>
                        <p class="text-[8px] font-black text-blue-400 uppercase tracking-tighter">Fazendas Gestão</p>
                    </div>

                    <div class="bg-white shadow-sm rounded-xl p-5 border-l-4 border-purple-600 h-32 flex flex-col justify-between">
                        <p class="text-purple-400 text-[9px] font-black uppercase tracking-wider">Faturamento</p>
                        <p class="text-lg font-black text-purple-900 tracking-tighter leading-none">{{ formatarDinheiro(kpis?.faturamento || 0) }}</p>
                        <p class="text-[8px] font-black text-purple-600 uppercase tracking-tighter">Total Acumulado</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    <div class="flex flex-col">
                        <div class="flex justify-between items-center mb-4 h-10">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">📦 Estoque em Silos</h3>
                            <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-1 rounded-full font-bold uppercase tracking-tighter">{{ estoque.length }} Produtos</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-for="(item, index) in estoquesVisiveis" :key="index" 
                                 class="bg-white p-5 rounded-xl shadow-sm border-b-4 border-amber-400 flex flex-col justify-between h-48 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-black text-gray-700 text-sm uppercase tracking-tighter">{{ item.cultura }}</span>
                                        <span class="bg-amber-100 text-amber-800 text-[9px] px-2 py-0.5 rounded-full font-bold uppercase">Disponível</span>
                                    </div>
                                    <div class="text-3xl font-black text-gray-900">
                                        {{ parseFloat(item.total).toLocaleString('pt-BR') }} <span class="text-xs font-bold text-gray-400 uppercase">sc</span>
                                    </div>
                                </div>
                                <button @click="abrirVenda(item)" class="w-full bg-gray-50 text-emerald-700 border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-colors py-2 rounded font-black text-[10px] uppercase flex justify-center items-center gap-2">
                                    <span>💲</span> Vender Saca
                                </button>
                            </div>
                            
                            <div v-if="estoque.length > 2" class="sm:col-span-2 flex justify-center mt-2">
                                <button @click="mostrarTodosEstoques = !mostrarTodosEstoques"
                                    class="text-[9px] font-bold text-gray-400 hover:text-emerald-600 transition-all uppercase tracking-widest bg-white px-4 py-1.5 rounded-full border border-gray-100 shadow-sm">
                                    {{ mostrarTodosEstoques ? 'Ver Menos ▲' : 'Ver Todos ▼' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div class="flex justify-between items-center mb-4 h-10">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">🚜 Acessar Fazenda</h3>
                            <Link href="/fazendas/create" class="bg-emerald-50 text-emerald-700 border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                <span>+</span> Nova Fazenda
                            </Link>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-for="fazenda in lista_fazendas" :key="fazenda.id" 
                                 class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 group h-48 flex flex-col transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
                                <Link :href="`/fazendas/${fazenda.id}`" class="flex-1 flex flex-col p-5 justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-1">
                                            <h4 class="text-sm font-black text-gray-900 group-hover:text-emerald-600 transition-colors uppercase tracking-tighter">
                                                {{ fazenda.nome }}
                                            </h4>
                                            <span class="text-[9px] text-gray-300 font-mono">#{{ fazenda.id }}</span>
                                        </div>
                                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-tight flex items-center gap-1">
                                            📍 {{ fazenda.cidade || 'GERAL' }}
                                        </p>
                                    </div>
                                    <div class="pt-3 border-t border-gray-50 flex justify-between items-center">
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                            {{ fazenda.talhoes_count || 0 }} Talhões
                                        </span>
                                        <span class="text-emerald-600 font-bold text-[10px] uppercase flex items-center gap-1">Entrar →</span>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-50 no-print">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2 uppercase tracking-tight">
                        <span>📊</span> Distribuição por Cultura
                    </h3>
                    <div v-if="grafico && grafico.data.length > 0" class="h-72 relative">
                        <Doughnut :data="chartData" :options="chartOptions" />
                    </div>
                    <div v-else class="h-64 flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-100 rounded-xl bg-gray-50">
                        <span class="text-4xl mb-2">🌱</span>
                        <p class="font-black uppercase tracking-widest text-[10px]">Terra em descanso</p>
                    </div>
                </div>

            </div>
        </div>

        <div v-if="modalVendaAberto" class="fixed inset-0 bg-black/60 flex items-center justify-center z-[100] p-4 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-black text-gray-800 uppercase tracking-tighter">Vender Produção</h3>
                    <button @click="modalVendaAberto = false" class="text-gray-400 hover:text-gray-600 transition-colors text-xl">✕</button>
                </div>
                
                <form @submit.prevent="realizarVenda" class="space-y-6">
                    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex justify-between items-center">
                        <div>
                            <p class="text-[9px] text-emerald-800 uppercase font-black tracking-widest">Produto</p>
                            <p class="text-xl font-black text-emerald-900 uppercase">{{ formVenda.cultura }}</p>
                        </div>
                        <span class="text-3xl">💰</span>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Comprador / Destino</label>
                        <input type="text" v-model="formVenda.comprador" 
                               class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 font-bold text-gray-700" 
                               placeholder="Ex: Cooperativa ou Nome do Cliente" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Qtd (Sacas)</label>
                            <input type="number" step="0.01" v-model="formVenda.quantidade" 
                                   class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 font-bold text-gray-700" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Preço Unit. (R$)</label>
                            <input type="number" step="0.01" v-model="formVenda.preco_unitario" 
                                   class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 font-bold text-gray-700" required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Data da Operação</label>
                        <input type="date" v-model="formVenda.data_venda" 
                               class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 font-bold text-gray-700 uppercase" required />
                    </div>

                    <button type="submit" 
                            class="w-full bg-emerald-600 text-white font-black py-5 rounded-xl hover:bg-emerald-700 transition-all uppercase tracking-widest text-xs shadow-lg shadow-emerald-100 flex items-center justify-center gap-2">
                        <span>🚀</span> Confirmar e Registrar Venda
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>