<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Account;
use App\Http\Controllers\view;
use Illuminate\Support\Facades\Hash;


class ChangePasswordController extends Controller {
  
    public function getFormResetPassword(Request $request,$token){
      return View('content.authentications.reset-password',['token'=> $token]); 
    }
    public function passwordReset(Request $request){
      return $this->verifyToken($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
    }
   // Verify if token is valid  
   private function verifyToken($request){
    return DB::table('password_resets')->where([
      'email' => $request->input('email'),
      'token' => $request->input('token')
    ]);
    }
    // Token not found response
    private function tokenNotFoundError() {
        return view('content.pages.pages-misc-error');
    }
    // Reset password
    private function resetPassword($request) {
        // find email
        $acc = Account::where('email',$request->email)->first();
        // update password
        $acc->update([
          'password'=>Hash::make($request->input('password'))
        ]);
        $acc->save();
        // remove verification data from db
        $this->verifyToken($request)->delete();
        return view('content.authentications.reset-password-success');
    }    
}