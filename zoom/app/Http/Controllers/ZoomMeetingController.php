<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Utility;
use App\Models\ProjectUser;
use App\Models\ZoomMeeting;
use Illuminate\Http\Request;
use App\Traits\ZoomMeetingTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ZoomMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ZoomMeetingTrait;

    const MEETING_TYPE_INSTANT               = 1;
    const MEETING_TYPE_SCHEDULE              = 2;
    const MEETING_TYPE_RECURRING             = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;
    const MEETING_URL                        = "https://api.zoom.us/v2/";


    public function index()
    {
        try {
           $zoomMeetings = ZoomMeeting::latest()->get();

            return response()->json($zoomMeetings);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|array',
            // 'user_id.*' => 'exists:users,id',
            'start_date' => 'required|date_format:Y-m-d\TH:i',
            'duration' => 'required|integer|min:1',
            'password' => 'nullable|string|max:10',
            // 'synchronize_type' => 'nullable|in:google_calender',
        ]);
        
        try {
            $data['title']             = $request->title;
            $data['start_time']        = date('y:m:d H:i:s', strtotime($request->start_date));
            $data['duration']          = (int)$request->duration;
            $data['password']          = $request->password;
            $data['host_video']        = 0;
            $data['participant_video'] = 0;

            $meeting_create = $this->createMeeting($data);
            
            if(isset($meeting_create['success']) && $meeting_create['success'] == true)
            {
                $meeting_id = isset($meeting_create['data']['id']) ? $meeting_create['data']['id'] : 0;
                $start_url  = isset($meeting_create['data']['start_url']) ? $meeting_create['data']['start_url'] : '';
                $join_url   = isset($meeting_create['data']['join_url']) ? $meeting_create['data']['join_url'] : '';
                $status     = isset($meeting_create['data']['status']) ? $meeting_create['data']['status'] : '';
                $password  = isset($meeting_create['data']['password']) ? $meeting_create['data']['password'] : '';
                
                DB::beginTransaction();
                $zoomMeeting             = new ZoomMeeting();
                $zoomMeeting->title      = $request->title;
                $zoomMeeting->meeting_id = $meeting_id;
                $zoomMeeting->user_id    = implode(',', $request->user_id);
                $zoomMeeting->start_date = date('y:m:d H:i:s', strtotime($request->start_date));
                $zoomMeeting->duration   = $request->duration;
                $zoomMeeting->start_url  = $start_url;
                $zoomMeeting->join_url   = $join_url;
                $zoomMeeting->status     = $status;
                $zoomMeeting->password   = $password;
                $zoomMeeting->save();
            }
            
            // if($request->get('synchronize_type')  == 'google_calender') {
            //     $type ='zoom_meeting';
                
            //     $request1=new ZoomMeeting();
            //     $request1->title=$request->title;
            //     $request1->start_date=$request->start_date;
            //     $request1->end_date=$request->start_date;
                
            //     Utility::addCalendarData($request1 , $type);

            // }
            DB::commit();
            
            return response()->json(['success' => true, 'message' => __('Meeting successfully created.'), 'data' => $meeting_create['data']]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ZoomMeeting $zoomMeeting
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ZoomMeeting $zoomMeeting)
    {
        $zoomMeeting = ZoomMeeting::find($zoomMeeting->id);
        
        if($zoomMeeting) {
            return response()->json($zoomMeeting);
        } else {
            return redirect()->back()->with('error', __('Meeting not found.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ZoomMeeting $zoomMeeting
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ZoomMeeting $zoomMeeting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ZoomMeeting $zoomMeeting
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ZoomMeeting $zoomMeeting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ZoomMeeting $zoomMeeting
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ZoomMeeting $zoomMeeting)
    {
        $zoomMeeting->delete();
        
        return redirect()->json(['success' => true, 'message' => __('Meeting successfully deleted.')]);
    }

    public function statusUpdate()
    {
        $meetings = ZoomMeeting::where('created_by', \Auth::user()->id)->pluck('meeting_id');
        foreach($meetings as $meeting)
        {
            $data = $this->get($meeting);

            if(isset($data['data']) && !empty($data['data']))
            {
                $meeting = ZoomMeeting::where('meeting_id', $meeting)->update(['status' => $data['data']['status']]);
            }
        }

    }

    public function calender(Request $request)
    {
        $user      = \Auth::user();
        $transdate = date('Y-m-d', time());
        $zoomMeetings = ZoomMeeting::latest()->get();
        $calandar = [];

        foreach($zoomMeetings as $zoomMeeting)
        {
            $arr['id']        = $zoomMeeting['id'];
            $arr['title']     = $zoomMeeting['title'];
            $arr['start']     = $zoomMeeting['start_date'];
            $arr['end']       = $zoomMeeting['end_date'];
            $arr['className'] = 'event-primary';
            $arr['url']       = route('zoom-meeting.show', $zoomMeeting['id']);
            $calandar[]     = $arr;
        }
        return view('zoom-meeting.calender', compact('calandar', 'transdate', 'zoomMeetings'));
    }

    //for Google Calendar
    public function get_zoom_meeting_data(Request $request)
    {

        if($request->get('calender_type') == 'goggle_calender')
        {
            $type ='zoom_meeting';
            $arrayJson =  Utility::getCalendarData($type);
        }
        else
        {
            $data =ZoomMeeting::where('created_by', \Auth::user()->creatorId())->get();
            $arrayJson = [];
            foreach($data as $val)
            {
                $arrayJson[] = [
                    "id"=> $val->id,
                    "title" => $val->title,
                    "start" => $val->start_date,
                    "className" => 'event-primary',
                    "textColor" => '#51459d',
                    'url'      => route('zoom-meeting.show', $val->id),
                ];
            }
        }
        return $arrayJson;
    }
}
