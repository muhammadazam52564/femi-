<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Address;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try
        {
            $validator = \Validator::make($request->all(), [
                'phone' => 'required|unique:users',
                'email' => 'required|unique:users',
                'name' => 'required',
                'password' => 'required|min:6|max:30',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                    'data' => null
                ], 400);
            }else{
                $user = new User;
                $user->email = $request->email;
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->otp = 1234;
                $user->password = bcrypt($request->password);
                if ( $user->save()) {
                    $user1 = User::where('email', $request->email)->get();
                    return response()->json([
                        'status' => true,
                        'message' => 'SignUp Successfully ',
                        'data' =>  $user1->makeHidden(['verified_at', 'updated_at', 'created_at']),
                    ]  , 200);
                }
            }
        }catch(\Exception $e){

            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }
    }
    public function login(Request $request)
    {
        try
        {
            $validator = \Validator::make($request->all(), [
                'email' => 'bail|required',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                    'data'  => null
                ], 400);
            }else{
                $str = '@';
                if (preg_match("/{$str}/i", $request->email)) {
                    if (auth()->attempt(['email' => $request->email, 'password' => $request->password])){
                        return response()->json([
                            'status' => true,
                            'message' => 'Successfully Loged In',
                            'data' => auth()->user()->makeHidden(['created_at', 'updated_at']),
                        ], 200);

                    }
                    else{
                        return response()->json([
                            'status' => false,
                            'message' =>'Invalid Credetials',
                            'data' => null,
                            ], 400);
                    }
                }else{
                    if (auth()->attempt(['phone' => $request->email, 'password' => $request->password])){
                        return response()->json([
                            'status' => true,
                            'message' => 'Successfully Loged In',
                            'data' => auth()->user()->makeHidden(['created_at', 'updated_at']),
                        ], 201);

                    }
                    else{
                        return response()->json([
                            'status' => false,
                            'message' =>'Invalid Credetials',
                            'data' => null,
                            ], 400);
                    }
                }
            }
        } catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action!',
                'error' => $validator->errors()->first(),
            ], 400);
        }
    }
    public function update_profile_image(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'   => 'required',
                'image'     => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }else{
                $user = User::find($request->user_id);
                if(empty($user)){
                    return response()->json([
                        'status'    => 400,
                        'error'     => 'User Does Not Exists!',
                        'data'      => null,
                    ], 400);
                }else{
                    $base64_image = $request->image;
                    if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                        $data = substr($base64_image, strpos($base64_image, ',') + 1);
                        $data = base64_decode($data);
                        $img = preg_replace('/^data:image\/\w+;base64,/', '', $base64_image);
                        $type = explode(';', $base64_image)[0];
                        $type = explode('/', $type)[1]; // png or jpg etc
                        if($type == 'png' || $type == 'PNG' || $type == 'jpg' || $type == 'JPG' || $type == 'jpeg' || $type == 'JPEG'){
                            $imageName = Str::random(10).'.'.$type;
                            \Storage::disk('profile_images')->put($imageName, $data); // this disk is defined in config/filesystems.php under Disks section
                            $img_path = 'profile_images/'.$imageName;
                        }else{
                            return response()->json([
                                'status' => 400,
                                'error' => 'Please Choose a Valid Image!',
                                'data' => null,
                            ], 400);
                        }
                    }
                    $user->profile_image = $img_path;
                    if($user->save()){
                        $user = User::find($request->user_id);
                        return response()->json([
                            'status' => 200,
                            'message' => 'Profile Image Updated Successfully!',
                            'data' => $user->makeHidden(['created_at', 'updated_at', 'email_verified_at', 'verification_code', 'self_description', 'type']),
                        ], 200);
                    }
                }
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status'    => 400,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }
    }
    public function update_profile(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'user_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                    'data'  => null
                ], 400);
            }else{
                if (empty(User::find($request->user_id))) {
                    return response()->json([
                        'status'    => false,
                        'error'     => 'User Not Exists',
                        'data'      => null
                    ], 400);
                }
                $user = User::find($request->user_id);
                if ($request->has('email')){

                    $email = User::where('id', $request->user_id)->pluck('email');

                    if ($email[0] != $request->email){

                        if (User::where('id', '!=', $request->user_id)->where('email', $request->email)->count() == 0) {
                            $user->email = $request->email;
                        }else{
                            return response()->json([
                                'status' => false,
                                'error' => 'Email Already Taken',
                                'data'  => null
                            ], 400);
                        }
                    }
                }
                if ($request->has('name')) {
                    $user->name = $request->name;
                }
                if( $user->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Profile Updated Successfully ',
                        'data' =>  $user->makeHidden(['verified_at', 'updated_at', 'created_at']),
                    ]  , 200);
                }
            }
        }catch(\Exception $e){

            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action!',
                'error' => $validator->errors()->first(),
            ], 400);
        }
    }
    public function change_password(Request $request)
    {
        try{
             $validator = \Validator::make($request->all(), [
                'user_id'       => 'bail|required',
                'old_password'  => 'required',
                'password'      => 'required|min:6',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'user not exist',
                    'data' => null,
                ], 400);
            }
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' =>  false,
                    'message' => 'incorrect old paasord',
                    'data' => null,
                ], 400);
            }
            $user->password = bcrypt($request->password);
            if($user->save()){
                return response()->json([
                    'status' => true,
                    'message' => 'Password Changed Successfully!',
                    'data' => $user->makeHidden(['created_at', 'updated_at', 'verification_code', 'type', 'token']),
                ], 200);
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
    public function forgot_password(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'bail|required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Could Not Complete Your Acction',
                    'error' => $validator->errors()->first(),
                ], 400);
            }
            $user = User::where('email', $request->email)->first();
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }
            // $code = rand(1111, 9999);
            $code = 1234;
            $user->verification_code = $code;
            $user->save();
            $data = [
                "opt"=> $code,
            ];
            // \Mail::to($request->email)->send(new ForgotPassword($code));
                return response()->json([
                    'status' => 200,
                    'message' => 'A Verification Code has been Sent to your Email!',
                    'data' => $data,
                ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }
    public function verify_code(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'bail|required',
                'verification_code' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Could Not Complete Your Acction',
                    'error' => $validator->errors()->first(),
                ], 400);
            }
            $user = User::where('email', $request->email)->first();
            if($request->verification_code == $user->verification_code)
            {
                $user->email_verified_at = Carbon::now();
                // $user->verification_code = null;
                $user->verification_code = 1234;
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Verification  Successful!',
                    'data' => $user,
                ], 200);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid Verification Code!',
                    'data' => null,
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => $e->getMessage(),
                ], 200);
            }
        }
    }
    public function set_password(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'bail|required',
                'password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Could Not Complete Your Acction',
                    'error' => $validator->errors()->first(),
                ], 400);
            }
            $user = User::where('email', $request->email)->first();
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }
            $user->password = bcrypt($request->password);
            if($user->save()){
                return response()->json([
                    'status' => 200,
                    'message' => 'Password Changed Successfully!',
                    'data' => $user->makeHidden(['created_at', 'updated_at', 'email_verified_at', 'verification_code', 'cover_image', 'self_description', 'opening_time', 'type']),
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson)
            {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage(),
                    'data'  => null
                ], 400);
            }
        }
    }
    public function add_address(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'   => 'required',
                'title'     => 'required',
                'city'      => 'required',
                'area'      => 'required',
                'street'    => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'user does not exists!',
                    'data' => null,
                ], 200);
            }else{
                $add = new Address;
                $add->user_id   = $request->user_id ;
                $add->title     = $request->title ;
                $add->city      = $request->city ;
                $add->area      = $request->area ;
                $add->street    = $request->street ;
                if($add->save()){
                    return response()->json([
                        'status' => 200,
                        'message' => 'Address Added Successfully',
                        'data' => null,
                    ], 200);
                }
            }

        }catch(\Exception $e)
        {
            if($request->expectsJson)
            {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage(),
                    'data'  => null
                ], 400);
            }
        }
    }
    public function signout(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'user_id' => 'bail|required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $user->token = null;
            if($user->save())
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'Logged Out Successfullty !',
                    'data' => null,
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => null,
                ], 200);
            }
        }
    }
}

