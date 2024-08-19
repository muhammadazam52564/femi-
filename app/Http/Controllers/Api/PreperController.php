<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryType;
use App\Models\DietaryType;
use App\Models\OrderTiming;
use App\Models\Account;
use App\Models\Address;
use App\Models\Cuisine;
use App\Models\Order;
use App\Models\User;
use App\Models\Type;
use App\Models\Menu;
use Carbon\Carbon;
use Hash;

class PreperController extends Controller
{
    public function image_upload(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'image'       => 'required',
                'location'    => 'required'
            ]);
            if ($validator->fails()) 
            {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            if ($request->has('image')){

                $newfilename = time() .'.'. $request->image->getClientOriginalExtension();
                $request->file('image')->move(public_path($request->location), $newfilename);
            }
            $address = $request->location.'/'.$newfilename;
            return response()->json([
                'status'     => true,
                'message'    => 'Successfully Uploaded',
                'image'      => $address
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'status'    => false,
                    'error'     => $e->getMessage(),
                    'data'      => null
                ], 400);
            }
    }
    public function register(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name'          => 'required',
                'email'         => 'required|unique:user',
                'phone'         => 'required|unique:user',
                'password'      => 'required|min:6|max:30',

                'address_title' => 'required',
                'lat'           => 'required',
                'long'          => 'required',
                'street'        => 'required',

                'ac'            => 'required',
                'sort_code'     => 'required',
                'iban'          => 'required',
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
                $user->role     = 2;
                $user->password = bcrypt($request->password);
                $user->otp      = 1234;

                if ($request->has('description')) 
                {
                    $user->description    = $request->description;
                }
                if ($request->has('image')) 
                {
                    $user->profile_image = $request->image;
                }
                 
                $user->save();

                $address                    = new Address;
                $address->title             = $request->address_title;
                $address->street_address    = $request->street;
                $address->user_id           = $user->id;
                $address->lat               = $request->lat;
                $address->long              = $request->long;
                $address->save();

                $account                    = new Account;
                $account->account_number    = $request->ac;
                $account->sort_code         = $request->sort_code;
                $account->iban              = $request->iban;
                $account->user_id           = $user->id;
                $account->save();
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
                        if (auth()->user()->status == 1 && auth()->user()->role == 2) {
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
                        if (auth()->user()->status == 1 && auth()->user()->role == 2) {
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

    

    
    public function change_password(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'       => 'required',
                'old_password'  => 'required|min:6',
                'new_password'  => 'required|min:6',
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
                    'status'    => false,
                    'error'     => 'user does not exists!',
                    'data'      => null,
                ], 200);
            }
            if (!Hash::check($request->old_password, $user->password)) 
            {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Current password Incorrect',
                    'data'      => null
                ], 200);
            }
            $user->password = bcrypt($request->new_password);
            if($user->save()){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Password Changed Successfully!',
                    'data'      => null
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson)
            {
                return response()->json([
                    'status' => false,
                    'error' => $e->getMessage(),
                    'data'  => null
                ], 400);
            }
        }
    }

    public function edit_profile(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'   => 'required',
                'name'      => 'required',
                'email'     => 'required',
                'phone'     => 'required',
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
                    'status'    => false,
                    'error'     => 'user does not exists!',
                    'data'      => null,
                ], 200);
            }
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            
            if ($request->has('profile_image')) 
            {
                $user->profile_image = $request->profile_image;

            }

            if($user->save()){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Information Changed Successfully!',
                    'data'      => null
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson)
            {
                return response()->json([
                    'status' => false,
                    'error' => $e->getMessage(),
                    'data'  => null
                ], 400);
            }
        }
    }

    public function edit_account(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'           => 'required',
                'account_number'    => 'required',
                'sort_code'         => 'required',
                'iban'              => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $account = Account::where('user_id', $request->user_id)->first();
            if(empty($account))
            {
                return response()->json([
                    'status'    => false,
                    'error'     => '* Account details not found',
                    'data'      => null,
                ], 200);
            }
            $account->account_number    = $request->account_number;
            $account->sort_code         = $request->sort_code;
            $account->iban              = $request->iban;

            if($account->save()){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Information Changed Successfully!',
                    'data'      => null
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson)
            {
                return response()->json([
                    'status' => false,
                    'error' => $e->getMessage(),
                    'data'  => null
                ], 400);
            }
        }
    }

    public function dropdowns(Request $request)
    {
        try{
            $delivery    = DeliveryType::all();
            $dietary     = DietaryType::all();
            $cuisine     = Cuisine::all();
            $type        = Type::all();

            $data = [
                'delivery'  => $delivery,
                'dietary'   => $dietary,
                'cuisine'   => $cuisine,
                'type'      => $type,
            ];
            return response()->json([
                'status'    => true,
                'message'   => 'Dropdowns',
                'data'      => $data,
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

    public function add_menu(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'preper_id'         => 'required',
                'title'             => 'required',
                'description'       => 'required',
                'quantity'          => 'required',
                'price'             => 'required',

                'delivery_type_id'  => 'required',
                'cuisine_id'        => 'required',
                'dietary_type_id'   => 'required',
                'type_id'           => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $menu = new Menu;

            $menu->preper_id            = $request->preper_id;
            $menu->title                = $request->title;
            $menu->description          = $request->description;
            $menu->quantity             = $request->quantity;
            $menu->price                = $request->price;

            $menu->delivery_type_id     = $request->delivery_type_id;
            $menu->cuisine_id           = $request->cuisine_id;
            $menu->dietary_type_id      = $request->dietary_type_id;
            $menu->type_id              = $request->type_id;


            if ($request->has('instructions')) 
            {
                $menu->instructions = $request->instructions;
            }
            if ($request->has('image')) 
            {
                $menu->image = $request->image;
            }
            if($menu->save()){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Menu created successfully!',
                    'data'      => null
                ], 200);
            }
            else{
                return "failed";
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'data'  => null
            ], 400);
        }
    }

    public function edit_menu(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'menu_id'           => 'required',
                'title'             => 'required',
                'description'       => 'required',
                'quantity'          => 'required',
                'price'             => 'required',

                'delivery_type_id'  => 'required',
                'cuisine_id'        => 'required',
                'dietary_type_id'   => 'required',
                'type_id'           => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $menu = Menu::find($request->menu_id);
            $menu->title                = $request->title;
            $menu->description          = $request->description;
            $menu->quantity             = $request->quantity;
            $menu->price                = $request->price;

            $menu->delivery_type_id     = $request->delivery_type_id;
            $menu->cuisine_id           = $request->cuisine_id;
            $menu->dietary_type_id      = $request->dietary_type_id;
            $menu->type_id              = $request->type_id;


            if ($request->has('instructions')) {
                $menu->instructions     = $request->instructions;
            }
            if ($request->has('image')) 
            {
                $menu->image = $request->image;
            }
            if($menu->save()){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Menu Updated successfully!',
                    'data'      => null
                ], 200);
            }
            else{
                return "failed";
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'data'  => null
            ], 400);
        }
    }
    public function menu_list($preper)
    {
        try{
            $delivery    = Menu::where('preper_id', $preper)->get();
            if (Menu::where('preper_id', $preper)->count() < 1) 
            {
                $delivery = null;
            }
            return response()->json([
                'status'    => true,
                'message'   => 'Menu List',
                'data'      => $delivery,
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
    public function menu($id)
    {
        try{
            $delivery    = Menu::with('cuisine', 'type', 'dietry', 'delivery')->where('id', $id)->first();
            unset($delivery->type_id);
            unset($delivery->cuisine_id);
            unset($delivery->dietary_type_id);
            unset($delivery->delivery_type_id);
            return response()->json([
                'status'    => true,
                'message'   => 'Single menu',
                'data'      => $delivery->makeHidden([ "created_at", "updated_at"]),
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
    public function delete_menu($id)
    {
        try{
            $delivery    = Menu::find($id)->delete();
            return response()->json([
                'status'    => true,
                'message'   => 'Deleted Successfully',
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

    public function set_order_pickup(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'preper_id'         => 'required',
                'order_days'        => 'required',
                'till_date'         => 'required',
                'delivery_day'      => 'required',
                'start_time'        => 'required',
                'end_time'          => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $timings                = new OrderTiming;
            $timings->preper_id     = $request->preper_id;
            $timings->order_days    = $request->order_days;
            $timings->till_date     = Carbon::createFromFormat('d-m-Y', $request->till_date);
            $timings->delivery_day  = $request->delivery_day;
            $timings->start_time    = $request->start_time;
            $timings->end_time      = $request->end_time;
            $timings->save();
            return response()->json([
                'status'    => true,
                'message'   => 'Saved Successfully',
                'data'      => $timings
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
    public function edit_order_pickup(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'id'                => 'required',
                'order_days'        => 'required',
                'till_date'         => 'required',
                'delivery_day'      => 'required',
                'start_time'        => 'required',
                'end_time'          => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $timings                = OrderTiming::where('preper_id',$request->id)->orderBy('id', 'DESC')->first();
            $timings->order_days    = $request->order_days;
            $timings->till_date     = Carbon::createFromFormat('d-m-Y', $request->till_date);
            $timings->delivery_day  = $request->delivery_day;
            $timings->start_time    = $request->start_time;
            $timings->end_time      = $request->end_time;
            $timings->save();
            return response()->json([
                'status'    => true,
                'message'   => 'Saved Successfully',
                'data'      => $timings
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

    public function order_pickup($preper_id)
    {
        try{
            $timings = OrderTiming::where('preper_id',$preper_id)->orderBy('id', 'DESC')->first();
            return response()->json([
                'status'    => true,
                'message'   => 'Timings list',
                'data'      => $timings
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

    public function order_requests($preper_id)
    {
        try{
            $orders = Order::where('preper_id', $preper_id)->where('status', 'pending')->get();
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

    public function accept_order($order_id)
    {
        try{
            $order          = Order::find($order_id);
            if ($order->status !== "pending") 
            {
                return response()->json([
                    'status'    => false,
                    'message'   => '* Order Already Accepted',
                    'data'      => 'null'
                ], 200);
            }
            $order->status  = 'accepted';
            $order->save();
            $orders = Order::where('preper_id', $order->preper_id)->where('status', 'pending')->get();
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
}
