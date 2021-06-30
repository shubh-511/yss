<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
Use App\RoleModule;
Use App\Module;
use Illuminate\Support\Facades\Auth; 
use Validator;
trait CheckPermission
{
	 public function permission($userId)
    {
    	$user = User::where('id', $userId)->first();
    	$role_module=RoleModule::whereIn('role_id',[$user->role_id])->pluck('module_id')->toArray();
    	$module_name=Module::whereIn('id',$role_module)->pluck('module_name')->toArray();
    	return $module_name;
    }
}