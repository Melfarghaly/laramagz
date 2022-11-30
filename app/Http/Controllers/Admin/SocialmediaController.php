<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SocialmediaDataTable;
use App\Http\Controllers\Controller;
use App\Models\Socialmedia;
use App\Services\Slug;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;


class SocialmediaController extends Controller
{
    /**
     * SocialmediaController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:read-social-media', ['except' => ['ajaxSearch', 'getSocmed']]);
        $this->middleware('permission:add-social-media', ['only' => ['create']]);
        $this->middleware('permission:update-social-media', ['only' => ['edit']]);
    }

    /**
     * @return mixed
     */
    public function ajaxSearch()
    {
        $keyword = request()->get('q');
        return Socialmedia::select('id', 'name')->where("name", "LIKE", "%$keyword%")->get();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSocmed(Request $request)
    {
        $data = Socialmedia::find($request->id);
        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @param SocialmediaDataTable $dataTable
     * @return Response
     */
    public function index(SocialmediaDataTable $dataTable)
    {
        return $dataTable->render('admin.socialmedia.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Slug $slug
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(Slug $slug)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|min:2',
            'url' => 'required|unique:socialmedia,url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        Socialmedia::create([
            'name' => request('name'),
            'slug' => $slug->createSlug(request('name')),
            'url'  => request('url'),
            'icon' => request('icon') ? request('icon') : 'fab fa-globe'
        ]);

        return response()->json(['success' => __('Social media saved successfunlly!')]);
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
     * @return Application|Factory|View|View
     */
    public function edit($id)
    {
        $socialmedia = Socialmedia::findOrFail($id);
        return view('admin.socialmedia.edit', compact('socialmedia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Slug $slug
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Slug $slug, $id)
    {
        $this->authorize('update-social-media');
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|min:2',
            'url'  => 'required|unique:socialmedia,url, ' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $socialmedia = Socialmedia::findOrFail($id);

        $socialmedia->update([
            'name' => request('name'),
            'slug' => $slug->createSlug(request('name')),
            'url'  => request('url'),
            'icon' => request('icon') ? request('icon') : 'fab fa-globe'
        ]);

        return response()->json(['success' => __('Social media changed successfully!')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::allows('delete-social-media')) {
            Socialmedia::destroy($id);
            return response()->json(['success' => __('Social Media deleted successfully.')]);
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }

    /**
     * Remove the multi resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function massdestroy(Request $request)
    {
        if (Gate::allows('delete-social-media')) {
            $socialmedia_id_array = request('id');
            $socialmedia = Socialmedia::whereIn('id', $socialmedia_id_array);
            if($socialmedia->delete()) {
                return response()->json(['success' => __('Social media deleted successfully.')]);
            } else {
                return response()->json(['error' => __('Social media deleted not successfully.')]);
            }
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }

    }
}
