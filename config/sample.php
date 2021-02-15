<?php

return [
	

"userType" => [
                ["name" => "Admin", 	"code" => "admin", "admin_type" => 1],
				["name" => "Sub Admin", "code" => "subadmin", "admin_type" => 1],
				["name" => "Club", 		"code" => "club", "admin_type" => 0]
			],

"gender"	=> [
				["name" => "Male", "code" => "male"],
				["name" => "Female", "code" => "female"],
				["name" => "Other", "code" => "other"],
],

"deviceType"	=> [
				["name" => "Android", "code" => "android", "mobile_device" => 1],
				["name" => "IOS", "code" => "ios", "mobile_device" => 1],
				["name" => "Web", "code" => "web", "mobile_device" => 0],
],

"eventType" => [
                ["name" => "Club Meet"],
				["name" => "Dance Party"],
				["name" => "Fun"]
			],
"permission"   =>[
					["name"=>"Can View Club Users","code"=>"club.view"],
					["name"=>"Can edit club details","code"=>"club.edit"],
					["name"=>"Can delete club details","code"=>"club.destroy"],
					["name"=>"Can create club","code"=>"club.create"],
					["name"=>"Can create event","code"=>"event.create"],
					["name"=>"Can edit event","code"=>"event.edit"],
					["name"=>"Can view event","code"=>"event.view"],
					["name"=>"Can delete event","code"=>"event.destroy"],
					["name"=>"Can view user","code"=>"user.view"],
					["name"=>"Can view user detail","code"=>"user.detail"],
					["name"=>"Can delete user","code"=>"user.destroy"],
					["name"=>"Can change user status","code"=>"user.status"],
					["name"=>"Can view setting","code"=>"setting.view"],
					["name"=>"Can create event type","code"=>"eventType.create"],
					["name"=>"Can edit event type","code"=>"eventType.edit"],
					["name"=>"Can view event type","code"=>"eventType.view"],
					["name"=>"Can delete event type","code"=>"eventType.destroy"],
				],

];