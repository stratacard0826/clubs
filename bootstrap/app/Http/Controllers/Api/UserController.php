<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use App\SocialToken;
use App\DeviceToken;
use App\PasswordReset;
use App\UserNotification;
use App\Follower;
use App\Events\LoginEvent;
use Illuminate\Support\Facades\Hash;
use Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $new_request = $request->all();
        //Log::debug($new_request);
        $new_request["gender"] = (int)$request->gender;
        $validator = Validator::make($new_request,
            [
              'name'       	=> 'required|min:'.limit("name.min").'|max:'.limit("name.max").'|valid_name',
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max").'|not_exists:users,email',
              'phone'       => 'required|numeric|digits:'.limit("phone.max").'|not_exists:users,phone',
              'tel_code'    => 'required|in:+1',
              
              'password'    => 'required|min:'.limit("password.min").'|max:'.limit("password.max").'|password',
              'profile_pic' => 'nullable|image|mimes:'.limit("profile_pic.format").'|min:'.limit("profile_pic.min").'|max:'.limit("profile_pic.max").',|dimensions:min_width='.limit("profile_pic.min_width").',min_height='.limit("profile_pic.min_height"),
              'gender'      => 'required|integer|exists:genders,id,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

 		if(!$validator->fails()){
            $filePath = "images/profile/";
            if($request->hasFile('profile_pic')){
                $file = $request->file('profile_pic')->store($filePath);
                $file = "storage/".$file;
            }else{
                $file =  "storage/".$filePath.uniqid().".png";
                Avatar::create(strtoupper($request->name))->save($file, $quality = 90);
            }

 			$user = new User();
 			$user->name 		  = $request->name;
 			$user->email 	      = $request->email;
 			$user->password 	  = bcrypt($request->password);
 			$user->phone 	      = $request->phone;
 			$user->tel_code 	  = $request->tel_code;
 			$user->gender 	      = $request->gender;
 			$user->profile_pic    = url($file);
 			$user->active 	      = 1;
 			$user->created_by     = 1;
 			$user->updated_by     = 1;

 			if($user->save()){
	 			$token = $user->createToken('FunClub')->accessToken;
                event(new LoginEvent($user, $request->ip(), $request->device_type));
 				$response_data = ["success" => 1, "token" => $token, "data" => $user];
 			}else{
 				$response_data = ["success" => 0, "message" => __("site.server_error")];
 			}
 			
 		}else{
 			$response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
 		}
        //Log::debug($response_data);
 
        return response()->json($response_data);
    }


    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialRegister(Request $request)
    {
        $socialProvider = $request->provider;
        $validator = Validator::make($request->all(),
            [
              'name'        => 'required|min:'.limit("name.min").'|max:'.limit("name.max"),
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max").'|not_exists:users,email',
              'phone'       => 'required|numeric|digits:'.limit("phone.max").'|not_exists:users,phone',
              'tel_code'    => 'required|in:+1',
              'profile_pic' => 'nullable|image|mimes:'.limit("profile_pic.format").'|min:'.limit("profile_pic.min").'|max:'.limit("profile_pic.max").',|dimensions:min_width='.limit("profile_pic.min_width").',min_height='.limit("profile_pic.min_height"),
              'gender'      => 'required|exists:genders,id,active,1,deleted_at,NULL',
              'code'        => 'required|min:'.limit("social_token.min").'|max:'.limit("social_token.max").'|not_exists:social_tokens,code,active,1,provider,'.$socialProvider.',deleted_at,NULL',
              'provider'    => ['required', Rule::in(config("site.social_providers"))],
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);
        if(!$validator->fails()){

        	$filePath = "images/profile/";
            if($request->hasFile('profile_pic')){
                //$file = $request->file('profile_pic')->store($filePath);
                $file = $request->file('profile_pic')->store($filePath);
                $file = "storage/".$file;
            }else{
                $file =  "storage/".$filePath.uniqid().".png";
                Avatar::create(strtoupper($request->name))->save($file, $quality = 90);
            }

            $user = new User();
            $user->name           = $request->name;
            $user->email          = $request->email;
            $user->password       = "";
            $user->phone          = $request->phone;
            $user->tel_code       = $request->tel_code;
            $user->gender         = $request->gender;
            $user->profile_pic    = url($file);
            $user->active         = 1;
            $user->created_by     = 1;
            $user->updated_by     = 1;


            if($user->save()){
                $socialToken = new SocialToken();
                $socialToken->user_id = $user->id;
                $socialToken->code = $request->code;
                $socialToken->provider = $request->provider;
                $socialToken->active = 1;
                $socialToken->created_by = 1;
                $socialToken->updated_by = 1;
                if($socialToken->save()){
                    $token = $user->createToken('FunClub')->accessToken;
                    event(new LoginEvent($user, $request->ip(), $request->device_type));
                    $response_data = ["success" => 1, "token" => $token, "data" => $user];
                }else{
                    $response_data = ["success" => 0, "message" => __("site.server_error")];
                }
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        
 
        return response()->json($response_data);
    }




    /**
     * Handles Social Login Check
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialCheck(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max"),
              'code'        => 'required|max:200',
              'provider'    => ['required', Rule::in(config("site.social_providers"))],
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);
        if(!$validator->fails()){
            $user = User::whereEmail($request->email)->whereActive(1)->first();
            $social_token = SocialToken::whereCode($request->code)->whereProvider($request->provider)->whereActive(1)->first();
            if($user){
            	if($social_token && $social_token->user_id != $user->id){
            		$response_data = ["success" => 0, "register" => 0, "message" => __("validation.not_exists", ["Attribute" => "Social Code"])];
            	}else{
            		if(!$social_token){
            			$socialToken = new SocialToken();
            			$socialToken->user_id = $user->id;
            			$socialToken->code = $request->code;
            			$socialToken->provider = $request->provider;
            			$socialToken->active = 1;
            			$socialToken->created_by = $user->id;
            			$socialToken->updated_by = $user->id;
            			$socialToken->save();
            		}

	                $token = $user->createToken('FunClub')->accessToken;
                    event(new LoginEvent($user, $request->ip(), $request->device_type));
	                $response_data = ["success" => 1, "register" => 1, "token" => $token, "data" => $user];
            	}
            }else{
                $response_data = ["success" => 0, "register" => 0, "message" => __("validation.not_found", ["attr" => "User"])];
                /*if($social_token){
                    $response_data = ["success" => 0, "register" => 0, "message" => __("validation.not_exists", ["Attribute" => "Social Code"])];
                }else{
            	}*/
            }
            
        }else{
            $response_data = ["success" => 0, "register" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        
 
        return response()->json($response_data);
    }
 
    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'email'       => 'required|email',
              'password'   => 'required',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            $credentials = [
                'email'     => $request->email,
                'active'    => 1
            ];

            $user = User::whereEmail($credentials)->first();

            if ($user) {

                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('FunClub')->accessToken;
                    event(new LoginEvent($user, $request->ip(), $request->device_type));
                    $response_data = ["success" => 1,  "token" => $token, "data" => $user];
                } else {
                    $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
                }

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }
 
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $response_data = ["success" => 1, 'data' => auth()->user()];
        return response()->json($response_data);
    }


    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'name'        => 'required|min:'.limit("name.min").'|max:'.limit("name.max").'|valid_name',
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max").'|unique:users,email,'.$user->id.',id',
              'phone'       => 'required|numeric|digits:'.limit("phone.max").'|unique:users,phone,'.$user->id.',id',
              'tel_code'    => 'required|in:+1',
              'profile_pic' => 'nullable|image|mimes:'.limit("profile_pic.format").'|max:'.limit("profile_pic.max"),
              'gender'      => 'required|exists:genders,id,active,1,deleted_at,NULL',
              //'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);
        if(!$validator->fails()){

        	$filePath = "images/profile/";
            if($request->hasFile('profile_pic')){
                $user->profile_pic = url("storage/".$request->file('profile_pic')->store($filePath));
            }

            $user->name        = $request->name;
            $user->email       = $request->email;
            $user->phone       = $request->phone;
            $user->tel_code    = $request->tel_code;
            $user->gender      = $request->gender;
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

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
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


    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fcmUpdate(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'fcm_code'    => 'required|min:'.limit("fcm_code.min").'|max:'.limit("fcm_code.max"),
              'device_type' => 'required|exists:device_types,id,active,1,deleted_at,NULL',
            ]);

        if(!$validator->fails()){

            $defaultValue = ['user_id' => $user->id, 'code' => $request->fcm_code, 'device_type' => $request->device_type];
            $otherValues = ['created_by' => $user->id, 'updated_by' => $user->id];
            $deviceToken = DeviceToken::updateOrCreate($defaultValue, $otherValues);

            if($deviceToken){
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "FCM Code"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

        /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'password'  => 'required|confirmed|min:'.limit("password.min").'|max:'.limit("password.max").'|password',
            ]);

        if(!$validator->fails()){
            $user = auth()->user();

            $user->password = bcrypt($request->password);
            $user->updated_by = $user->id;
            if($user->save()){
                PasswordReset::whereEmail($user->email)->delete();
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Password"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }

        return response()->json($response_data);
    }


        /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function logout(Request $request)
    {
        
        $user = auth()->user();
        $user->token()->revoke();

        $response_data = ["success" => 1, "message" => __("site.logout_success")];

        return response()->json($response_data);
    }

    


}
