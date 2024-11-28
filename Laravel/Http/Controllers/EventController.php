<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Event_category;
use App\Models\Package;
use App\Models\Sms_template;
use App\Models\Student;
use App\Models\Student_package;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class EventController extends Controller
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
        return view('event.list');
    }

    public function eventList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Event::join('event_categories','events.event_category_id','=','event_categories.id');

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "name") {
                    $values = $values->where('event_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('events.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get(['events.*','event_categories.name'])->map(function ($recode) {
                $recode->show = route('event.show', [$recode->id]);
                $recode->edit = route('event.edit', [$recode->id]);
                $recode->delete = route('event.delete', [$recode->id]);
                $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
                $recode->display_from_date = $recode->display_from->format('d-m-Y');
                $recode->display_to_date = $recode->display_to->format('d-m-Y');
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $event_categorys = Event_category::get();
        $packages = Package::where('status',1)->get();
        return view('event.create',compact('event_categorys','packages'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'event_category_id' => 'required',
            'display_from' => 'required|date_format:Y-m-d',
            'start_time' => 'required',
            'display_to' => 'required|date_format:Y-m-d',
            'end_time' => 'required',
            'show_in' => 'required',
            'package_id' => 'required',
            'keyword' => 'required',
            'event_image' => 'required'
        ]);
        $data['show_in'] = implode(",", $request->input('show_in'));
        $data['package_id'] = implode(",", $request->input('package_id'));
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        if ($request->hasFile('event_image')) {
            $event_image = Str::uuid() . '.' . $request->event_image->getClientOriginalExtension();
            $request->event_image->storeAs('public/events', $event_image);
            $data['event_image'] = asset('storage/events/'. $event_image);
        }

        $event = Event::create($data);

        $student_packages = Student_package::whereIn('package_id',explode(',',$event->package_id))->groupBy('user_id')->pluck('user_id')->toArray();
        foreach($student_packages as $student_package)
        {
            $user = User::where('id',$student_package)->first();
            $sEventData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $user->user->name,
                'student_email' => $user->user->email,
                'event_title' => $event->name,
                'event_content' => $event->description,
            ];
            $user->eventEmailStudent($sEventData);

            $student = Student::where('user_id',$user->id)->first();
            $puser = User::where('id',$student->parents()->user_id)->first();

            $pEventData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $student->user->name,
                'student_email' => $student->user->email,
                'event_title' => $event->name,
                'parent_name' => $puser->name,
                'parent_email' => $puser->email,
            ];

            $puser->eventEmailParent($pEventData);

            $data['event'] = $event->name;
            $template = Sms_template::find(18);
            $template->sendMessage($data);
        }

        return redirect()->route('event.index')->with('success', 'Event created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Event $event)
    {
        return view('event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Event $event)
    {
        $event_categorys = Event_category::get();
        $packages = Package::where('status',1)->get();
        return view('event.edit', compact('event','event_categorys','packages'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'event_category_id' => 'required',
            'display_from' => 'required|date_format:Y-m-d',
            'start_time' => 'required',
            'display_to' => 'required|date_format:Y-m-d',
            'end_time' => 'required',
            'show_in' => 'required',
            'package_id' => 'required',
            'status' => 'required',
            'keyword' => 'required',
            'event_image' => 'nullable'
        ]);

        $data['show_in'] = implode(",", $request->input('show_in'));
        $data['package_id'] = implode(",", $request->input('package_id'));
        $data['updated_by'] = auth()->user()->id;

        if ($request->hasFile('event_image')) {
            $event_image = Str::uuid() . '.' . $request->event_image->getClientOriginalExtension();
            $request->event_image->storeAs('public/events', $event_image);
            $data['event_image'] = asset('storage/events/'. $event_image);
        }

        $event->update($data);

        return redirect()->route('event.index')->with('success', 'Event updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('event.index')->with('success', 'Event deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function eventMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Event::where('id', $id)->delete();
        }

        return redirect()->route('event.index')->with('success', 'Event deleted successfully');
    }
}
