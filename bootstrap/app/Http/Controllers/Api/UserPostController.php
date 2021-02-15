<?php

namespace App\Http\Controllers\Api;
use Validator;
use Auth;
use App\UserPost;
use App\PostAttachment;
use App\PostComment;
use App\PostLike;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UserPostController extends Controller
{
   //INDEX
	public function index()
	{
		dd('hai');
	}

	//INDEX
	public function getPosts(Request $request)
	{
		$validator = Validator::make($request->all(),
            [
              'user_data'    	=> 'nullable|exists:users,id,active,1,deleted_at,NULL'
            ]);
		if(!$validator->fails()){
			if($request->filled("user_data")){
				$user = User::find($request->user_data);
			}else{
				$user = auth()->user();
			}
			
			$posts = UserPost::with(["attachments:id,post_id,file,type"])->whereUserId($user->id)->orderBy("created_at", "desc")->get();
			$response_data = ["success" => 1,  "data" => $posts];
		}else{
			$response_data = ["success" => 0,  "message" => __("validation.not_found", ["attr" => "User"])];
		}
		return response()->json($response_data);
	}

	//INDEX
	public function getPostAttachments()
	{
		$user = auth()->user();
		$posts = $user->postAttachments;
		$response_data = ["success" => 1,  "data" => $posts];
		return response()->json($response_data);
	}

	//CREATE POST
	public function create(Request $request)
	{
		$post 		= new UserPost();
		$attach 	= new PostAttachment();
		$attached 	= new PostAttachment();
		
		$validator = Validator::make($request->all(),
            [
              'text'          => 'nullable|max:'.limit("post_text.max"),
              'image.*'       => 'nullable|image|mimes:'.limit("post_image.format").'|limit_file:image,'.limit("post_image.count"),
              'thumb_image.*' => 'required_with:image.*||image|mimes:'.limit("post_image.format").'|limit_file:thumb_image,'.limit("post_image.count"),
              'video.*'       => 'nullable|mimes:'.limit("post_video.format").'|limit_file:video,'.limit("post_video.count"),
              'thumb_video.*' => 'required_with:video.*|image|mimes:'.limit("thumb_image.format").'|limit_file:thumb_video,'.limit("post_video.count")
            ]);

		if(!$validator->fails()){
 
			$text				= "";
			$text 				= $request->text;
			$post->text 		= $text;
			$post->user_id 		= Auth::user()->id; 
			$post->created_by 	= Auth::user()->id;
			$post->updated_by 	= Auth::user()->id;
			$created			= $post->save();

			$attachments = [];
			if($created)
			{
				//SAVE POST IMAGE
				if($request->image > 0)
				{
					$filePath = "post/images";
		            if($request->hasFile('image'))
		            { 
		            	$i = 0;
					    foreach($request->image as $images)
					    {
		               		$attachment = [];
		               		$image = url("storage/".$images->store($filePath));
		               		$thumb_image = url("storage/".$request->file("thumb_image.".$i++)->store($filePath));
           		            $attachment["post_id"] 		= $post->id; 
           		            $attachment["user_id"]		= Auth::user()->id; 
       		            	$attachment["type"] 		= 1; 
           					$attachment["file"] 		= $image;
           					$attachment["thumb_file"] 	= $thumb_image;
           					$attachment["created_by"] 	= Auth::user()->id;
           					$attachment["updated_by"] 	= Auth::user()->id;
           					$attachments[] = $attachment;
		           		}
		            }

				}

				//SAVE POST VIDEOS
				if($request->video > 0)
				{
					$filePath2 = "post/videos";
	            	if($request->hasFile('video')){
	            		$i = 0;
	            		foreach($request->video as $videos)
					    {
	    		            $attachment 		= []; 
	                		$video = url("storage/".$videos->store($filePath2));
	                		$thumb_video = url("storage/".$request->file("thumb_video.".$i++)->store($filePath2));
	    		            $attachment["post_id"] 		= $post->id; 
	    		            $attachment["user_id"]		= Auth::user()->id; 
			            	$attachment["type"] 		= 1; 
	    					$attachment["file"] 		= $video;
	    					$attachment["thumb_file"] 	= $thumb_video;
	    					$attachment["created_by"] 	= Auth::user()->id;
	    					$attachment["updated_by"] 	= Auth::user()->id;
	    					$attachments[] = $attachment;
	                	}
	            	}
				}
				
			}//CREATED END

			if(count($attachments) > 0){
				$attchementResult = PostAttachment::insert($attachments);
			}
			
			$response_data = ["success" => 1, "message" => __("validation.create_success", ["attr" => "Post"])];
                        
        }//VALIDATOR END
        else
        {
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }

       	return response()->json($response_data);
	}
	//UPDATE POST
	public function update(Request $request)
	{
		$post 		    = new UserPost();
		$attached 	    = new PostAttachment();
		$validator = Validator::make($request->all(),
            [
				'post_id'	    => 'required|numeric',
				'text'          => 'nullable|max:'.limit("post_text.max"),
             	'image.*'       => 'nullable|image|mimes:'.limit("post_image.format").'|limit_file:image,'.limit("post_image.count"),
              	'thumb_image.*' => 'required_with:image.*||image|mimes:'.limit("post_image.format").'|limit_file:thumb_image,'.limit("post_image.count"),
              	'video.*'       => 'nullable|mimes:'.limit("post_video.format").'|limit_file:video,'.limit("post_video.count"),
              	'thumb_video.*' => 'required_with:video.*|image|mimes:'.limit("thumb_image.format").'|limit_file:thumb_video,'.limit("post_video.count")
            ]);

		if(!$validator->fails()){
			$text       = "";
			$post 		= UserPost::whereId($request->post_id);
			$text 		= $text;
			$data 		= ['text' => $text ];
			$updated 	= $post->update($data);
			$attachments = [];

			if($updated)
			{
				$delete 	= PostAttachment::wherePostId($request->post_id);    
                $delete->delete();

				//SAVE POST IMAGE
				if($request->image > 0)
				{
					$filePath = "post/images";
		            if($request->hasFile('image'))
		            { 
		            	$i = 0;
					    foreach($request->image as $images)
					    {
		               		$attachment = [];
		               		$image = url("storage/".$images->store($filePath));
		               		$thumb_image = url("storage/".$request->file("thumb_image.".$i++)->store($filePath));
           		            $attachment["post_id"] 		= $request->post_id; 
           		            $attachment["user_id"]		= Auth::user()->id; 
       		            	$attachment["type"] 		= 1; 
           					$attachment["file"] 		= $image;
           					$attachment["thumb_file"] 	= $thumb_image;
           					$attachment["created_by"] 	= Auth::user()->id;
           					$attachment["updated_by"] 	= Auth::user()->id;
           					$attachments[] = $attachment;
		           		}
		            }
				}

				//SAVE POST VIDEOS
				if($request->video > 0)
				{
					$filePath2 = "post/videos";
	            	if($request->hasFile('video')){
	            		$i = 0;
	            		foreach($request->video as $videos)
					    {
	    		            $attachment 		= []; 
	                		$video = url("storage/".$videos->store($filePath2));
	                		$thumb_video = url("storage/".$request->file("thumb_video.".$i++)->store($filePath2));
	    		            $attachment["post_id"] 		= $request->post_id; 
	    		            $attachment["user_id"]		= Auth::user()->id; 
			            	$attachment["type"] 		= 1; 
	    					$attachment["file"] 		= $video;
	    					$attachment["thumb_file"] 	= $thumb_video;
	    					$attachment["created_by"] 	= Auth::user()->id;
	    					$attachment["updated_by"] 	= Auth::user()->id;
	    					$attachments[] = $attachment;
	                	}
	            	}
				}
				
				
			}//UPDATED END

			if(count($attachments) > 0){
				$attchementResult = PostAttachment::insert($attachments);
			}

			$response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Post"])];
                        
        }//VALIDATOR END
        else
        {
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }

       	return response()->json($response_data);
	}


	//POST DESTROY
    public function destroy(Request $request)
    {
        $validator  = Validator::make($request->all(),
            [
              'post_id' => 'required|integer|exists:user_posts,id,active,1,deleted_at,NULL',
            ]);

        if (!$validator->fails()) 
            { 
                $post 	 = UserPost::whereId($request->post_id);    
                $deleted = $post->delete();
                if($deleted)
                {
					$attached 	= PostAttachment::wherePostId($request->post_id);    
                	$attached->delete();
                }
                $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'post'])]; 
            }else
            {
                $response_data =  ['success' => 0, 'message' => __('validation.check_fields') , 'errors' => $validator->errors()];
            }

        return response()->json($response_data);
    }
	
    //CREATE LIKE
    public function like(Request $request)
    {
		$validator = Validator::make($request->all(),
            [
              'post_id'    => 'required|numeric',
              'status'	   =>' required|numeric|in:0,1',
            ]);

		if(!$validator->fails()){

			if($request->status == 0){
                $this->createLike($request->post_id);
            }elseif($request->status == 1){
                $this->deleteLike($request->post_id);
            }

        	$response_data =  ['success' => 1, 'message' => __('validation.update_success',['attr'=>'like'])]; 
        }else
            {
                $response_data =  ['success' => 0, 'message' => __('validation.check_fields') , 'errors' => $validator->errors()];
            }
        return response()->json($response_data);
    }

    //CREATE LIKE
    private function createLike($post_id)
    {
    	$userId  = Auth::user()->id;
    	$like 	 = PostLike::whereUserId($userId)->whereActive(1)->first();
    	if(!$like)
    	{
    		$userId      = Auth::id();      
	        $currentDate = date("Y-m-d H:i:s");
	        $record      = ['likeable_id' => $post_id, 'user_id' => $userId, 'type' => 1,'created_by' => $userId, 'updated_by' => $userId];
	        $success     = PostLike::Create($record); 
	    	if($success)
	    	{	
				$post    = new UserPost();
				$post 	 = UserPost::whereId($post_id);
				$data 	 = UserPost::whereId($post_id)->first();
				$count   = $data->like_count + 1; 
				$update  = ['like_count' => $count];
				$post->update($update);
	    			
	    	}
    	}
        
    }

    //DELETE LIKE
    private function deleteLike($post_id)
    {
    	$userId  = Auth::user()->id;
    	$like 	 = PostLike::whereUserId($userId)->whereActive(1)->first();
    	if($like)
    	{
	       $delete = PostLike::where("post_id", $post_id)->delete();
	        	if($delete)
	        	{
	        		$post   = new UserPost();
	        		$post 	= UserPost::whereId($post_id);
	        		$data 	= UserPost::whereId($post_id)->first();
	        		$count  = $data->like_count - 1; 
	            	$update = ['like_count' => $count];
					$post->update($update);
	        	}
    	}
	}

	public function share(Request $request)
	{

	}

	
}
