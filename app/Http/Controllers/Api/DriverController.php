<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Licence;
use App\Models\Address;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class DriverController extends Controller
{
    public function register(Request $request)
    {
        try
        {
            $validator = \Validator::make($request->all(), [
                'name'                  => 'required',
                'email'                 => 'required|unique:user',
                'phone'                 => 'required|unique:user',
                'password'              => 'required|min:6|max:30',

                'address_title'         => 'required',
                'lat'                   => 'required',
                'long'                  => 'required',
                'street'                => 'required',

                'ac'                    => 'required',
                'sort_code'             => 'required',
                'iban'                  => 'required',

                'registration_number'   => 'required',
                'registration_date'     => 'required',
                'modal'                 => 'required',
                'class'                 => 'required',
                'color'                 => 'required',

                'licence_front'         => 'required',
                'licence_back'          => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }else{
                $user           = new User;
                $user->name     = $request->name;
                $user->email    = $request->email;
                $user->phone    = $request->phone;
                $user->role     = 4;
                $user->password = bcrypt($request->password);
                $user->otp      = 1234;
                $user->save();

                $address                    = new Address;
                $address->title             = $request->address_title;
                $address->street_address    = $request->street;
                $address->user_id           = $user->id;
                $address->lat               = $request->lat;
                $address->long              = $request->long;
                $address->save();

                $vehicle                        = new Vehicle;
                $vehicle->registration_number   = $request->registration_number;
                $vehicle->registration_date     = Carbon::parse($request->registration_date);
                $vehicle->modal                 = $request->modal;
                $vehicle->class                 = $request->class;
                $vehicle->color                 = $request->color;
                $vehicle->user_id               = $user->id;
                $vehicle->save();

                $account                    = new Account;
                $account->account_number    = $request->ac;
                $account->sort_code         = $request->sort_code;
                $account->iban              = $request->iban;
                $account->user_id           = $user->id;
                $account->save();

                $licence                    = new Licence;
                $licence->user_id           = $user->id;
                $licence_front = time() .$user->id.'front'.'.'. $request->licence_front->getClientOriginalExtension();
                $request->file('licence_front')->move(public_path("licence"), $licence_front);
                $licence->licence_front = 'licence/'.$licence_front;

                $licence_back = time() .$user->id.'back'.'.'. $request->licence_back->getClientOriginalExtension();
                $request->file('licence_back')->move(public_path("licence"), $licence_back);
                $licence->licence_back = 'licence/'.$licence_back;
                $licence->save();

                $data = [
                    'otp' => $user->otp,
                    'id' => $user->id
                ];
                return response()->json([
                    'status'    => true,
                    'message'   => 'SignUp Successfully ',
                    'token'     => $user->createToken('my-app-token')->plainTextToken,
                    'data'      =>  $data
                ]  , 200);
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
                'email'     => 'required',
                'password'  => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }else{
                $str = '@';
                if (preg_match("/{$str}/i", $request->email)) {
                    if (auth()->attempt(['email' => $request->email, 'password' => $request->password])){
                        if (auth()->user()->status == 1 && auth()->user()->role == 4) {
                            $token = auth()->user()->createToken('my-app-token')->plainTextToken;
                            return response()->json([
                                'status'    => true,
                                'message'   => 'Loged In Successful',
                                'token'     => $token,
                                'data'      => auth()->user()->makeHidden(["profile_image", "email_verified_at", "role", "status", "otp", "created_at",  "updated_at", "apple_id", "google_id", "facebook_id"]),
                            ], 200);
                        }
                    }
                    else{

                        return response()->json([
                            'status'    => false,
                            'message'   =>'Invalid Credetials',
                            'data'      => null,
                        ], 200);
                    }
                }else{
                    if (auth()->attempt(['phone' => $request->email, 'password' => $request->password])){
                        if (auth()->user()->status == 1 && auth()->user()->role == 4) {
                            $token = auth()->user()->createToken('my-app-token')->plainTextToken;
                            return response()->json([
                                'status'    => true,
                                'message'   => 'Loged In Successful',
                                'token'     => $token,
                                'data'      => auth()->user()->makeHidden(["profile_image", "email_verified_at", "role", "status", "otp", "created_at",  "updated_at", "apple_id", "google_id", "facebook_id"]),
                            ], 200);
                        }
                    }
                    else{
                        return response()->json([
                            'status'    => false,
                            'message'   =>'Invalid Credetials',
                            'data'      => null,
                        ], 200);
                    }
                }

                return response()->json([
                    'status'    => false,
                    'message'   =>'User Account Blocked or Unverified',
                    'data'      => null,
                ], 200);
            }


        } catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function order_requests(Request $request)
    {
        try{
            $orders = Order::where('method', 'delivery')->where('driver_id', null)->get();
            return $orders;
            return response()->json([
                'status'    => true,
                'message'   => 'Saved Successfully',
                'data'      => null
            ], 200);


        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'data'  => null
            ], 400);
        }
    }

    public function accept_order(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'driver_id'     => 'required',
                'order_id'      => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $order          = Order::find($request->order_id);
            if ($order->driver_id !== null) 
            {
                return response()->json([
                    'status'    => false,
                    'message'   => '* Order Already Accepted by driver',
                    'data'      => 'null'
                ], 200);
            }
            $order->driver_id  = $request->driver_id;
            $order->save();
            $orders = Order::where('method', 'delivery')->where('driver_id', null)->get();
            return response()->json([
                'status'    => true,
                'message'   => 'Order Accepted Successfully',
                'data'      => $orders
            ], 200);


        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'data'  => null
            ], 400);
        }
    }

    public function driver_order_stats($driver_id)
    {
        try{
            $pending          = Order::where('driver_id', $driver_id)->where('status', 'pending')->count();
            $completed        = Order::where('driver_id', $driver_id)->where('status', 'completed')->count();
            $ongoing          = Order::where('driver_id', $driver_id)->where('status', 'ongoing')->count();
            $cancelled        = Order::where('driver_id', $driver_id)->where('status', 'cancelled')->count();

            $data = [
                "pending"   => $pending,
                "completed" => $completed,
                "ongoing"   => $ongoing,
                "cancelled" => $cancelled

            ];
            return response()->json([
                'status'    => true,
                'message'   => 'diver order stats',
                'data'      => $data
            ], 200);


        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'data'  => null
            ], 400);
        }
    }
}
