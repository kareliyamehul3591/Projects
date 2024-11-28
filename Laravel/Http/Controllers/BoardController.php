<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
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
        return view('board.list');
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function boardList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Board;

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
                $recode->show = route('board.show', [$recode->id]);
                $recode->edit = route('board.edit', [$recode->id]);
                $recode->delete = route('board.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('board.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'board_name' => 'required|unique:boards',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Board::create($data);

        return redirect()->route('board.index')->with('success', 'Board created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Board $board)
    {
        return view('board.edit', compact('board'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Board $board)
    {
        $data = $request->validate([
            'board_name' => "required|unique:boards,board_name,$board->id,id",
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $board->update($data);

        return redirect()->route('board.index')->with('success', 'Board updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Board $board)
    {
        return view('board.show', compact('board'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Board $board)
    {
        $board->delete();

        return redirect()->route('board.index')->with('success', 'Board deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function boardMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Board::where('id', $id)->delete();
        }

        return redirect()->route('board.index')->with('success', 'Board deleted successfully');
    }

}
