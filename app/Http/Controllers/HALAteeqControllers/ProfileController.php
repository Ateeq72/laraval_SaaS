<?php
/**
 * Created by PhpStorm.
 * User: ateeq-ahmed
 * Date: 28/1/17
 * Time: 6:09 PM
 */

namespace App\Http\Controllers\HALAteeqControllers;


use App\Http\Controllers\Controller;
use App\Tenants;
use App\User;
use App\UserMultiTenant;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ProfileController extends Controller
{
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function main()
    {
        $data = self::getProfileData();
        return view('freelancer.profile')->with('data',$data);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'tenant_name' => 'required|max:255',
        ]);
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
        $userDataArray['user_tenant'] = isset($userData->tenant_name) && $userData->tenant_name != null ? $userData->tenant_name : '';

        return $userDataArray;
    }

    protected function updateProfile()
    {
        $data = Input::all();

        if(Auth::check()) {
            $curUserId = Auth::user()->id;
            $user = User::where('id',$curUserId)->first();
            $user->name = $data['name'];
            $user->email = $data['email'];

            if($data['password'] != 'NOCHANGE'){
            $user->password = bcrypt($data['password']);
            }

            $user->save();

            $userTenant = UserMultiTenant::where('user_id',$curUserId)->first();

            $tenant = Tenants::where('tenant_name',$data['tenant_name'])->first();

            if($userTenant != null && $tenant != null){
                $userTenant->user_id = $curUserId;
                $userTenant->tenant_id = $tenant->id;

                $userTenant->save();
            }
            else if($userTenant == null && $tenant != null){
                $userTenant = new UserMultiTenant();

                $userTenant->user_id = $curUserId;
                $userTenant->tenant_id = $tenant->id;
                $userTenant->save();
            }
            else if($userTenant != null && $tenant == null)
            {
                $tenant = new Tenants();
                $tenant->tenant_name = $data['tenant_name'];
                $tenant->tenant_paid = 'N';
                $tenant->save();

                $userTenant->user_id = $curUserId;
                $userTenant->tenant_id = $tenant->id;
                $userTenant->save();
            }
            else if ($userTenant == null && $tenant == null){

                $tenant = new Tenants();
                $tenant->tenant_name = $data['tenant_name'];
                $tenant->tenant_paid = 'N';
                $tenant->save();

                $userTenant = new UserMultiTenant();
                $userTenant->user_id = $curUserId;
                $userTenant->tenant_id = $tenant->id;
                $userTenant->save();

            }

        return redirect('/profile');
        }
        return 'Failure';
    }
}