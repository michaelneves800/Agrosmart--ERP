<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({ insumosEmEstoque: Array });
const sintomas = ref('');
const resultado = ref(null);
const carregando = ref(false);

const diagnosticar = async () => {
    carregando.value = true;
    try {
        const res = await axios.post('/pragas/analisar', {
            sintomas: sintomas.value,
            insumos: props.insumosEmEstoque
        });
        resultado.value = res.data.diagnostico;
    } finally {
        carregando.value = false;
    }
};
</script>

<template>
    <AuthenticatedLayout title="Auxílio Pragas">
        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-red-100">
                    <h3 class="text-red-700 font-black mb-4 uppercase text-sm">Detectar Praga por IA</h3>
                    <textarea v-model="sintomas" class="w-full border-gray-200 rounded-xl mb-4 h-32" placeholder="Descreva as manchas, cores ou insetos que está vendo..."></textarea>
                    <button @click="diagnosticar" :disabled="carregando" class="w-full bg-red-600 text-white py-4 rounded-xl font-bold hover:bg-red-700 transition">
                        {{ carregando ? 'Processando Diagnóstico...' : 'Diagnosticar Praga' }}
                    </button>
                </div>

                <div v-if="resultado" class="mt-8 bg-white p-8 rounded-3xl shadow-sm border border-green-100">
                    <h4 class="font-bold text-gray-900 text-lg">Praga Identificada: {{ resultado.praga }}</h4>
                    <p class="mt-4 text-gray-600 leading-relaxed">{{ resultado.recomendacao }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>