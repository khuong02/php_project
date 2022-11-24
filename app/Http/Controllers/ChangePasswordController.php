<?php
namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ChangePasswordController extends Controller
{

    private $decodejwt;

    private function decodedJwt($jwtToken)
    {
        try {
            $private_key = env("JWT_SECRET");
            $tokenId = $jwtToken;
            $decoct = JWT::decode($tokenId, new Key($private_key, 'HS256'));
            return $decoct;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function verifyJwtToken($request)
    {
        $result = false;
        try {
            if ($this->decodejwt === null) {
                $this->decodejwt = $this->decodedJwt($request->token);
            }
            $email = $this->decodejwt->claims->email;
            $token = $this->decodejwt->claims->token;
            if ($this->verifyToken($email, $token)->count() > 0) {
                $result = true;
            }
        } catch (FailedToVerifyToken $e) {
            $result = false;
        }
        return $result;
    }

    private function verifyToken($email, $token)
    {
        return DB::table('password_resets')->where([
            'email' => $email,
            'token' => $token,
        ]);
    }

    private function resetPasswordUser($email, $password, $token)
    {
        try {
            $acc = Account::where('email', $email)->first();

            $acc->update([
                'password' => Hash::make($password),
            ]);
            $acc->save();

            $this->verifyToken($email, $token)->delete();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function resetPasswordAdmin($email, $password, $token)
    {
        try {
            $usre = DB::table('table_admins')->where(
                ['email' => $email]
            );
            $usre->update([
                'password' => Hash::make($password)
            ]);
            $usre->save();

            $this->verifyToken($email, $token)->delete();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getFormResetPassword(Request $request,$token)
    {
        try {
            if ($this->decodejwt === null) {
                $this->decodejwt = $this->decodedJwt($token);
            }
            if (time() > $this->decodejwt->exp) {
                return view('content.pages.pages-misc-token-exp');
            }
            if(!$this->verifyToken($this->decodejwt->claims->email,$this->decodejwt->claims->token)->count() > 0){
                return view('content.pages.pages-misc-token-exp');
            }
            return View('content.authentications.reset-password', ['token' => $token]);

        } catch (\Throwable $th) {
            return view('content.pages.pages-misc-under-maintenance');
        }
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);
        try {
            if($this->verifyJwtToken($request)){
                if ($this->decodejwt->claims->roles == "0") {
                    $this->resetPasswordUser($this->decodejwt->claims->email, $request->password, $this->decodejwt->claims->token);
                }
                if($this->decodejwt->claims->roles == "1") {
                    $this->resetPasswordAdmin($this->decodejwt->claims->email, $request->password, $this->decodejwt->claims->token);
                }
                return View('content.authentications.reset-password-success');
            }
            if (!$this->verifyJwtToken($request)) {
                return view('content.pages.pages-misc-token-exp');
            }
        } catch (\Throwable $th) {
            return view('content.pages.pages-misc-under-maintenance');
        }
    }


}
