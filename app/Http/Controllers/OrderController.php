<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            DB::beginTransaction();
            $validaterequest=$this->validator($request);
            if($validaterequest->fails())
                return response()->json(['errors'=>$validaterequest->errors()]);
            $order=Order::create([
                'idUser'=>auth()->user()->id,
                'idProduct'=>$request->get('idProduct'),
                'completed'=>'0',
                'date'=>date("Y-m-d")
            ]);
            DB::commit();
            return response()->json(array(
                'message'=>'Success',
                'data'=>$order
            ), 200);
        } catch (\Exception $e) {
            return $e;
            DB::rollback();
            return response()->json(array(
                'message'=>'Error'
            ), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            return response()->json(array(
                'message'=>'Success',
                'data'=>Order::join('Product','PurchaseOrder.idProduct','=','Product.id')->join('User','PurchaseOrder.idUser','=','User.id')->select('PurchaseOrder.id as idOrder','Product.name as nameProduct','completed','price','User.name as nameUser')->get()
            ), 200);
        } catch (\Exception $e) {
            return response()->json(array(
                'message'=>'Error'
            ), 400);
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
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            Order::destroy($request->get('idOrder'));
            return response()->json(array(
                'message'=>'Success'
            ), 200);
        } catch (\Exception $e) {
            return response()->json(array(
                'message'=>'Error'
            ), 400);
        }
    }

    public function completedOrder(Request $request){
        try {
            $order=Order::find($request->idOrder);
            $order->completed=1;
            $order->save();
            return response()->json(array(
                'message'=>'Success'
            ), 200);
        } catch (\Exception $e) {
            return response()->json(array(
                'message'=>'Error'
            ), 400);
        }
    }

    /**
     * Validate data.
     *
     * @param  object  $request
     */
    public function validator($request){
        return $validator = Validator::make($request->all(), [
            'idProduct' => 'required|numeric',
        ],[
            'idProduct.required'=>'Es necesario enviar el ID del producto',
            'idProduct.numeric'=>'El ID del producto debe ser númerico',
        ]);
    }
}
