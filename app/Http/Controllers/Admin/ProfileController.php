<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Users;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public $pathAvatar;

    /**
     * ProfileController constructor.
     */
    public function __construct()
    {
        $this->pathAvatar = storage_path('app/public/avatar');
        $this->middleware('permission:read-profile');
        $this->middleware('permission:update-profile', ['only' => ['edit']]);
    }

    /**
     * @param $filename
     * @param $filetype
     * @return string
     */
    public function base64_encode_image($filename = string, $filetype = string)
    {
        if ($filename) {
            $imgbinary = fread(fopen($filename, "r"), filesize($filename));
            return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = User::findOrFail(Auth::id());

        $roles = [];
        foreach ($user->getRoleNames() as $role) {
            $roles[] = $role;
        }
        $role = implode(' | ', $roles);

        $roles = $user->roles;
        $image = Users::getAvatar($user->photo);
        $userRel = $user->socialmedia()->get();
        $checkRelSocmed = $user->socialmedia()->exists();

        return view('admin.profile.show', compact('user','role','roles','checkRelSocmed','userRel','image'));
    }

    /**
     * @return Application|Factory|View
     */
    public function change_password()
    {
        return view('admin.profile.change_password');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function password_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password'     => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return redirect('admin/change-password')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail(Auth::id());

        $hashedPassword = $user->password;

        if (Hash::check($request->old_password, $hashedPassword))
        {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        } else {
            return redirect('admin/change-password')
                ->withErrors(['old_password'=>'Password wrong'])
                ->withInput();
        }

        return redirect()->route('view.password.edit')
            ->withSuccess(__('Change Password successfully!'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'name'       => 'required|string|min:2|max:100',
            'username'   => 'required|string|min:3|max:100|unique:users,username, ' . $id . ',id',
            'email'      => 'required|email|unique:users,email, ' . $id . ',id',
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name'       => request('name'),
            'username'   => request('username'),
            'email'      => request('email'),
            'occupation' => request('occupation'),
            'about'      => request('about'),
        ];

        if (request()->hasFile('image')) {
            $image = $request->image_base64;
            $name = request()->image->getClientOriginalName();

            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);

            $image = base64_decode($image);
            $path = $this->pathAvatar . '/' . $name;

            if (!File::exists($this->pathAvatar)) {
                File::makeDirectory($this->pathAvatar);
            }

            file_put_contents($path, $image);

            $data['photo'] = $name;
        }

        $user->update($data);

        if (request()->has('roles')) {
            $user->syncRoles(request('roles'));
        }
        Users::syncSocialMedia($id);

        if ($user->wasChanged()) {
            return redirect()->route('profile.index')
                ->withSuccess(__('Your profile has been successfully changed.'));
        }

        return redirect()->route('profile.index')
            ->withSuccess(__('There are no changes to your profile.'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $roles = [];
        foreach ($user->getRoleNames() as $role) {
            $roles[] = $role;
        }
        $role = implode(' | ', $roles);
        $roles = $user->roles;
        return view('admin.profile.show', compact('user','role','roles'));
    }
}
