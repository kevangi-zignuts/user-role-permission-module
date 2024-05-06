<?php

namespace App\Http\Controllers\users;

use App\Models\Annousement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAnnousementController extends Controller
{
    /**
     * show a listing of all the Annousements
     */
    public function index(Request $request)
    {
        $annousements = Annousement::all();
        // Querying Annousement with filter conditions
        // WRONG
        $annousments = Annousement::query()->where(function ($query) use ($request) {
            if ($request->input('filter') && $request->input('filter') == 'past') {
                $query->whereDate('date', '<',  now());
            }else if($request->input('filter') && $request->input('filter') == 'upcoming'){
                $query->whereDate('date', '>',  now());
            }
        })->paginate(8);

        // Append search and filter parameters to the pagination links
        $annousments->appends(['filter' => $request->input('filter')]);

        return view('user.annousment.index', ['annousements' => $annousements, 'filter' => $request->input('filter')]);
    }

    /**
     * show a View Page of Particular annousement
     */
    public function view($id)
    {
        $annousment = Annousement::findOrFail($id);
        if($annousment->status == 'N'){
            $annousment->update(['status' => 'V']);
        }

        return view('user.annousement.view', ['annousment' => $annousment]);
    }
}

