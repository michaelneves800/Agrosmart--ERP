<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({ dadosFazenda: Object });
const analiseIA = ref(null); 
const carregando = ref(true);

const gerarConsultoria = async () => {
    try {
        // Envia os dados para o serviço Python na porta 5000
        const response = await axios.post('/consultoria/gerar', props.dadosFazenda);
        
        if (response.data && response.data.relatorio) {
            analiseIA.value = response.data.relatorio;
        }
    } catch (e) {
        console.error("Erro na comunicação com o AgroBot:", e);
    } finally {
        carregando.value = false;
    }
};

onMounted(gerarConsultoria);

// Função auxiliar para limpar objetos JSON e exibir apenas o texto
const formatarTexto = (valor) => {
    if (!valor) return 'Informação indisponível no momento.';
    if (typeof valor === 'object') {
        return Object.values(valor)[0];
    }
    return valor;
};
</script>

<template>
    <AuthenticatedLayout title="Consultoria Técnica IA">
        <template #header>
            <h2 class="font-black text-2xl text-emerald-900 leading-tight">Consultoria Estratégica AgroBot</h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="bg-white rounded-3xl shadow-sm p-8 mb-8 border border-emerald-100 flex justify-between items-center">
                    <div>
                        <p class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-1">Diagnóstico em Tempo Real</p>
                        <h3 class="text-xl font-bold text-gray-800">Faturamento: R$ {{ dadosFazenda.faturamento }}</h3>
                    </div>
                    <div class="text-right">
                        <span class="bg-emerald-50 text-emerald-700 text-xs font-black px-4 py-2 rounded-full border border-emerald-200 uppercase">
                            Área: {{ dadosFazenda.area_total }} HA
                        </span>
                    </div>
                </div>

                <div v-if="carregando" class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl shadow-sm border border-gray-100">
                    <div class="animate-spin rounded-full h-14 w-14 border-t-4 border-emerald-500 mb-6"></div>
                    <p class="text-emerald-800 font-bold italic animate-pulse tracking-tight">O AgroBot está processando sua estratégia técnica...</p>
                </div>

                <div v-if="!carregando && analiseIA" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <div class="bg-white p-8 rounded-3xl shadow-sm border-b-8 border-blue-500 hover:shadow-lg transition">
                        <div class="text-3xl mb-4">💰</div>
                        <h4 class="font-black text-gray-900 uppercase text-xs mb-4 tracking-widest">Análise Financeira</h4>
                        <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-wrap font-medium">
                            {{ formatarTexto(analiseIA.financeiro) }}
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border-b-8 border-orange-500 hover:shadow-lg transition">
                        <div class="text-3xl mb-4">🌿</div>
                        <h4 class="font-black text-gray-900 uppercase text-xs mb-4 tracking-widest">Sugestões de Manejo</h4>
                        <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-wrap font-medium">
                            {{ formatarTexto(analiseIA.manejo) }}
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border-b-8 border-emerald-500 hover:shadow-lg transition">
                        <div class="text-3xl mb-4">🚜</div>
                        <h4 class="font-black text-gray-900 uppercase text-xs mb-4 tracking-widest">Melhorias e Estoque</h4>
                        <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-wrap font-medium">
                            {{ formatarTexto(analiseIA.melhorias) }}
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>