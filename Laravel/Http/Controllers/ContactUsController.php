<?php

namespace App\Http\Controllers;

use App\Models\Contact_us;
use Illuminate\Http\Request;

class ContactUsController extends Controller
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
        return view('contact_us.reply_list');
    }

    public function replyList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Contact_us::join('users','contact_uses.user_id','=','users.id');

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "name") {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('contact_uses.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['contact_uses.*','users.name'])->map(function ($recode) {
                $recode->show = route('reply.show', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function replyShow(Contact_us $contact_us)
    {
        return view('contact_us.reply_show', compact('contact_us'));
    }

    public function replyAnswer(Request $request, Contact_us $contact_us)
    {
        $data = $request->validate([
            'reply' => 'required'
        ]);

        $contact_us->reply = $data['reply'];
        $contact_us->is_reply = (($data['reply'] == NULL) ? 0 : 1) ;
        $contact_us->save();

        return redirect()->route('reply.show',$contact_us->id)->with('success','Reply to Message Done !');
    }
}
