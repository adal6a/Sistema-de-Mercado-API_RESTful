<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Category;
use App\Product;
use App\Transaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**************************************************/
        /* Para MySQL/MariaDB */
        /* Limpiar toda la BD antes de ejecutar los seeds */

        //Evitar inconsistencias al momento de limpiar
        //DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        //User::truncate();
        //Category::truncate();
        //Product::truncate();
        //Transaction::truncate();
        /**************************************************/

        //Acceso a la tabla pivote para poder limpiar
        DB::table('category_product')->truncate();

        //Cantidad de datos
        $cantidadUsuarios = 1000;
        $cantidadCategorias = 30;
        $cantidadProductos = 1000;
        $cantidadTransacciones = 1000;

        /* Ejecutar los seeds */
        factory(User::class, $cantidadUsuarios)->create();
        factory(Category::class, $cantidadCategorias)->create();

        factory(Product::class, $cantidadProductos)->create()->each(function($producto){
            $categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');

            $producto->categories()->attach($categorias);
        });

        factory(Transaction::class, $cantidadTransacciones)->create();
    }
}
