<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Recebe a lista de membros enviada pelo EquipeController
const props = defineProps({ membros: Array });

function mudarRole(membro, novaRole) {
    if (confirm(`Deseja alterar o acesso de ${membro.name} para ${novaRole}?`)) {
        const form = useForm({ role: novaRole });
        // Envia a atualização para o servidor
        form.patch(`/equipe/${membro.id}/role`, {
            preserveScroll: true,
            onSuccess: () => alert('Permissão atualizada com sucesso!')
        });
    }
}
</script>

<template>
    <Head title="Gestão de Equipe" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-black text-xl text-gray-800 leading-tight uppercase tracking-tighter">👥 Gestão de Equipe</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Membro</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">E-mail</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Nível</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="membro in membros" :key="membro.id" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900 uppercase text-xs">{{ membro.name }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 font-mono">{{ membro.email }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span :class="membro.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700'" 
                                          class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                        {{ membro.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <template v-if="$page.props.auth.user.id !== membro.id">
                                        <button v-if="membro.role === 'user'" 
                                                @click="mudarRole(membro, 'admin')"
                                                class="text-[9px] font-black text-purple-600 hover:bg-purple-50 px-3 py-1 rounded-lg border border-purple-100 uppercase transition-all">
                                            Promover a Admin
                                        </button>
                                        <button v-else 
                                                @click="mudarRole(membro, 'user')"
                                                class="text-[9px] font-black text-gray-400 hover:text-red-600 hover:bg-red-50 px-3 py-1 rounded-lg border border-gray-100 uppercase transition-all">
                                            Remover Admin
                                        </button>
                                    </template>
                                    <span v-else class="text-[9px] font-black text-gray-300 uppercase italic">Você (Admin)</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>