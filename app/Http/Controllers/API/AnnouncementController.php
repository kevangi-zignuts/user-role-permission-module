<?php

namespace App\Http\Controllers\API;

use DateTime;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     * show a listing of all the Annousements
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $perPage = 10;
        $announcements = Announcement::query()->where(function ($query) use ($request, $now) {
            if ($request->input('filter') && $request->input('filter') == 'past') {
                $query->whereRaw("CONCAT(date, ' ', time) < '{$now->toDateTimeString()}'");
            }else if($request->input('filter') && $request->input('filter') == 'upcoming'){
                $query->whereRaw("CONCAT(date, ' ', time) > '{$now->toDateTimeString()}'");
            }
        })->paginate($perPage);
        return response()->json($announcements);
    }

    /**
     * store a data of new Added announcement
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'message'       => 'required|string|max:64',
            'date'          => 'required|date_format:Y-m-d',
            'time'          => 'required',
        ]);
        // dd(Auth::check());
        Announcement::create($data);

        return response()->json($data);
    }

    /**
     * display a View Page of Particular announcement
     */
    public function view($id)
    {
        $announcement = Announcement::findOrFail($id);
        if(! $announcement){
            return response()->json('error');
        }

        return response()->json($announcement);
    }

    /**
     * update the annousement details
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:64',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required',
        ]);
        
        $announcement = Announcement::findOrFail($id);
        
        // past announcement can't be update validation
        $validator->after(function ($validator) use ($announcement) {
            if (strtotime($announcement->date . ' ' . $announcement->time) <= Carbon::now()->timestamp) {
                $validator->errors()->add('datetime', 'The announcement datetime are in past so announcement can not be updated');
            }
        });
        
        if($validator->fails()){
            return response()->json($validator->errors()->first());
        }

        // past announcement can't be update validation check is missing
        $announcement->update($request->only(['message', 'date', 'time']));

        return response()->json($announcement);
    }

    /**
     * delete the announcement data
     */
    public function delete($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'exists:announcements,id,deleted_at,NULL',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->first());
        }
    
        $announcement = Announcement::findOrFail($id);

        $validator->after(function ($validator) use ($announcement) {
            if (strtotime($announcement->date . ' ' . $announcement->time) <= Carbon::now()->timestamp) {
                $validator->errors()->add('datetime', 'The announcement can not be delete');
            }
        });

        if($validator->fails()){
            return response()->json($validator->errors()->first());
        }

        $announcement->delete();

        return Response::json(
            [
                'success' => true,
                'message' => "Announcement's data deleted Successfully",
            ],
            200
        );
    }
}
