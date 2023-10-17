<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'site.title',
                'display_name' => 'Site Title',
                'value' => 'Site Title',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Site',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'site.description',
                'display_name' => 'Site Description',
                'value' => 'Site Description',
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Site',
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'site.logo',
                'display_name' => 'Site Logo',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Site',
            ),
            3 => 
            array (
                'id' => 4,
                'key' => 'site.google_analytics_tracking_id',
                'display_name' => 'Google Analytics Tracking ID',
                'value' => NULL,
                'details' => '',
                'type' => 'text',
                'order' => 4,
                'group' => 'Site',
            ),
            4 => 
            array (
                'id' => 5,
                'key' => 'admin.bg_image',
                'display_name' => 'Admin Background Image',
                'value' => NULL,
                'details' => '',
                'type' => 'image',
                'order' => 5,
                'group' => 'Admin',
            ),
            5 => 
            array (
                'id' => 6,
                'key' => 'admin.title',
                'display_name' => 'Admin Title',
                'value' => 'Prestamos',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ),
            6 => 
            array (
                'id' => 7,
                'key' => 'admin.description',
                'display_name' => 'Admin Description',
                'value' => 'Sistema de Administración de Préstamos',
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Admin',
            ),
            7 => 
            array (
                'id' => 8,
                'key' => 'admin.loader',
                'display_name' => 'Admin Loader',
                'value' => NULL,
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Admin',
            ),
            8 => 
            array (
                'id' => 9,
                'key' => 'admin.icon_image',
                'display_name' => 'Admin Icon Image',
                'value' => NULL,
                'details' => '',
                'type' => 'image',
                'order' => 4,
                'group' => 'Admin',
            ),
            9 => 
            array (
                'id' => 10,
                'key' => 'admin.google_analytics_client_id',
            'display_name' => 'Google Analytics Client ID (used for admin dashboard)',
                'value' => NULL,
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ),
            10 => 
            array (
                'id' => 12,
                'key' => 'configuracion.development',
                'display_name' => 'Sistema en Desarrollo',
                'value' => '0',
                'details' => NULL,
                'type' => 'checkbox',
                'order' => 7,
                'group' => 'Configuración',
            ),
            11 => 
            array (
                'id' => 13,
                'key' => 'configuracion.dollar',
            'display_name' => 'Precio del Dolar ($)',
                'value' => '6.8465735',
                'details' => NULL,
                'type' => 'text',
                'order' => 8,
                'group' => 'Configuración',
            ),
            12 => 
            array (
                'id' => 14,
                'key' => 'configuracion.porcentageGarment',
            'display_name' => 'Interes de Prenda (%)',
                'value' => '10',
                'details' => NULL,
                'type' => 'text',
                'order' => 9,
                'group' => 'Configuración',
            ),
            13 => 
            array (
                'id' => 15,
                'key' => 'servidores.whatsapp',
                'display_name' => 'Whatsapp',
                'value' => 'https://waserver2.ideacreativa.dev',
                'details' => NULL,
                'type' => 'text',
                'order' => 10,
                'group' => 'Servidores',
            ),
            14 => 
            array (
                'id' => 16,
                'key' => 'servidores.image-from-url',
                'display_name' => 'Generador de imágenes',
                'value' => '',
                'details' => NULL,
                'type' => 'text',
                'order' => 11,
                'group' => 'Servidores',
            ),
        ));
        
        
    }
}