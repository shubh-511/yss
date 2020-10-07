<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\SiteSetting;

class SiteSettingSeeder extends Seeder
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
                        array("meta_key" => "","meta_value"=>"")
					);

		foreach ($data as $key => $value) {
				$db = SiteSetting::create($value);		
		}
    }
}
