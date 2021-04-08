<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

    /**
     * Authenticate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request){
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException  $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }

    /**
     * Get authenticated  User.
     *
     */
    public function getAuthenticatedUser()
    {
    try {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }

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
            $validaterequest=$this->validator($request);
            if($validaterequest->fails())
                return response()->json(['errors'=>$validaterequest->errors()]);
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'type' => $request->get('type')
            ]);
            $token = JWTAuth::fromUser($user);
            return response()->json(array(
                'message'=>'Success',
                'data'=>$user,
                'token'=>$token
            ),201);
        } catch (\Exception $e) {
            return response()->json(array(
                'message'=>'Error'
            ), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
            $user=User::find($request->idUser);
            $user->name=$request->get('name');
            $user->email=$request->get('email');
            $user->type=$request->get('type');
            $user->save();
            return response()->json(array(
                'message'=>'Success',
                'data'=>$user
            ), 200);
        } catch (\Exception $e) {
            return response()->json(array(
                'message'=>'Error'
            ), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        try {
            $user=User::find($request->idUser);
            $user->password=Hash::make($request->get('password'));
            $user->save();
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
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            User::destroy($request->idUser);
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:User',
            'password' => 'string|min:6',
            'type' => 'required',
        ],[
            'name.required'=>'Ingrese su nombre',
            'name.string'=>'El nombre solo debe contener caracteres alfabéticos',
            'name.max'=>'El nombre no debe tener más de 255 caracteres ',
            'email.required'=>'Ingrese su email',
            'email.string'=>'El email solo debe contener caracteres alfabéticos',
            'email.max'=>'El email no debe tener más de 255 caracteres ',
            'email.unique'=>'El email ya se encuentra registrado',
            'type.required'=>'El tipo de usuario es requerido'
        ]);
    }
}
