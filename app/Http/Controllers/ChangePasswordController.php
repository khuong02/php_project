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

    private $decoded;

    private function decodedJwt($request)
    {
        $private_key = env("JWT_SECRET");
        $tokenId = $request->token;
        try {
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
            if ($this->decoded === null) {
                $this->decoded = $this->decoded($request);
            } else {
                $email = $this->decoded->claims->email;
                $token = $this->decoded->claims->token;
            }
            if ($this->verifyToken($email, $token)->count() > 0) {
                $result = true;
            }
        } catch (FailedToVerifyToken $e) {
            $result = false;
        }
        return $result;
    }


    // Verify if token is valid
    private function verifyToken($email, $token)
    {
        return DB::table('password_resets')->where([
            'email' => $email,
            'token' => $token,
        ]);
    }

    private function tokenNotFoundError()
    {
        return view('content.pages.pages-misc-error');
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

    public function getFormResetPassword(Request $request)
    {

        try {
            if ($this->decoded === null) {
                $this->decoded = $this->decodedJwt($request);
            } else {
                if (time() > $this->decoded->exp) {
                    return back()->with('Erro', 'Link expired !!!');
                } else {
                    return View('content.authentications.reset-password', ['token' => $request->token]);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);

        if ($this->verifyJwtToken($request)) {
            if ($this->decoded->claims->roles == "0") {
                $this->resetPasswordUser($this->decoded->claims->email, $request->password, $this->decoded->claims->token);
                return View('content.authentications.reset-password-success');
            } else {
                $this->resetPasswordAdmin($this->decoded->claims->email, $request->password, $this->decoded->claims->token);
                return View('content.authentications.reset-password-success');
            }
        } else {
            $this->tokenNotFoundError();
        }
    }

}
