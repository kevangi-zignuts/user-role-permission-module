<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Annoucement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AnnoucementController extends Controller
{
    /**
     * show a listing of all the Annousements
     */
    public function index(Request $request)
    {
        // Querying Annousement with filter conditions
        // TOTALY WRONG LOGIC
        $annousments = Annoucement::query()->where(function ($query) use ($request) {
            if ($request->input('filter') && $request->input('filter') == 'past') {
                $query->whereDate('date', '<',  now());
            }else if($request->input('filter') && $request->input('filter') == 'upcoming'){
                $query->whereDate('date', '>',  now());
            }
        })->paginate(8);

        // Append filter parameters to the pagination links
        $annousments->appends(['filter' => $request->input('filter')]);

        return view('admin.annousment.index', ['annousements' => $annousments, 'filter' => $request->input('filter')]);
    }

    /**
     * display a form for crate a new annousment
     */
    public function create()
    {
        return view('admin.annousement.create');
    }

    /**
     * store a data of new Added annousment
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'message'       => 'required|string|max:64',
            'date'          => 'required|date',
            'time'          => 'required',
        ]);

        Annoucement::create($data);

        session(['success' => 'Annousement added successfully!']);
        return redirect()->route('annousements.index');
    }

    /**
     * display a View Page of Particular annousement
     */
    public function view($id)
    {
        $annousment = Annoucement::findOrFail($id);

        if($annousment){
            return redirect()->back()->with('error', 'Id cannot find');
        }


        return view('admin.annousement.view', ['annousment' => $annousment]);
    }

    /**
     * display a Form for edit the annousement details
     */
    public function edit($id)
    {
        $annousment = Annoucement::findOrFail($id);

        $annousementDateTime = new DateTime($annousment->date.' '.$annousment->time);
        $currentDateTime     = new DateTime();

        // Check annousment Date and Time if it is in past then can't access edit
        if ($annousementDateTime < $currentDateTime) {
            return redirect()->back()->with('error', 'Annousment Date and Time are in past');
        }

        return view('admin.annousement.edit', ['annousment' => $annousment]);
    }

    /**
     * update the annousement details
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'message'       => 'required|string|max:64',
            'date'          => 'required|date',
            'time'          => 'required',
        ]);
        // past announcement can't be update validation check is missing
        $annousment = Annoucement::findOrFail($id);
        if(!$annousment){
            return redirect()->back()->with('error', 'Id cannot find');
        }
        $annousment->update($request->only(['message', 'date', 'time']));

        session(['success' => 'Annousement Details updated successfully!']);
        return redirect()->route('annousements.index');
    }

    /**
     * delete the annousement data
     */
    public function delete($id)
    {
        // soft delete used on migartion and model but never used
        // validation missing
       // findOrFail
       // 200 is for the success and ok, fine, good response not for the errors
        $annousment = Annoucement::find($id);

        if (! $annousment) {
            return Response::json(
                [
                    'error'   => true,
                    'message' => 'We can not found data',
                ],
                200
            );
        }

        $annousementDateTime = new DateTime($annousment->date.' '.$annousment->time);
        $currentDateTime     = new DateTime();

        // Check annousment Date and Time if it is in past then can't access delete
        if ($annousementDateTime < $currentDateTime) {
            return Response::json(
                [
                    'error'   => true,
                    'message' => 'Annousment Date and Time are in past',
                ],
                200
            );
        }

        $annousment->delete();

        return Response::json(
            [
                'success' => true,
                'message' => "Annousment's data deleted Successfully",
            ],
            200
        );
    }
}
