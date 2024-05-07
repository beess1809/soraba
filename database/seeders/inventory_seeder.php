<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class inventory_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created_at = date('Y-m-d H:i:s');

        // DB::table('warehouses')->insert([
        //     'id' => 1,
        //     'code' => 'HOW',
        //     'name' => 'Head Warehouse',
        //     'address' => 'Sesame Street',
        //     'created_at' => $created_at,
        //     'updated_at' => $created_at
        // ]);

        // DB::table('uoms')->insert([
        //     [
        //         'id' => 1,
        //         'code' => 'pcs',
        //         'name' => 'Pieces',
        //         'created_at' => $created_at,
        //         'updated_at' => $created_at
        //     ],
        //     [
        //         'id' => 2,
        //         'code' => 'kaleng',
        //         'name' => 'Kaleng',
        //         'created_at' => $created_at,
        //         'updated_at' => $created_at
        //     ],
        // ]);

        // DB::table('categories')->insert([
        //     [
        //         'id' => 1,
        //         'name' => 'OTC',
        //         'created_at' => $created_at,
        //         'updated_at' => $created_at
        //     ],
        //     [
        //         'id' => 2,
        //         'name' => 'Alat Kesehatan',
        //         'created_at' => $created_at,
        //         'updated_at' => $created_at
        //     ],
        //     [
        //         'id' => 3,
        //         'name' => 'Pencernaan',
        //         'created_at' => $created_at,
        //         'updated_at' => $created_at
        //     ],
        // ]);

        // DB::table('vendors')->insert([
        //     [
        //         'id' => 1,
        //         'name' => 'AMS',
        //         'address' => 'Sesame Street',
        //         'created_at' => $created_at,
        //         'updated_at' => $created_at
        //     ],
        // ]);

        // DB::table('items')->insert([
        //     [
        //         'id' => 1,
        //         'uom_id' => 1,
        //         'warehouse_id' => 1,
        //         'category_id' => 1,
        //         'vendor_id' => 1,
        //         'name' => 'POLYSILANE SUSP 100 ML',
        //         'composition' => 'al. hidroksida, mag. hidroksida, simetikon',
        //         'qty' => 10,
        //         'sale_price' => 10000,
        //         'created_at' => $created_at,
        //         'updated_at' => $created_at
        //     ],
        //     [
        //         'id' => 2,
        //         'uom_id' => 1,
        //         'warehouse_id' => 1,
        //         'category_id' => 2,
        //         'vendor_id' => 1,
        //         'name' => 'OXYCAN 500cc',
        //         'composition' => '',
        //         'qty' => 10,
        //         'sale_price' => 25000,
        //         'created_at' => $created_at,
        //         'updated_at' => $created_at
        //     ],
        // ]);

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Mimin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('12345678'),
            'created_at' => $created_at,
            'updated_at' => $created_at
        ]);

        $administrator = Role::create([
            'id' => 1,
            'name' => 'administrator',
            'display_name' => 'Administrator',
            'description' => 'User is the owner of a given project'
        ]);
        $user = User::findOrFail(1);
        if ($user) {
            $user->attachRole($administrator);
        }

        DB::table('menus')->insert([
            ['id' => 1, 'menu_id' => null, 'name' => 'auth', 'display_name' => 'Auth', 'order_no' => 1, 'url' => '', 'icon' => 'fas fa-key', 'created_at' => $created_at],
            ['id' => 2, 'menu_id' => 1, 'name' => 'user', 'display_name' => 'User', 'order_no' => 1, 'url' => '/auth/user', 'icon' => 'far fa-user', 'created_at' => $created_at],
            ['id' => 3, 'menu_id' => 1, 'name' => 'role', 'display_name' => 'Role', 'order_no' => 2, 'url' => '/auth/role', 'icon' => 'fas fa-arrows-alt', 'created_at' => $created_at],
            ['id' => 4, 'menu_id' => 1, 'name' => 'menu', 'display_name' => 'Menu', 'order_no' => 3, 'url' => '/auth/menu', 'icon' => 'fas fa-list', 'created_at' => $created_at],
            ['id' => 5, 'menu_id' => 1, 'name' => 'permission', 'display_name' => 'Permission', 'order_no' => 4, 'url' => '/auth/permission', 'icon' => 'fas fa-user-secret', 'created_at' => $created_at],
            ['id' => 6, 'menu_id' => null, 'name' => 'master', 'display_name' => 'Master Data', 'order_no' => 2, 'url' => '#', 'icon' => 'fa fa-cogs', 'created_at' => $created_at],
            ['id' => 7, 'menu_id' => 6, 'name' => 'warehouse', 'display_name' => 'Warehouse', 'order_no' => 1, 'url' => '/master/warehouse', 'icon' => 'fas fa-warehouse', 'created_at' => $created_at],
            ['id' => 8, 'menu_id' => 6, 'name' => 'uom', 'display_name' => 'UOM', 'order_no' => 2, 'url' => '/master/uom', 'icon' => 'fa fa-balance-scale', 'created_at' => $created_at],
            ['id' => 9, 'menu_id' => 6, 'name' => 'category', 'display_name' => 'Category', 'order_no' => 3, 'url' => '/master/category', 'icon' => 'fa fa-folder-open', 'created_at' => $created_at],
            ['id' => 10, 'menu_id' => 6, 'name' => 'vendor', 'display_name' => 'Vendor', 'order_no' => 4, 'url' => '/master/vendor', 'icon' => 'fa fa-user-tie', 'created_at' => $created_at],
            ['id' => 11, 'menu_id' => null, 'name' => 'items', 'display_name' => 'Item', 'order_no' => 3, 'url' => '/items', 'icon' => 'fas fa-boxes', 'created_at' => $created_at],
            ['id' => 12, 'menu_id' => null, 'name' => 'invoice', 'display_name' => 'Invoice', 'order_no' => 4, 'url' => '/invoice', 'icon' => 'fas fa-receipt', 'created_at' => $created_at],
            // ['id' => 14, 'menu_id' => null, 'name' => 'purchase', 'display_name' => 'Purchase', 'order_no' => 5, 'url' => '#', 'icon' => 'fas fa-shopping-cart', 'created_at' => $created_at],
            // ['id' => 15, 'menu_id' => 14, 'name' => 'purchase-order', 'display_name' => 'Purchase Order', 'order_no' => 1, 'url' => '/purchase', 'icon' => 'fas fa-cart-plus', 'created_at' => $created_at],
            // ['id' => 16, 'menu_id' => 14, 'name' => 'purchase-order-report', 'display_name' => 'Report', 'order_no' => 2, 'url' => '/purchase/report', 'icon' => 'fas fa-chart-line', 'created_at' => $created_at],
            // ['id' => 17, 'menu_id' => null, 'name' => 'sales', 'display_name' => 'Sales', 'order_no' => 6, 'url' => '#', 'icon' => 'fas fa-store', 'created_at' => $created_at],
            // ['id' => 18, 'menu_id' => 17, 'name' => 'sales-order', 'display_name' => 'Sales Order', 'order_no' => 1, 'url' => '/sales', 'icon' => 'fas fa-store-alt', 'created_at' => $created_at],
            // ['id' => 19, 'menu_id' => 17, 'name' => 'sales-order-report', 'display_name' => 'Sales Order Report', 'order_no' => 2, 'url' => '/sales/report', 'icon' => 'fas fa-chart-line', 'created_at' => $created_at],
            // ['id' => 20, 'menu_id' => null, 'name' => 'transfer-items', 'display_name' => 'Transfer Item', 'order_no' => 7, 'url' => '/transfer', 'icon' => 'fas fa-exchange-alt', 'created_at' => $created_at],
            // ['id' => 21, 'menu_id' => null, 'name' => 'finance-report', 'display_name' => 'Laporan Keuangan', 'order_no' => 8, 'url' => '/finance_report', 'icon' => 'fas fa-balance-scale', 'created_at' => $created_at],
        ]);

        DB::table('menu_role')->insert([
            ['menu_id' => 1, 'role_id' => 1],
            ['menu_id' => 2, 'role_id' => 1],
            ['menu_id' => 3, 'role_id' => 1],
            ['menu_id' => 4, 'role_id' => 1],
            ['menu_id' => 5, 'role_id' => 1],
            ['menu_id' => 6, 'role_id' => 1],
            ['menu_id' => 7, 'role_id' => 1],
            ['menu_id' => 8, 'role_id' => 1],
            ['menu_id' => 9, 'role_id' => 1],
            ['menu_id' => 10, 'role_id' => 1],
            ['menu_id' => 11, 'role_id' => 1],
            ['menu_id' => 12, 'role_id' => 1],
        ]);


        DB::table('statuses')->insert([
            [
                'id' => 1,
                'name' => 'Draft',
                'created_at' => $created_at,
                'updated_at' => $created_at
            ],
            [
                'id' => 2,
                'name' => 'Complete',
                'created_at' => $created_at,
                'updated_at' => $created_at
            ],
            [
                'id' => 3,
                'name' => 'Canceled',
                'created_at' => $created_at,
                'updated_at' => $created_at
            ],
        ]);

        DB::table('payment_types')->insert([
            [
                'id' => 1,
                'name' => 'Credit Card',
                'created_at' => $created_at,
                'updated_at' => $created_at
            ],
            [
                'id' => 2,
                'name' => 'Debit Card',
                'created_at' => $created_at,
                'updated_at' => $created_at
            ],
            [
                'id' => 4,
                'name' => 'E-Money',
                'created_at' => $created_at,
                'updated_at' => $created_at
            ],
            [
                'id' => 3,
                'name' => 'QRIS',
                'created_at' => $created_at,
                'updated_at' => $created_at
            ],
        ]);

        DB::table('parameters')->insert([
            [
                'id' => 1,
                'code' => 'dispensing',
                'name' => 'Dispensing Cost',
                'value' => '7000',
                'created_at' => $created_at,
                'updated_at' => $created_at
            ],
        ]);
    }
}
