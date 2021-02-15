<?php

namespace App\Http\Controllers\Api;

use Pusher\Pusher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BroadcastAuthController extends Controller
{
    /**
     * Authenticate
     * @param  Request $request
     * @return Response
     */
    public function auth(Request $request)
    {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );
        return $pusher->socket_auth($request->channel_name, $request->socket_id);
    }
}