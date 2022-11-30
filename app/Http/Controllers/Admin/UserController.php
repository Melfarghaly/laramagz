<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Helpers\Users;
use App\Http\Controllers\Controller;
use App\Models\Socialmedia;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    public $pathAvatar;
    public $dimensions;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->pathAvatar = storage_path('app/public/avatar');
        $this->middleware('permission:read-users');
        $this->middleware('permission:add-users', ['only' => ['create','store']]);
        $this->middleware('permission:update-users', ['only' => ['edit', 'update']]);
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
     * Display a listing of the resource.
     *
     * @param UserDataTable $dataTable
     * @return Response
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string|min:3|max:100|regex:/^[A-Za-z. \-\']+$/',
            'username' => 'required|string|min:3|max:100|unique:users,username|alpha_dash',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles'    => 'required|exists:roles,name'
        ]);

        $user = new User;
        $user->name       = request('name');
        $user->username   = request('username');
        $user->email      = request('email');
        $user->password   = Hash::make(request('password'));
        $user->occupation = request('occupation');
        $user->about      = request('about');


        // if image available
        if (request()->hasFile('image')) {
            $image = request('image_base64');
            $name = request()->image->getClientOriginalName();

            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);

            $fileImage = base64_decode($image);
            $path = $this->pathAvatar . '/' . $name;

            if (!File::exists($this->pathAvatar)) {
                File::makeDirectory($this->pathAvatar);
            }

            file_put_contents($path, $fileImage);

            $user->photo = $name;
        }

        $user->save();

        $user->givePermissionTo(['update-users', 'read-posts', 'add-posts', 'update-posts', 'delete-posts']);

        $user->assignRole(request('roles'));

        if ( request()->filled('socmed') ) {
            foreach ( request('socmed') as $item ) {
                $socmed = Socialmedia::find($item);
                if(request($socmed->slug) !== "") {
                    $user->socialmedia()->attach($item, [
                        'url' => request($socmed->slug)
                    ]);
                }
            }
        }

        return redirect()->route('users.index')
            ->withSuccess(__('Saving successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        User::checkUserAuthorization($id);

        $user = User::findOrFail($id);

        $roles          = $user->roles;
        $image          = Users::getAvatar($user->photo);
        $userRel        = $user->socialmedia()->get();
        $checkRelSocmed = $user->socialmedia()->exists();

        return view('admin.user.edit', compact('user', 'roles', 'image', 'checkRelSocmed','userRel'));
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
        $data = $request->validate([
            'name'       => 'required|string|min:2|max:100|regex:/^[A-Za-z. \-\']+$/',
            'username'   => 'required|string|min:3|max:100|unique:users,username, ' . $id . ',id|alpha_dash',
            'email'      => 'required|email|unique:users,email, ' . $id . ',id',
            'password'   => 'nullable|min:6',
            'roles'      => 'required',
        ]);

        $user             = User::findOrFail($id);
        $user->name       = request('name');
        $user->username   = request('username');
        $user->password   = request('password') ? Hash::make(request('password')) : $user->password;
        $user->email      = request('email');
        $user->occupation = request('occupation');
        $user->about      = request('about');

        // if image available
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

            $user->photo = $name;
        }

        $user->save();
        $user->syncRoles($request->roles);
        Users::syncSocialMedia($id);

        return redirect()->route('users.index')
            ->withSuccess(__('Updating successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::allows('delete-users')) {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['success' => __('Deleted successfully.')]);
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }

    /**
     * Remove the multi resource from storage.
     *
     * @return JsonResponse
     */
    public function massdestroy()
    {
        if (Gate::allows('delete-users')) {
            $user_id_array = request('id');

            $users = User::whereIn('id', $user_id_array)->get();

            foreach($users as $item) {
                if ($item->photo != "noavatar.png") {
                    Storage::disk('public')->delete('avatar/' . $item->photo);
                }
            }

            $user = User::whereIn('id', $user_id_array);
            if($user->delete()) {
                return response()->json(['success' => __('Deleted successfully.')]);
            } else {
                return response()->json(['error' => __('Deleted not successfully.')]);
            }
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }

    /**
     * @param $filename
     * @return BinaryFileResponse
     */
    public function displayImageUser($filename)
    {
        if (Storage::disk('public')->exists('avatar/' . $filename)) {
            return Storage::disk('public')->response('avatar/' . $filename);
        } else {
            return response()->file(public_path('/img/noavatar.png'));
        }
    }
}
