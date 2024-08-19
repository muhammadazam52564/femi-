<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Mail\OrderAdminEmail;
use App\Mail\OrderEmail;
use \Illuminate\Database\Eloquent\Collection;
use App\Models\Answer;
use App\Models\ShippingDetail;
use Illuminate\Http\Request;
use App\Models\UserAnswer;
use App\Models\DailyFacts;
use App\Models\Question;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Period;
use App\Models\Order;
use App\Models\Blog;
use App\Models\DayText;
use App\Models\Scadual;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use App\Mail\OTPEmail;
use Stripe;
class CustomerController extends Controller
{
    public function register(Request $request){
        try
        {
            $validator = \Validator::make($request->all(), [
                'name'          => 'required',
                'email'         => 'required|unique:user',
                // 'email'         => 'required',
                'password'      => 'required|min:6|max:30',
                'end_day'       => 'required',
                'days'          => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                    'data' => null
                ], 400);
            }else{
                $user           = new User;
                $user->name     = $request->name;
                $user->email    = $request->email;
                $user->role     = 3;
                $user->password = bcrypt($request->password);
                if ($request->has('token')){
                    $user->token = $request->token;
                }
                $user->otp = rand(1000, 9999);
               
                $user->save();

                // 1.first day of last Cycle(FDLC) +no of days of cycle=last day of last cycle (LDLC)
                // 2. LDLC+1=first dordersdayay of next Cycle (FDNC)

                // 3. FDNC+no of days of cycle=last day of next cycle(LDNC)

                // LDNC-14=mid day of ovlution operiod(Om)
                // ovlution period=om-2,om-2,om,om+1,om+2
                // BLUE
                // mentruation period cycle=FDNC,FDNC+1,FDNC+2,FDNC+3,FDNC+4,FDNC+5, FDNC+6
                // RED

                $FDNC  = date('Y-m-d', strtotime($request->end_day. ' + 0  days'));
                for ($i=0; $i < 12; $i++){ 
                    $first = $request->days;
                    $last = $request->days - 1;
                    $FDNC  = date('Y-m-d', strtotime($FDNC. ' + '.$first.' days'));
                    $LDNC  = $FDNC;
                    $LDNC  = date('Y-m-d', strtotime($FDNC. ' + '.$last.' days'));
                    $first       = $FDNC;
                    $second      = date('Y-m-d', strtotime($FDNC. ' + 1 days'));
                    $third       = date('Y-m-d', strtotime($FDNC. ' + 2 days'));
                    $fourth      = date('Y-m-d', strtotime($FDNC. ' + 3 days'));
                    $fifth       = date('Y-m-d', strtotime($FDNC. ' + 4 days'));
                    $sixth       = date('Y-m-d', strtotime($FDNC. ' + 5 days'));
                    $seventh     = date('Y-m-d', strtotime($FDNC. ' + 6 days'));
                    $mentruation = $first. "," . $second. "," . $third. "," . $fourth. "," . $fifth. "," . $sixth. "," .$seventh;
                    $mid = date('Y-m-d', strtotime($LDNC. ' - 14  days'));

                    $first_om      = date('Y-m-d', strtotime($mid. ' - 2 days'));
                    $second_om     = date('Y-m-d', strtotime($mid. ' - 1 days'));
                    $third_om      = $mid;
                    $fourth_om     = date('Y-m-d', strtotime($mid. ' + 1 days'));
                    $fifth_om      = date('Y-m-d', strtotime($mid. ' + 2 days'));

                    $ovlution = $first_om. "," . $second_om. "," . $third_om. "," . $fourth_om. "," . $fifth_om;
                    $period                 = new Period;
                    $period->user_id        =  $user->id;
                    $period->period_start   = $FDNC;
                    $period->period_end     = $LDNC;
                    $period->ovlution_start = $first_om;

                    $period->ovlution       = $ovlution;

                    $period->mentruation    = $mentruation;
                    $period->save();
                }
                $user->periods = Period::where('user_id', $user->id)->orderBy('id', 'ASC')->get();
                return response()->json([
                    'status'    => true,
                    'message'   => 'SignUp Successfully ',
                    'token'     => $user->createToken('my-app-token')->plainTextToken,
                    'data'      => $user
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

    public function login(Request $request){
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
                if (auth()->attempt(['email' => $request->email, 'password' => $request->password])){
                    $user = User::find(auth()->user()->id);
                    if ($request->has('token')){
                        $user->token = $request->token;
                        $user->save();
                    }
                    $user->periods = Period::where('user_id', $user->id)->get();
                    return response()->json([
                        'status'    => true,
                        'message'   => 'Loged In Successful',
                        'token'     => $user->createToken('my-app-token')->plainTextToken,
                        'data'      => $user,
                    ], 200);
                }
                else{
                    return response()->json([
                        'status'    => false,
                        'message'   =>'Invalid Credetials',
                        'data'      => null,
                        ], 200);
                }
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

    public function forgot_password(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'email'    => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $user = User::where('email', $request->email)->first();
            if(empty($user)){
                return response()->json([
                    'status'    => false,
                    'error'     => 'user does not exists !',
                    'data'      => null,
                ], 200);
            }
            $code       = rand(1000, 9999);
            $user->otp  = $code;
            $user->save();
            $data = [
                "opt"   => $code,
                "id"    => $user->id
            ];
            \Mail::to($request->email)->send(new OTPEmail($user->otp));
            return response()->json([
                'status'    => true,
                'message'   => 'verification code has been sent to your email !',
                'tokem'     => $user->createToken('my-app-token')->plainTextToken,
                'data'      => $data,
            ], 200);

        }catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 200);
        }
    }

    public function verify_otp(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'    => 'required',
                'otp'        => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $user = User::find($request->user_id);

            if($request->otp == $user->otp){

                $user->email_verified_at = Carbon::now();
                $user->otp               = null;
                $user->status            = 1;
                $user->save();
                return response()->json([
                    'status'    => true,
                    'message'   => 'Account Successfully Verified!',
                    'data'      => $user->makeHidden(["profile_image", "email_verified_at", "role", "status", "otp", "created_at",  "updated_at", "apple_id", "google_id", "facebook_id"]),
                ], 200);
            }else{
                return response()->json([
                    'status'    => false,
                    'message'   => 'Invalid OTP !',
                    'data'      => null,
                ], 200);
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status'     => false,
                'error'      => $e->getMessage(),
                'data'       => null,
            ], 200);
        }
    }

    public function set_password(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'   => 'required',
                'password'  => 'required|min:6',
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
            $user->password = bcrypt($request->password);
            if($user->save()){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Password Changed Successfully!',
                    'data'      => $user
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

    public function user_delete($id){
        try{
            $user = User::find($id);
            if(empty($user)){
                return response()->json([
                    'status' => false,
                    'message' => 'User does not exists!',
                    'data' => 0,
                ], 200);
            }
            $user->delete;
            return response()->json([
                    'status'  => true,
                    'message' => 'User Deleted Succesfully !',
                    'data'    => 0,
                ], 200);
        }catch(\Exception $e){
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                    'data' => 0,
                ], 200);
            }
        }
    }

    public function periods($id){
        try{
                $periods          = Period::where('user_id', $id)->get();
                return response()->json([
                    'status'    => true,
                    'message'   => 'Periods deails ',
                    'data'      => $periods
                ]  , 200);
        } catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function questions(){
        try
        {
            $question = Question::with('answers')->orderBy('id', 'ASC')->get();
            return response()->json([
                'status'    => true,
                'message'   => 'Questions',
                'data'      => $question,
            ], 200);
        } catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function answersSave(Request $request){
        try{
                $answer = [];
                foreach ($request->items as $item) {
                    array_push($answer, $item['answer_id']);
                }
                $user          = User::find($request['user_id']);
                $user->answers = implode(",", $answer);
                $user->save();
                return response()->json([
                    'status'    => true,
                    'message'   => 'Answers Successfully',
                    'data'      => null

                ]  , 200);
        } catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function facts(){
        try
        {
            $facts = DailyFacts::orderBy('id', 'DESC')->get();
            return response()->json([
                'status'    => true,
                'message'   => 'Daily Facts',
                'data'      => $facts,
            ], 200);
        } catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function blogs(){
        try
        {
            $blogs = Blog::orderBy('id', 'DESC')->get();
            return response()->json([
                'status'    => true,
                'message'   => 'Blogs',
                'data'      => $blogs,
            ], 200);
        } catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function blog($id){
        try
        {
            $blog = Blog::find($id);
            return response()->json([
                'status'    => true,
                'message'   => 'Blog Details',
                'data'      => $blog,
            ], 200);
        } catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function products(){
        try{
            $products = Product::with('images')->where('parent', null)->get();
            foreach ($products as $product) {
                if ($product->type == 'vp'){
                    $product->price = "from ". Product::where('parent', $product->id)
                                            ->where("type", "sub_product")
                                            ->orderBy('price', 'ASC')
                                            ->select('price')
                                            ->first()->price;
                    unset($product->type);
                }
            }
            return response()->json([
                'status'    => true,
                'message'   => 'Products list',
                'data'      =>  $products,
            ], 200);

        } catch(\Exception $e){

            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      =>  null,
            ], 400);
        }
    }

    public function recomended_products($id){
        try {
            $final_products = new Collection;
            $answers = explode(",", User::find($id)->answers);

            foreach ($answers as $answer) {
                $products = Product::with('images')
                    ->where('parent', null)
                    ->whereRaw("find_in_set('".$answer."', products.answers)")
                    ->get();
                $final_products = $final_products->merge($products);
            }

            if (count($final_products) == 0) {
                // Update this line to assign the retrieved products to $final_products
                $final_products = Product::with('images')
                    ->where('parent', null)
                    ->whereIn('name', ['Pretty Panty', 'Femi Wipes', 'Femi Liners', 'Best selling bundle'])
                    ->get();
            }

            foreach ($final_products as $product) {
                if ($product->type == 'vp') {
                    $product->calculated_price = "from " . Product::where('parent', $product->id)
                        ->where("type", "sub_product")
                        ->orderBy('price', 'ASC')
                        ->select('price')
                        ->first()->price;
                }
                // Uncomment the following line if you want to include product images
                // $product->images = ProductImage::where('product_id', $product->id)->select('image')->get();
            }

            return response()->json([
                'status' => true,
                'message' => 'Products list',
                'data' =>  $final_products,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'data' =>  null,
            ], 400);
        }
    }
    public function product($id){
        try{
            $product = Product::with('images')->where('id', $id)->first();
            if ($product->type == 'sp')
            {
                $product->sub_products = [];
            }
            if ($product->type == 'vp')
            {
                $product->sub_products = Product::where('parent', $product->id)
                                        ->where("type", "sub_product")
                                        ->orderBy('price', 'ASC')
                                        ->select('id', 'name', 'quantity', 'price')
                                        ->get();
            }
            if ($product->type == 'gp')
            {
                $product->sub_products = Product::where('parent', $product->id)
                                        ->where("type", "sub_product")
                                        ->select('id', 'name', 'quantity', 'price')
                                        ->get();
                $product->addons = Product::where('parent', $product->id)
                                        ->where("type", "addon")
                                        ->select('id', 'name', 'price')
                                        ->get();
            }
            // unset($product->type);
            return response()->json([
                'status'    => true,
                'message'   => 'Product details',
                'data'      => $product,
            ], 200);

        } catch(\Exception $e){

            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      =>  null,
            ], 400);
        }
    }

    public function stripe_payment(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'       => 'required',
                'card_number'   => 'required',
                'month'         => 'required|min:1|max:2',
                'year'          => 'required|min:4|max:4',
                'cvc'           => 'required|min:3|max:3',
                'amount'        => 'required',
                'currency'      => 'required',
                'product_id'    => 'required',
                'first_name'    => 'required',
                'last_name'     => 'required',
                'address'       => 'required',
                'city'          => 'required',
                'state'         => 'required',
                'apt'           => 'required',
                'country'       => 'required',
                'postal_code'   => 'required',
            ]);
            if ($validator->fails()){
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                    'data' => null
                ], 400);
            }else{
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET')
                );
                $token              = $stripe->tokens->create([
                    'card'          => [
                        'number'        => $request->card_number,
                        'exp_month'     => $request->month,
                        'exp_year'      => $request->year,
                        'cvc'           => $request->cvc,
                    ],
                ]);
                $charge = Stripe\Charge::create ([
                    "amount"        => $request->amount *100,
                    "currency"      => $request->currency,
                    "source"        => $token->id,
                    "description"   => 'payment with credit/debit card'
                ]);
                if ($charge->status == "succeeded") {
                    $payment                     = new Payment;
                    $payment->amount             = $request->amount;
                    $payment->transaction_id     = $charge->id;
                    $payment->description        = 'payment with credit/debit card'; 
                    $payment->user_id            = $request->user_id;
                    
                    if ($payment->save()) {
                        $product                        = Product::find($request->product_id);
                        $order                          = new Order;
                        $shippingDetails = new ShippingDetail;
                        $order->user_id                 = $request->user_id;
                        $order->product_id              = $request->product_id;
                        $order->transection_id          = $charge->id;
                        $order->amount                  = $product->price;
                        $shippingDetails->user_id       = $request->user_id;
                        $shippingDetails->first_name    = $request->first_name;
                        $shippingDetails->last_name     = $request->last_name;
                        $shippingDetails->address       = $request->address;
                        $shippingDetails->city          = $request->city;
                        $shippingDetails->state         = $request->state;
                        $shippingDetails->postal_code   = $request->postal_code;
                        $shippingDetails->country       = $request->country;
                        $shippingDetails->apt           = $request->apt;
                        $shippingDetails->card_number   = $request->card_number;
                        $shippingDetails->expiry        = $request->month . '/' . $request->year;
                        $shippingDetails->cvc           = $request->cvc;

                        $order->save();
                        $shippingDetails->order_id      = $order->id;
                        $shippingDetails->save();

                        \Mail::to($order->user->email)->send(new OrderEmail($order->user->name, $order->id, $order->created_at, $order->product->product_name, $order->amount ));
                        \Mail::to("Info@femisecrets.com")->send(new OrderAdminEmail($order->user->name, $order->id, $order->created_at, $order->product->product_name, $order->amount ));
                    }
                    $res = [
                        'order_id'               => $order->id, 
                        'transaction_id'         => $charge->id,
                        'status'                 => $charge->status,
                        'amount'                 => $charge->amount / 100 .' '. $charge->currency,
                        'Name'                   => $shippingDetails->first_name
                    ];
                    return response()->json([
                        'status'    => true,
                        'message'   => "Order successfully Placed",
                        'data'      => $res
                    ], 200);
                }else{
                    return response()->json([
                        'status'    => false,
                        'message'   => "payment transection failed",
                        'data'      => null
                    ], 400);
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

    public function daily_message(){
        try{
            $messages = DayText::orderBy('id', 'DESC')->get();
            return response()->json([
                'status'    => true,
                'message'   => 'Messages',
                'data'      => $messages,
            ], 200);

        } catch(\Exception $e){
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function daily_health(){
        try{
            $Scaduals = Scadual::orderBy('id', 'DESC')->get();
            return response()->json([
                'status'    => true,
                'message'   => 'Scaduals',
                'data'      => $Scaduals,
            ], 200);

        } catch(\Exception $e){
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function add_perioid(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'       => 'required',
                'end_day'       => 'required',
                'days'          => 'required',
            ]);
            if ($validator->fails()){
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                    'data' => null
                ], 400);
            }else{
                if (Period::where('user_id', $request->user_id)->count() > 0) {
                    Period::where('user_id', $request->user_id)->delete();
                }

                $FDNC  = date('Y-m-d', strtotime($request->end_day. ' + 0  days'));
                for ($i=0; $i < 12; $i++) 
                { 
                    $first = $request->days;
                    $last = $request->days - 1;
                    $FDNC  = date('Y-m-d', strtotime($FDNC. ' + '.$first.' days'));
                    $LDNC  = $FDNC;
                    $LDNC  = date('Y-m-d', strtotime($FDNC. ' + '.$last.' days'));
                    $first       = $FDNC;
                    $second      = date('Y-m-d', strtotime($FDNC. ' + 1 days'));
                    $third       = date('Y-m-d', strtotime($FDNC. ' + 2 days'));
                    $fourth      = date('Y-m-d', strtotime($FDNC. ' + 3 days'));
                    $fifth       = date('Y-m-d', strtotime($FDNC. ' + 4 days'));
                    $sixth       = date('Y-m-d', strtotime($FDNC. ' + 5 days'));
                    $seventh     = date('Y-m-d', strtotime($FDNC. ' + 6 days'));
                    $mentruation = $first. "," . $second. "," . $third. "," . $fourth. "," . $fifth. "," . $sixth. "," .$seventh;
                    $mid = date('Y-m-d', strtotime($LDNC. ' - 14  days'));

                    $first_om      = date('Y-m-d', strtotime($mid. ' - 2 days'));
                    $second_om     = date('Y-m-d', strtotime($mid. ' - 1 days'));
                    $third_om      = $mid;
                    $fourth_om     = date('Y-m-d', strtotime($mid. ' + 1 days'));
                    $fifth_om      = date('Y-m-d', strtotime($mid. ' + 2 days'));

                    $ovlution = $first_om. "," . $second_om. "," . $third_om. "," . $fourth_om. "," . $fifth_om;
                    $period                 = new Period;
                    $period->user_id        = $request->user_id;
                    $period->period_start   = $FDNC;
                    $period->period_end     = $LDNC;
                    $period->ovlution       = $ovlution;
                    $period->mentruation    = $mentruation;
                    $period->save();
                }
                $periods = Period::where('user_id', $request->user_id)->get();
                return response()->json([
                    'status'    => true,
                    'error'     => "Periods List",
                    'data'      => $periods
                ], 200);

            }
        }catch(\Exception $e){

            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }

    }

    public function add_appointment(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'name'       => 'required',
                'phone'      => 'required',
                'email'      => 'required',
                'note'       => 'required',
            ]);
            if ($validator->fails()){
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                    'data' => null
                ], 400);
            }else{
                $id = auth('sanctum')->user()->id;
                $appointment = new Appointment;
                $appointment->user_id = $id;
                $appointment->name = $request->name;
                $appointment->phone = $request->phone;
                $appointment->email = $request->email;
                $appointment->note = $request->note;
                $appointment->save();
                return response()->json([
                    'status'    => true,
                    'error'     => "Appointment successfully added",
                    'data'      => $appointment
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }

    }
    
    public function appointments(Request $request){
        try{
            $id = auth('sanctum')->user()->id;
            $appointment = Appointment::where('user_id', $id)->get();
            return response()->json([
                'status'    => true,
                'message'   => "Appointment List",
                'data'      => $appointment
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }

    }
    
}

