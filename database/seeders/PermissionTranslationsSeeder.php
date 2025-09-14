<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Translation;

class PermissionTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $traducciones = [
            'browse_admin'       => 'Ver panel de administración',
            'browse_bread'       => 'Ver BREAD',
            'browse_database'    => 'Ver Base de datos',
            'browse_media'       => 'Ver Multimedia',
            'browse_compass'     => 'Ver Brújula',

            'browse_menus'       => 'Ver Menús',
            'read_menus'         => 'Leer Menús',
            'edit_menus'         => 'Editar Menús',
            'add_menus'          => 'Agregar Menús',
            'delete_menus'       => 'Eliminar Menús',

            'browse_roles'       => 'Ver Roles',
            'read_roles'         => 'Leer Roles',
            'edit_roles'         => 'Editar Roles',
            'add_roles'          => 'Agregar Roles',
            'delete_roles'       => 'Eliminar Roles',

            'browse_users'       => 'Ver Usuarios',
            'read_users'         => 'Leer Usuarios',
            'edit_users'         => 'Editar Usuarios',
            'add_users'          => 'Agregar Usuarios',
            'delete_users'       => 'Eliminar Usuarios',

            'browse_settings'    => 'Ver Configuraciones',
            'read_settings'      => 'Leer Configuraciones',
            'edit_settings'      => 'Editar Configuraciones',
            'add_settings'       => 'Agregar Configuraciones',
            'delete_settings'    => 'Eliminar Configuraciones',

            'browse_categories'     => 'Ver Categorías',
            'read_categories'       => 'Leer Categorías',
            'edit_categories'       => 'Editar Categorías',
            'add_categories'        => 'Agregar Categorías',
            'delete_categories'     => 'Eliminar Categorías',

            'browse_posts'          => 'Ver Post',
            'read_posts'            => 'Leer Post',
            'edit_posts'            => 'Editar Post',
            'add_posts'             => 'Agregar Post',
            'delete_posts'          => 'Eliminar Post',

            'browse_pages'          => 'Ver Páginas',
            'read_pages'            => 'Leer Páginas',
            'edit_pages'            => 'Editar Páginas',
            'add_pages'             => 'Agregar Páginas',
            'delete_pages'          => 'Eliminar Páginas',

            'browse_contacts'       => 'Ver Contáctos',
            'read_contacts'         => 'Leer Contáctos',
            'edit_contacts'         => 'Editar Contáctos',
            'add_contacts'          => 'Agregar Contáctos',
            'delete_contacts'       => 'Eliminar Contáctos',

            'browse_types'          => 'Ver Tipos',
            'read_types'            => 'Leer Tipos',
            'edit_types'            => 'Editar Tipos',
            'add_types'             => 'Agregar Tipos',
            'delete_types'          => 'Eliminar Tipos',

            'browse_rooms'          => 'Ver Habitaciones',
            'read_rooms'            => 'Leer Habitaciones',
            'edit_rooms'            => 'Editar Habitaciones',
            'add_rooms'             => 'Agregar Habitaciones',
            'delete_rooms'          => 'Eliminar Habitaciones',

            'browse_units'          => 'Ver Unidades',
            'read_units'            => 'Leer Unidades',
            'edit_units'            => 'Editar Unidades',
            'add_units'             => 'Agregar Unidades',
            'delete_units'          => 'Eliminar Unidades',

            'browse_taxonomies'     => 'Ver Categorías',
            'read_taxonomies'       => 'Leer Categorías',
            'edit_taxonomies'       => 'Editar Categorías',
            'add_taxonomies'        => 'Agregar Categorías',
            'delete_taxonomies'     => 'Eliminar Categorías',

            'browse_products'       => 'Ver Productos',
            'read_products'         => 'Leer Productos',
            'edit_products'         => 'Editar Productos',
            'add_products'          => 'Agregar Productos',
            'delete_products'       => 'Eliminar Productos',

            'browse_transactions'   => 'Ver Transacciones',
            'read_transactions'     => 'Leer Transacciones',
            'edit_transactions'     => 'Editar Transacciones',
            'add_transactions'      => 'Agregar Transacciones',
            'delete_transactions'   => 'Eliminar Transacciones',

            'browse_reservations'   => 'Ver Reservaciones',
            'read_reservations'     => 'Leer Reservaciones',
            'edit_reservations'     => 'Editar Reservaciones',
            'add_reservations'      => 'Agregar Reservaciones',
            'delete_reservations'   => 'Eliminar Reservaciones',

            'browse_business'       => 'Ver Empresas',
            'read_business'         => 'Leer Empresas',
            'edit_business'         => 'Editar Empresas',
            'add_business'          => 'Agregar Empresas',
            'delete_business'       => 'Eliminar Empresas',

            'browse_businesses'     => 'Ver Empresas',
            'read_businesses'       => 'Leer Empresas',
            'edit_businesses'       => 'Editar Empresas',
            'add_businesses'        => 'Agregar Empresas',
            'delete_businesses'     => 'Eliminar Empresas',

            'browse_invoices'       => 'Ver Tipo comprobantes',
            'read_invoices'         => 'Leer Tipo comprobantes',
            'edit_invoices'         => 'Editar Tipo comprobantes',
            'add_invoices'          => 'Agregar Tipo comprobantes',
            'delete_invoices'       => 'Eliminar Tipo comprobantes',

            'browse_movements'      => 'Ver comprobantes',
            'read_movements'        => 'Leer comprobantes',
            'edit_movements'        => 'Editar comprobantes',
            'add_movements'         => 'Agregar comprobantes',
            'delete_movements'      => 'Eliminar comprobantes',

            'browse_igv_types'      => 'Ver Tipos de IGV',
            'read_igv_types'        => 'Leer Tipos de IGV',
            'edit_igv_types'        => 'Editar Tipos de IGV',
            'add_igv_types'         => 'Agregar Tipos de IGV',
            'delete_igv_types'      => 'Eliminar Tipos de IGV',

            'browse_expenses'       => 'Ver Gastos',
            'read_expenses'         => 'Leer Gastos',
            'edit_expenses'         => 'Editar Gastos',
            'add_expenses'          => 'Agregar Gastos',
            'delete_expenses'       => 'Eliminar Gastos',

            'browse_boxes'          => 'Ver Cajas',
            'read_boxes'            => 'Leer Cajas',
            'edit_boxes'            => 'Editar Cajas',
            'add_boxes'             => 'Agregar Cajas',
            'delete_boxes'          => 'Eliminar Cajas',

            'browse_pay_methods'    => 'Ver Métodos de pago',
            'read_pay_methods'      => 'Leer Métodos de pago',
            'edit_pay_methods'      => 'Editar Métodos de pago',
            'add_pay_methods'       => 'Agregar Métodos de pago',
            'delete_pay_methods'    => 'Eliminar Métodos de pago',

            'browse_boxe_openings'  => 'Ver Aperturas de caja',
            'read_boxe_openings'    => 'Leer Aperturas de caja',
            'edit_boxe_openings'    => 'Editar Aperturas de caja',
            'add_boxe_openings'     => 'Agregar Aperturas de caja',
            'delete_boxe_openings'  => 'Eliminar Aperturas de caja',

            'browse_boxe_movements' => 'Ver Movimientos de caja',
            'read_boxe_movements'   => 'Leer Movimientos de caja',
            'edit_boxe_movements'   => 'Editar Movimientos de caja',
            'add_boxe_movements'    => 'Agregar Movimientos de caja',
            'delete_boxe_movements' => 'Eliminar Movimientos de caja',

            'browse_boxe_closures'  => 'Ver Cierres de caja',
            'read_boxe_closures'    => 'Leer Cierres de caja',
            'edit_boxe_closures'    => 'Editar Cierres de caja',
            'add_boxe_closures'     => 'Agregar Cierres de caja',
            'delete_boxe_closures'  => 'Eliminar Cierres de caja',
        ];

        foreach ($traducciones as $key => $value) {
            Translation::updateOrCreate([
                'table_name'  => 'permissions',
                'column_name' => 'key',
                'foreign_key' => \DB::table('permissions')->where('key', $key)->value('id'),
                'locale'      => 'es',
            ], [
                'value' => $value,
            ]);
        }
    }
}