<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Traits\UpdateGenericClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use App\Mail\UserEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

use Exception;

class UserController extends Controller
{
    // public function Login(Request $request)
    // {
    //     try {
    //         $input = $request->all();
    //         $result = "";
    //         $sql = User::where([['username', $input['username']], ['password', $input['password']]])->first();
    //         if($sql){
    //             return response()->json([ 'status'=> 'Success', 'message' => "Bienvenido", 'data' => $sql ], 200);
    //         }else{
    //             return response()->json(['status'=>'Error','message' => "No existe el usuario",'data' => $sql], 422);                
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['status'=>'Error','message' => "No existe el usuario",'data' => $e], 422);
    //     }
    // }

    public function EmailToUser(Request $request)
    {
        $input = $request->all();
        try {            
            $userData["userName"] = $input['name'];
            $userData["userEmail"] = $input['email'];
            $userData["userPhone"] = $input['phone_number'];
            $userData["userMessage"] = $input['message'];
            Mail::send(new UserEmail($userData));
            return response()->json([ 'status'=> 'Success', 'message' => "Su correo ha sido enviado", 'data' => $userData ], 200);
        }catch(\Exception $e){
            return response()->json(['status'=>'Error','message' => "Error al enviar el correo",'data' => $e->getMessage()], 422);
        }
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->get('name'),
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
            'role_id' => $request->get('role_id')
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }
    

    public function AuthUser(Request $request)
    {
        $input = $request->all();
        $credentials = $request->only('username', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status'=>'Error','message' => "Credenciales incorrectas"], 422);
            }else{
                User::where("username", $input['username'])->update(array('token' => $token));
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $info = User::where("username", $input['username'])->first();
        $result = array(
            "name" => $info->name,
            "username" => $info->username,
            "role_id" => $info->role_id
        );
        return response()->json([ 'status'=> 'Success', 'message' => "Bienvenido", 'data' => $result, 'token' => $token], 200);
    }

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
            return response()->json(['token'], 200);
    }
}
