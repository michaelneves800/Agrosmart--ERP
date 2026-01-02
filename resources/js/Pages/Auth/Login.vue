<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Acesso AgroSmart" />

    <div class="min-h-screen flex items-center justify-center bg-cover bg-center" 
         style="background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
        
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

        <div class="z-10 w-full max-w-md px-6">
            <div class="bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl overflow-hidden border border-white/20">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                            <span class="text-3xl">🌱</span>
                        </div>
                        <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">AgroSmart</h1>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mt-1">Gestão de Safra e Finanças</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">E-mail de Acesso</label>
                            <input type="email" v-model="form.email" 
                                   class="w-full rounded-xl border-gray-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" 
                                   required autofocus />
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Sua Senha</label>
                            <input type="password" v-model="form.password" 
                                   class="w-full rounded-xl border-gray-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" 
                                   required />
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" v-model="form.remember" class="rounded text-emerald-600 border-gray-300 focus:ring-emerald-500" />
                                <span class="text-xs text-gray-600 font-bold uppercase tracking-tighter">Lembrar-me</span>
                            </label>
                        </div>

                        <button type="submit" 
                                :disabled="form.processing"
                                class="w-full bg-emerald-600 text-white font-black py-4 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 active:scale-[0.98] uppercase tracking-widest text-sm">
                            Entrar no Sistema
                        </button>
                    </form>
                </div>
                
                <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Tecnologia para o Campo v2.0</p>
                </div>
            </div>
        </div>
    </div>
</template>