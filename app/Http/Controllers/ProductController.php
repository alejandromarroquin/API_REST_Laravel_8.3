<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
            $product=Product::create([
                'name'=>$request->get('name'),
                'code'=>$request->get('code'),
                'price'=>$request->get('price')
            ]);
            DB::commit();
            return response()->json(array(
                'message'=>'Success',
                'data'=>$product
            ), 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array(
                'message'=>'Success'
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
                'data'=>Product::all()
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $product=Product::find($request->get('idProduct'));
            $product->name=$request->get('name');
            $product->code=$request->get('code');
            $product->price=$request->get('price');
            $product->save();
            return response()->json(array(
                'message'=>'Success',
                'data'=>$product
            ), 200);
        } catch (\Exception $e) {
            return response()->json(array(
                'message'=>'Error'
            ), 400);
        }
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
            Product::destroy($request->get('idProduct'));
            return response()->json(array(
                'message'=>'Success'
            ), 200);
        } catch (\Exception $e) {
            return response()->json(array(
                'message'=>'Success'
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|min:10|max:10',
            'price' => 'required|numeric'
        ],[
            'name.required'=>'Ingrese el nombre del producto',
            'name.string'=>'El nombre solo debe ser una cadena de texto',
            'name.max'=>'El nombre no debe tener más de 255 caracteres ',
            'code.required'=>'Ingrese el código del producto',
            'code.string'=>'El email solo debe ser una cadena de texto',
            'code.min'=>'El código debe tener una longitud de 10 digitos',
            'code.max'=>'El código debe tener una longitud de 10 digitos',
            'price.required'=>'Ingresa el precio del producto',
            'price.numeric'=>'El precio debe ser númerico',
        ]);
    }

}
