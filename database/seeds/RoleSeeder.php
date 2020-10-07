<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;

class RoleSeeder extends Seeder
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
                        array("role" => "admin","name"=>"Administrator"),
                        array("role" => "counsellor","name"=>"Counsellor"),
                        array("role" => "consumer","name"=>"Consumer")
					);

		foreach ($data as $key => $value) {
				$db = Role::create($value);		
		}
    }
}
