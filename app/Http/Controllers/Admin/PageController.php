<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PageDataTable;
use App\Helpers\Posts;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Term;
use App\Services\Slug;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public $dimensionWidth;
    public $dimensionHeight;

    /**
     * PageController constructor.
     */
    public function __construct()
    {
        $this->dimensionWidth = '640';
        $this->dimensionHeight = '426';
        if (!File::exists(storage_path('app/public/images'))) {
            File::makeDirectory(storage_path('app/public/images'));
        }
        $this->middleware('permission:read-pages');
        $this->middleware('permission:add-pages', ['only' => ['create']]);
        $this->middleware('permission:update-pages', ['only' => ['edit']]);
        $this->middleware('permission:delete-pages', ['only' => ['destroy', 'massdestroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PageDataTable $dataTable
     * @return Response
     */
    public function index(PageDataTable $dataTable)
    {
        return $dataTable->render('admin.page.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('admin.page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required|min:3',
            'slug'  => ['required', Rule::unique('posts', 'post_name')->where(function ($query) {
                return $query->wherePostType('page');
            }),]
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // status (draft or publish) based button submit
        $status = request()->has('draft') ? 'draft' : (request()->has('publish') ? 'publish' : NULL);

        $page = new Post;

        // if image available
        if (request()->hasFile('image')) {
            $page->post_image = Posts::postThumb(request('image'));
        }

        $page->post_title       = strip_tags(Str::title(request('title')));
        $page->post_name        = Slug::slug(request('slug'), '-');
        $page->post_summary     = request('summary');
        $page->post_content     = request('content');
        $page->meta_description = strip_tags(request('meta_description'));
        $page->meta_keyword     = strip_tags(request('meta_keyword'));
        $page->post_status      = $status;
        $page->post_hits        = 0;
        $page->post_author      = Auth::id();
        $page->post_type        = 'page';
        $page->save();

        return redirect()->route('pages.index')
            ->withSuccess(__('Page saved successfully!'));
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
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        $page = Post::findOrFail($id);
        $image = Posts::getPostThumb($page->post_image);
        return view('admin.page.edit', compact('page','image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Slug $slug
     * @param int $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(Slug $slug, $id)
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required|min:3',
            'slug'  => ['required', Rule::unique('posts', 'post_name')->where(function ($query) {
                return $query->wherePostType('page');
            })->ignore($id),]
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $page                   = Post::findOrFail($id);
        $page->post_title       = strip_tags(Str::title(request('title')));
        $page->post_name        = Slug::slug(request('slug'), '-');
        $page->post_summary     = request('summary');
        $page->post_content     = request('content');
        $page->post_type        = 'page';
        $page->post_status      = request()->has('draft') ? 'draft' : (request()->has('publish') ? 'publish' : null);
        $page->meta_description = strip_tags(request('meta_description'));
        $page->meta_keyword     = strip_tags(request('meta_keyword'));

        if (request('isimage') == "true") {
            if (request()->hasFile('image')) {
                if (!empty($page->post_image)) {
                    Storage::disk('public')->delete('images/' . $page->post_image);
                }
                $page->post_image = Posts::postThumb(request('image'));
            }
        } else {
            if (!empty($page->post_image)) {
                Storage::disk('public')->delete('images/' . $page->post_image);
            }
            $page->post_image = null;
        }

        $page->save();

        return redirect()->route('pages.index')
            ->withSuccess(request(__('Page was updated successfully .')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::allows('delete-pages')) {
            $post = Post::findOrFail($id);
            if ($post->post_image) {
                Storage::disk('public')->delete('images/' . $post->post_image);
            }

            preg_match_all('/<img(.*?)src=("|\'|)(.*?)("|\'| )(.*?)>/s', $post->post_content, $url_images);

            foreach ($url_images[3] as $url_image) {
                $image = last(explode('/', $url_image));
                Storage::disk('public')->delete('images/' . $image);
            }

            Post::destroy($id);
            return response()->json(['success' => __('Page deleted successfully.')]);
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
        if (Gate::allows('delete-pages')) {
            $pages_id_array = request('id');

            $pages = Post::whereIn('id', $pages_id_array)->get();

            foreach($pages as $item) {
                if ($item->post_image) {
                    Storage::disk('public')->delete('images/' . $item->post_image);
                }

                preg_match_all('/<img(.*?)src=("|\'|)(.*?)("|\'| )(.*?)>/s', $item->post_content, $url_images);

                foreach ($url_images[3] as $url_image) {
                    $image = last(explode('/', $url_image));
                    Storage::disk('public')->delete('images/' . $image);
                }
            }

            $pages = Post::whereIn('id', $pages_id_array);
            if($pages->delete()) {
                return response()->json(['success' => __('Page deleted successfully.')]);
            } else {
                return response()->json(['error' => __('Page deleted not successfully.')]);
            }
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }
}
