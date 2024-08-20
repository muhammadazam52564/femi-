<?php

    namespace App\Http\Controllers;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use App\Models\ProductImage;
    use App\Models\DailyFacts;
    use App\Models\Question;
    use App\Models\Product;
    use App\Models\Blog;
    use App\Models\DayText;
    use App\Models\Order;
    use App\Models\Scadual;
    use App\Models\Period;
    use App\Models\PushNotification;
    use App\Models\Appointment;
    use App\Models\User;
    use Carbon\Carbon;
    use Redirect;
    use URL;
    use Http;


    class MainController extends Controller
    {
        public function customers(){
            $users = User::where('role', 3)->get();
            $compacts = compact('users');
            return view('admin.users', $compacts);
        }

        public function delete_user($id){
            $user = User::find($id);
            $user->delete();
            return Redirect::back()->with('msg','user deleted Successfully');
        }

        public function user_status($id, $status){
            // return $status;
            $rider = User::find($id);
            $rider->status = $status;
            if($rider->save())
            {
                if ($status == 1) {
                    return Redirect::back()->with('msg', 'Unblocked Successfully');
                }else{
                    return Redirect::back()->with('msg', 'Blocked Successfully');
                }

            }
        }

        public function blogs(){
            $blogs = Blog::orderBy('id', 'DESC')->get();
            $compacts = compact('blogs');
            return view('admin.blogs', $compacts);
        }

        public function SaveBlog(Request $request){
            try {
                $validated = $request->validate([
                    'title'     => 'required',
                    'content'   => 'required',

                ]);
                $blog = new Blog;
                $blog->title   =  $request->title; 
                $blog->content =  $request->content; 

                if ($request->has('image')) 
                {
                    $image = time() . $request->image->getClientOriginalExtension();
                    $request->file('image')->move(public_path("blogs"), $image);
                    $blog->image = 'blogs/'.$image;
                }
                $blog->save();
                return redirect('admin/blogs')->with('msg', 'blog successfully save');
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function EditBlog($id){
            $blog = Blog::find($id);
            $compacts = compact('blog');
            return view('admin.editBlog', $compacts);
        }

        public function UpdateBlog(Request $request, $id){
            try {
                $validated = $request->validate([
                    'title'     => 'required',
                    'content'   => 'required',

                ]);
                $blog = Blog::find($id);
                $blog->title   =  $request->title; 
                $blog->content =  $request->content; 

                if ($request->has('image')) 
                {
                    $image = time() . $request->image->getClientOriginalExtension();
                    $request->file('image')->move(public_path("blogs"), $image);
                    $blog->image = 'blogs/'.$image;
                }
                $blog->save();
                return redirect('admin/blogs')->with('msg', 'blog successfully updated');
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function deleteBlog($id){
            $fact = Blog::find($id)->delete();
            return redirect('admin/blogs')->with('msg', 'Blog successfully deleted');
        }

        public function facts(){
            $facts = DailyFacts::orderBy('id', 'DESC')->get();
            $compacts = compact('facts');
            return view('admin.facts', $compacts);
        }

        public function SaveFact(Request $request){
            try {
                $validated = $request->validate([
                    'title' => 'required',
                ]);
                $fact = new DailyFacts;
                $fact->title =  $request->title; 
                if ($request->has('image')) 
                {
                    $image = time() . $request->image->getClientOriginalExtension();
                    $request->file('image')->move(public_path("facts"), $image);
                    $fact->image = 'facts/'.$image;
                }
                $fact->save();
                return redirect('admin/facts')->with('msg', 'fact successfully save');
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function EditFact($id){
            $fact = DailyFacts::find($id);
            $compacts = compact('fact');
            return view('admin.editFact', $compacts);
        }

        public function UpdateFact(Request $request, $id){
            try {
                $validated = $request->validate([
                    'title' => 'required',
                ]);
                $fact           = DailyFacts::find($id);
                $fact->title    =  $request->title; 
                if ($request->has('image')) 
                {
                    $image = time() . $request->image->getClientOriginalExtension();
                    $request->file('image')->move(public_path("facts"), $image);
                    $fact->image    = 'facts/'.$image;
                }
                $fact->save();
                return redirect('admin/facts')->with('msg', 'fact successfully updated');
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function deleteFact($id){
            $fact = DailyFacts::find($id)->delete();
            return redirect('admin/facts')->with('msg', 'fact successfully deleted');
        }

        public function products(){
            $products = Product::with('images')->where('parent', null )->get();
            foreach ($products as $product) {
                if ($product->type == 'vp' && Product::where('parent', $product->id)->count() > 0)
                {
                    $product->price = "from ". Product::where('parent', $product->id)
                                            ->where("type", "sub_product")
                                            ->orderBy('price', 'ASC')
                                            ->first()->price;
                }
            }
            $compacts = compact('products');
            return view('admin.products', $compacts);
        }

        public function new_product(){
            $questions = Question::with('answers')->get();
            // return $questions;
            $compacts  = compact('questions');
            return view('admin.addProduct', $compacts);
        }

        public function add_product(Request $request){
            try{
                // return implode(",", json_decode($request->answers));
                $validator = Validator::make($request->all(), [
                    'product_description'   => 'required|min:1',
                    'type'                  => 'required|min:1',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status'    => false,
                        'error'     => $validator->errors()->first(),
                        'data'      => null,
                    ], 400);
                }
                else
                {   
                    $newanswers = "";
                    if ($request->has('answers')) {     
                        $answers    = json_decode($request->answers);
                        $i= 0;
                        foreach($answers as $answer) 
                        {
                            if ($i == 0) {
                                $newanswers = $newanswers .implode(',', $answer);
                            }else{
                                $newanswers = $newanswers .",".implode(',', $answer);
                            }
                            $i++;
                        }
                    }
                    if($request->type === 'sp'){
                        $product                = new Product;
                        $product->name          = $request->product_name;
                        $product->quantity      = $request->product_qty;
                        $product->price         = $request->product_price;
                        $product->description   = $request->product_description;
                        $product->type          = $request->type;
                        $product->answers       = $newanswers;
                        if ($product->save()) {
                            $images = $request->file;
                            foreach ($images as $image)
                            {
                                $newfilename = time() .'.'. $image->getClientOriginalExtension();
                                $image->move(public_path("product_images"), $newfilename);
                                $img_path = 'product_images/'.$newfilename;
                                $image = new ProductImage;
                                $image->image = $img_path;
                                $image->product_id = $product->id;
                                $image->save();
                            }

                            return response()->json([
                                'status'    => 200,
                                'message'   => 'Product Successfully Added',
                                'data'      => null
                            ]  , 200);
                        }
                    }

                    if($request->type === 'vp')
                    {
                        $varients               = json_decode($request->varients);
                        $product                = new Product;
                        $product->name          = $request->product_name;
                        $product->description   = $request->product_description;
                        $product->answers       = $newanswers;
                        // $product->category_id   = $request->product_category;
                        $product->type          = 'vp';
                        if ($product->save())
                        {
                            $images = $request->file;
                            foreach ($images as $image)
                            {
                                // $img_path =  $image->move('product_images/', time().'.'.$image->getClientOriginalExtension());
                                // return $img_path;

                                $newfilename = time() .'.'. $image->getClientOriginalExtension();
                                $image->move(public_path("product_images"), $newfilename);
                                $img_path = 'product_images/'.$newfilename;

                                $image = new ProductImage;
                                $image->image = $img_path;
                                $image->product_id = $product->id;
                                $status = $image->save();
                            }
                            foreach ($varients as  $varient)
                            {
                                $sub_product = new Product;
                                $sub_product->name          = $varient->varient;
                                $sub_product->price         = $varient->price;
                                // $sub_product->category_id   = $request->product_category;
                                $sub_product->quantity      = $varient->qty;
                                $sub_product->parent        = $product->id;
                                $sub_product->type          = "sub_product";
                                $sub_product->save();
                            }
                            return response()->json([
                                'status'    => 200,
                                'message'   => 'Product Successfully Added ',
                                'data'      => null
                            ]  , 200);
                        }
                    }
                    if($request->type === 'gp')
                    {
                        $products               = json_decode($request->products);
                        $product                = new Product;
                        $product->name          = $request->product_name;
                        $product->price         = $request->price;
                        $product->description   = $request->product_description;
                        $product->category_id   = $request->product_category;
                        $product->type          = 'gp';
                        if ($product->save())
                        {
                            $images = $request->file;
                            foreach ($images as $image)
                            {
                                // $img_path =  $image->move('product_images/', time().'.'.$image->getClientOriginalExtension());
                                // return $img_path;

                                $newfilename = time() .'.'. $image->getClientOriginalExtension();
                                $image->move(public_path("product_images"), $newfilename);
                                $img_path    = 'product_images/'.$newfilename;

                                $image = new ProductImage;
                                $image->image = $img_path;
                                $image->product_id = $product->id;
                                $status = $image->save();
                            }
                            foreach ($products as  $subproduct)
                            {
                                $sub_product                = new Product;
                                $sub_product->name          = $subproduct->product;
                                $sub_product->quantity      = $subproduct->qty;
                                $sub_product->category_id   = $request->product_category;
                                $sub_product->parent        = $product->id;
                                $sub_product->type          = "sub_product";
                                $sub_product->save();
                            }
                            if($request->has('addons') && count(json_decode($request->addons)) > 0) {
                                $addons = json_decode($request->addons);
                                foreach ($addons as  $addon)
                                {
                                    $new_addon             = new Product;
                                    $new_addon->name       = $addon->addon;
                                    $new_addon->price      = $addon->price;
                                    $new_addon->type       = 'addon';
                                    $new_addon->parent     = $product->id;
                                    $new_addon->save();
                                }
                            }

                            return response()->json([
                                'status'    => 200,
                                'message'   => 'Product Successfully Added ',
                                'data'      => null
                            ]  , 200);
                        }
                    }
                }
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'error' => $e->getMessage(),
                    'data' => 0,
                ], 400);
            }
        }
        
        public function deleteProduct($id){
            $product = Product::find($id);
            $product->delete();
            return back()->with('msg', 'deleted successfully');
            // $products = Product::where('parent')
        }

        public function textDay(){
            $texts = DayText::orderBy('id', 'DESC')->get();
            // return $texts;
            $compacts = compact('texts');
            return view('admin.texts', $compacts);
        }

        public function SaveTextDay(Request $request){
            try {
                $validated = $request->validate([
                    'day'       => 'required|unique:day_texts',
                    'content'   => 'required',

                ]);
                $text = new DayText;
                $text->day    =  $request->day; 
                $text->text   =  $request->content; 
                $text->save();
                return redirect('admin/text-days')->with('msg', 'Successfully Added');
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function EditTextDay($id){
            $text = DayText::find($id);
            $compacts = compact('text');
            return view('admin.editText', $compacts);
        }

        public function UpdateTextDay(Request $request, $id){
            try {
                $validated = $request->validate([
                    'day'       => 'required',
                    'content'   => 'required',

                ]);
                $text         =  DayText::find($id);
                $text->day    =  $request->day; 
                $text->text   =  $request->content; 
                $text->save();
                return redirect('admin/text-days')->with('msg', 'Successfully Updated');
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function deleteTextDay($id){
            $fact = DayText::find($id)->delete();
            return redirect('admin/text-days')->with('msg', 'Message successfully deleted');
        }

        public function orders(){
            $orders = Order::orderBy('id', 'DESC')->get();
            $compacts = compact('orders');
            return view('admin.orders', $compacts);
        }
        public function showOrder($orderId){
            $order = Order::find($orderId);

            if (!$order) {
                abort(404, 'Order not found');
            }
    
            return view('admin.showOrders', compact('order'));
        }
        
        public function scads(){
            $scaduals = Scadual::orderBy('id', 'DESC')->get();
            $compacts = compact('scaduals');
            return view('admin.scads', $compacts);
        }

        public function SaveScad(Request $request){
            try {
                $validated = $request->validate([
                    'title' => 'required',
                ]);
                $scad = new Scadual;
                $scad->title =  $request->title; 
                if ($request->has('image')) 
                {
                    $image = time() . $request->image->getClientOriginalExtension();
                    $request->file('image')->move(public_path("scads"), $image);
                    $scad->image = 'scads/'.$image;
                }
                $scad->save();
                return redirect('admin/scads')->with('msg', 'Scadual successfully Added');
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function EditScad($id){
            $scadual = Scadual::find($id);
            $compacts = compact('scadual');
            return view('admin.editScad', $compacts);
        }

        public function UpdateScad(Request $request, $id){
            try {
                $validated = $request->validate([
                    'title' => 'required',
                ]);
                $scad           = Scadual::find($id);
                $scad->title    =  $request->title; 
                if ($request->has('image')) 
                {
                    $image = time() . $request->image->getClientOriginalExtension();
                    $request->file('image')->move(public_path("scads"), $image);
                    $scad->image    = 'scads/'.$image;
                }
                $scad->save();
                return redirect('admin/scads')->with('msg', 'Scadual successfully updated');
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function deleteScad($id){
            $scad = Scadual::find($id)->delete();
            return redirect()->back()->with('msg', 'Scadual successfully deleted');
        }

        public function appointments(){
            $appointments = Appointment::orderBy('id', 'DESC')->get();
            $compacts = compact('appointments');
            return view('admin.appointments', $compacts);
        }
    
        public function deleteAppointment($id){
            $appointment = Appointment::find($id)->delete();
            return redirect()->back()->with('msg', 'Appointment successfully deleted');
        }
        

        public function UpdateAppointment(Request $request, $id){
            try {
                // $validated = $request->validate([
                //     'appointment_id' => 'required',
                // ]);
                $appointment            = Appointment::find($id);
                $appointment->status    =  $request->status; 
                if ($request->has('image')) 
                {
                    $image = time() . $request->image->getClientOriginalExtension();
                    $request->file('image')->move(public_path("scads"), $image);
                    $scad->image    = 'scads/'.$image;
                }
                $scad->save();
                return redirect('admin/scads')->with('msg', 'Scadual successfully updated');
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function cron_job_notifications(Request $request){
            try{
                $server_key = "key=AAAAnhAwszk:APA91bEsYv4uiL9cZe_-x_ACoVlnEzx4ohIxKD6S05wq4l2ufdzNQqDOhB2iebljhMyVbhwT5Z-cY_dsVZsV0DH5Ks6QAC783c2V92XIm34weNfnWgwiQWD2j4iJ0osqSOs-80vQ5JOX";
                $user_ids = User::pluck('id')->toArray();

                $seven_days_date = Carbon::now()->addDays(7)->format('Y-m-d');
                print("seven_days_date");
                print($seven_days_date)
                $user_ids_for_seven_days = User::pluck('id')->get();
                
                // $user_ids_for_seven_days = array_unique(Period::whereDate('period_start', $seven_days_date)->pluck("user_id")->toArray());
                print("user_ids_for_seven_days");
                print_r($user_ids_for_seven_days);

                foreach($user_ids_for_seven_days as $id){
                    $notification                   = new PushNotification;
                    $notification->title            = "7 days until your period";
                    $notification->description      = "You period will be here in 7 days Order your Femi Secrets products now.";
                    $notification->type             = "7days";
                    $notification->user_id          = $id;
                    $notification->save();

                    $server_key = "key=AAAAnhAwszk:APA91bEsYv4uiL9cZe_-x_ACoVlnEzx4ohIxKD6S05wq4l2ufdzNQqDOhB2iebljhMyVbhwT5Z-cY_dsVZsV0DH5Ks6QAC783c2V92XIm34weNfnWgwiQWD2j4iJ0osqSOs-80vQ5JOX";
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => $server_key
                    ])->post('https://fcm.googleapis.com/fcm/send', [
                        'to'            => User::find($id)->token,
                        'priority'      => 'high',
                        'notification'  => [
                        'title'         => "7 days until your period ",
                        'body'          => "You period will be here in 7 days Order your Femi Secrets products now."
                        ]
                    ]);
                }

                $exact_day_date = Carbon::now()->format('Y-m-d');
                $user_ids_for_exact_day = array_unique(Period::whereDate('period_start', $exact_day_date)->pluck("user_id")->toArray());
                print("user_ids_for_exact_day");
                print_r($user_ids_for_exact_day);

                foreach($user_ids_for_exact_day as $id){
                    $notification                   = new PushNotification;
                    $notification->title            = "Your period starts today";
                    $notification->description      = "Your period is starting, use the Femi App to help you get through it calmly. ";
                    $notification->type             = "7days";
                    $notification->user_id          = $id;
                    $notification->save();

                    $server_key = "key=AAAAnhAwszk:APA91bEsYv4uiL9cZe_-x_ACoVlnEzx4ohIxKD6S05wq4l2ufdzNQqDOhB2iebljhMyVbhwT5Z-cY_dsVZsV0DH5Ks6QAC783c2V92XIm34weNfnWgwiQWD2j4iJ0osqSOs-80vQ5JOX";
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => $server_key
                    ])->post('https://fcm.googleapis.com/fcm/send', [
                        'to'            => User::find($id)->token,
                        'priority'      => 'high',
                        'notification'  => [
                        'title'         => "Your period starts today",
                        'body'          => "Your period is starting, use the Femi App to help you get through it calmly. "
                        ]
                    ]);
                }

                $three_days_date_om              = Carbon::now()->addDays(3)->format('Y-m-d');
                $user_ids_for_three_days_date_om = array_unique(Period::whereDate('ovlution_start', $three_days_date_om)->pluck("user_id")->toArray());
                print("user_ids_for_three_days_date_om");
                print_r($user_ids_for_three_days_date_om);

                foreach($user_ids_for_three_days_date_om as $id){
                    $notification                   = new PushNotification;
                    $notification->title            = "Ovulation begins in 3 days";
                    $notification->description      = "If you are family planning, get ready for ovulation in 3 days.";
                    $notification->type             = "7days";
                    $notification->user_id          = $id;
                    $notification->save();

                    $server_key = "key=AAAAnhAwszk:APA91bEsYv4uiL9cZe_-x_ACoVlnEzx4ohIxKD6S05wq4l2ufdzNQqDOhB2iebljhMyVbhwT5Z-cY_dsVZsV0DH5Ks6QAC783c2V92XIm34weNfnWgwiQWD2j4iJ0osqSOs-80vQ5JOX";
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => $server_key
                    ])->post('https://fcm.googleapis.com/fcm/send', [
                        'to'            => User::find($id)->token,
                        'priority'      => 'high',
                        'notification'  => [
                        'title'         => "Ovulation begins in 3 days ",
                        'body'          => "If you are family planning, get ready for ovulation in 3 days."
                        ]
                    ]);
                }

                if( Carbon::now()->format('l') == 1 || Carbon::now()->format('l') == 15 ){
                    foreach($user_ids as $id){
                        $notification                   = new PushNotification;
                        $notification->title            = "Get prepared for you period ";
                        $notification->description      = "Order you Femi Secrets Products now to prepare for your period. ";
                        $notification->type             = "7days";
                        $notification->user_id          = $id;
                        $notification->save();

                        $server_key = "key=AAAAnhAwszk:APA91bEsYv4uiL9cZe_-x_ACoVlnEzx4ohIxKD6S05wq4l2ufdzNQqDOhB2iebljhMyVbhwT5Z-cY_dsVZsV0DH5Ks6QAC783c2V92XIm34weNfnWgwiQWD2j4iJ0osqSOs-80vQ5JOX";
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => $server_key
                        ])->post('https://fcm.googleapis.com/fcm/send', [
                            'to'            => User::find($id)->token,
                            'priority'      => 'high',
                            'notification'  => [
                            'title'         => "Get prepared for you period  ",
                            'body'          => "Order you Femi Secrets Products now to prepare for your period. "
                            ]
                        ]);
                    }
                }



                return response()->json([
                    'status'    => true,
                    'message'   => "Notification successfully added",
                    'data'      => $user_ids
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
// Notifications. 

// 1 (7 days before their period) 
// Title: 7 days until your period 
// Description: You period will be here in 7 days Order your Femi Secrets products now. 

// Should be a button for them to push to order products. 

// 2 (on the day their period should start)

// Title: Your period starts today
// Description: Your period is starting, use the Femi App to help you get through it calmly. 

// 3 (3 days before ovulation)
// Title: Ovulation begins in 3 days 
// Description: If you are family planning, get ready for ovulation in 3 days. 


// 4  1st and 15 of every month to every user 

// Title: Get prepared for you period 
// Description: Order you Femi Secrets Products now to prepare for your period. 

// Link to products page.
