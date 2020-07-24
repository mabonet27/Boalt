<?php

namespace App\Http\Controllers;

use App\Notifications;
use App\User;
use Illuminate\Http\Request;
use App\Jobs\ProcessNotifications;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return Notifications::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|numeric',
            'title'         => 'required',
            'body'          => 'required',
            'schedule'      => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = User::with('notifications')->where(['id' => $request->input('user_id')])->first();

        if (!$user) {
            return response()->json(['message' => 'This user does\'nt exist.'], 404);
        }

        $params = [
            'title' => $request->input('title'),
            'body'  => $request->input('body'),
        ];

	    $delay = $request->input('schedule') > 0 ? now()->addMinutes($request->input('schedule')) : now();

	    ProcessNotifications::dispatch($user, $params)->delay($delay);

        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $notification = Notifications::where(['id' => $id])->first();

        if (!$notification) {
            return response()->json(['message' => 'This notification does\'nt exist.'], 404);
        }

        $notification->update(['status' => $request->status]);

        return response()->json($notification, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notifications::where(['id' => $id])->first();
        $notification->delete();
    }
}
