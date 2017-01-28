<?php
/**
 * Created by PhpStorm.
 * User: ateeq-ahmed
 * Date: 28/1/17
 * Time: 6:09 PM
 */

namespace App\Http\Controllers\HALAteeqControllers;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function main()
    {
        $data = self::getProfileData();
        return view('freelancer.profile')->with('data',$data);
    }

    public function getProfileData(){
        $user_id = Auth::user()->id;

        $userData = User::leftJoin('user_multi_tenant','user_multi_tenant.user_id','=','users.id')
                        ->leftJoin('tenants','tenants.id','=','user_multi_tenant.tenant_id')
                        ->selectRaw('users.name as user_name,' .
                                'users.email as user_email,' .
                                'tenants.tenant_name as tenant_name')
                        ->first();

        $userDataArray = array();

        $userDataArray['user_name'] = isset($userData->user_name) && $userData->user_name != null ? $userData->user_name : '';
        $userDataArray['user_email'] = isset($userData->user_email) && $userData->user_email != null ? $userData->user_email : '';
        $userDataArray['user_tenant'] = isset($userData->user_tenant) && $userData->user_tenant != null ? $userData->user_tenant : '';

        return $userDataArray;
    }

    protected function updateProfile(array $data)
    {
        if(Auth::check()) {
            $user = User::update(array(
                'name' => $data['name'],
                'email' => $data['email']))
                ->where('id',Auth::user()->id);

            if($data['password'] != 'NOCHANGE'){
                $user = User::update(array(
                    'password' => bcrypt($data['password'])))
                    ->where('id',Auth::user()->id);
            }

        }
        return '';
    }
}