<?php

namespace Database\Seeders;

use App\Models\Albergue;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Datos de prueba para desarrollo y demostración.
 * Los nombres de albergues y personas son ficticios.
 */
class DatabaseSeeder extends Seeder
{
    // Contraseña SOLO para desarrollo. En producción se debe cambiar.
    private const CLAVE = 'password123';

    public function run(): void
    {
        // ---------- Administrador ----------
        User::create([
            'name' => 'Administrador Huellitas',
            'email' => 'admin@huellitas.pe',
            'password' => env('ADMIN_PASSWORD', self::CLAVE),
            'rol' => 'admin',
        ]);

        // ---------- Albergues (un usuario por albergue) ----------
        $datosAlbergues = [
            ['Albergue Patitas Unidas', 'patitas@huellitas.pe', 'Cerro Colorado', true,
                'Albergue independiente que rescata perros y gatos de la zona norte de Arequipa.'],
            ['Rescate Chachani', 'chachani@huellitas.pe', 'Cayma', true,
                'Grupo de rescatistas voluntarios que gestiona hogares temporales.'],
            ['Refugio Huellas del Misti', 'misti@huellitas.pe', 'Yanahuara', false,
                'Refugio pendiente de validación por el administrador.'],
        ];

        $albergues = [];
        foreach ($datosAlbergues as [$nombre, $correo, $distrito, $validado, $descripcion]) {
            $usuario = User::create([
                'name' => $nombre,
                'email' => $correo,
                'password' => self::CLAVE,
                'rol' => 'albergue',
                'distrito' => $distrito,
            ]);

            $albergues[$nombre] = Albergue::create([
                'user_id' => $usuario->id,
                'nombre' => $nombre,
                'distrito' => $distrito,
                'descripcion' => $descripcion,
                'validado' => $validado,
            ]);
        }

        // ---------- Mascotas con ficha sanitaria ----------
        // [nombre, especie, raza, edad_meses, tamano, genero, estado, descripcion,
        //  [esterilizado, antirrabica, quintuple, desparasitado]]
        $mascotasPatitas = [
            ['Luna', 'perro', 'Mestiza', 24, 'mediano', 'hembra', 'disponible',
                'Luna fue rescatada en Cerro Colorado. Es juguetona, sociable con otros perros y aprende rápido. Busca un hogar con espacio y mucho cariño.',
                [false, true, true, true]],
            ['Max', 'perro', 'Labrador mestizo', 48, 'grande', 'macho', 'disponible',
                'Max es tranquilo y muy leal. Camina bien con correa y se lleva bien con niños.',
                [true, true, true, true]],
            ['Misu', 'gato', 'Doméstico', 6, 'pequeno', 'hembra', 'en_proceso',
                'Misu es una gatita curiosa y cariñosa. Ya tiene una solicitud en evaluación.',
                [false, true, true, true]],
            ['Nala', 'gato', 'Doméstico', 36, 'pequeno', 'hembra', 'adoptada',
                'Nala ya encontró su hogar definitivo.',
                [true, true, true, true]],
            ['Rocky', 'perro', 'Pastor mestizo', 84, 'grande', 'macho', 'disponible',
                'Rocky es un perro senior, sereno y agradecido. Ideal para una familia tranquila.',
                [true, true, true, false]],
            ['Canela', 'perro', 'Mestiza', 4, 'pequeno', 'hembra', 'disponible',
                'Canela es una cachorra llena de energía. Necesita completar su esquema de vacunas.',
                [false, true, false, true]],
        ];

        $mascotasChachani = [
            ['Toby', 'perro', 'Criollo', 18, 'mediano', 'macho', 'disponible',
                'Toby es activo y le encanta correr. Se adapta bien a casas con patio.',
                [true, true, true, true]],
            ['Mía', 'gato', 'Siamés mestizo', 12, 'pequeno', 'hembra', 'disponible',
                'Mía es independiente y muy limpia. Le gusta dormir junto a la ventana.',
                [true, true, true, true]],
            ['Simba', 'gato', 'Doméstico', 60, 'mediano', 'macho', 'disponible',
                'Simba es un gato adulto, tranquilo y cariñoso con las personas.',
                [false, true, true, false]],
            ['Kira', 'perro', 'Husky mestiza', 30, 'grande', 'hembra', 'disponible',
                'Kira necesita mucho ejercicio diario y un hogar con experiencia previa.',
                [true, true, true, true]],
        ];

        $mascotas = [];
        $cargar = function (Albergue $albergue, array $lista) use (&$mascotas) {
            foreach ($lista as [$nombre, $especie, $raza, $edad, $tamano, $genero, $estado, $descripcion, $ficha]) {
                $mascota = $albergue->mascotas()->create([
                    'nombre' => $nombre,
                    'especie' => $especie,
                    'raza' => $raza,
                    'edad_meses' => $edad,
                    'tamano' => $tamano,
                    'genero' => $genero,
                    'estado' => $estado,
                    'descripcion' => $descripcion,
                    'foto_url' => null, // las fotos se suben desde el panel del albergue
                ]);

                [$esterilizado, $antirrabica, $quintuple, $desparasitado] = $ficha;
                $mascota->fichaMedica()->create([
                    'esterilizado' => $esterilizado,
                    'vacuna_antirrabica' => $antirrabica,
                    'vacuna_quintuple' => $quintuple,
                    'desparasitado' => $desparasitado,
                    'fecha_ultima_revision' => now()->subDays(20)->toDateString(),
                ]);

                $mascotas[$nombre] = $mascota;
            }
        };

        $cargar($albergues['Albergue Patitas Unidas'], $mascotasPatitas);
        $cargar($albergues['Rescate Chachani'], $mascotasChachani);

        // ---------- Adoptantes ----------
        $maria = User::create([
            'name' => 'María Quispe',
            'email' => 'maria@huellitas.pe',
            'password' => self::CLAVE,
            'rol' => 'adoptante',
            'distrito' => 'Cayma',
        ]);

        $jorge = User::create([
            'name' => 'Jorge Mamani',
            'email' => 'jorge@huellitas.pe',
            'password' => self::CLAVE,
            'rol' => 'adoptante',
            'distrito' => 'Paucarpata',
        ]);

        // ---------- Solicitudes de ejemplo ----------
        Solicitud::create([
            'mascota_id' => $mascotas['Luna']->id,
            'adoptante_id' => $maria->id,
            'numero_personas' => 4,
            'tipo_vivienda' => 'propia',
            'tiene_patio' => true,
            'permiso_mascotas' => null,
            'experiencia_previa' => 'Tuve un perro durante 10 años.',
            'acepta_compromiso' => true,
            'estado' => 'pendiente',
        ]);

        Solicitud::create([
            'mascota_id' => $mascotas['Max']->id,
            'adoptante_id' => $jorge->id,
            'numero_personas' => 3,
            'tipo_vivienda' => 'alquilada',
            'tiene_patio' => false,
            'permiso_mascotas' => true,
            'experiencia_previa' => 'Es mi primera adopción.',
            'acepta_compromiso' => true,
            'estado' => 'pendiente',
        ]);

        Solicitud::create([
            'mascota_id' => $mascotas['Misu']->id,
            'adoptante_id' => $maria->id,
            'numero_personas' => 4,
            'tipo_vivienda' => 'propia',
            'tiene_patio' => true,
            'permiso_mascotas' => null,
            'experiencia_previa' => 'Convivimos con gatos desde hace años.',
            'acepta_compromiso' => true,
            'estado' => 'aprobada',
            'respuesta' => 'Aprobada. Coordinemos la entrega esta semana.',
        ]);
    }
}