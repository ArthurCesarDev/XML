<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        // Validar os dados do formulário
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentar autenticar o usuário
        if (Auth::attempt($credentials)) {
            // Autenticação bem-sucedida
            return redirect()->route('dashboard'); // Substitua 'dashboard' pela rota para a área restrita
        } else {
            // Autenticação falhou
            return back()->withErrors(['email' => 'Credenciais inválidas']);
        }
    }

    public function destroy()
    {
        Auth::logout(); // Faz logout do usuário
        return redirect()->route('login.index'); // Redireciona para a página de login
    }
}