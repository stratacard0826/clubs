<?php

namespace App\Http\Controllers\PrivateArea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
use App\AdminUser;
use Illuminate\Support\Facades\Hash;
use Validator;

class AdminUserController extends Controller
{
    public function index()
    {
        $response_data["title"] = __("title.private.employee_list");
        return view("private.employee.list")->with($response_data);
    }

    public function getList(Request $request)
    {
        $query = AdminUser::whereHas('userType', function ($query) {
                    $query->where('code', '=', 'subadmin');
                });
        
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }
       return Datatables::of($query->get())->make(true);
    }

    public function myProfile(Request $request)
    {
        $response_data["title"] = __("title.private.my_profile");
        $response_data["profile"] = auth()->user();
        return view("private.employee.myprofile")->with($response_data);
        
    }

    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            "current_password"  => "required",
            'password'          => 'required|confirmed|min:'.limit("password.min").'|max:'.limit("password.max").'|password|different:current_password',
        ]);

        if(!$validator->fails()){
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->updated_by = $user->id;
                if($user->save()){
                    $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Password"])];
                }else{
                    $response_data = ["success" => 0, "message" => __("site.server_error")];
                }
            }else{

                $response_data = ["success" => 0,  "message" => __("site.invalid_password")];
            }
        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }

        return response()->json($response_data);
        
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'name'        => 'required|min:'.limit("name.min").'|max:'.limit("name.max").'|valid_name',
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max").'|unique:users,email,'.$user->id.',id,active,1,deleted_at,NULL',
              'phone'       => 'required|numeric|digits:'.limit("phone.max").'|unique:users,phone,'.$user->id.',id,active,1,deleted_at,NULL',
              'profile_pic' => 'nullable|image|mimes:'.limit("profile_pic.format").'|max:'.limit("profile_pic.max"),
            ]);
        if(!$validator->fails()){

        	$filePath = "images/private/profile/";
            if($request->hasFile('profile_pic')){
                $user->profile_pic = url("storage/".$request->file('profile_pic')->store($filePath));
            }

            $user->name        = $request->name;
            $user->email       = $request->email;
            $user->phone       = $request->phone;
            $user->tel_code    = +1;
            $user->updated_by  = $user->id;

            if($user->save()){
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Profile"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    "value" => "required|in:0,1",
                    "pk" => "required|exists:admin_users,id",
                ]);
        if(!$validator->fails()){
            $user = AdminUser::find($request->pk);
            $user->active = $request->value;
            if($user->save()){
                $response_data = ["success" => 1, "message" => __("validator.update_success", ["attr" => "Employee Status"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }else{
            $response_data = ["success" =>  0, "message" => __("validation.refresh_page")];
        }
       return response()->json($response_data);
    }

    //Employee Delete
    public function destroy(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:admin_users,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $user = AdminUser::find($request->data); 
            if(!$user->isAdmin()){
                if($user->delete()){
                    $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'Employee'])]; 
                }else{
                    
                    $response_data =  ['success' => 1, 'message' => __('site.server_error')]; 
                }    
            }else{
                $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
            }

        }else
        {
            $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
        }

        return response()->json($response_data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function logout()
    {
    	Auth::logout();
        return redirect(route("login"));
    }
}
