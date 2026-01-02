<script setup>
import { ref, nextTick } from 'vue';
import axios from 'axios';

const aberto = ref(false);
const carregando = ref(false);
const mensagens = ref([
    { role: 'bot', text: 'Olá! Sou o AgroBot. Posso tirar dúvidas sobre sua fazenda ou sobre agricultura em geral. O que deseja saber?' }
]);

const pergunta = ref('');
const chatScroll = ref(null);

async function enviarPergunta(texto) {
    const p = texto || pergunta.value;
    if (!p || carregando.value) return;

    mensagens.value.push({ role: 'user', text: p });
    pergunta.value = '';
    carregando.value = true;

    await nextTick();
    if (chatScroll.value) chatScroll.value.scrollTop = chatScroll.value.scrollHeight;

    try {
        const response = await axios.post('/ia/perguntar', { pergunta: p });
        mensagens.value.push({ role: 'bot', text: response.data.resposta });
    } catch (error) {
        const msgErro = error.response?.data?.resposta || 'Erro técnico desconhecido.';
    mensagens.value.push({ role: 'bot', text: msgErro });
    } finally {
        carregando.value = false;
        await nextTick();
        if (chatScroll.value) chatScroll.value.scrollTop = chatScroll.value.scrollHeight;
    }
}
</script>

<template>
    <div class="fixed bottom-6 right-6 z-[100]">
        <button @click="aberto = !aberto" class="bg-emerald-600 hover:bg-emerald-700 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center transition-all border-4 border-white active:scale-90">
            <span v-if="!aberto" class="text-2xl">🤖</span>
            <span v-else class="text-xl">✕</span>
        </button>

        <div v-if="aberto" class="absolute bottom-20 right-0 w-80 md:w-96 bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col animate-bounce-in">
            <div class="bg-emerald-600 p-5 text-white">
                <p class="text-[9px] font-black uppercase tracking-widest opacity-70">Consultoria 24h</p>
                <h3 class="font-black uppercase tracking-tighter leading-none text-lg">AgroBot Especialista</h3>
            </div>

            <div ref="chatScroll" class="h-80 overflow-y-auto p-4 space-y-4 bg-gray-50/50">
                <div v-for="(msg, i) in mensagens" :key="i" :class="msg.role === 'bot' ? 'justify-start' : 'justify-end'" class="flex">
                    <div :class="msg.role === 'bot' ? 'bg-white text-gray-800' : 'bg-emerald-600 text-white'" 
                         class="max-w-[85%] p-3 rounded-2xl shadow-sm text-xs font-bold border border-gray-100 leading-relaxed">
                        {{ msg.text }}
                    </div>
                </div>
                <div v-if="carregando" class="flex justify-start">
                    <div class="bg-white p-3 rounded-2xl shadow-sm flex gap-1">
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce"></div>
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce [animation-delay:0.2s]"></div>
                    </div>
                </div>
            </div>

            <form @submit.prevent="enviarPergunta()" class="p-3 bg-white border-t border-gray-100 flex gap-2">
                <input v-model="pergunta" type="text" placeholder="Pergunte qualquer coisa..." class="flex-1 border-gray-200 rounded-2xl text-xs font-bold focus:ring-emerald-500" />
                <button type="submit" :disabled="carregando" class="bg-emerald-600 text-white p-3 rounded-2xl hover:bg-emerald-700 disabled:bg-gray-300">
                    🚀
                </button>
            </form>
        </div>
    </div>
</template>

<style scoped>
.animate-bounce-in { animation: bounceIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
@keyframes bounceIn {
    from { opacity: 0; transform: translateY(30px) scale(0.9); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>