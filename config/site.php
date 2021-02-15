<?php

return [

	"redirectTo" => "/private/dashboard",

	"limit"	=> [
		"name" 				=> ["min" => 3, "max" => 75],
		"email" 			=> ["min" => 3, "max" => 75],
		"phone" 			=> ["min" => 10, "max" => 10],
		"password" 			=> ["min" => 8, "max" => 16],
		"profile_pic" 		=> ["min" => 10, "max" => 2048, "format" => "jpeg,png,jpg,gif,svg", "min_width" => 100, "min_height" => 100],
		"fcm_code" 			=> ["min" => 3, "max" => 200],
		"social_token" 		=> ["min" => 3, "max" => 200],
		"bio" 				=> ["min" => 3, "max" => 60],
		"event_detail" 		=> ["min" => 30, "max" => 200],
		"event_name" 		=> ["min" => 3, "max" => 200],
		"event_location" 	=> ["min" => 3, "max" => 200],
		"event_organisation"=> ["min" => 3, "max" => 200],
		"post_text" 		=> ["min" => 2, "max" => 600],
		"post_comment" 		=> ["min" => 1, "max" => 100],
		"post_image" 	    => ["format" => "jpeg,png,jpg,gif,svg", "max" => 2048, "count" => 10 ],
		"chat_image" 	    => ["format" => "jpeg,png,jpg,gif,svg", "max" => 2048, "count" => 10 ],
		"sticker_image" 	=> ["format" => "jpeg,png,jpg,gif,svg", "max" => 5120, "count" => 10 ],
		"thumb_image" 	    => ["format" => "jpeg,png,jpg,gif,svg", "max" => 2048, "count" => 10 ],
		"post_video" 	    => ["format" => "mp4,mov,ogg,qt", "max" => 102400, "count" => 10 ],
		"file"              => ["format" => "mkv,mp4,avi,mpeg,jpeg,png,jpg,gif","count" => 10 ],
		"user_type"         => ["min" => 3, "max" => 40],
		"chat_message"      => ["min" => 1, "max" => 1200],
		"event_distance"    => 300,
	],

	"social_providers" => ["google", "twitter", "facebook"],

	"chat_type" => ["text" => 1, "image" => 2],

	"status" => ["Inactive", "Active"],

	/*"date_format" => ["front" => "DD/MM/YYYY", "back" => "d/m/Y"],
	"date_time_format" => ["front" => "DD/MM/YYYY hh:mm A", "back" => "d/m/Y H:i A"],*/

	"date_format" => ["front" => "MM/DD/YYYY", "back" => "m/d/Y"],
	"date_time_format" => ["front" => "MM/DD/YYYY hh:mm A", "back" => "m/d/Y H:i A"],

	"pagination" => [
		"notification" 		=> 20, 
		"events" 			=> 10, 
		"attenders" 		=> 10, 
		"likes" 			=> 10, 
		"comments" 			=> 10, 
		"posts" 			=> 10,
		"stickers"			=> 10,
		"block"			    => 30,
		"chats"			    => 30,
	],
];