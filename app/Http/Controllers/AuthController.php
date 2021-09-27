<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Validator;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Invitation;

class AuthController extends Controller
{

    public function getUser()
    {
        $Usuario = new User();
        $user = $Usuario
        ->select('id', 'name','email')
        ->where('id',auth()->user()->id)
        ->first();
        
        return $this->responseSuccess($user, 200);
    }

    public function authenticate(Request $request)
    {
        $login = $request->only('email', 'password');
        $credentials = [
            'email' => $login['email'],
            'password' =>$login['password']
        ];
     
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Login ou senha incorretos'], 401);
        }
        $user = $this->getDataUsuario(trim($credentials["email"]));
        return $this->responseSuccess(compact("token","user"), 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'gender' => 'required|string|max:1',
            'password' => 'required|string|confirmed|min:6',
            'inviteCode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $invitation = new Invitation();
        $check = $invitation->select('hash', 'valid')->where('hash', $request->inviteCode)->first();

        if (!empty($check)) {
            if ($check['valid'] == 1) {

                $invitation->where('hash', $request->inviteCode)
                    ->update(['valid' => 0]);

                $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

                $data = Carbon::now();
                $invite = substr(md5(uniqid($user->email.$data, true)) , 0, 8);

                $checkInvite = $invitation->where('hash', $invite)->first();

                if (empty($checkInvite)) {
                    $invitation = new Invitation();
                    $invitation->create(array('user_id' => $user->id,
                        'hash' => $invite,
                        'valid' => 1));
                }

                return response()->json(['message' => 'Usuário registrado com sucesso',
                    'user' => $user], 201);
            } else {
                return response()->json('Código do convite inválido', 401);
            }

        } else {
            return response()->json('Código do convite inválido', 401);
        }

    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Usuário deslogado com sucesso!']);
    }

    public function refreshToken(Request $request) {
        try {
            $tokenOld = JWTAuth::getToken();
            $newToken = JWTAuth::parseToken()->refresh();
            // $user = JWTAuth::authenticate($newToken);
        } catch (TokenExpiredException $e) {
            abort(409, $e->getMessage());
        } catch (JWTException $e) {
            abort(409, $e->getMessage());
        }
        return $this->responseSuccess(compact('newToken'),200);
    }

    /*
    * Método retorna dados do usuário quando realizar login.
    */
    public function getDataUsuario($login) {
        $usuario = new User();

        return $usuario->where('email', $login)
            ->select(
                'id',
                'name',
                'email',
            )
            ->first();
    }

    public function userProfile()
        {
            return response()->json(auth()->user()->interest);
        }
}
