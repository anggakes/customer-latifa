<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/5/17
 * Time: 2:04 PM
 */

namespace App\Http\Controllers\Profile;


use App\Events\AfterRegister;
use App\Http\Controllers\Controller;
use App\Listeners\SendEmailVerification;
use App\Listeners\SendHandphoneVerification;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Validator;
use Hash;
use JWTAuth;

class ProfileController extends Controller
{

    public function index(){

        $user = User::find(request()->user()->id);

        return ["user" => $user];
    }


    public function update(){

        $data = request()->all();
        $user = User::find(request()->user()->id);

        $validator = $this->validator($data);
        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->getMessageBag()->first());
        }

        if(isset($data['name']) AND $data['name'] != $user->name){
            // check if has already taken ?
            $user->name = $data['name'];
        }

        if(isset($data['email']) AND $data['email'] != $user->email){
            // check if has already taken ?
            $user->email = $data['email'];

            $a = new SendEmailVerification();
            $a->handle(new AfterRegister($user));
        }

        if(isset($data['handphone']) AND $data['handphone'] != $user->handphone){
            // check if has already taken ?
            $user->handphone = $data['handphone'];

            $a = new SendHandphoneVerification();
            $a->handle(new AfterRegister($user));
        }

        if($user->save()){
            return [
                "user"  => $user,
                "token" => JWTAuth::refresh(JWTAuth::getToken())
            ];
        }else{
            throw new BadRequestHttpException("Terjadi kesalahan di server");
        }

    }

    public function changePassword(){
        $data = request()->all();
        $oldPassword = $data['old_password'];
        $newPassword = $data['new_password'];

        $user = User::find(request()->user()->id);

        if($oldPassword == $newPassword){
            throw new BadRequestHttpException("Password baru dan lama tidak boleh sama");
        }

        if(!$oldPassword AND !$newPassword){
            throw new BadRequestHttpException("Password baru dan lama tidak boleh Kosong");
        }

        $validator = $this->validator(['password' => $newPassword]);
        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->getMessageBag()->first());
        }

        if(!$oldPassword AND !$newPassword){
            throw new BadRequestHttpException("Password baru dan lama tidak boleh Kosong");
        }


        if(!Hash::check($oldPassword, $user->password)){
            throw new BadRequestHttpException("Password Lama anda salah");
        }

        $password = Hash::make($newPassword);
        $user->password = $password;

        if($user->save()){
            return [
                "user"  => $user,
                "token" => JWTAuth::refresh(JWTAuth::getToken())
            ];
        }else{
            throw new BadRequestHttpException("Terjadi kesalahan di server");
        }

    }

    public function validator($data){

            return Validator::make($data, [
                'name'     => 'between:3,255',
                'email'    => 'between:3,255|email|unique:users',
                'password' => 'between:4,255',
                'handphone'=> 'between:3,255|unique:users',

            ]);

    }
}