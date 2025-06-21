<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceGroup;

class DeviceGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeviceGroup::firstOrCreate(
            ['name' => 'Gnosis'],
            ['description' => 'Eine Sensorik-Gruppe für Aquaponiksysteme zur Überwachung kritischer Wasserparameter und Sicherstellung optimaler Bedingungen für Fische und Pflanzen']
        );

        DeviceGroup::firstOrCreate(
            ['name' => 'Apollo'],
            ['description' => 'Eigene Geräte, die durch Ausführen von Skripten Daten senden']
        );

        DeviceGroup::firstOrCreate(
            ['name' => 'Sonstige Geräte'],
            ['description' => 'Verschiedene Geräte, die keiner spezifischen Gruppe zugeordnet sind']
        );
    }
}
