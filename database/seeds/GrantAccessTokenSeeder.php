<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\GrantAccessToken;

class GranAccessTokenSeeder extends Seeder
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
                        array("site_url" => "http://yss.com","site_key"=>"JXiUC5SrgsKPycCnYklhxCnsrW")
					);

		foreach ($data as $key => $value) {
				$db = GrantAccessToken::create($value);		
		}
    }
}
