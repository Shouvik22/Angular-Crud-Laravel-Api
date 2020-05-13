<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\User;

use App\product;
use App\test;

use App\api_model;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }



    public function __construct()
    {
        
        date_default_timezone_set('Asia/Calcutta');
        $cur_date          = date("Y-m-d");
        $cur_date_time     = date("Y-m-d H:i:s");
        
        /*--------------------Secret Key Assign For login-----------------------*/
        $privatekey                             = 'FdZe43N0Af2jt21QYegW'; //private Key
        $comapany_code                          = 'parent1'; //Company Code
        /*------------------- Secret Key  Assign For Bearer Token---------------*/
        $accessKey                               = '73523550-1524613478-7449'; //Access Key
        $comapany_code_for_bearer_token_generate = 'empmanage1'; //Company Code
        $private_key_for_bearer_token_generate   = 'AeNgeik6tai3Jahv-eMoog8aiEihai7ie'; //secret key
        /*---------------------------------------------------------------------*/
        //blockio init
        $this->cur_date                                   = $cur_date;
        $this->cur_date_time                              = $cur_date_time;
       
        $this->comapany_code                               = $comapany_code;
        $this->accessKey                                   = $accessKey;
        $this->comapany_code_for_bearer_token_generate     = $comapany_code_for_bearer_token_generate;
        $this->private_key_for_bearer_token_generate       = $private_key_for_bearer_token_generate ;
    }
    

    public function store(Request $request)
    {
        if (count($request->ingredients)) 
        {   
            foreach ($request->ingredients as $value) 
            {
               
                $test = new test;
                $test->name = $request->name;
                
                $test->desctiption = $request->desctiption;
                $test->imagePath = $request->imagePath;
                
                $test->ingredient_name = $value->name;
                $test->ingredient_amount = $value->amount;
                $test->save();
            }
        }

        
        $data = array();
        $data['status'] = 200;
        $data['message'] = 'success';
        
        return $data;
    }


    public function signup(Request $request)
    {
        $input = $request->all();
        $data = array();
        $CheckBaseSecurity = ApiController::Check_Base_Security();
        if ($CheckBaseSecurity == '1') {
            $post_data['name']       = $input['username'];
            
            $post_data['password']         = $input['password'];
            $login_id  = api_model::signup($post_data);

            /*-----------------------Bearer Token Generate--------------------------*/
            $t     = time(); //TimeStamp
            $clear = trim($this->comapany_code_for_bearer_token_generate . $this->accessKey . $t);
            $hash  = base64_encode(hash_hmac('sha256', $clear, $this->private_key_for_bearer_token_generate, true));
            /*-----------------------Update Bearer Token---------------------------*/
            // $login = api_model::update_bearer_token($hash, $login_id);
            $login = User::where('id',$login_id)->first();
            $login->token = $hash;
            $login->save();
            /*-----------------------------------------------------------*/
            $user_details_arr = array("bearer_token" => $hash, "user_id" => $login_id);
            $data['status'] = 200;
            $data['message'] = 'success';
            $result = array();
            $result['token'] = $hash;
            $result['username'] = $input['username'];
            $result['user_id'] = $login_id;
            $data['result'] = $result;
            
            return $data;
        } 
        else{
            $error = array();

            $data['status'] = 500;
            $data['message'] = 'error';
            $data['result'] = null;
        }
    }



    // /*----------------------------------Check_Base_Security----------------------------------------------*/
    public function Check_Base_Security()
    {
        $headers                        = array();
        /*----------------------Header Data Fetch------------------------*/
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) != 'HTTP_') {
                continue;
            }
            $header           = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        //print_r($header);die;
        $secret               = $headers['Secret'];
        $public_key           = $headers['Public'];
        /*--------------------------------------------------------------*/
        $clear                = trim($this->comapany_code . $this->privatekey . $public_key);
        $hash                 = base64_encode(sha1($clear));
        if ($secret != $hash) {
            return 0;
        }
        return 1;
    }

    // =================================if the record send in query paramater===================================//
    // public function generate_token(Request $request)
    // {
    //     $data = array();
    //     if ($request->Secret == 'NDY4ZmJlM2YwZTgyNTgzMzgyYzViZWJlOTg3MTI1OWE5NTg4ODg0MA==' && $request->Public == '456') 
    //     {
    //         $user = User::where('name',$request->username)->first();
    //         $data['status'] = 200;
    //         $data['message'] = 'success';
    //         $result = array();
    //         $result['token'] = $user->token;
    //         $result['username'] = $user->name;
    //         $data['result'] = $result;
            
    //         return $data;
    //     }
    //     else
    //     {
    //         $data['status'] = 500;
    //         $data['message'] = 'error';
    //         $data['result'] = null;

    //         return $data;
    //     }
        
    // }
    // =================================if the record send in query paramater===================================//



    // =================================if the record send in header ==============================//
    public function generate_token(Request $request)
    {
        $data = array();
        $CheckBaseSecurity = ApiController::Check_Base_Security();
        
        if ($CheckBaseSecurity == '1') 
        {
            $password = md5($request->password);
            $user = User::where('name',$request->username)->where('password',$password)->first();
            if ($user) 
            {
                $data['status'] = 200;
                $data['message'] = 'success';
                $result = array();
                $result['token'] = $user->token;
                $result['username'] = $user->name;
                $result['user_id'] = $user->id;
                $data['result'] = $result;
                
                return $data;
            }
            else
            {
                $data['status'] = 500;
                $data['message'] = 'Credetial does not match';
                $data['result'] = null;

                return $data;
            }   
        }
        else
        {
            $data['status'] = 500;
            $data['message'] = 'Private key missmatch';
            $data['result'] = null;

            return $data;
        }
    }

    //==================================== if the record send in header================================ //



    public function fetch_list()
    {
        $data               = array();
        $product            = product::get();
        $data['status']     = 200;
        $data['message']    = 'success';
        $data['result']     = $product;
        return $data;
    }

    public function add_list(Request $request)
    {
        $data               = array();
        $product            = new product;
        $product->name      = $request->name;
        $product->price     = $request->price;

        if ($request->image) 
        {
                
                $files = $request->image;               
                $name  = time()."_".$files->getClientOriginalName();                         
                
                $image = $files->move(public_path().'/uploads/image/',$name);                      
                $product->image = '/image/'.$name;             
        }
        $product->save();

        $data['status'] = 200;
        
        $data['message'] = 'success';

        return $data;
    }

    public function edit_list(Request $request,$id)
    {
        
        $data               = array();
        $product            = product::where('id',$id)->first();
        $data['status']     = 200;
        $data['message']    = 'success';
        $data['result']     = $product;
        return $data;
    }

    public function update_list(Request $request,$id)
    {
        $data               = array();
        $product            = product::where('id',$id)->first();
        $product->name      = $request->name;

        $product->price     = $request->price;
        // $product->status    = $request->status;

        $product->save();
        
        $data['status']     = 200;
        $data['message']    = 'success';
        $data['result']     = [];
        return $data;
    }


    
    public function delete_list(Request $request,$id)
    {

        $data               = array();
        $product            = product::where('id',$id)->first();

        $product->delete();
        $data['status']     = 200;
        $data['message']    = 'success';

        return $data;
    }
}
