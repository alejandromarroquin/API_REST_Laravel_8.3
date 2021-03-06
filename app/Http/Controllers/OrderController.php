<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;

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
     * Create Order.
     * 
     * @bodyParam idProduct integer required ID product purchase
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
            DB::rollback();
            return response()->json(array(
                'message'=>'Error'
            ), 400);
        }
    }

    /**
     * Show list Orders.
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
     * Remove Order.
     * 
     *  @bodyParam idOrder integer required ID of order delete
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

    /**
     * Update status completed.
     * 
     * @bodyParam idOrder integer required ID of the order
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
     * Get Events Calendar.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEventsCalendar(){
        try {
            return Event::get();
        } catch (\Exception $e) {
            return response()->json(array(
                'message'=>'Error'
            ), 400);
        }
    }

    /**
     * Create Events Calendar.
     * 
     * @bodyParam nameEvent string required Name of event in calendar
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function createEventCalendar(Request $request){
        try {
            $event=new Event;
            $event->name=$request->nameEvent;
            $event->startDateTime=Carbon::now();
            $event->endDateTime=Carbon::now()->addHour();
            $event->save();
            return response()->json(array(
                'message'=>'Sucess',
                'data'=>$event
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
            'idProduct.numeric'=>'El ID del producto debe ser n??merico',
        ]);
    }

}
