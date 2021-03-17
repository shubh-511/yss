<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\ListingRegion;

class ListingRegionSeeder extends Seeder
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
                        array("region_name" => "Region-1"),
                        array("region_name" => "Region-2"),
                        array("region_name" => "Region-3")
					);

		foreach ($data as $key => $value) {
				$db = ListingRegion::create($value);		
		}
    }
}
