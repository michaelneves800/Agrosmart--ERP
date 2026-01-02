<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ 
    fazenda: Object,
    previsao: Object 
});

// --- FUNÇÃO PARA EXCLUIR FAZENDA ---
const excluirFazenda = () => {
    if (confirm(`⚠️ ATENÇÃO: Deseja realmente excluir a fazenda "${props.fazenda.nome}"? Esta ação removerá todos os dados e não pode ser desfeita.`)) {
        router.delete(`/fazendas/${props.fazenda.id}`);
    }
};

// --- CONTROLE DOS MODAIS ---
const modalColheitaAberto = ref(false);
const modalInsumoAberto = ref(false); 
const modalTalhaoAberto = ref(false); 
const cicloSelecionadoParaColheita = ref(null);

// --- FORMULÁRIOS ---
const formTalhao = useForm({ 
    nome: '',
    area_hectares: '',
    tipo_solo: 'Argiloso'
});

const formColheita = useForm({
    quantidade_colhida: '',
    umidade: '',
    preco_venda_saca: '',
    data_colheita: new Date().toISOString().split('T')[0],
    finalizar_ciclo: false
});

const formInsumo = useForm({ 
    nome: '',
    categoria: 'Defensivo',
    quantidade: '',
    unidade: 'L',
    custo_unitario: ''
});

// --- AÇÕES ---
function salvarTalhao() {
    formTalhao.post(`/fazendas/${props.fazenda.id}/talhoes`, {
        onSuccess: () => {
            modalTalhaoAberto.value = false;
            formTalhao.reset();
        }
    });
}

function consultarIA(cicloId) {
    router.post(`/ciclos/${cicloId}/prever`, {}, {
        preserveScroll: true,
        onStart: () => alert('🛰️ Conectando satélite e processando dados climáticos...'),
    });
}

function abrirColheita(ciclo) {
    cicloSelecionadoParaColheita.value = ciclo;
    modalColheitaAberto.value = true;
    formColheita.reset();
}

function salvarColheita() {
    formColheita.post(`/ciclos/${cicloSelecionadoParaColheita.value.id}/colher`, {
        onSuccess: () => {
            modalColheitaAberto.value = false;
        },
        onError: () => {
            alert('Erro ao registrar colheita. Verifique os dados.');
        }
    });
}

function salvarInsumo() { 
    formInsumo.post(`/fazendas/${props.fazenda.id}/insumos`, {
        onSuccess: () => {
            modalInsumoAberto.value = false;
            formInsumo.reset();
        }
    });
}

const formatMoney = (val) => parseFloat(val).toLocaleString('pt-BR', {style:'currency', currency:'BRL'});
</script>

<template>
    <Head :title="fazenda.nome" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">
                        <Link href="/dashboard" class="hover:text-emerald-600 transition">Dashboard</Link>
                        <span class="mx-2 text-gray-300">/</span>
                        <span class="text-emerald-800">{{ fazenda.nome }}</span>
                    </div>
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">{{ fazenda.nome }}</h1>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">📍 {{ fazenda.cidade }} - {{ fazenda.estado }}</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <template v-if="$page.props.auth.user.role === 'admin'">
                        <Link :href="`/fazendas/${fazenda.id}/edit`" 
                              class="bg-white border border-gray-200 text-amber-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-50 transition-all shadow-sm">
                            ✏️ Editar Dados
                        </Link>

                        <button @click="excluirFazenda" 
                                class="bg-white border border-red-100 text-red-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition-all shadow-sm">
                            🗑️ Excluir Fazenda
                        </button>

                        <button @click="modalTalhaoAberto = true" 
                                class="bg-emerald-600 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100">
                            + Novo Talhão
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
                
                <section>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2 uppercase tracking-tight">
                        <span>🌱</span> Áreas de Plantio (Talhões)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="talhao in fazenda.talhoes" :key="talhao.id" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-emerald-300 transition group h-full flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <div class="bg-emerald-100 text-emerald-800 p-3 rounded-lg">🌱</div>
                                    <span class="text-[9px] font-bold text-gray-300 bg-gray-50 px-2 py-1 rounded font-mono">ID: {{ talhao.id }}</span>
                                </div>
                                <h3 class="text-lg font-black text-gray-900 mb-1 uppercase tracking-tighter">{{ talhao.nome }}</h3>
                                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-tight mb-4">{{ talhao.tipo_solo || 'Solo padrão' }} • {{ talhao.area_hectares }} ha</p>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-100">
                                <div v-if="talhao.ciclos && talhao.ciclos.length > 0" class="bg-emerald-50 rounded-lg p-4 border border-emerald-100">
                                    <div class="text-center mb-4">
                                        <p class="text-[9px] font-black text-emerald-800 uppercase tracking-widest mb-1">Safra Ativa</p>
                                        <p class="text-lg font-black text-emerald-700 uppercase">{{ talhao.ciclos[0].cultura }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button @click="consultarIA(talhao.ciclos[0].id)" class="bg-white text-emerald-700 border border-emerald-200 text-[10px] font-black py-2 rounded uppercase hover:bg-emerald-100 transition shadow-sm">🤖 IA</button>
                                        
                                        <button @click="abrirColheita(talhao.ciclos[0])" class="bg-amber-100 text-amber-800 border border-amber-200 text-[10px] font-black py-2 rounded uppercase hover:bg-amber-200 transition shadow-sm">🚜 Colher</button>
                                    </div>
                                </div>
                                <Link v-else-if="$page.props.auth.user.role === 'admin'" :href="`/talhoes/${talhao.id}/plantar`" class="block w-full text-center bg-gray-50 text-gray-400 font-black text-[10px] py-3 rounded-lg hover:bg-emerald-600 hover:text-white transition uppercase tracking-widest shadow-sm">🌱 Registrar Plantio</Link>
                                <div v-else class="text-center py-3 text-gray-300 font-bold uppercase text-[10px] italic">Aguardando Plantio</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-12 bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-black text-gray-800 mb-8 flex items-center gap-2 uppercase tracking-tighter">
                        <span>📜</span> Linha do Tempo de Colheitas
                    </h2>

                    <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-emerald-500 before:via-gray-100 before:to-transparent">
                        <div v-for="colheita in $page.props.historico" :key="colheita.id" class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-emerald-600 text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                                🚜
                            </div>
                            <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-emerald-200 transition-all ml-4 md:ml-0">
                                <div class="flex justify-between items-start mb-1">
                                    <time class="font-black text-[10px] text-emerald-600 uppercase tracking-widest">
                                        {{ new Date(colheita.data_colheita).toLocaleDateString('pt-BR') }}
                                    </time>
                                    <span class="bg-emerald-100 text-emerald-800 text-[8px] px-2 py-0.5 rounded font-black uppercase tracking-tighter italic font-mono">Safra Finalizada</span>
                                </div>
                                
                                <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">
                                    {{ colheita.cultura }} 
                                    <span class="text-gray-300 mx-2 text-[9px] font-normal">•</span> 
                                    <span class="text-gray-400 text-[9px]">TALHÃO ID: {{ colheita.talhao_id }}</span>
                                </h4>

                                <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2 border-t border-gray-200 pt-3">
                                    <div>
                                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Total</p>
                                        <p class="text-xs font-black text-gray-800">{{ colheita.quantidade_colhida }} sc</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Rendimento</p>
                                        <p class="text-xs font-black text-blue-600">
                                            {{ (colheita.quantidade_colhida / (fazenda.talhoes.find(t => t.id === colheita.talhao_id)?.area_hectares || 1)).toFixed(1) }} sc/ha
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Faturamento</p>
                                        <p class="text-xs font-black text-emerald-600">
                                            {{ formatMoney(colheita.quantidade_colhida * (colheita.preco_venda_saca || 0)) }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Umidade</p>
                                        <p class="text-xs font-black" :class="colheita.umidade > 14 ? 'text-red-500' : 'text-emerald-600'">
                                            {{ colheita.umidade || '0' }}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="$page.props.historico.length === 0" class="text-center py-10 text-gray-400 font-bold uppercase text-[10px] tracking-widest">
                            Nenhum histórico disponível para esta fazenda.
                        </div>
                    </div>
                </section>

                <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2 uppercase tracking-tight">
                                <span>🧪</span> Almoxarifado & Insumos
                            </h2>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Estoque de produtos.</p>
                        </div>
                        <button v-if="$page.props.auth.user.role === 'admin'" @click="modalInsumoAberto = true" class="bg-blue-600 text-white px-4 py-2 rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                            <span>+</span> Registrar Compra
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-400 uppercase font-black text-[9px] tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Produto</th>
                                    <th class="px-6 py-4">Categoria</th>
                                    <th class="px-6 py-4 text-right">Estoque</th>
                                    <th class="px-6 py-4 text-right">Valor Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="insumo in fazenda.insumos" :key="insumo.id" class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-black text-gray-900 uppercase tracking-tight">{{ insumo.nome }}</td>
                                    <td class="px-6 py-4"><span class="bg-gray-100 px-2 py-1 rounded text-[9px] font-black uppercase tracking-tighter">{{ insumo.categoria }}</span></td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900 uppercase">{{ parseFloat(insumo.quantidade).toLocaleString('pt-BR') }} {{ insumo.unidade }}</td>
                                    <td class="px-6 py-4 text-right text-emerald-700 font-black">R$ {{ (insumo.quantidade * insumo.custo_medio).toLocaleString('pt-BR', {minimumFractionDigits: 2}) }}</td>
                                </tr>
                                <tr v-if="!fazenda.insumos || fazenda.insumos.length === 0">
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold uppercase text-[10px] tracking-widest">Nenhum produto cadastrado.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>

        <div v-if="modalTalhaoAberto" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 animate-bounce-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800 uppercase tracking-tighter">Cadastrar Novo Talhão</h3>
                    <button @click="modalTalhaoAberto = false" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <form @submit.prevent="salvarTalhao" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nome do Talhão</label>
                        <input type="text" v-model="formTalhao.nome" class="w-full border-gray-200 rounded-xl font-bold" placeholder="Ex: Área da Estrada" required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Área (Hectares)</label>
                            <input type="number" step="0.01" v-model="formTalhao.area_hectares" class="w-full border-gray-200 rounded-xl font-bold" placeholder="50.00" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tipo de Solo</label>
                            <select v-model="formTalhao.tipo_solo" class="w-full border-gray-200 rounded-xl font-bold">
                                <option>Argiloso</option><option>Arenoso</option><option>Misto</option><option>Humífero</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 text-white font-black py-4 rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-100 uppercase tracking-widest text-xs">Salvar Cadastro</button>
                </form>
            </div>
        </div>

        <div v-if="modalInsumoAberto" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 animate-bounce-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800 uppercase tracking-tighter">Registrar Nova Compra</h3>
                    <button @click="modalInsumoAberto = false" class="text-gray-400">✕</button>
                </div>
                <form @submit.prevent="salvarInsumo" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nome do Produto</label>
                        <input type="text" v-model="formInsumo.nome" class="w-full border-gray-200 rounded-xl font-bold" required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Categoria</label>
                            <select v-model="formInsumo.categoria" class="w-full border-gray-200 rounded-xl font-bold">
                                <option>Defensivo</option><option>Fertilizante</option><option>Semente</option><option>Combustivel</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Unidade</label>
                            <select v-model="formInsumo.unidade" class="w-full border-gray-200 rounded-xl font-bold">
                                <option>L</option><option>Kg</option><option>Sc</option><option>Ton</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Quantidade</label>
                            <input type="number" step="0.01" v-model="formInsumo.quantidade" class="w-full border-gray-200 rounded-xl font-bold" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Custo Unitário (R$)</label>
                            <input type="number" step="0.01" v-model="formInsumo.custo_unitario" class="w-full border-gray-200 rounded-xl font-bold" required />
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100 uppercase tracking-widest text-xs transition">Salvar Entrada</button>
                </form>
            </div>
        </div>

        <div v-if="$page.props.flash.previsao" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative animate-bounce-in overflow-hidden border border-emerald-100">
                <button @click="$page.props.flash.previsao = null" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>

                <div class="p-8 pb-6 text-center">
                    <div class="bg-emerald-100 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-4 rotate-3">
                        <span class="text-4xl">🧠</span>
                    </div>
                    
                    <h3 class="text-2xl font-black text-gray-800 mb-1 uppercase tracking-tighter">Análise Concluída</h3>
                    <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-[0.2em] mb-6">Inteligência Artificial AgroSmart</p>

                    <div class="bg-gray-50 rounded-2xl p-6 mb-6 space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Estimativa / ha:</span>
                            <span class="text-emerald-700 font-black text-lg">{{ $page.props.flash.previsao.estimativa_sacas_ha }} sc</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Confiança:</span>
                            <span class="bg-blue-100 text-blue-800 text-[10px] font-black px-2 py-1 rounded uppercase">
                                {{ $page.props.flash.previsao.confianca }}%
                            </span>
                        </div>
                         <div class="flex justify-between items-center pt-1">
                            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Chuva (Real):</span>
                            <span class="text-blue-600 font-black text-lg uppercase">{{ $page.props.flash.previsao.chuva_periodo }}</span>
                        </div>
                    </div>

                    <button @click="$page.props.flash.previsao = null" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-xl shadow-lg shadow-emerald-100 uppercase tracking-widest text-xs transition">
                        Entendido
                    </button>
                </div>
            </div>
        </div>

        <div v-if="modalColheitaAberto" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 animate-bounce-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-gray-800 uppercase tracking-tighter">Registrar Colheita</h3>
                    <button @click="modalColheitaAberto = false" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                
                <form @submit.prevent="salvarColheita" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Quantidade Total (Sacas)</label>
                        <input type="number" step="0.01" v-model="formColheita.quantidade_colhida" class="w-full border-gray-200 rounded-xl font-bold" placeholder="0.00" required />
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Preço da Saca (R$)</label>
                        <input type="number" step="0.01" v-model="formColheita.preco_venda_saca" class="w-full border-gray-200 rounded-xl font-bold" placeholder="0.00" required />
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Data da Colheita</label>
                            <input type="date" v-model="formColheita.data_colheita" class="w-full border-gray-200 rounded-xl font-bold" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Umidade %</label>
                            <input type="number" step="0.1" v-model="formColheita.umidade" class="w-full border-gray-200 rounded-xl font-bold" placeholder="14.0" />
                        </div>
                    </div>

                    <label class="flex items-center gap-2 bg-amber-50 p-3 rounded-lg border border-amber-100 cursor-pointer group">
                        <input type="checkbox" v-model="formColheita.finalizar_ciclo" class="rounded text-amber-500" /> 
                        <span class="text-[10px] font-black text-amber-800 uppercase tracking-widest">Finalizar Safra e mover para histórico</span>
                    </label>

                    <button type="submit" :disabled="formColheita.processing" 
                            class="w-full bg-amber-500 text-white font-black py-4 rounded-xl hover:bg-amber-600 transition shadow-lg shadow-amber-100 uppercase tracking-widest text-xs">
                        {{ formColheita.processing ? 'Processando...' : 'Confirmar Colheita' }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>