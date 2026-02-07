<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DebitController extends Controller
{
    /**
     * Lista todos os usuários com débito pendente
     * Apenas admin e bibliotecário podem acessar
     */
    public function index()
    {
        // Verifica se é admin ou bibliotecário
        if (!auth()->user()->isAdmin() && !auth()->user()->isBibliotecario()) {
            abort(403, 'Você não tem permissão para acessar esta página.');
        }

        // Busca usuários com débito > 0
        $usersWithDebit = User::where('debit', '>', 0)
            ->orderBy('debit', 'desc')
            ->paginate(15);

        return view('debits.index', compact('usersWithDebit'));
    }

    /**
     * Quita o débito de um usuário
     * Apenas admin e bibliotecário podem executar
     */
    public function clear(User $user)
    {
        // Verifica se é admin ou bibliotecário
        if (!auth()->user()->isAdmin() && !auth()->user()->isBibliotecario()) {
            abort(403, 'Você não tem permissão para executar esta ação.');
        }

        $debitoAnterior = $user->debit;

        $user->quitarDebito();

        \Log::info("Débito quitado: Usuário {$user->id} ({$user->name}), Valor: R$ {$debitoAnterior}, Por: " . auth()->user()->name);

        return redirect()
            ->back()
            ->with('success', "Débito de R$ " . number_format($debitoAnterior, 2, ',', '.') . " do usuário {$user->name} foi quitado com sucesso!");
    }
}
