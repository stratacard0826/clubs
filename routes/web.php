<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	 //$filePath = "images/profile/";
	// /Avatar::create('John Doe')->save("storage/".$filePath.uniqid().".png", $quality = 90);
    return view('welcome');
});
Route::get('/lala', function () {
	echo base64_encode(file_get_contents("storage/dashboard/b98741194f551f1c5ac8b94e2a22b4de.png"));

});

Route::get('/fcm', "HomeController@fcm");


Route::prefix("private")->namespace("PrivateArea")->group(function(){
	Auth::routes();
});

Route::prefix("private")->middleware("auth")->name("private.")->namespace("PrivateArea")->group(function(){


	Route::get('dashboard', "DashboardController@index")->name('dashboard');

	Route::get('logout', "AdminUserController@logout")->name('logout');
	
	
	//User related Routes
	Route::get('user/list', "UserController@index")->name("users");
	Route::post('user/list', "UserController@getList")->name("users.list");
	Route::post('user/status', "UserController@updateStatus")->name("user.status");
	Route::post('user/block', "PostController@updateBlock")->name("user.block");
	Route::post('user/destroy', "UserController@destroy")->name("user.destroy");
	Route::get('user/export/{id}/{key}/{from}/{to}', "UserController@export")->name("user.export");
	Route::post('user/count', "UserController@userCount")->name("user.count");
	Route::get('user/profile/{key}', "UserController@viewProfile")->name("user.profile");
	Route::get('user/viewpost/{key}', "PostController@userViewPost")->name("user.viewpost");
	Route::post('user/getposts', "PostController@getPosts")->name("user.getposts");
	Route::post('user/getcommentlist', "PostController@getCommentList")->name("user.getcommentlist");
	Route::post('user/postblock', "PostController@updatePostBlock")->name("user.postblock");
	Route::post('user/commentblock', "PostController@updateCommentBlock")->name("user.commentblock");

	//Admin User related Routes
	Route::get('employee/list', "AdminUserController@index")->name("employees");
	Route::post('employee/list', "AdminUserController@getList")->name("employee.list");
	Route::post('employee/create', "AdminUserController@create")->name("employee.create");
	Route::post('employee/edit', "AdminUserController@edit")->name("employee.edit");
	Route::post('employee/update', "AdminUserController@update")->name("employee.update");
	Route::post('employee/status', "AdminUserController@updateStatus")->name("employee.status");
	Route::post('employee/destroy', "AdminUserController@destroy")->name("employee.destroy");
	Route::get('employee/profile/{key}', "AdminUserController@viewProfile")->name("employee.profile");
	Route::get('my-profile', "AdminUserController@myProfile")->name("myprofile");
	Route::post('password-update', "AdminUserController@passwordUpdate")->name("passwordUpdate");
	Route::post('profile-update', "AdminUserController@profileUpdate")->name("profileUpdate");

	//Club User related Routes
	Route::get('club/list', "ClubController@index")->name("club")->middleware("ican:club.view");
	Route::post('club/list', "ClubController@getList")->name("club.list")->middleware("ican:club.view");
	Route::post('club/register', "ClubController@store")->name("club.register")->middleware("ican:club.create");
	Route::post('club/destroy', "ClubController@destroy")->name("club.destroy")->middleware("ican:club.view");
	Route::post('club/status', "ClubController@updateStatus")->name("club.status")->middleware("ican:club.edit");
	Route::post('club/edit', "ClubController@edit")->name("club.edit")->middleware("ican:club.edit");
	Route::post('club/update', "ClubController@update")->name("club.update")->middleware("ican:club.edit");
	Route::get('club/profile/{id}', "ClubController@viewProfile")->name("club.profile")->middleware("ican:club.profile");
	Route::get('club/eventlist/{id}', "ClubController@eventList")->name("club.eventlist")->middleware("ican:club.eventlist");
	Route::post('club/geteventlist', "ClubController@getEventList")->name("club.geteventlist")->middleware("ican:club.geteventlist");

	//Event related Routes
	Route::get('event/list', "EventController@index")->name("events");
	Route::post('event/list', "EventController@getList")->name("events.list");
	Route::post('event/create', "EventController@create")->name("event.create");
	Route::post('event/edit', "EventController@edit")->name("event.edit");
	Route::post('event/update', "EventController@update")->name("event.update");
	Route::post('event/status', "EventController@updateStatus")->name("event.status");
	Route::post('event/destroy', "EventController@destroy")->name("event.destroy");
	Route::get('event/bookinglist/{id}', "EventController@getUserBookingEventList")->name("event.bookinglist");
	Route::post('event/getbookinglist', "EventController@getEventBookingList")->name("event.getbookinglist");
	Route::post('event/cancelled', "EventController@eventCancelled")->name('event.cancelled');

	//Admin User Type Routes
	Route::get('admin-type', "AdminTypeController@index")->name("admintypes");
	Route::post('admin-type/list', "AdminTypeController@getUserTypeList")->name("admintype.list");
	Route::post('admin-type/create', "AdminTypeController@create")->name("admintype.create");
	Route::post('admin-type/edit', "AdminTypeController@edit")->name("admintype.edit");
	Route::post('admin-type/update', "AdminTypeController@update")->name("admintype.update");
	Route::post('admin-type/status', "AdminTypeController@updateStatus")->name("admintype.status");
	Route::post('admin-type/destroy', "AdminTypeController@destroy")->name("admintype.destroy");


	//Admin Event Type Route
	Route::get('event-type', "EventTypeController@index")->name("eventtypes")->middleware("ican:eventType.view");
	Route::post('event-type/list', "EventTypeController@getEventTypeList")->name("eventtype.list")->middleware("ican:eventType.view");
	Route::post('event-type/create', "EventTypeController@create")->name("eventtype.create")->middleware("ican:eventType.create");
	Route::post('event-type/edit', "EventTypeController@edit")->name("eventtype.edit")->middleware("ican:eventType.edit");
	Route::post('event-type/update', "EventTypeController@update")->name("eventtype.update")->middleware("ican:eventType.edit");
	Route::post('event-type/status', "EventTypeController@updateStatus")->name("eventtype.status")->middleware("ican:eventType.edit");
	Route::post('event-type/destroy', "EventTypeController@destroy")->name("eventtype.destroy")->middleware("ican:eventType.destroy");

	//Admin sticker Route
	Route::get('stickers', "StickerController@index")->name("stickers")->middleware("ican:eventType.view");
	Route::post('sticker/list', "StickerController@getStickerList")->name("sticker.list")->middleware("ican:eventType.view");
	Route::post('sticker/create', "StickerController@create")->name("sticker.create")->middleware("ican:eventType.create");
	Route::post('sticker/edit', "StickerController@edit")->name("sticker.edit")->middleware("ican:eventType.edit");
	Route::post('sticker/update', "StickerController@update")->name("sticker.update")->middleware("ican:eventType.edit");
	Route::post('sticker/status', "StickerController@updateStatus")->name("sticker.status")->middleware("ican:eventType.edit");
	Route::post('sticker/destroy', "StickerController@destroy")->name("sticker.destroy")->middleware("ican:eventType.destroy");


	//Admin sticker Type Route
	Route::get('stickerstype', "StickersTypeController@index")->name("stickerstype")->middleware("ican:eventType.view");
	Route::post('sticker-type/list', "StickersTypeController@getStickerTypeList")->name("stickertype.list")->middleware("ican:eventType.view");
	Route::post('sticker-type/create', "StickersTypeController@create")->name("stickertype.create")->middleware("ican:eventType.create");
	Route::post('sticker-type/edit', "StickersTypeController@edit")->name("stickertype.edit")->middleware("ican:eventType.edit");
	Route::post('sticker-type/update', "StickersTypeController@update")->name("stickertype.update")->middleware("ican:eventType.edit");
	Route::post('sticker-type/status', "StickersTypeController@updateStatus")->name("stickertype.status")->middleware("ican:eventType.edit");
	Route::post('sticker-type/destroy', "StickersTypeController@destroy")->name("stickertype.destroy")->middleware("ican:eventType.destroy");


	//Admin Event Sub Category Route
	Route::get('event-sub-category', "EventSubCategoryController@index")->name("eventsubcategories")->middleware("ican:eventType.view");
	Route::post('event-sub-category/list', "EventSubCategoryController@getEventSubCategoryList")->name("eventsubcategory.list")->middleware("ican:eventType.view");
	Route::post('event-sub-category/create', "EventSubCategoryController@create")->name("eventsubcategory.create")->middleware("ican:eventType.create");
	Route::post('event-sub-category/edit', "EventSubCategoryController@edit")->name("eventsubcategory.edit")->middleware("ican:eventType.edit");
	Route::post('event-sub-category/update', "EventSubCategoryController@update")->name("eventsubcategory.update")->middleware("ican:eventType.edit");
	Route::post('event-sub-category/status', "EventSubCategoryController@updateStatus")->name("eventsubcategory.status")->middleware("ican:eventType.edit");
	Route::post('event-sub-category/destroy', "EventSubCategoryController@destroy")->name("eventsubcategory.destroy")->middleware("ican:eventType.destroy");

	//Admin User Routes
	Route::get('get-permission', "PermissionController@getPermissionList")->name("permission.list");
	Route::post('permission/update', "PermissionController@update")->name("permission.update");
	/*Route::get('admin-users', "AdminTypeController@index")->name("adminusers");
	Route::post('admin-user/list', "AdminTypeController@getUserTypeList")->name("adminuser.list");
	Route::post('admin-user/create', "AdminTypeController@create")->name("adminuser.create");
	Route::post('admin-user/edit', "AdminTypeController@edit")->name("adminuser.edit");
	Route::post('admin-user/status', "AdminTypeController@updateStatus")->name("adminuser.status");
	Route::post('admin-user/destroy', "AdminTypeController@destroy")->name("adminuser.destroy");*/

	

});




Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function(){
	Avatar::create('Joko Widodo')->toBase64();
	dd(bcrypt("secret"));
	return view("admin.auth.login");
});

Route::get('/has', function(){
	return ["hiu"];
})->name('error');

