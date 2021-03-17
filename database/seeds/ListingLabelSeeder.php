<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\ListingLabel;

class ListingLabelSeeder extends Seeder
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
                        array("label_name" => "Label-1"),
                        array("label_name" => "Label-2"),
                        array("label_name" => "Label-3")
					);

		foreach ($data as $key => $value) {
				$db = ListingLabel::create($value);		
		}
    }
}
