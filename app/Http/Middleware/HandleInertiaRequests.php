<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            
            // Informações do Usuário Logado
            'auth' => [
                'user' => $request->user(),
            ],

            // --- AQUI ESTÁ O QUE FALTAVA ---
            // O pacote de mensagens (Sucesso, Erro, IA)
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'erro' => fn () => $request->session()->get('erro'),
                'previsao' => fn () => $request->session()->get('previsao'), // O resultado da IA via satélite
            ],
        ];
    }
}
