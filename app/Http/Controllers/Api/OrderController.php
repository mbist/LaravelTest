<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $Orders = Order::all();
            return response()->json([
            "success" => true,
            "message" => "Order List",
            "data" => $Orders
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Ops something went wrong, Please try again",
                
                ]);
        }
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try {
            $input = $request->all();
            
            $validator = Validator::make($input, [
            'customer' => 'required',
            'payed' => 'required'
            ]);
            if($validator->fails()){
                return $validator->errors();       
            }
            $Order = Order::create($input);
            return response()->json([
            "success" => true,
            "message" => "Order created successfully.",
            "data" => $Order
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Ops something went wrong, Please try again",
                
                ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            $Order = Order::find($id);
            if (is_null($Order)) {
                return response()->json([
                    "success" => false,
                    "message" => "Invalid Order",
                
                    ]);    
            
            }
            return response()->json([
            "success" => true,
            "message" => "Order retrieved successfully.",
            "data" => $Order
            ]);
        }
        catch (\Exception $e) {
        return response()->json([
        "success" => false,
        "message" => "Ops something went wrong, Please try again",

        ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(int $id,Request $request, Order $order)
    {
        try {
            $input = $request->all();
           
            $validator = Validator::make($input, [
            'customer' => 'required',
            'payed' => 'required|numeric|between:0,99.99'
            ]);
            if($validator->fails()){
                return $validator->errors();       
            }
            $reqdata=['customer' => $input['customer'],'payed'=>$input['payed']];
            $order= Order::where('id',$id)->update( $reqdata);
            return response()->json([
            "success" => true,
            "message" => "order updated successfully.",
            "data" => $order
            ]);
        }
        catch (\Exception $e) {
        return response()->json([
        "success" => false,
        "message" => "Ops something went wrong, Please try again",

        ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id,Order $order)
    {
        try {
            $order::destroy($id);
            return response()->json([
            "success" => true,
            "message" => "order deleted successfully.",
            "data" => $order
            ]);
        }
            catch (\Exception $e) {
            return response()->json([
            "success" => false,
            "message" => "Ops something went wrong, Please try again",

            ]);
        }
    }
    function order_product($id,Request $request, OrderItem $OrderItem){
       
        try {
            $input = $request->all();
            $Order = Order::find($id);
            if($Order->payed==0){

                
                $OrderItem->order_id = $id;
                $OrderItem->product_id = $input['product_id'];
                
                $OrderItem->save();
                return response()->json([
                "success" => true,
                "message" => "order updated successfully.",
                "data" => $OrderItem
            ]);
            }
            else{
                return response()->json([
                    "success" => false,
                    "message" => "Order already payed.",
                
                    ]);  
            }
        }
        catch (\Exception $e) {
        return response()->json([
        "success" => false,
        "message" => "Ops something went wrong, Please try again",

        ]);
        }
        

    }
    function payment($id,Request $request,Order $order){
        
        try {
            $Order = Order::find($id);
            if (is_null($Order)) {
                return response()->json([
                    "success" => false,
                    "message" => "Invalid Order",
                
                    ]); 
             }
            
            if($Order->payed==0)
            {
                $input = $request->all();
                $validator = Validator::make($input, [
                    'customer_email' => 'required|email',
                    'value' => 'required|numeric|between:0,99.99'
                    ]);
                    if($validator->fails()){
                        return $validator->errors();       
                    }
                $data=[
                    'order_id' => (int)$id,
                    'customer_email' => $input['customer_email'],
                    "value"=> $input['value']
                ];
                
                $response = Http::post('https://superpay.view.agentur-loop.com/pay', $data);

                $jsonData = $response->json();
                if(isset($jsonData['message']) && $jsonData['message']!='')
                {
                    if($jsonData['message']=='Payment Successful'){
                        $reqdata['payed'] =1;
                        $order= Order::where('id',$id)->update( $reqdata);
                    
                        return response()->json([
                            "success" => true,
                            "message" => $jsonData['message'],
                        
                            ]); 
                        }
                    else
                    {
                        return response()->json([
                            "success" => false,
                            "message" => $jsonData['message'],
                        
                            ]); 
                    }
                }
            }
            else{
                return response()->json([
                    "success" => false,
                    "message" => "payment already processed for this order",
                
                    ]); 
            }
        }
        catch (\Exception $e) 
        {
           echo  $e->getMessage();
            return response()->json([
            "success" => false,
            "message" => "Ops something went wrong, Please try again",

            ]);
        }

    }
}
