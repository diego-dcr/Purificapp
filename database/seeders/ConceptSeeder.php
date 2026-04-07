<?php

namespace Database\Seeders;

use App\Models\Concept;
use Illuminate\Database\Seeder;

class ConceptSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $concepts = [
            ['code' => '1', 'name' => 'pag 25'],
            ['code' => '2', 'name' => 'pag 22'],
            ['code' => '3', 'name' => 'fiado 25'],
            ['code' => '4', 'name' => 'fiado 22'],
            ['code' => '5', 'name' => 'pf 25'],
            ['code' => '6', 'name' => 'pf 22'],
            ['code' => '7', 'name' => 'pl contado'],
            ['code' => '8', 'name' => 'pl fiado'],
            ['code' => '9', 'name' => 'abono dinero pls fiados'],
            ['code' => '10', 'name' => 'prestado'],
            ['code' => '11', 'name' => 'reg pl'],
            ['code' => '12', 'name' => 'extra'],
            ['code' => '13', 'name' => 'reg extra'],
            ['code' => '14', 'name' => 'cambio'],
            ['code' => '15', 'name' => 'roto'],
            ['code' => '16', 'name' => 'faltante'],
            ['code' => '17', 'name' => 'abono faltante'],
            ['code' => '18', 'name' => 'gas'],
            ['code' => '19', 'name' => 'refa'],
            ['code' => '20', 'name' => 'prestamo $'],
            ['code' => '21', 'name' => 'mecanico'],
            ['code' => '22', 'name' => 'casa'],
            ['code' => '23', 'name' => 'gratis emp'],
            ['code' => '24', 'name' => 'gratis clte'],
            ['code' => '25', 'name' => 'feria'],
            ['code' => '26', 'name' => 'abonos'],
            ['code' => '27', 'name' => 'baja'],
            ['code' => '28', 'name' => 'roto produccion'],
            ['code' => '29', 'name' => 'anexos'],
            ['code' => '30', 'name' => 'sueldo rey'],
            ['code' => '31', 'name' => 'sueldo paco'],
            ['code' => '32', 'name' => 'sueldo lalo'],
            ['code' => '33', 'name' => '-'],
            ['code' => '34', 'name' => '-'],
            ['code' => '35', 'name' => 'dinero de caja sueldos'],
            ['code' => '36', 'name' => 'cambios fiados'],
            ['code' => '37', 'name' => 'pago cambios fiados'],
            ['code' => '38', 'name' => 'pago cambios contado'],
            ['code' => '39', 'name' => 'pago pls contado'],
            ['code' => '40', 'name' => 'entrega'],
            ['code' => '50', 'name' => 'imss rey'],
            ['code' => '51', 'name' => 'imss paco'],
            ['code' => '55', 'name' => 'paga fiados 23'],
            ['code' => '56', 'name' => 'lavadora 7289'],
            ['code' => '57', 'name' => 'roto rey'],
            ['code' => '58', 'name' => 'roto paco'],
            ['code' => '59', 'name' => 'roto lalo'],
            ['code' => '60', 'name' => 'roto isa'],
            ['code' => '61', 'name' => 'roto berna'],
            ['code' => '62', 'name' => 'roto vero'],
            ['code' => '63', 'name' => 'roto saul'],
            ['code' => '64', 'name' => 'roto gera'],
            ['code' => '65', 'name' => 'roto julio'],
            ['code' => '66', 'name' => 'rechazos en puri'],
            ['code' => '75', 'name' => 'a mitad'],
            ['code' => '100', 'name' => 'inicia garrafones año'],
        ];

        foreach ($concepts as $concept) {
            Concept::updateOrCreate(
                ['code' => $concept['code']],
                [
                    'name' => $concept['name'],
                    'type' => Concept::TYPE_INCOME,
                ],
            );
        }
    }
}
