<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\web_filtering_setting;

class api_model extends Model
{

    // update
    public static function updatetoken($bearer_token, $loginid)
    {

        $sql = "SELECT id from secrets  WHERE fk_user_id='" . $loginid . "' AND  app_type='0' ";
        $search = DB::select($sql);

        if (count($search) > 0) {
            $sql = "UPDATE secrets SET bearer_token='" . $bearer_token . "' WHERE fk_user_id='" . $loginid . "'AND  app_type='0' ";
            $res = DB::update($sql);
        } else {

            $sql = "INSERT INTO secrets SET bearer_token='" . $bearer_token . "', fk_user_id='" . $loginid . "' , app_type='0' ";
            $res = DB::insert($sql);

        }

        return $res;

    }

    // update
    public static function signup($postdata)
    {
        $password = md5($postdata['password']);
        

        $newUserId = DB::table('users')->insertGetId([
            'name' => $postdata['name'],
            'password' => $password
        ]);

        return $newUserId;

    }

    public static function addchild($postdata)
    {

        $newkidId = DB::table('kids')->insertGetId([
            'fk_user_id' => $postdata['parent_id'],
            'kid_name' => $postdata['name'],
            'age' => $postdata['age'],
            'gender' => $postdata['gender'],
            'image_url' => $postdata['image'],
        ]);

        return $newkidId;

    }

//add new slot
    public static function addchoreslot($postdata)
    {

        $newslot = DB::table('chore_slots')->insertGetId([
            'fk_user_id' => $postdata['user_id'],
            'slot_name' => $postdata['slot_name'],
            'start_time' => $postdata['start_time'],
            'end_time' => $postdata['end_time'],

        ]);

        return $newslot;

    }

    //get slot

    public static function fetch_slot($user_id)
    {
        $slot = DB::table('chore_slots')->select('slot_id', 'slot_name', 'fk_user_id', 'start_time', 'end_time')->where('fk_user_id', '=', $user_id)->get();
        return $slot;

    }

    //add chores
    public static function addchores($postdata)
    {

        $newchoreId = DB::table('chores')->insertGetId([
            'fk_user_id' => $postdata['user_id'],
            'fk_slot_id' => $postdata['fk_slot_id'],
            'fk_kid_id' => $postdata['fk_kid_id'],
            'chore_name' => $postdata['chore_name'],
            'chore_description' => $postdata['chore_description'],
            'chore_type' => $postdata['chore_type'],
            'chore_frequency' => $postdata['chore_frequency'],
            'image_url' => $postdata['image_url'],
            'auto_approve' => $postdata['auto_approve'],
            'chore_time' => $postdata['chore_time'],
            'screen_time' => $postdata['screen_time'],
            'is_device_access_required' => $postdata['is_device_access_required'],

        ]);

        return $newchoreId;

    }

    //get chores

    public static function fetch_chores($user_id)
    {
        $allkid = DB::table('chores')->select('chore_id', 'fk_slot_id', 'fk_user_id', 'fk_kid_id', 'chore_name', 'chore_description', 'chore_type', 'chore_frequency', 'image_url', 'auto_approve', 'screen_time', 'chore_time', 'is_device_access_required', 'created_at', 'updated_at')->where('fk_user_id', '=', $user_id)->get();
        return $allkid;

    }



    //get chores
            // $kids = DB::select("SELECT * FROM kids WHERE fk_user_id = '$user_id'");
    // get kids

    public static function fetch_kids($user_id)
    {
        $kids = DB::table('kids')->select('kid_id','fk_user_id','kid_name','gender','age','image_url','created_at','updated_at')->where('fk_user_id', '=', $user_id)->get();
        return $kids;
    }
    // get kids
    //get contact
    public static function fetch_contact($user_id)
    {
        $contact = DB::select("SELECT * FROM emergency_contacts WHERE fk_user_id = '$user_id'");

        return $contact;
    }
    //get contact 


    public static function fetch_chores_childwise($kids_id)
    {
        $all_slots = DB::table('chores')->select('fk_slot_id')->where('fk_kid_id', '=', $kids_id)->get();

        $chore_arr = array();
        $i = 0;
        foreach ($all_slots as $all_slot) {
            $i++;

            $chore_arr[$all_slot->fk_slot_id] = DB::table('chores')->select('chore_id', 'fk_slot_id', 'chores.fk_user_id', 'fk_kid_id', 'chore_name', 'chore_description', 'chore_type', 'chore_frequency', 'image_url', 'auto_approve', 'screen_time', 'chore_time', 'is_device_access_required','slot_name') ->leftJoin('chore_slots', 'chores.fk_slot_id', '=', 'chore_slots.slot_id')->where('fk_kid_id', '=', $kids_id)->where('fk_slot_id', '=', $all_slot->fk_slot_id)->get();

        }

        //$allkid = DB::table('chores')->select('chore_id', 'fk_slot_id', 'fk_user_id', 'fk_kid_id', 'chore_name', 'chore_description', 'chore_type', 'chore_frequency', 'image_url', 'auto_approve', 'screen_time', 'chore_time', 'is_device_access_required')->where('fk_kid_id', '=', $kids_id)->get();

        return $chore_arr;

    }

    public static function fetch_child($parent_id)
    {
       $allkid = DB::table('kids')->select('kid_id', 'fk_user_id','kid_name', 'gender', 'age', 'image_url','created_at','updated_at')->where('fk_user_id', '=', $parent_id)->get();

        // $allkid = DB::select("SELECT kids.kid_id,kids.kid_name,kids.gender,kids.age,kids.image_url,secrets.bearer_token FROM kids LEFT JOIN secrets ON kids.fk_user_id = secrets.fk_user_id WHERE
        //     kids.fk_user_id = '$parent_id'");

        //$allkid = DB::select("SELECT * FROM kids WHERE fk_user_id = '$parent_id'");
        // $allkid = DB::select("SELECT kids.*,devices.device_id FROM kids LEFT JOIN devices ON kids.kid_id = devices.fk_kid_id WHERE kids.fk_user_id = '$parent_id'");
        // $allkid_details = array();

        // $i=0;
        // foreach ($allkid as $value) 
        // {

        //     $allkid_details[$i]['kid_id'] = $value->kid_id;
        //     $allkid_details[$i]['user_id'] = $value->fk_user_id;

        //     $allkid_details[$i]['kid_name'] = $value->kid_name;
        //     $allkid_details[$i]['gender'] = $value->gender;
        //     $allkid_details[$i]['age'] = $value->age;

        //     $allkid_details[$i]['image_url'] = $value->image_url;
        //     $allkid_details[$i]['created_at'] = $value->created_at;

        //     $allkid_details[$i]['updated_at'] = $value->updated_at;
        //     $allkid_details[$i]['device_id'] = ($value->device_id == NULL) ? '' : $value->device_id;

        //     $i++;
        // }

        // print_r($allkid);exit();
       return $allkid;



   }
   public static function fetch_childfor_kid($parent_id)
   {
    $allkid = DB::select("SELECT kids.*,devices.device_id,devices.mode FROM kids LEFT JOIN devices ON kids.kid_id = devices.fk_kid_id WHERE kids.fk_user_id = '$parent_id'");
    $allkid_details = array();

    $i=0;
    foreach ($allkid as $value) 
    {


        $allkid_details[$i]['kid_id'] = (string)$value->kid_id;
        $allkid_details[$i]['user_id'] = (string)$value->fk_user_id;

        $allkid_details[$i]['kid_name'] = $value->kid_name;
        $allkid_details[$i]['gender'] = (string)$value->gender;
        $allkid_details[$i]['age'] = (string)$value->age;

        $allkid_details[$i]['image_url'] = $value->image_url;
        $allkid_details[$i]['created_at'] = $value->created_at;

        $allkid_details[$i]['updated_at'] = $value->updated_at;

        $allkid_details[$i]['device_id'] = ($value->device_id == NULL) ? '' : (string)$value->device_id;

        $allkid_details[$i]['device_mood'] = ($value->mode == NULL) ? '' : $value->mode;

        $i++;
    }

        // print_r($allkid);exit();
    return $allkid_details;
}
/*----Fetch child devices by anup------------------*/
public static function fetch_child_devices($parent_id,$kid_id)
{
    
    $alldevices = DB::table('devices')
    ->select('devices.device_id','devices.mode', 'devices.fk_user_id', 'devices.fk_kid_id', 'devices.device_name', 'devices.device_type','devices.created_at','devices.updated_at','kids.kid_name','kids.image_url')
    
    ->leftjoin('kids', 'kids.kid_id', '=', 'devices.fk_kid_id')
    ->where('devices.fk_user_id', '=', $parent_id)->where('devices.fk_kid_id', '=', $kid_id)->where('device_status', '=' ,'1')->where('is_deleted','=','1')->orderBy('devices.created_at', 'ASC')->get();

                           // print_r($alldevices);exit();
    return $alldevices;

}


/*-----------------------------------------------------*/

public static function fetch_chores_timeline($time)
{
        // $chore = DB::select("SELECT chores.*,kids.kid_name,kids.gender,kids.age,kids.image_url,secrets.bearer_token FROM kids LEFT JOIN secrets ON kids.fk_user_id = secrets.fk_user_id WHERE kids.fk_user_id = '$parent_id'");




}

    //login latest working----------------

public static function loginother($loginid, $loginpassword)
{
        // $sql = "SELECT * FROM users WHERE email='" . $loginid . "' AND password='" . md5($loginpassword) . "'";
    $user = User::where('email',$loginid)->where('password',md5($loginpassword))->get();
    if (count($user) > 0) 
    {
        // echo "hi";exit();
    }
    else
    {
        // echo bcrypt($loginpassword);exit();
        $user = User::where('email',$loginid)->where('app_password',md5($loginpassword))->get();

    }
        // echo $user;exit();
        // $res = DB::select($sql);
        // print_r($res);exit();
    $login_details_arr = array();
    if (count($user) > 0) {
            // echo "hi";exit();
        foreach ($user as $key) {
            $login_details_arr['login_id'] = $key->id;
            $login_details_arr['plan_id'] = $key->current_plan_id;
        }
           // return $login_id;
            // echo $login_id;exit();
    } else {
        $login_details_arr['login_id'] = 0;
        $login_details_arr['plan_id'] = 0;
    }
    return $login_details_arr;

}

    //check bearer token

public static function checkbearertoken($loginid, $bearer)
{

    $sql = "SELECT * from secrets WHERE fk_user_id='" . $loginid . "' AND bearer_token='" . $bearer . "' and app_type='0' ";
    $res1 = DB::select($sql);

    if (count($res1) > 0) {
        $sql = "SELECT id,no_of_kids,name,email FROM users WHERE id='" . $res1[0]->fk_user_id . "'   ";
        $res = DB::select($sql);
        return $res;

    } else {
        return $res1;
    }

}

public static function checkbearertokenchild($loginid, $bearer,$kidid)
{
   /* $sql = "SELECT * from secrets WHERE fk_user_id='" . $loginid . "' AND bearer_token='" . $bearer . "' and app_type='1' ";
    $res1 = DB::select($sql);*/
	
	/*-----------Modify above logic------------*/
	 $res = DB::table('kids')->select('kid_id')
               ->where('fk_user_id',$loginid)
			   ->where('kid_id','=',$kidid)
               ->where('kids_bearer_token','=',$bearer)
               ->get();
        //return $res;
	/*-----------------------------------------*/

    if (count($res) > 0) {
		
       // $sql = "SELECT id,no_of_kids,name,email FROM users WHERE id='" . $res1[0]->fk_user_id . "'   ";
	    $sql = "SELECT id,no_of_kids,name,email FROM users WHERE id='" . $loginid. "'   ";
        $res = DB::select($sql);
        return $res;

    } else {
        return $res;
    }
}

public static function checkemail($email)
{

    $res1 = DB::table('users')->where('email', '=', $email)->first();
    if ($res1) {

        return 1;

    } else {
        return 0;

    }

}

    // update
public static function updatetoken_child($bearer_token, $loginid)
{

    $sql = "SELECT * from secrets  WHERE fk_user_id='" . $loginid . "' AND  app_type='1' ";
    $search = DB::select($sql);
        // echo count($search);exit();
    if (count($search)> 0) {
        $sql = "UPDATE secrets SET bearer_token='" . $bearer_token . "' WHERE fk_user_id='" . $loginid . "'AND  app_type='1' ";
        $res = DB::update($sql);
    } else {

        $sql = "INSERT INTO secrets SET bearer_token='" . $bearer_token . "', fk_user_id='" . $loginid . "', app_type='1' ";
        $res = DB::insert($sql);

    }

    return $res;

}

// insert setting_web_filtering
public static function insert_web_filtering($postdata,$web_filtering_setting)
{
    $web_filtering_setting->fk_user_id          = $postdata['user_id'];

    $web_filtering_setting->fk_kid_id           = $postdata['kid_id'];
    $web_filtering_setting->button_status       = $postdata['button_status'];
    $web_filtering_setting->save();

    return $web_filtering_setting;      
}


/*-------------------Fetch Chore Slots by (Anup)--------------------*/
public static function fetch_chore_solts($user_id)
{
    $all_chore_slots_results = DB::table('chore_slots')->select('slot_id', 'slot_name', 'start_time', 'end_time')->where('fk_user_id', '=', $user_id)->get();
    return $all_chore_slots_results;

}
/*-------------------Fetch All Chore As Per Slot Id by (Anup)--------------------*/
public static function fetch_all_chores_as_per_slot_id($user_id,$slot_id)
{

    $all_chores_results = DB::table('chores')->select('chore_id', 'fk_kid_id', 'chore_name', 'image_url')->where('fk_user_id', '=', $user_id)->where('fk_slot_id', '=', $slot_id)->get();
    return $all_chores_results;

}


/*--------------------------------------------------------*/



/*-------------------Fetch All Chore As Per Slot Id by modified--------------------*/
public static function fetch_all_chores_as_per_slot_id_modified($user_id,$slot_id,$kid_id,$times)
{
        // echo $slot_id;exit();
       // $today = Carbon::today();
        // $all_chores_results = DB::table('chores')->select('chore_id', 'fk_kid_id', 'chore_name', 'image_url')->where('fk_user_id', '=', $user_id)->where('fk_slot_id', '=', $slot_id)->where('fk_kid_id', '=', $kid_id)->where('last_activity','=',$times)->get();

    $all_chores_results = DB::select("SELECT chores.chore_id,chores.fk_kid_id,chores.chore_name,chores.image_url as chore_img_url,chores.chore_description,chores.fk_chore_media_id,chores.chore_type,chores.chore_frequency,chores.auto_approve,chores.screen_time,chores.chore_time   ,chores.is_device_access_required,chores.last_activity,complete_chore_details.start_time,complete_chore_details.end_time,complete_chore_details.image_url as verification_img_url,complete_chore_details.audio_url,complete_chore_details.is_self_verify,complete_chore_details.is_approve,complete_chore_details.status FROM chores  LEFT JOIN complete_chore_details ON chores.chore_id = complete_chore_details.fk_chore_id WHERE chores.fk_user_id = '$user_id' AND chores.fk_slot_id = '$slot_id' AND chores.fk_kid_id = '$kid_id' AND chores.last_activity = '$times'");
        // print_r($all_chores_results);exit();
    return $all_chores_results;

}

public static function checkMail($email){
    $res = DB::table('users')->where('email',$email)->count();
    if($res > 0){
        return 1;
    }
    else{
        return 0;
    }
}

public static function updateOTP($email,$otp){
    $res = DB::table('users')->where('email',$email)->update(['otp'=>$otp]);
    return $res;
}

public static function getUser($email){
    $res = DB::table('users')->where('email',$email)->first();
    return $res;
}

public static function checkOtp($email,$otp){
    $res = DB::table('users')->where('email',$email)->first();
    if($res->otp == $otp){
        return 1;
    }
    else{
        return 0;
    }        
}

public static function setBlankOtp($email){
    $res = DB::table('users')->where('email',$email)->update(['otp'=>'']);
    return $res;
}

public static function updatePassword($email,$pass)
{
    $res = DB::table('users')->where('email',$email)->first();
    if ($res->type == 1) 
    {
        $res = DB::table('users')->where('email',$email)->update(['app_password'=>md5($pass)]);
    }
    else
    {

        $res = DB::table('users')->where('email',$email)->update(['password'=>md5($pass)]);
    }
    
    return $res;
}

    public static function record_device($user_id)
    {

        $devices = DB::select("SELECT kids.kid_name,kids.image_url,devices.device_id,devices.fk_user_id,devices.fk_kid_id,devices.device_name,devices.device_type,devices.device_token FROM kids LEFT JOIN devices ON kids.kid_id = devices.device_id WHERE devices.fk_user_id = $user_id");
        return  $devices;
    }

    public static function add_custom_chore_kid_age($age)
    {
        // echo $age;exit();
        $kid_age = DB::select("SELECT * FROM filter_ages WHERE start_age <= $age AND end_age >= $age");
        // print_r($kid_age);exit();
        return $kid_age;
    }

    public static function add_custom_chore_duration($chore_time)
    {
        $duration = DB::select("SELECT * FROM default_durations WHERE start_duration <= '$chore_time' AND end_duration >= '$chore_time'");
        return $duration;
    }



    public static function check_mood($from,$kid_id)
    {
        $sig_kid = Kid::where('kids_device_type','ios')->where('kid_id',$kid_id)->first();
        // echo $sig_kid;exit();

            $user = User::find($sig_kid->fk_user_id);
            // $from = Carbon::parse($request->ShootDateTime)->timezone($user->user_timezone);

            $explode_from = explode(' ', $from);

            $time = $explode_from[1];
            // print_r($time);exit();

            $date = $explode_from[0];
            $device = Device::whereNotNull('app_service_id')->where('fk_kid_id',$sig_kid->kid_id)->orderBy('created_at','desc')->first();

            // $todo_chore = api_model::newnewchore_timeline_for_kid($time,$sig_kid->fk_user_id,$sig_kid->fk_kid_id,$date);





            // 10-01-2020


                 // getRequestHeaders for authentication  
            // $input = $request->all();        

            $post_data = array(); 
            $post_data['time'] = $time;

            $post_data['user_id'] = $sig_kid->fk_user_id; 
            $post_data['kid_id'] = $sig_kid->kid_id;
            $post_data['date'] = $date;       

            // $slot_ids = '';
            // $upcomingslot_ids = '';

            // $time = $request->date;  
                   
            $current_time = date('H:i:s', strtotime($post_data['time']));

                       
                        $chore_result = array();
                      
                            $user = User::find($sig_kid->fk_user_id); // new line
                            // echo $user;exit();
                            if ($user->current_plan_id == 0) // new line
                            {

                                $chore        = chore::where('fk_kid_id',$post_data['kid_id']);
                                
                                // ->whereRaw("fk_slot_id in (".$slot_ids.")");

                                $chore->where(function ($chore) use ($post_data){


                                    $chore->orwhere(function ($chore) use ($post_data) {

                                        $chore->where('chore_frequency',0)->where('is_priority','0');

                                    });
                                });

                                $chore_values = $chore->with('complete_chore')->get();
                            }
                            else // new line
                            {

                                $chore        = chore::where('fk_kid_id',$post_data['kid_id'])->where('is_priority','0');

                                // ->whereRaw("fk_slot_id in (".$slot_ids.")");


                                $chore_values = $chore->with(['complete_chore'=>function($q) use($date) {

                                                                                // Query the name field in status table                       
                                                                                $q->whereDate('current_date', '=', $date); // '=' is optional

                                                                            }])->get();
                            }
                            
                            
                            // echo $chore_values;exit();
                            

                            $chore_value = array();
                            $k = 0;
                            foreach ($chore_values as $value) 
                            {
                                $chore_inactive_date = chore_inactive_date::where('fk_chore_id',$value->chore_id)->where('date',$post_data['date'])->first();
                                if ($chore_inactive_date) 
                                {
                                    
                                    // echo "hi";
                                }
                                else
                                {   
                                    if ($value->chore_frequency == 1) 
                                    {
                                        if($user->current_plan_id) 
                                        {
                                            $date1 = strtotime(date('Y-m-d',strtotime($value->created_at)));
                                            $date2 = strtotime(date('Y-m-d',strtotime($post_data['date'])));
                                            $diff = abs($date2 - $date1);
                                            $days = abs(round($diff / 86400));
                                            if($days % $value->chore_showing_duration  == 0)
                                            {
                                                 
                                                if (!$value->is_priority == '1') 
                                                {
                                                    
                                                    if (!$value->complete_chore == null) 
                                                    {
                                                        
                                                        if ($value->complete_chore->is_approve == 2) 
                                                        {

                                                        }
                                                        else
                                                        {
                                                            
                                                            $chore_value[$k] = $value;
                                                            $k++;
                                                        }
                                                    }  
                                                    else
                                                    {
                                                        $chore_value[$k] = $value;
                                                        $k++;
                                                    } 
                                                }                         
                                            }
                                        }
                                        else
                                        {
                                            $date1 = strtotime(date('Y-m-d',strtotime($value->created_at)));
                                            $date2 = strtotime($post_data['date']);
                                            if($date1==$date2)
                                            {
                                                if (!$value->is_priority == '1') 
                                                {
                                                    if (!$value->complete_chore == null) 
                                                    {
                                                        if ($value->complete_chore->is_approve == 2) 
                                                        {

                                                        }
                                                        else
                                                        {
                                                            $chore_value[$k] = $value;
                                                            $k++;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $chore_value[$k] = $value;

                                                        $k++;
                                                    } 
                                                }    
                                            }
                                        }   
                                    }
                                    elseif ($value->chore_frequency == 0) 
                                    {

                                        $date1 = strtotime(date('Y-m-d',strtotime($value->created_at)));
                                        $date2 = strtotime($post_data['date']);

                                        if($date1==$date2)
                                        {                                

                                            if (!$value->is_priority == '1') 
                                            {
                                                if (!$value->complete_chore == null) 
                                                {
                                                    if ($value->complete_chore->is_approve == 2) 
                                                    {

                                                    }
                                                    else
                                                    {
                                                        $chore_value[$k] = $value;
                                                        $k++;
                                                    }
                                                }  
                                                else
                                                {
                                                    $chore_value[$k] = $value;
                                                    $k++;
                                                }
                                            }   
                                        }

                                    }
                                    elseif ($value->chore_frequency == 2) 
                                    {

                                        $nameOfDay = date('D', strtotime($post_data['date']));
                                        $nameOfWeek = date('W', strtotime($post_data['date']));                     

                                        $created_date = explode(' ', $value->created_at);
                                        $createdat_week = date('W', strtotime($created_date[0]));


                                        $diff_week = $nameOfWeek-$createdat_week;


                                        if (($nameOfWeek == $createdat_week) || (is_float($diff_week/$value->chore_showing_duration) !== true)) 
                                        {
                                            // echo "hi";exit();
                                            $chore_showing_specific_date = Chore_showing_specific_date::where('fk_chore_id',$value->chore_id)->where('fk_chorefrequency',$value->chore_frequency)->get();

                                            foreach ($chore_showing_specific_date as $specific_date) 
                                            {
                                                if ($specific_date->specific_dates_or_days == $nameOfDay) 
                                                {
                                                    if (!$value->is_priority == '1') 
                                                    {
                                                        if (!$value->complete_chore == null) 
                                                        {
                                                            if ($value->complete_chore->is_approve == 2) 
                                                            {

                                                            }
                                                            else
                                                            {
                                                                $chore_value[$k] = $value;
                                                                $k++;
                                                            }

                                                        } 
                                                        else
                                                        {
                                                            $chore_value[$k] = $value;
                                                            $k++;
                                                        } 
                                                    }
                                                }   
                                            }
                                        }
                                    }
                                    elseif ($value->chore_frequency == 3) 
                                    {
                                        $date1 = $value->created_at;
                                        $date1_month = explode('-', $date1);

                                        $date2 = $post_data['date'];
                                        $date2_month = explode('-', $date2);

                                        $diff = $date2_month[1] - $date1_month[1];
                                        if (($date2_month[1] == $date1_month[1]) || (is_float($diff/$value->chore_showing_duration) !== true)) 
                                        {

                                            $chore_showing_specific_date = Chore_showing_specific_date::where('fk_chore_id',$value->chore_id)->where('fk_chorefrequency',$value->chore_frequency)->get();
                                            foreach ($chore_showing_specific_date as $specific_date) 
                                            {
                                                if ($specific_date->specific_dates_or_days == $date2_month[2]) 
                                                {
                                                    if (!$value->is_priority == '1') 
                                                    {
                                                        if (!$value->complete_chore == null) 
                                                        {
                                                            if ($value->complete_chore->is_approve == 2) 
                                                            {

                                                            }
                                                            else
                                                            {
                                                                $chore_value[$k] = $value;
                                                                $k++;
                                                            }           

                                                        }
                                                        else
                                                        {
                                                            $chore_value[$k] = $value;

                                                            $k++;
                                                        } 
                                                    }   
                                                }
                                                    
                                            }
                                        }

                                    }

                                }


                            }
                            // print_r($chore_value);exit();
                            // new
                            $j=0;
                            foreach ($chore_value as $res_value)
                            {
                                $chore_result[$j]['chore_id'] = (string)$res_value->chore_id;

                                $chore_result[$j]['fk_slot_id'] = (string)$res_value->fk_slot_id;
                                $chore_result[$j]['fk_user_id'] = (string)$res_value->fk_user_id;

                                $chore_result[$j]['fk_kid_id'] = (string)$res_value->fk_kid_id;
                                $chore_result[$j]['chore_name'] = (string)$res_value->chore_name;
                                $chore_result[$j]['chore_description'] = (string)$res_value->chore_description;

                                if ($res_value->fk_chore_media_id>0) 
                                {

                                    $chore_media = chore_media::find($res_value->fk_chore_media_id);

                                    $chore_result[$j]['image_url'] = (string)$chore_media->image_url;
                                }
                                else
                                {                     
                                    $chore_result[$j]['image_url'] = '';

                                }

                                $chore_result[$j]['chore_type'] = (string)$res_value->chore_type;
                                $chore_result[$j]['chore_frequency'] = (string)$res_value->chore_frequency;

                                $chore_result[$j]['fk_chore_media_id'] = (string)$res_value->fk_chore_media_id;              
                                $chore_result[$j]['auto_approve'] = (string)$res_value->auto_approve;

                                $chore_result[$j]['screen_time'] = (string)$res_value->screen_time;
                                $chore_result[$j]['chore_time'] = (string)$res_value->chore_time;

                                $chore_result[$j]['is_device_access_required'] = (string)$res_value->is_device_access_required;

                                $chore_result[$j]['last_activity'] = (string)$res_value->last_activity;
                                $chore_created_time = explode(' ', $res_value->created_at);

                                $chore_result[$j]['chore_created_time'] = (string)$chore_created_time[1];
                                if ($res_value->complete_chore == null) 
                                {
                                    $chore_result[$j]['status'] = '';
                                    $chore_result[$j]['chore_start_time'] = '';
                                }
                                else
                                {
                                    $chore_result[$j]['status'] = (string)$res_value->complete_chore->status;

                                    $chore_result[$j]['chore_start_time'] = $res_value->complete_chore->start_time;
                                }
                                if ($res_value->complete_chore == null) 
                                {
                                    $chore_result[$j]['complete_chore_id'] = '';
                                }
                                else
                                {
                                    $chore_result[$j]['complete_chore_id'] = (string)$res_value->complete_chore->id;

                                }
                                if ($res_value->complete_chore == null) 
                                {

                                    $chore_result[$j]['diff_time'] = '';
                                }
                                else
                                {
                                    if ($res_value->complete_chore->diff_time == null) 
                                    {
                                        $chore_result[$j]['diff_time'] = '';
                                    }
                                    else
                                    {
                                        $chore_result[$j]['diff_time'] = $res_value->complete_chore->diff_time;
                                    }
                                }
                                if ($res_value->complete_chore == null) 
                                {
                                    $chore_result[$j]['is_approve'] = '';
                                }
                                else
                                {

                                    $chore_result[$j]['is_approve'] = (string)$res_value->complete_chore->is_approve;

                                }
                                $chore_result[$j]['is_anytime']     = (string)$res_value->is_anytime;

                                $chore_result[$j]['time']           = 'present';

                                $chore_result[$j]['is_priority']    = (string)$res_value->is_priority;
                                $j++;

                            }
                          
                            $data['present']        = $chore_result;
                        

      
                                if (!empty($data['present'])) 
                                {
                                                $kid_details = Kid::where('kid_id',$sig_kid->kid_id)->first();

                                                if ($kid_details->kids_device_type == 'ios') 
                                                {
                                                    // echo $kid_details->kid_id;exit();
                                                    $device = Device::where('fk_kid_id',$kid_details->kid_id)->where('device_type','ios')->where('device_status',1)->orderBy('created_at','asc')->whereNotNull('app_service_id')->first();

                                                    // echo  $device;exit();
                                                    if ($device) 
                                                    {
                                                        if (!empty($device->app_service_id)) 
                                                        {
                                                            
                                                            $ch = curl_init('https://saturdaymorningapp.com/mdm/api.php?command=LOCK_DOWN_MODE&appServiceId='.$device->app_service_id);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                                                            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                                           // echo $result = curl_exec($ch);
                                                            //return $result;
                                                            $httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE); // this results 0 every time
                                                           $response = curl_exec($ch);
                                                           // if ($response === false)
                                                               // $response = curl_error($ch);
                                                           // echo stripslashes($response);
                                                            curl_close($ch);
                                                        }
                                                    }             
                                                }         
                                }
                            return $data;    
    }
}
