<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageController extends Controller
{
    public $path;
    public $pathAvatar;
    public $dimensions;

    /**
     * ImageController constructor.
     */
    public function __construct()
    {
        $this->path = storage_path('app/public/images');
        $this->dimensions = ['245', '300', '500'];
        $this->pathAvatar = storage_path('app/public/avatar');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function resizeImage()
    {
        return view('resizeImage');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function resizeImagePost(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $input['imagename'] = time() . '.' . $image->extension();

        $destinationPath = public_path('/thumbnail');
        $img = Image::make($image->path());
        $img->resize(640, 426, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $input['imagename']);

        $destinationPath = public_path('/img');
        $image->move($destinationPath, $input['imagename']);

        return back()
            ->withSuccess('Image Upload successful')
            ->with('imageName', $input['imagename']);
    }

    /**
     * Show the application dashboard.
     *
     * @return Application|Factory|View
     */
    public function imageCrop()
    {
        return view('imageCrop');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadNewUserPhoto(Request $request)
    {
        $data = $request->image;
        $name = $request->name;

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

        $data = base64_decode($data);

        $image_name = $name;
        $path = $this->pathAvatar . '/' . $image_name;

        if (!File::exists($this->pathAvatar)) {
            File::makeDirectory($this->pathAvatar);
        }

        file_put_contents($path, $data);

        return response()->json(['success' => 'done']);
    }


    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadUserPhoto(Request $request)
    {
        $data = $request->image;
        $name = $request->name;

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

        $data = base64_decode($data);
        $path = $this->pathAvatar . '/' . $name;

        if (!File::exists($this->pathAvatar)) {
            File::makeDirectory($this->pathAvatar);
        }

        file_put_contents($path, $data);

        return response()->json(['success' => 'done']);
    }


    /**
     * @param $filename
     * @return BinaryFileResponse
     */
    public function displayImage($filename)
    {
        if(Storage::disk('public')->exists('images/' . $filename)){
            return Storage::disk('public')->response('images/' . $filename);
        } else {
            return response()->file(public_path('/img/noimage.png'));
        }
    }

    /**
     * @param $filename
     * @return mixed
     */
    public function displayPostImage($filename)
    {
        if(Storage::disk('public')->exists('images/' . $filename)){
            return Image::make(storage_path('app/public/images/' . $filename))->response();
        }else{
            return Image::make(public_path('img/noimage.png'))->response();
        }
    }

    /**
     * @param $filename
     * @return BinaryFileResponse
     */
    public function displayAdImage($filename)
    {
        if(Storage::disk('public')->exists('ad/' . $filename)){
            return Storage::disk('public')->response('ad/' . $filename);
        } else {
            return response()->file(public_path('/img/noimage.png'));
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function uploadImagePost(Request $request)
    {
        $getFileName = pathinfo($request->image->getClientOriginalName(), PATHINFO_FILENAME);
        $getFileExtension = pathinfo($request->image->getClientOriginalExtension(), PATHINFO_FILENAME);

        $fileName = $getFileName . '-' . Str::random(10) . '.' . $getFileExtension;

        $img = Image::make($request->image);
        $resizeImage = $img->resize('640', null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->insert($resizeImage, 'center');

        if (!File::exists($this->path)) {
            File::makeDirectory($this->path);
        }

        $img->save($this->path . '/' . $fileName);

        return route('image.displayImage', $fileName);
    }

    /**
     * @return string
     */
    public function removeUploadImage()
    {
        $path= Arr::get(parse_url(request('image')), 'path');
        $filename = explode('/', $path);

        if ( Storage::disk('public')->delete('images/' . Arr::last($filename))) {
            return "File Delete Successfully";
        } else {
            return "Failed to delete file";
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function removeUserPhoto(Request $request)
    {
        if (isset($request->id)) {
            $user = User::findOrFail($request->id);
            Storage::disk('public')->delete('avatar/' . $user->photo);
            $user->update(['photo' => 'noavatar.png']);
            return 'success';
        }
    }
}
