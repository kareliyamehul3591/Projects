<?php

namespace App\Http\Controllers;

use App\Models\Difficulty_level_criteria;
use Illuminate\Http\Request;

class DifficultyLevelCriteriaController extends Controller
{
    public function index()
    {
        $easy = Difficulty_level_criteria::where('id',1)->first();
        $medium = Difficulty_level_criteria::where('id',2)->first();
        $difficult = Difficulty_level_criteria::where('id',3)->first();
        return view('difficulty_level.list',compact('easy','medium','difficult'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'very_easy1' => 'required',
            'easy1' => 'required',
            'difficult1' => 'required',
            'very_difficult1' => 'required',
            'sum_easy' => 'required|in:100',
            'very_easy2' => 'required',
            'easy2' => 'required',
            'difficult2' => 'required',
            'very_difficult2' => 'required',
            'sum_medium' => 'required|in:100',
            'very_easy3' => 'required',
            'easy3' => 'required',
            'difficult3' => 'required',
            'very_difficult3' => 'required',
            'sum_difficulty' => 'required|in:100'
        ],[
            'very_easy1.required' => 'required and cannot be empty',
            'very_easy2.required' => 'required and cannot be empty',
            'very_easy3.required' => 'required and cannot be empty',
            'easy1.required' => 'required and cannot be empty',
            'easy2.required' => 'required and cannot be empty',
            'easy3.required' => 'required and cannot be empty',
            'difficult1.required' => 'required and cannot be empty',
            'difficult2.required' => 'required and cannot be empty',
            'difficult3.required' => 'required and cannot be empty',
            'very_difficult1.required' => 'required and cannot be empty',
            'very_difficult2.required' => 'required and cannot be empty',
            'very_difficult3.required' => 'required and cannot be empty',
            'sum_easy.in' => 'Total percentage must be 100',
            'sum_medium.in' => 'Total percentage must be 100',
            'sum_difficulty.in' => 'Total percentage must be 100'
        ]);

        Difficulty_level_criteria::where('id',1)->update([
            'very_easy' => $request->input('very_easy1'),
            'easy' => $request->input('easy1'),
            'difficult' => $request->input('difficult1'),
            'very_difficult' => $request->input('very_difficult1'),
        ]);
        Difficulty_level_criteria::where('id',2)->update([
            'very_easy' => $request->input('very_easy2'),
            'easy' => $request->input('easy2'),
            'difficult' => $request->input('difficult2'),
            'very_difficult' => $request->input('very_difficult2'),
        ]);
        Difficulty_level_criteria::where('id',3)->update([
            'very_easy' => $request->input('very_easy3'),
            'easy' => $request->input('easy3'),
            'difficult' => $request->input('difficult3'),
            'very_difficult' => $request->input('very_difficult3'),
        ]);
        return redirect()->route('difficulty.level')->with('success','Difficulty Level Criteria Updated Successfully !');
    }
}
