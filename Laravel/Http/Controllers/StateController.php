<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all records.
     */
    public function index()
    {
        return view('state.list');
    }

    public function stateList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = State::join('countries', 'states.country_id', '=', 'countries.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "country_name") {
                    $values = $values->where('countries.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('states.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get(['states.*', 'countries.country_name'])->map(function ($recode) {
                $recode->show = route('state.show', [$recode->id]);
                $recode->edit = route('state.edit', [$recode->id]);
                $recode->delete = route('state.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $countries = Country::where('status', '1')->get();

        return view('state.create', compact('countries'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'state_name' => 'required|unique:states',
            'country_id' => 'required',
        ],[
            'country_id.required' => 'The country field is required.',
        ]);

        foreach (explode('+', $request->input('state_name')) as $state_name) {
            $data['state_name'] = $state_name;
            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            State::create($data);
        }

        return redirect()->route('state.index')->with('success', 'State created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(State $state)
    {
        $countries = Country::where('status', '1')->get();

        return view('state.edit', compact('state', 'countries'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, State $state)
    {
        $data = $request->validate([
            'state_name' => "required|unique:states,state_name,$state->id,id",
            'country_id' => 'required',
            'status' => 'required',
        ],[
            'country_id.required' => 'The country field is required.',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $state->update($data);

        return redirect()->route('state.index')->with('success', 'State updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(State $state)
    {
        $country = Country::where('id', $state->country_id)->first();

        return view('state.show', compact('state', 'country'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(State $state)
    {
        $state->delete();

        return redirect()->route('state.index')->with('success', 'State deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function stateMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            State::where('id', $id)->delete();
        }

        return redirect()->route('state.index')->with('success', 'State deleted successfully');
    }

}
