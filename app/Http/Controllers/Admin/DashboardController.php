<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Albergue;
use App\Models\Mascota;
use App\Models\Solicitud;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Panel de métricas de impacto (RF10) y albergues pendientes (Fig. 10).
 */
class DashboardController extends Controller
{
    public function index()
    {
        // Si una mascota adoptada no tiene fecha de adopción, se usa su última actualización
        $dias = Mascota::where('estado', 'adoptada')
            ->avg(DB::raw('TIMESTAMPDIFF(DAY, created_at, COALESCE(adoptada_en, updated_at))'));

        $metricas = [
            'adopciones' => Mascota::where('estado', 'adoptada')->count(),
            'disponibles' => Mascota::where('estado', 'disponible')->count(),
            'pendientes' => Solicitud::where('estado', 'pendiente')->count(),
            'dias' => $dias === null ? null : (int) round($dias),
        ];

        // Adopciones de los últimos 6 meses (según la fecha en que pasaron a "adoptada")
        $abreviaturas = [1 => 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $adoptadas = Mascota::where('estado', 'adoptada')
            ->get(['adoptada_en', 'updated_at'])
            ->map(fn ($m) => Carbon::parse($m->adoptada_en ?? $m->updated_at)->format('Y-m'))
            ->countBy();

        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $meses[] = [
                'etiqueta' => $abreviaturas[(int) $fecha->format('n')],
                'total' => $adoptadas[$fecha->format('Y-m')] ?? 0,
            ];
        }
        $maximo = max(1, max(array_column($meses, 'total')));

        $pendientes = Albergue::where('validado', false)->latest('id')->take(5)->get();

        return view('admin.dashboard', compact('metricas', 'meses', 'maximo', 'pendientes'));
    }
}