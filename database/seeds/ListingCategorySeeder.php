<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\ListingCategory;

class ListingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
		$data = array(
                        array("category_name" => "Category-1"),
                        array("category_name" => "Category-2"),
                        array("category_name" => "Category-3")
					);

		foreach ($data as $key => $value) {
				$db = ListingCategory::create($value);		
		}
    }
}
