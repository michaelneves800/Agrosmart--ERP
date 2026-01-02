<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    nome: '',
    cidade: '',
    estado: ''
});

function salvarFazenda() {
    form.post('/fazendas', {
        onSuccess: () => {
            // Redireciona automático para o Dashboard pelo Controller
        }
    });
}
</script>

<template>
    <Head title="Nova Fazenda" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cadastrar Nova Fazenda</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    
                    <form @submit.prevent="salvarFazenda" class="space-y-6">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nome da Propriedade</label>
                            <input type="text" v-model="form.nome" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Ex: Fazenda Santa Maria" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Cidade</label>
                                <input type="text" v-model="form.cidade" class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="Ex: Ponta Grossa" required />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Estado (UF)</label>
                                <select v-model="form.estado" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                                    <option value="" disabled>Selecione...</option>
                                    <option value="PR">Paraná</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="BA">Bahia</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 mt-6">
                            <Link href="/dashboard" class="text-gray-500 hover:text-gray-800 font-medium">Cancelar</Link>
                            
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition transform active:scale-95">
                                Salvar Fazenda
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>