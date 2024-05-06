<?php

namespace App\Http\Controllers\API;

use DateTime;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class AnnouncementController extends Controller
{
    /**
     * show a listing of all the Annousements
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $announcements = Announcement::query()->where(function ($query) use ($request, $now) {
            if ($request->input('filter') && $request->input('filter') == 'past') {
                $query->whereRaw("CONCAT(date, ' ', time) < '{$now->toDateTimeString()}'");
            }else if($request->input('filter') && $request->input('filter') == 'upcoming'){
                $query->whereRaw("CONCAT(date, ' ', time) > '{$now->toDateTimeString()}'");
            }
        })->paginate(8);
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
        $request->validate([
            'message'       => 'required|string|max:64',
            'date'          => 'required|date_format:Y-m-d',
            'time'          => 'required',
        ]);
        // past announcement can't be update validation check is missing
        $announcement = Announcement::findOrFail($id);
        if(!$announcement){
            return response()->json('error');
        }
        $announcement->update($request->only(['message', 'date', 'time']));

        return response()->json($announcement);
    }

    /**
     * delete the announcement data
     */
    public function delete($id)
    {
        // soft delete used on migartion and model but never used
        // validation missing
       // findOrFail
       // 200 is for the success and ok, fine, good response not for the errors
        $announcement = Announcement::findOrFail($id);

        if (! $announcement) {
            return Response::json(
                [
                    'error'   => true,
                    'message' => 'We can not found data',
                ],
                404
            );
        }

        $announcementDateTime = new DateTime($announcement->date.' '.$announcement->time);
        $currentDateTime     = new DateTime();

        // Check annousment Date and Time if it is in past then can't access delete
        if ($announcementDateTime < $currentDateTime) {
            return Response::json(
                [
                    'error'   => true,
                    'message' => 'Announcement Date and Time are in past',
                ],
                400
            );
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
