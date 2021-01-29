<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helper\GlobalHelper;
 use Illuminate\Support\Facades\Input;
use App\Mail\WelcomeEmail;
use Illuminate\Database\Query\Builder;
use Hash;
use Session;
use DB;
use Image;
use App\User;
use App\Admin;
Use App\Models\YoutubelinkManage;
Use App\Models\FlipbooklinkManage;
Use App\Models\CookinglinkManage;
Use App\Models\IllujoinlinkManage;
Use App\Models\Q_alinkManage;
Use App\Models\MusicnaturelinkManage;
Use App\Models\Agenda;
Use App\Models\Alert;

class HomeController extends Controller
{
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	 
	 public function howto()
    {
	return view('howto');
	   
    } 
	 
	 
    public function index()
    {
		if(Auth::guard('users')->user()) {
            $YoutubelinkManage = YoutubelinkManage::get();
		$you['youlink'] = $YoutubelinkManage;
		
		$FlipbooklinkManage = FlipbooklinkManage::get();
		$flip['booklink'] = $FlipbooklinkManage;
		
		$IllujoinlinkManage = IllujoinlinkManage::get();
		$join['illujoin'] = $IllujoinlinkManage;
		
		 
		
		$gallery = DB::select('select * from gallery');
        $logo = DB::select('select * from logos');
        
        $userId = Auth::guard('users')->id();
        DB::enableQueryLog();
        $currentMeeting = DB::table("adminappointment")->whereRaw("find_in_set($userId, users1)")
            ->where('utc_datetime', '<=', date('Y-m-d H:i:s'))->where('utc_datetime', '>=', date('Y-m-d H:i:s', strtotime('-1 hour')))
            ->first();
        // dd(DB::getQueryLog());
		
        $Agenda = Agenda::get();
        
		//$agendas['agenda'] = $Agenda;
		
		return view('home')->with(['you'=>$you,'join'=>$join,'flip'=>$flip,'agenda'=>$Agenda,'gallery'=>$gallery,'logo'=>$logo, 'currentMeeting'=>$currentMeeting]);
		
        }else{
            return Redirect::to('/signin');
        }
		
		
        
    }
    public function meetingAdmin(){
       $meeting=$this->getMeeting();
       $data = Admin::find($meeting->user_id);
       return $data;
    }
    public function getMeeting(){
       $userId=Auth::guard('users')->user()->id;
       $data = DB::table("adminappointment")->whereRaw("find_in_set($userId,users1)")->orderBy('id','desc')->first();
       return $data;
    }
	public function login()
    {
        return view('login');
    }
	 
	public function sendJsonResponse($response)
    {
        if( $response['action'] )
        {
            return response()->json($response, '200'); 
        }
        return response()->json($response, '400'); 
    }
    
public function fetchdata()
{   
    $temps = DB::select('select id,text from alert where action=1');       
    
    if(count($temps)>0)
    {
            $response['action']     = true;  
            $response['msg']       = $temps[0]->text;
            $response['id']       = $temps[0]->id;
            return $this->sendJsonResponse($response);  
    }
    else
    {
        $response['action']     = false;  
        $response['msg']       = "still no message";
        return $this->sendJsonResponse($response);  
    }
    
}


public function sendJsonResponsev($response)
    {
        if( $response['lives'] )
        {
            return response()->json($response, '200'); 
        }
        return response()->json($response, '400'); 
    }


public function getv()
{   
    $temps = DB::select('select * from  youtubelink');       
    
    if(count($temps)>0)
    {
            $response['lives']     = true;  
            $response['msg']       =$temps[0]->live;
            $response['id']       = $temps[0]->live;
            return $this->sendJsonResponsev($response);  
    }
    else
    {
        $response['lives']     = false;  
        $response['msg']       = "still no message";
        return $this->sendJsonResponsev($response);  
    }
    
}









 






}
