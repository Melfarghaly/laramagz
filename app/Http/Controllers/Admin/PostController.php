<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PostDataTable;
use App\Helpers\Posts;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\{Post, Term, TermTaxonomy, User};
use App\Services\Slug;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public $path;
    public $dimensionWidth;
    public $dimensionHeight;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        $this->path = storage_path('app/public/images'); //Post image storage path
        $this->dimensionWidth = '640'; //image width
        $this->dimensionHeight = '426'; //image height
        if (!File::exists($this->path)) {
            File::makeDirectory($this->path); //create path if not exist
        }
        $this->middleware('permission:read-posts');
        $this->middleware('permission:add-posts', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-posts', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PostDataTable $dataTable
     * @return Response
     */
    public function index(PostDataTable $dataTable)
    {
        return $dataTable->render('admin.post.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('admin.post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Slug $slug
     * @return RedirectResponse
     */
    public function store(Slug $slug)
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required|min:3',
            'slug'  => ['required', Rule::unique('posts', 'post_name')->where(function ($query) {
                return $query->wherePostType('post');
            }),]
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // status (draft or publish) based button submit
        $status = request()->has('draft') ? 'draft' : (request()->has('publish') ? 'publish' : NULL);

        $post = new Post;

        // if image available
        if (request()->hasFile('image')) {
            $post->post_image = Posts::postThumb(request('image'));
        }

        $post->post_title       = strip_tags(Str::title(request('title')));
        $post->post_name        = Slug::slug(request('slug'), '-');
        $post->post_summary     = request('summary');
        $post->post_content     = request('content');
        $post->meta_description = strip_tags(request('meta_description'));
        $post->meta_keyword     = strip_tags(request('meta_keyword'));
        $post->post_status      = $status;
        $post->post_visibility  = request('visibility');
        $post->post_hits        = 0;
        $post->post_author      = Auth::id();
        $post->post_type        = 'post';

        $post->save();

        if (request()->filled('categories')) {
            foreach (request('categories') as $category) {
                $checkCategory = Term::where('id', $category)->exists();
                if ($checkCategory) {
                    $cat_taxonomy_id = Term::find($category)->taxonomy->id;
                    $post->termtaxonomy()->attach([
                        'term_taxonomy_id' => $cat_taxonomy_id
                    ]);
                } else {
                    $new_category_taxonomy = new TermTaxonomy([
                        'taxonomy' => 'category'
                    ]);

                    $category= Term::create([
                        'name' => Str::title($category),
                        'slug' => Str::slug($category),
                    ]);

                    $get_idCategory_term = Term::find($category->id);
                    $get_idCategory_term->taxonomy()->save($new_category_taxonomy);

                    $category_taxonomy_id = Term::find($category->id)->taxonomy->id;
                    $post->termtaxonomy()->attach([
                        'term_taxonomy_id' => $category_taxonomy_id
                    ]);
                }
            }
        }

        if (request()->filled('tags')) {
            foreach (request('tags') as $tag) {

                $checkTag = Term::where('id', $tag)->exists();

                if ($checkTag) {
                    $tag_taxonomy_id = Term::find($tag)->taxonomy->id;
                    $post->termtaxonomy()->attach([
                        'term_taxonomy_id' => $tag_taxonomy_id
                    ]);
                } else {
                    $new_tag_taxonomy = new TermTaxonomy([
                        'taxonomy' => 'tag'
                    ]);

                    $tag = Term::create([
                        'name' => Str::title($tag),
                        'slug' => Str::slug($tag, '-')
                    ]);

                    $get_idTag_term = Term::find($tag->id);
                    $get_idTag_term->taxonomy()->save($new_tag_taxonomy);

                    $tag_taxonomy_id = Term::find($tag->id)->taxonomy->id;
                    $post->termtaxonomy()->attach([
                        'term_taxonomy_id' => $tag_taxonomy_id
                    ]);
                }
            }
        }

        return redirect()->route('posts.index')->withSuccess(__('Saving successfully!'));
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
        $post = Post::findOrFail($id);

        // check term_relationships category
        $checkCategory = is_null( Post::findOrFail($id)->termtaxonomy->where('taxonomy','category')->first() );

        if ( $checkCategory == TRUE )
        {
            $categories = array();
        }
        else
        {
            // Get Categories
            foreach ($post->termtaxonomy->where('taxonomy', 'category') as $taxonomy) {
                $taxonomyId = $taxonomy->term_id;
                $categories[] = Term::findOrFail($taxonomyId);
            }
        }

        $checkTag = is_null( Post::findOrFail($id)->termtaxonomy->where('taxonomy','tag')->first() );

        if ( $checkTag == TRUE )
        {
            $tags = array();
        }
        else
        {
            // Get tags
            foreach ($post->termtaxonomy->where('taxonomy', 'tag') as $taxonomy) {
                $taxonomyId = $taxonomy->term_id;
                $tags[] = Term::findOrFail($taxonomyId);
            }
        }

        // visibility option
        $visibility = $post->post_visibility;

        // file
        $image = Posts::getPostThumb($post->post_image);

        return view('admin.post.edit', compact('post', 'categories', 'tags', 'image', 'visibility'));
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
        $post = Post::findOrFail($id);

        $validator = Validator::make(request()->all(), [
            'title' => 'required|min:3',
            'slug'  => ['required', Rule::unique('posts', 'post_name')->where(function ($query) {
                return $query->wherePostType('post');
            })->ignore($id),]
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = array(
            'post_title'       => strip_tags(Str::title(request('title'))),
            'post_name'        => Slug::slug(request('slug'), '-'),
            'post_summary'     => request('summary'),
            'post_content'     => request('content'),
            'post_type'        => 'post',
            'post_visibility'  => request('visibility'),
            'meta_description' => strip_tags(request('meta_description')),
            'meta_keyword'     => strip_tags(request('meta_keyword'))
        );

        $data['post_status'] = request()->has('draft') ? 'draft' : (request()->has('publish') ? 'publish' : NULL);

        if (request('isimage') == "true") {
            if (request()->hasFile('image')) {
                if (!empty($post->post_image)) {
                    Storage::disk('public')->delete('images/' . $post->post_image);
                }
                $data['post_image'] = Posts::postThumb(request('image'));
            }
        } else {
            if (!empty($post->post_image)) {
                Storage::disk('public')->delete('images/' . $post->post_image);
            }
        }

        Post::where('id', $id)->update($data);

        if (request()->filled('categories') OR request()->filled('tags')) {

            if ( request()->filled('categories') ) {

                foreach ( request('categories') as $category ) {

                    $checkCategory = Term::where('id', $category)->exists();

                    if ( $checkCategory === TRUE ) {
                        $taxonomy_id[] = Term::find($category)->taxonomy->id;
                    } else {
                        $new_category_taxonomy = new TermTaxonomy([
                            'taxonomy' => 'category'
                        ]);

                        $category= Term::create([
                            'name' => strip_tags(Str::title($category)),
                            'slug' => Str::slug($category, '-')
                        ]);

                        $get_idCategory_term = Term::find($category->id);
                        $get_idCategory_term->taxonomy()->save($new_category_taxonomy);

                        $taxonomy_id[] = Term::find($category->id)->taxonomy->id;
                    }
                }
            }

            if ( request()->filled('tags') ) {
                foreach ( request('tags') as $tag ) {
                    $checkTag = Term::where('id', $tag)->exists();

                    if ( $checkTag === TRUE ) {

                        $taxonomy_id[] = Term::find($tag)->taxonomy->id;
                    } else {

                        $new_tag_taxonomy = new TermTaxonomy([
                            'taxonomy' => 'tag'
                        ]);

                        $tag = Term::create([
                            'name' => strip_tags(Str::title($tag)),
                            'slug' => Str::slug($tag, '-')
                        ]);

                        $get_idTag_term = Term::find($tag->id);
                        $get_idTag_term->taxonomy()->save($new_tag_taxonomy);

                        $taxonomy_id[] = Term::find($tag->id)->taxonomy->id;
                    }
                }
            }
        }else{
            $taxonomy_id = [];
        }

        $post->find($id)
            ->termtaxonomy()
            ->sync($taxonomy_id);

        return redirect()->route('posts.index')->withSuccess(__('Updating successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::allows('delete-posts')) {
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
            return response()->json(['success' => __('Post deleted successfully.')]);
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
        if (Gate::allows('delete-posts')) {
            $posts_id_array = request('id');

            $posts = Post::whereIn('id', $posts_id_array)->get();

            foreach ($posts as $item) {

                if ($item->post_image) {
                    Storage::disk('public')->delete('images/' . $item->post_image);
                }

                preg_match_all('/<img(.*?)src=("|\'|)(.*?)("|\'| )(.*?)>/s', $item->post_content, $url_images);

                foreach ($url_images[3] as $url_image) {
                    $image = last(explode('/', $url_image));
                    Storage::disk('public')->delete('images/' . $image);
                }
            }

            $post = Post::whereIn('id', $posts_id_array);
            if ($post->delete()) {
                return response()->json(['success' => __('Post deleted successfully.')]);
            } else {
                return response()->json(['error' => __('Post deleted not successfully.')]);
            }
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }
}
