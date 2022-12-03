<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function storeUploads(Request $request, $nameFill)
    {
        try {
            $imgUrl = cloudinary()->upload($request->file($nameFill)->getRealPath())->getSecurePath();
            return $imgUrl;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
