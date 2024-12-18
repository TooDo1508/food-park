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
            if($oldPath && File::exists(public_path($oldPath))){
                File::delete((public_path($oldPath)));
            }

            return $path.'/'.$imageName;
        }

        return NULL;
    }

    function removeImage(string $path) : void {
        if(File::exists(public_path($path))){
            File::delete((public_path($path)));
        }
    }


}
