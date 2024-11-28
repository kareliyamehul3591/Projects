<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
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
        return view('country.list');
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function countryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Country;

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                $values = $values->where($opt, 'LIKE', "%$q[$key]%");
            }
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {
                $recode->show = route('country.show', [$recode->id]);
                $recode->edit = route('country.edit', [$recode->id]);
                $recode->delete = route('country.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('country.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'country_name' => 'required|unique:countries',
            'country_code' => 'required',
            'country_currency' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Country::create($data);

        return redirect()->route('country.index')->with('success', 'Country created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Country $country)
    {
        return view('country.edit', compact('country'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Country $country)
    {
        $data = $request->validate([
            'country_name' => "required|unique:countries,country_name,$country->id,id",
            'country_code' => 'required',
            'country_currency' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $country->update($data);

        return redirect()->route('country.index')->with('success', 'Country updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Country $country)
    {
        return view('country.show', compact('country'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()->route('country.index')->with('success', 'Country deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function countryMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Country::where('id', $id)->delete();
        }

        return redirect()->route('country.index')->with('success', 'Country deleted successfully');
    }

}
