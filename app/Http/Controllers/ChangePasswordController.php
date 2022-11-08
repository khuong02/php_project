<?php
namespace App\Http\Controllers;

use App\Http\Controllers\view;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ChangePasswordController extends Controller
{

    private $decoded;

    public function getFormResetPassword(Request $request, $token)
    {
        return View('content.authentications.reset-password', ['token' => $token]);
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        if($this->verifyJwtToken($request)){
            if($this->decoded->claims->roles == "0"){
                $this->resetPasswordUser($this->decoded->claims->email,$request->password,$this->decoded->claims->token);
                return View('content.authentications.reset-password-success');
            }else{
                $this->resetPasswordAdmin($this->decoded->claims->email,$request->password,$this->decoded->claims->token);
                return View('content.authentications.reset-password-success');
            }
        }else{
            $this->tokenNotFoundError();
        }
    }


    private function verifyJwtToken($request){
        $result = false;
        $serviceAccount  = json_decode(file_get_contents(storage_path()."/serviceAccount.json"),true);
        $private_key = $serviceAccount["private_key"];
        $tokenId = $request->token;


        try {
            $this->decoded = JWT::decode($tokenId, new Key($private_key, 'HS256'));
            $email = $this->decoded->claims->email;
            $roles = $this->decoded->claims->roles;
            $token = $this->decoded->claims->token;
            if ($this->verifyToken($email,$token)->count() > 0) {
                $result = true;
            }
        } catch (FailedToVerifyToken $e) {
            $result = false;
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
        $acc = Account::where('email', $email)->first();
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
