<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function storeUploads(Request $request)
    {
        try {
            $imgUrl = cloudinary()->upload($request->file('file')->getRealPath())->getSecurePath();
            return $imgUrl;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
