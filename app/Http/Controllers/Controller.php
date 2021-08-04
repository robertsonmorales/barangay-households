<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Auth;
use DB;
use Crypt;
use Hash;
use Validator;
use Artisan;
use Route;
use Gate;
use Arr;
use Str;
use Carbon\Carbon;

use App\Models\User;
use App\Models\AuditTrailLogs;

use Purifier;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;
    public function __construct(User $user){
        $this->user = $user;
    }

    public function ipAddress(){
        $_IP = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $_IP = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $_IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $_IP = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $_IP = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $_IP = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $_IP = $_SERVER['REMOTE_ADDR'];
        else
            $_IP = 'UNKNOWN';
        return $_IP;
    }

    public function audit_trail_logs($module, $action_taken, $remarks, $dataid){
        $user = Auth::user();
        $username = Auth::check() ? $user->username : '';

        $currentPath= Route::getFacadeRoot()->current()->uri();
        $currentPath = explode('/', $currentPath);
        $module = ($module == '') ? strtoupper($currentPath[0]) : strtoupper($module);

        $mode = strtoupper((array_key_exists(2, $currentPath)) ? $currentPath[2] : '');
        $data = ($dataid == '') ? '' : ' ID: '.$dataid;
        $action_taken = ($mode == 'EDIT' || $mode == 'DELETE')
            ? $mode.' ID: '.$dataid
            : (($action_taken == '') 
                ? 'VIEWING '.$module
                : strtoupper($action_taken.$data));

        AuditTrailLogs::insert([
            'module' => @$module,
            // 'route' => @$route,
            'action' => $action_taken,
            'username' => @$username,
            'remarks' => @$remarks,
            'ip' => $this->ipAddress(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    public function breadcrumbs($name, $mode){
        return $breadcrumb = array(
            'name' => $name,
            'mode' => $mode
        );
    }

    public function safeInputs($input){
        return Purifier::clean($input, [
            'HTML.Allowed' => ''
        ]);
    }

    public function changeValue($rows){
        foreach ($rows as $key => $value) {
    
            if(Arr::exists($value, 'status')){
                 if($value->status == 1){
                    $value->status = 'Active';
                 }else{
                    $value->status = 'In-active';
                 }
            }

            if(Arr::exists($value, 'created_by')){
                $users = User::find($value['created_by']);
                $value['created_by'] = @$users->username;
            }

            if(Arr::exists($value, 'updated_by')){
                $users = User::find($value['updated_by']);
                $value['updated_by'] = @$users->username;
            }
        }

        return $rows;
    }

    // encrypt data
    public function encrypts($data){
        return Crypt::encryptString($data);
    }

    // decrypt data
    public function decrypts($data){
        return Crypt::decryptString($data);
    }

    public function hashCheck($password, $hash){
        return Hash::check($password, $hash);
    }

    public function hashMake($password){
        return Hash::make($password);
    }

    public function generateNavigationFiles($model, $controller, $type, $folder){
        $controller = $folder.'/'.$controller.'Controller';

        $makeController = Artisan::queue('make:controller', [
            'name' => $controller, 
            '--resource' => $controller
        ]);

        $makeModel = Artisan::queue('make:model', [
            'name' => $model, 
            '-m' => $model
        ]);
    }

    public function uploadImage($data, $path, $width, $height){
        if ($data->isValid()) {
            $publicFolder = $path;
            $profileImage = $data->getClientOriginalName(); // returns original name
            $extension = $data->getclientoriginalextension(); // returns the file extension
            $newProfileImage = strtolower(Str::random(20)).'-'.date('Y-m-d').'.'.$extension;
            $move = $data->storeAs($publicFolder, $newProfileImage);
          
            if ($move) {
                return $newProfileImage;
            }
        }
    }
}
