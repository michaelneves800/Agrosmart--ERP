<script setup>
import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const show = ref(false);
const message = ref('');
const type = ref('success'); // 'success' ou 'error'

const page = usePage();

const hideToast = () => {
    show.value = false;
};

// Monitora mensagens vindas do servidor via Inertia Flash
watch(() => page.props.flash?.message, (newMsg) => {
    if (newMsg) {
        message.value = newMsg;
        type.value = 'success';
        show.value = true;
        setTimeout(hideToast, 5000); // Some após 5 segundos
    }
}, { immediate: true });

watch(() => page.props.flash?.error, (newErr) => {
    if (newErr) {
        message.value = newErr;
        type.value = 'error';
        show.value = true;
        setTimeout(hideToast, 5000);
    }
}, { immediate: true });
</script>

<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="show" class="fixed top-5 right-5 z-[100] max-w-sm w-full shadow-2xl rounded-2xl pointer-events-auto border overflow-hidden"
             :class="type === 'success' ? 'bg-emerald-600 border-emerald-400' : 'bg-red-600 border-red-400'">
            <div class="p-4 flex items-center gap-3">
                <div class="flex-shrink-0 text-2xl">
                    <span v-if="type === 'success'">✅</span>
                    <span v-else>⚠️</span>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-black text-white uppercase tracking-widest">Notificação</p>
                    <p class="text-sm font-bold text-white leading-tight mt-1">{{ message }}</p>
                </div>
                <button @click="hideToast" class="text-white/60 hover:text-white transition">
                    ✕
                </button>
            </div>
            <div class="h-1 bg-white/20 animate-progress"></div>
        </div>
    </Transition>
</template>

<style scoped>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
.animate-progress {
    animation: progress 5s linear forwards;
}
</style>