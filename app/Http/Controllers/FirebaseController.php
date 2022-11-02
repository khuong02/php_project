<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;

class FirebaseController extends Controller
{
    public function verifyToken(Request $request){
        $factory = (new Factory)->withServiceAccount('/Users/longtran/Downloads/project-7053e-firebase-adminsdk-3orve-b9769b67dd.json');
        $auth = $factory->createAuth();
        
        $idToken = $request->header('authorization');

        try {
            $verifiedIdToken = $auth->verifyIdToken($idToken);
        } catch (FailedToVerifyToken $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }

        $uname = $verifiedIdToken->claims()->get('name');
        $email = $verifiedIdToken->claims()->get('email');
        $image = $verifiedIdToken->claims()->get('picture');

        $userModel = new User();
        $user = $userModel->findUserByEmail($email);
        if(!isset($user->email)){
            $user = User::create([
                "username"=>$uname,
                "email"=>$email,
                "avatar"=>$image,
            ]);
        }
        $customToken = $auth->createCustomToken($user->id,[],'P6D');
        $customTokenString = $customToken->toString();

        return response()->json([
            'status' => 200,
            'token' => $customTokenString,
        ], 200);
    }
}
