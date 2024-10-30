<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Models\User;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function index() : View{
        return view('admin.profile.index');
    }

    public function updateProfile(ProfileUpdateRequest $request) : RedirectResponse{

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        toastr('Update successfully.', 'success');

        return redirect()->back();
    }
}
