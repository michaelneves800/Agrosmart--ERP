<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Toast from '@/Components/Toast.vue';
import ChatBot from '@/Components/ChatBot.vue';
// resources/js/Layouts/AuthenticatedLayout.vue
import NavLink from '@/Components/NavLink.vue'; 
// Estado do Dropdown
const dropdownMaquinarioOpen = ref(false);
const isActive = (path) => usePage().url.startsWith(path);
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <Toast /> 
        
        <ChatBot />

        <nav class="bg-white border-b border-gray-200 shadow-sm relative z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="shrink-0 flex items-center mr-10">
                            <Link href="/dashboard" class="font-bold text-xl text-emerald-700 flex items-center gap-2">
                                🌱 AgroSmart
                            </Link>
                        </div>

                        <div class="hidden space-x-8 sm:flex h-full items-center">
                            <Link href="/dashboard" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 h-full transition duration-150 ease-in-out"
                                  :class="isActive('/dashboard') ? 'border-emerald-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700'">
                                Dashboard
                            </Link>

                            <Link v-if="$page.props.auth.user.role === 'admin'" 
                                  href="/equipe" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 h-full transition duration-150 ease-in-out"
                                  :class="isActive('/equipe') ? 'border-emerald-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700'">
                                Equipe
                            </Link>
                            
                            <Link href="/financeiro" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 h-full transition duration-150 ease-in-out"
                                  :class="isActive('/financeiro') ? 'border-emerald-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700'">
                                Financeiro
                            </Link>
                            <Link :href="route('consultoria.index')" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-emerald-50 group">
    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
    </svg>
    <span class="ml-3 font-bold">Consultoria por IA</span>
</Link>

<NavLink :href="route('pragas')" :active="route().current('pragas')">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        Auxílio Pragas IA
    </div>
</NavLink>

                            <div class="relative h-full flex items-center">
                                <button @click="dropdownMaquinarioOpen = !dropdownMaquinarioOpen" 
                                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 focus:outline-none transition h-full">
                                    Maquinário
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>

                                <div v-if="dropdownMaquinarioOpen" 
                                     @click="dropdownMaquinarioOpen = false"
                                     class="absolute top-14 left-0 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-50">
                                    <Link href="/maquinas" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        🚜 Minha Frota
                                    </Link>
                                    <Link href="/manutencoes" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        🔧 Manutenções
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Olá, {{ $page.props.auth.user.name }}
                        </div>

                        <Link :href="route('logout')" method="post" as="button" 
                            class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest flex items-center gap-1 border border-red-100 shadow-sm">
                            <span>🚪</span> Sair
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow relative z-0" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main class="relative z-0">
            <slot />
        </main>
        
        <div v-if="dropdownMaquinarioOpen" @click="dropdownMaquinarioOpen = false" class="fixed inset-0 z-40 bg-transparent"></div>
    </div>
</template>