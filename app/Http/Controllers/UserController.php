<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\UpdateGenericClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    public function Login(Request $request)
    {
        try {
            $input = $request->all();
            $result = "";
            $sql = User::where([['username', $input['username']], ['password', $input['password']]])->first();
            if($sql){
                return response()->json([ 'status'=> 'Success', 'message' => "Bienvenido", 'data' => $sql ], 200);
            }else{
                return response()->json(['status'=>'Error','message' => "No existe el usuario",'data' => $sql], 422);                
            }
        } catch (\Exception $e) {
            return response()->json(['status'=>'Error','message' => "No existe el usuario",'data' => $e], 422);
        }
    }
}
