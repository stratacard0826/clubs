<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\UserPost;
use App\PostComment;
use App\PostLike;

class CommentController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    	$user = auth()->user();
    	$validator = Validator::make($request->all(),
        [
            'post_id'          	=> 'required|integer|exists:user_posts,id,active,1,deleted_at,NULL',
            'text'          	=> 'required|max:'.limit("post_text.max"),
            'parent_id'         => 'nullable|integer|exists:post_comments,id,deleted_at,NULL',

        ]);

		if(!$validator->fails()){
			$parent_id =0;
			if(!empty($request->parent_id) || $request->parent_id!=0)
			{
				$parent_id = $request->parent_id;
			}
			$comment = new PostComment();
			$comment->post_id           = $request->post_id;
			$comment->text           	= $request->text;
			$comment->user_id           = $user->id;
			$comment->parent_id         = $parent_id;
			$comment->like_count        = 0;
			$comment->created_by        = $user->id;
			$comment->updated_by        = $user->id;
			if($comment->save())
			{
				$response_data =  ['success' => 1, 'message' => __('validation.create_success',['attr'=> 'Comment'])];
			}else
			{
				$response_data = ["success" => 0, "message" => __("site.server_error")];
			}
		}
		else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        
        return response()->json($response_data);
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PostComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();
    	$validator = Validator::make($request->all(),
        [
        	'comment_id'        => 'required|integer|exists:post_comments,id,deleted_at,NULL',
            'text'          	=> 'required|max:'.limit("post_text.max"),

        ]);
        if(!$validator->fails()){
			$comment = PostComment::whereId($request->comment_id)->first();
			$comment->text           	= $request->text;
			$comment->user_id           = $user->id;
			$comment->updated_by        = $user->id;
			if($comment->save())
			{
				$response_data =  ['success' => 1, 'message' => __('validation.update_success',['attr'=> 'Comment'])];
			}else
			{
				$response_data = ["success" => 0, "message" => __("site.server_error")];
			}
		}
		else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        
        return response()->json($response_data);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PostComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:post_comments,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $comment = PostComment::find($request->data); 
            if($comment->delete()){
                $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'Comment'])]; 
            }else{
                
                $response_data =  ['success' => 1, 'message' => __('site.server_error')]; 
            }    
        }else
        {
            $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
        }

        return response()->json($response_data);
    }

     /**
     * increment or decrement the specified resource from storage.
     *
     * @param  \App\PostComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function commentLike(Request $request)
    {
    	$user = auth()->user();
    	$validator = Validator::make($request->all(),
            [
              'comment_id' 	=> 'required|exists:post_comments,id,deleted_at,NULL',
              'comment_status' 		=> 'required|integer|in:0,1',
            ]);
            
        if (!$validator->fails()) 
        { 

        	//Like means 1 and unlike means 0
        	$deleted_at = NULL;
            $like = "Like";

            if($request->comment_status == 0)
            {
                $like = "Unlike";
                $deleted_at = now();
            }
        	$data = PostLike::updateOrInsert(
                        ['likeable_id' => $request->comment_id, 'type' => 2, 'user_id' => $user->id],
                        ['created_by' => $user->id, 'updated_by' => $user->id, "deleted_at" => $deleted_at]
                    );
        	if($data)
        	{
        		if($request->comment_status == 1)
        		{
        			PostComment::find($request->comment_id)->increment('like_count');
        		}elseif ($request->comment_status == 0) {
        			PostComment::find($request->comment_id)->decrement('like_count');
        		}

        		$response_data =  ['success' => 1, 'message' => __('validation.unfollow_success',['attr'=> $like])];
        	}
            else
            {
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }else
        {
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }

        return response()->json($response_data);
    }


    /**
     * Get Comment List specified resource from storage.
     *
     * @param  \App\PostComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
        [
          'post_id'     => 'required|exists:user_posts,id,active,1,deleted_at,NULL',
          'limit'       => 'nullable|numeric',
        ]);
        if(!$validator->fails())
        {
            $query = PostComment::with('user:id,name,profile_pic')->wherePostId($request->post_id);
            if($request->limit > 0){
                $data = $query->paginate($request->limit);
            }else{
                $data = $query->get();
            }
            dd($data);
            $response_data = ["success" => 1, "data" => $data];
        }
        else
        {
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }

        return response()->json($response_data);
    }
}
