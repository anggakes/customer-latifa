<?php

namespace App\Http\Controllers\Auth;

use App\Events\AfterRegister;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Validator;
use JWTAuth;
use Auth;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */

    public function register(Request $request){
        $data = $request->all();
        $validator = $this->validator($data, "register");
        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->getMessageBag()->first());
        }
        $data['password'] = Hash::make($data["password"]);
        $user = User::create($data);

        $token = JWTAuth::fromUser($user);

        event(new AfterRegister($user));

        return ['user' => $user, 'token' => $token];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function login(Request $request){
        if($request->has("email")){
            $credentials = $request->only('email', 'password');
        }if($request->has("handphone")){
            $credentials = $request->only('handphone', 'password');
        }else{
            throw new BadRequestHttpException("Email/ponsel tidak boleh Kosong");
        }


        $auth = Auth::attempt($credentials);

        if (!$auth) {
            throw new UnauthorizedHttpException("",'Email/ponsel atau password anda salah');
        }

        $user  = Auth::user();
        $token = JWTAuth::fromUser($user);

        return [
            "token" => $token,
            "user"  => $user
        ];

    }

    public function refreshToken(){

        $token = JWTAuth::getToken();

        if(!$token){
            throw new BadRequestHtttpException('Token not provided');
        }
        try{

            $token = JWTAuth::refresh($token);

        }catch(TokenInvalidException $e){
            throw new UnauthorizedHttpException("",'The token is invalid');
        }

        return ['token'=>$token];
    }

    /**
     * @param $data
     * @param string $type
     * @return mixed
     */

    public function validator($data, $type='login'){
        if($type == 'register'){
            return Validator::make($data, [
                'name'     => 'required|between:3,255',
                'email'    => 'required|between:3,255|email|unique:users',
                'password' => 'required|between:4,255',
                'handphone'=> 'required|between:3,255|unique:users',

            ]);
        }else{
            return Validator::make($data, [
                'email'    => 'between:3,255|email',
                'password' => 'required|between:4,255',
                'handphone'=> 'between:3,255',

            ]);
        }

    }
}
