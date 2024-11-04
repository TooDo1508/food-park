<?php

namespace App\Traits;

use File;
use Illuminate\Http\Request;

trait FileUploadTrait{
    function uploadImage(Request $request, $inputName, $oldPath=NULL, $path="/uploads"){
        if($request->hasFile($inputName)){
            $image = $request->{$inputName};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'media_'.uniqid().'.'.$ext;

            // move(save folder, file name move to folder)
            $image->move(public_path($path), $imageName);

            // delete old file image

            return $path.'/'.$imageName;
        }
    }

    function removeImage(string $path) : void {
        if(File::exists(public_path($path))){
            File::delete((public_path($path)));
        }
    }


}
