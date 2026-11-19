<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Listado de usuarios (RF09), con filtro por rol.
 */
class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $rol = $request->query('rol');

        $usuarios = User::query()
            ->when(
                in_array($rol, ['admin', 'albergue', 'adoptante'], true),
                fn ($q) => $q->where('rol', $rol)
            )
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.usuarios.index', compact('usuarios', 'rol'));
    }
}