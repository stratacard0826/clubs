<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Follower;
use App\User;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    /**
     * Returns Follower List
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersList(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'name'        => 'nullable|string',
              'limit'       => 'nullable|numeric',
            ]);
        if(!$validator->fails()){

            $query = User::select("id", "name", "gender","profile_pic");
            
            if($request->filled("name")){
                $query->search($request->name);
               
            }else{
                $query->orderBy('name', 'asc');
            }

            $query->whereActive(1);
            if($request->limit > 0){
                $data = $query->paginate($request->limit);
            }else{
                $data = $query->get();
            }
            $response_data = ["success" => 1, "data" => $data];
        }
        else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        
        return response()->json($response_data);
    }


       /**
     * Create Follow or Unfollow
     *
     * @param  [string] email
     * @return [string] message
     */
    public function follow(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), 
            [
                'status'     => 'required|numeric|in:0,1',
                'user_id'    => 'required|numeric|not_in:'.$user->id,
            ]);

        if(!$validator->fails()){
            
            $deleted_at = NULL;
            $follow = "Follow";

            if($request->status == 0)
            {
                $follow = "Unfollow";
                $deleted_at = now();
            }

            $data = Follower::updateOrInsert(
                        ['follower_id' => $user->id, 'followed_id' => $request->user_id],
                        ['created_by' => $user->id, 'updated_by' => $user->id, "deleted_at" => $deleted_at]
                    );

            if($data)
             {
                 $response_data =  ['success' => 1, 'message' => __('validation.unfollow_success',['attr'=> $follow])];
             }
             else
             {
                 $response_data = ["success" => 0, "message" => __("site.server_error")];
             }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }

        return response()->json($response_data);
    }

      /**
     * Follower List
     *
     * @param  [string] email
     * @return [string] message
     */
    public function followerList(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'limit'        => 'nullable|numeric',
              'status'       => 'required|numeric|in:1,2',
            ]);
        if(!$validator->fails()){

            if($request->status == 1)
            {
                $follower = Follower::select("followed_id")->whereFollowerId($user->id)->get();
                $query = User::whereIn("id",$follower);
                if($request->limit > 0){
                    $data = $query->paginate($request->limit);
                }else{
                    $data = $query->get();
                }

                $data = $user->followerList;
            }
            else
            {
                $follower = Follower::select("follower_id")->whereFollowedId($user->id)->get();
                $query = User::whereIn("id",$follower);
                if($request->limit > 0){
                    $data = $query->paginate($request->limit);
                }else{
                    $data = $query->get();
                }

                $data = $user->followedList;
            }

            
            //dd($data->toArray());
            $response_data = ["success" => 1, "data" => $data];
            
        }
        else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        
        return response()->json($response_data);
    }


}
