<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ProfilePasswordUpdateRequest;
use App\Http\Requests\Frontend\ProfileUpdateRequset;
use App\Traits\FileUploadTrait;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    use FileUploadTrait;

    function updateProfile(ProfileUpdateRequset $request): RedirectResponse
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        toastr()->success('Profile update success fully');
        return redirect()->back();
    }

    function updatePassword(ProfilePasswordUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        toastr('Update password successfully.', 'success');

        return redirect()->back();
    }

    function updateAvatar(Request $request)
    {
        $user = Auth::user();
        $imagePath = $this->uploadImage($request, 'avatar');
        $user->avatar = isset($imagePath) ? $imagePath : $user->avatar;
        $user->save();

        toastr('Update password successfully.', 'success');

        return response([
            'status' => 'success',
            'message' => 'Avatar updated successfully',
        ]);
    }
}
