<?php
namespace App\Http\Controllers;

use App\Http\Controllers\view;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Kreait\Firebase\Factory;

class ChangePasswordController extends Controller
{
    private $verifyTokenId;

    public function getFormResetPassword(Request $request, $token)
    {
        return View('content.authentications.reset-password', ['token' => $token]);
    }


    public function passwordReset(Request $request)
    {
        dd($request);
        if($this->verifyJwtToken($request)){ 
            if($verifyTokenId->CustomToken()->get('roles') == "0"){
                $this->resetPasswordUser($verifyTokenId->CustomToken()->get('email'),$request->password,$verifyTokenId->CustomToken()->get('token'));
            }else{
                $this->resetPasswordAdmin($verifyTokenId->CustomToken()->get('email'),$request->password,$verifyTokenId->CustomToken()->get('token'));
            }
        }else{
            $this->tokenNotFoundError();
        }
        // return $this->verifyJwtToken($request) ? $this->resetPassword($request) : 
    }


    private function verifyJwtToken($request){
        $result = false;

        $factory = (new Factory)->withServiceAccount(env('PATH_FIREBASE_TOKEN','D:\Workspace\laravel\php_project\serviceAccount.json'));
        $auth = $factory->createAuth();
        $tokenId = $request->token;

        try {
            $verifyTokenId = $auth->verifyIdToken($tokenId);
        } catch (FailedToVerifyToken $e) {
            $result = false;
        }
         
        $email = $verifyTokenId->CustomToken()->get('email');
        $roles = $verifyTokenId->CustomToken()->get('roles');
        $token = $verifyTokenId->CustomToken()->get('token');

        if ($this->verifyToken($request)->count() > 0) {
            $result = true;
        }
        return $result;
    }

    // Verify if token is valid
    private function verifyToken($email,$token)
    {
        return DB::table('password_resets')->where([
            'email' => $email,
            'token' => $token,
        ]);
    }
    // Token not found response
    private function tokenNotFoundError()
    {
        return view('content.pages.pages-misc-error');
    }
    // Reset password
    private function resetPasswordUser($email,$password,$token)
    {
        // find email
        $acc = Account::where('email', $request->email)->first();
        // update password
        $acc->update([
            'password' => Hash::make($password),
        ]);
        $acc->save();
        // remove verification data from db
        $this->verifyToken($email,$token)->delete();
        return view('content.authentications.reset-password-success');
    }

    private function resetPasswordAdmin($email,$password,$token)
    {
        $usre =  DB::table('table_admins')->where(
            ['email' => $email]
        );
        $usre->update([
            'password' => Hash::make($password)
        ]);
        $usre->save();
        // remove verification data from db
        $this->verifyToken($email,$token)->delete();
        return view('content.authentications.reset-password-success');
    }
}
