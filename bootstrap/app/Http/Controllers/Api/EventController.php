<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use App\SocialToken;
use App\DeviceToken;
use App\PasswordReset;
use App\Events\LoginEvent;
use Illuminate\Support\Facades\Hash;
use Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Event;
use App\EventType;
use App\EventBook;
use App\UserNotification;
use Yajra\Datatables\Datatables;
use DB;

class EventController extends Controller
{
    /**
     * Returns Event Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        //Diatance Calc
        /*    SELECT id, name, address, lat, lng, ( 3959 * acos( cos( radians('$lat') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$long') ) + sin( radians('$lat') ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < '$radius' ORDER BY distance LIMIT 0 , 20
        /6371*/


        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'lat'         => 'nullable|numeric',
              'long'        => 'nullable|numeric|required_with:lat',
              'event_type'  => 'nullable|exists:event_types,id,active,1,deleted_at,NULL',
              'name'        => 'nullable|string',
              'event_date'  => 'nullable|date',
              'limit'       => 'nullable|numeric',
            ]);

        if(!$validator->fails()){

            $rawQuery = "*";

            $query = Event::with(['eventType:id,name', 'clubUser:id,name,code,user_type']);

            if($request->has("event_type") && $request->event_type != ""){
                $query->whereEventType($request->event_type);
            }
            if($request->has("event_date") && $request->event_date != ""){
                $query->whereDate("start_date", $request->event_date);
            }
            $query->select("*");

            if($request->filled("lat") && $request->filled("long")){
                $rawQuery = "( 3959 * acos( cos( radians('{$request->lat}') ) * cos( radians( lat ) ) * cos( radians(lng) - radians('{$request->long}') ) + sin( radians('{$request->lat}') ) * sin( radians( lat ) ) ) ) as distance";
                $query->addselect(DB::raw($rawQuery));
                $query->orderBy("distance");
            }

            if($request->filled("name")){
                $query->search($request->name);
            }


            $query->whereDate('start_date', ">=", now());
            $query->whereActive(1);
            
            if($request->limit > 0){
                $data = $query->paginate($request->limit);
            }else{
                $data = $query->get();
            }
            $eventTypes = EventType::select("id", "name")->whereActive(1)->get();

            $response_data = ["success" => 1, "data" => ["events" => $data, "event_types" => $eventTypes]];
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    /**
     * Returns Event Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEventTypes(Request $request)
    {
        $user = auth()->user();
        $data = EventType::select("id", "name")->whereActive(1)->get();
        $response_data = ["success" => 1, "data" => $data];
        return response()->json($response_data);
    }

    /**
     * Returns Event Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookEvent(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'event_id'            => 'required|exists:events,id,active,1,cancelled_at,NULL,deleted_at,NULL',
              'status'              => 'required|in:0,1',
              'notification'        => 'nullable|integer|exists:user_notifications,id,to_user,'.$user->id.",active,1,deleted_at,NULL",
            ]);

        if(!$validator->fails()){
            $now = now();
            $eventBook = EventBook::updateOrInsert(
                            ["user_id" => $user->id, "event_id" => $request->event_id],
                            ["status" => $request->status, "created_by" => $user->id, "updated_by" => $user->id, "updated_at" => $now]
                            );
            if($eventBook){
                $eventBook = EventBook::where(["user_id" => $user->id, "event_id" => $request->event_id])->first();
                if($eventBook->created_at == NULL){
                    $eventBook->created_at = $now;
                    $eventBook->save();
                }
                if($request->filled("notification")){
                    UserNotification::whereId($request->notification)->update(['read_at' => now()]);
                }
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Event Status"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }

        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
        
    }

    /**
     * Returns Event Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBookedEvent(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'name'                => 'nullable|string',
              'limit'               => 'nullable|integer',
            ]);

        if(!$validator->fails()){
            $name = "";
            if($request->filled("name")){
                $name = $request->name;
            }
            $query = EventBook::whereHas("event", function($query) use($name) {
                                if(!empty($name)){
                                    $query->search("name");
                                }
                                $query->whereNull("cancelled_at");
                        })->whereUserId($user->id);
            
            if($request->has("limit") && $request->limit > 0){
                $data = $query->paginate($request->limit);
            }else{
                $data = $query->get();
            }
            $response_data = ["success" => 1, "data" => $data];

            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
        
    }


    public function getEventDetail(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'event_data'      => 'required|exists:events,id,active,1,cancelled_at,NULL,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
            $event = Event::whereId($request->event_data)->first();
            $event->bookedUsers->sortBy("pivot_created_at");
            $response_data = ["success" => 1, "data" => $event];
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }
}
