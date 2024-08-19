<?php

namespace App\Http\Controllers;

use App\Models\ShippingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ShippingDetailController extends Controller
{
    public function store(Request $request)
    {
        try{
            $validator  = Validator::make($request->all(),[
                "user_id"=> "required",
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'apt' => 'required',
                'country' => 'required',
                'postal_code' => 'required',
                'card_number' => 'required',
                'expiry' => 'required',
                'cvc' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }else {
                $user = User::find($request->get("user_id"));
                if(!$user){
                    return response()->json(['message' => 'User not found.'], 404);
                }
                $shippingDetail = ShippingDetail::create($request->all());
            }
    
    
            return response()->json($shippingDetail, 201);
        }catch(\Exception $e){
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }
    }
    public function getByUserId($userId)
    {
        $shippingDetails = ShippingDetail::where('user_id', $userId)->get();

        if (!$shippingDetails) {
            return response()->json(['message' => 'No shipping details found'], 404);
        }else {
            return response()->json($shippingDetails);
        }

    }

    public function destroy($id)
    {
        $shippingDetail = ShippingDetail::find($id);

        if (!$shippingDetail) {
            return response()->json(['message' => 'Shipping detail not found.'], 404);
        }

        $shippingDetail->delete();

        return response()->json(['message' => 'Shipping detail deleted successfully.']);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'apt' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
            'card_number' => 'required',
            'expiry' => 'required',
            'cvc' => 'required',
        ]);

        $shippingDetail = ShippingDetail::find($id);

        if (!$shippingDetail) {
            return response()->json(['message' => 'Shipping detail not found.'], 404);
        }

        $shippingDetail->update($request->all());

        return response()->json(['message' => 'Shipping detail updated successfully.']);
    }

}
