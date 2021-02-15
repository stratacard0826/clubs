<?php

namespace App\Http\Controllers\PrivateArea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Event;
use App\EventBook;
use App\EventType;
use Yajra\Datatables\Datatables;
use App\Events\EventCreationEvent;
use App\User;

class EventController extends Controller
{
    public function index()
    {
        $response_data["title"] = __("title.private.event_list");
        $response_data["eventTypes"] = EventType::whereActive(1)->get();
        return view("private.event.list")->with($response_data);
    }

    public function getList(Request $request)
    {
        $query = Event::with(['eventType:id,name']);

        if(Auth::user()->isClub()){
            $query->whereClubUser(Auth::id());
        }

        if($request->has("event_type") && $request->event_type != ""){
            $query->whereEventType($request->event_type);
        }
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }

       return Datatables::of($query->get())->make(true);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    "name"          => "required|between:".limit("event_name.min").",".limit("event_name.max"),
                    "event_date"    => "required|event_date",
                    "event_type"    => "required|exists:event_types,id,active,1,deleted_at,NULL",
                    "detail"        => "required|between:".limit("event_detail.min").",".limit("event_detail.max"),
                    "location"      => "required|between:".limit("event_location.min").",".limit("event_location.max"),
                    "lat"           => "required|numeric",
                    "long"          => "required|numeric",
                ]);

        if(!$validator->fails()){
            $event = new Event();
            $event->name = $request->name;
            $event->club_user = Auth::id();
            $event->event_type = $request->event_type;
            $event->detail = $request->detail;
            $event->location = $request->location;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->start_time = $request->start_time;
            $event->end_time = $request->end_time;
            $event->lat = $request->lat;
            $event->lng = $request->long;
            $event->active = 1;
            $event->created_by = Auth::id();
            $event->updated_by = Auth::id();

            if($event->save()){
                $event->code = "EVENT000".$event->id;
                $event->save();
                $where = ["active" => 1];
                $users = User::where($where)->get();
                event(new EventCreationEvent($users, $event, Auth::id()));
                $response_data = ["success" => 1, "message" => __("validation.create_success", ["attr" => "Event"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }else{
            $response_data = ["success" =>  0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }
       return response()->json($response_data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'eventId' => 'required|exists:events,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $eventId = $request->eventId;
            $event = Event::where('id', $eventId)->first();
            if($event)
            {

                $response_data = ["success" => 1, "message" => __("validation.edit_success"), "record" => $event];
            }
            else
            {
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }
        else
        {
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
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
            $user = Event::find($request->pk);
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
            $user = Event::find($request->data); 
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

    public function getUserBookingEventList($key, Request $request)
    {
        try {
            $key = $key;
            $response_data["title"] = __("title.private.eventbooking_list");
            $event = Event::whereCode($key)->whereActive(1)->first();
            if($event){
                $response_data["code"] = $event->code;
                
                return view("private.event.bookinglist")->with($response_data);
            }else{
                return redirect(route("private.event"));
            }
        } catch (DecryptException $e) {
            return redirect(route("private.event"));
        }
        
    }

    public function getEventBookingList(Request $request)
    {
        $event = Event::whereCode($request->event_code)->first();

        if($event){
            $data = $event->bookedUsers;
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start_date=$request->start_date;
                $end_date=$request->end_date;
        	   $data = $data->where('pivot.created_at','>=',$start_date)->where('pivot.created_at','<=',$end_date);
            }
            
        }else{
        	$data = [];
        }
       return Datatables::of($data)->make(true);
    }
}
