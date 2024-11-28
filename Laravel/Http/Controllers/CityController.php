<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class CityController extends Controller
{


    /**
     * Display a listing of all records.
     */
    public function index()
    {
        return view('city.list');
    }

    public function cityList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = city::join('states', 'cities.state_id', '=', 'states.id')
                ->join('countries', 'states.country_id', '=', 'countries.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "state_name") {
                    $values = $values->where('states.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "country_name") {
                    $values = $values->where('countries.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('cities.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['cities.*', 'states.state_name', 'countries.country_name'])->map(function ($recode) {
                $recode->show = route('city.show', [$recode->id]);
                $recode->edit = route('city.edit', [$recode->id]);
                $recode->delete = route('city.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $states = state::where('status', '1')->get();

        return view('city.create', compact('states'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:cities',
            'state_id' => 'required',
        ],[
            'state_id.required' => 'The state field is required.',
        ]);

        foreach (explode('+', $request->input('name')) as $name) {
            $data['name'] = $name;
            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            city::create($data);
        }

        return redirect()->route('city.index')->with('success', 'City created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(City $city)
    {
        $states = State::where('status', '1')->get();

        return view('city.edit', compact('city', 'states'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, city $city)
    {
        $data = $request->validate([
            'name' => "required|unique:cities,name,$city->id,id",
            'state_id' => 'required',
            'status' => 'required',
        ],[
            'state_id.required' => 'The state field is required.',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $city->update($data);

        return redirect()->route('city.index')->with('success', 'City updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(city $city)
    {
        return view('city.show', compact('city'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(city $city)
    {
        $city->delete();

        return redirect()->route('city.index')->with('success', 'City deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function cityMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            city::where('id', $id)->delete();
        }

        return redirect()->route('city.index')->with('success', 'City deleted successfully');
    }

}
