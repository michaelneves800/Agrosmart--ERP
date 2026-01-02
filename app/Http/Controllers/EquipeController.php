<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EquipeController extends Controller
{
    public function index()
    {
        // Apenas admins podem acessar esta tela
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Acesso negado.');
        }

        return Inertia::render('Equipe/Index', [
            'membros' => User::all(['id', 'name', 'email', 'role'])
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user->update($validated);

        return back()->with('message', "Nível de acesso de {$user->name} atualizado!");
    }
}