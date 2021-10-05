<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class AuthController extends Controller
{

    public function getUser()
    {
        $Usuario = new User();
        $user = $Usuario
            ->select('id', 'name', 'email')
            ->where('id', auth()->user()->id)
            ->first();

        return $this->responseSuccess($user, 200);
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors()->toJson(), 400);
        }

        $login = $request->only('email', 'password');
        $credentials = [
            'email' => $login['email'],
            'password' => $login['password'],
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Login ou senha incorretos'], 401);
        }
        $user = $this->getDataUsuario(trim($credentials["email"]), $token);
        // return $this->responseSuccess(compact("token", "user"), 200);

        return $user;
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
            return $this->responseError($validator->errors()->toJson(), 400);
        }
        /// Checar se se existe o código de convite
        $invitation = new Invitation();
        $check = $invitation->select('hash', 'valid')->where('hash', $request->inviteCode)->first();

        if (!empty($check) and ($check['valid'] == 1)) {

                //Invalidar código de convite

                $invitation->where('hash', $request->inviteCode)
                    ->update(['valid' => 0]);

                $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

                $data = Carbon::now();
                $invite = substr(md5(uniqid($user->email . $data, true)), 0, 8);

                $checkInvite = $invitation->where('hash', $invite)->first();

                //Checar se o código de convite já existe no servidor

                if (empty($checkInvite)) {
                    $invitation = new Invitation();
                    $invitation->create(array('user_id' => $user->id,
                        'hash' => $invite,
                        'valid' => 1));
                }

                //Atribuir ao usuário o grupo padão de usuários

                $grupo = new UserGroup;
                $grupo->create(array(
                        'user_id' => $user->id,
                        'group_id' => 1
                ));

                return $this->responseSuccess(['message' => 'Usuário registrado com sucesso',
                    'user' => $user], 200);
        } else {
            return $this->responseError('Código do convite inválido', 401);
        }

    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Usuário deslogado com sucesso!']);
    }

    public function refreshToken(Request $request)
    {
        try {
            $tokenOld = JWTAuth::getToken();
            $newToken = JWTAuth::parseToken()->refresh();
        } catch (TokenExpiredException $e) {
            abort(409, $e->getMessage());
        } catch (JWTException $e) {
            abort(409, $e->getMessage());
        }
        return $this->responseSuccess(compact('newToken'), 200);
    }

    /*
     * Método retorna dados do usuário quando realizar login.
     */
    public function getDataUsuario($login, $token)
    {
        $usuario = new User();
        $dataUser = $usuario->where('email', $login)
            ->select(
                'id',
                'name',
                'email',
            )
            ->first();       

        $group = new UserGroup();
        $userGroup = $group->where('user_id', $dataUser->id)->first()->group_id;        
    
        return $user = ['token' => $token, 'user' => $dataUser, 'group' => $userGroup];
    }

    public function userProfile()
    {
        return response()->json(auth()->user()->group);
    }
}
