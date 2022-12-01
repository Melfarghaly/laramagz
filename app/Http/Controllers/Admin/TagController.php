<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TagDataTable;
use App\Http\Controllers\Controller;
use App\Models\{Term, TermTaxonomy};
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
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:read-tags', ['except' => ['tagsSearch']]);
        $this->middleware('permission:add-tags', ['only' => ['store']]);
        $this->middleware('permission:update-tags', ['only' => ['update']]);
    }

    /**
     * @return mixed
     */
    public function tagsSearch()
    {
        $keyword = request()->get('q');
        return Term::select('id','name')->tag()->searchName($keyword)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param TagDataTable $dataTable
     * @return Response
     */
    public function index(TagDataTable $dataTable)
    {
        return $dataTable->render( 'admin.tag.index' );
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
     */
    public function store(Slug $slug)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $check_tag = Term::tag()->ofName(request('name'))->exists();
        if ($check_tag) {
            return response()->json(['error_exists' => __("The name has already been taken.")]);
        }
        $new_taxonomy = new TermTaxonomy([
            'taxonomy'=>'tag'
        ]);
        $terms = Term::create([
            'name' => Str::title(request('name')),
            'slug' => Str::slug(request('name'))
        ]);
        $terms->taxonomy()->save($new_taxonomy);

        return response()->json(['success' => __('Tag saved successfully!')]);
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
     * @return Application|Factory|View|Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $this->authorize('update-tags');
        $tag = Term::findOrFail($id);
        $taxonomy = Term::find($id)->taxonomy;
        return view('admin.tag.edit', ['tag' => $tag, 'taxonomy'=>$taxonomy]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update($id)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:3|regex:/^[a-zA-Z0-9 ]+$/'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $exists = Term::whereName(request('name'))->exists();
        if ($exists) {
            $check_exists_tag = Term::tag()->whereName(request('name'))->exists();
            $find_id          = Term::with('taxonomy')->whereName(request('name'))->first()->taxonomy['id'];

            if  ($check_exists_tag AND $find_id != $id) {
                return response()->json(['errors' => ['name' => __('The name has already been taken.')]]);
            }

        } else {
            $tag       = Term::find($id);
            $tag->name = Str::title(request('name'));
            $tag->slug = Str::slug(request('name'));
            $tag->save();

            TermTaxonomy::where('term_id', $id)->update(['taxonomy' => 'tag']);
        }
        return response()->json(['success' => __('Tag saved successfully!')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::allows('delete-tags')) {
            Term::destroy($id);
            return response()->json(['success' => __('Tag deleted successfully.')]);
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
        if (Gate::allows('delete-tags')) {
            $tags_id_array = request('id');
            TermTaxonomy::whereIn('parent',$tags_id_array)
                ->update(['parent' => '0']);
            $tags = Term::whereIn('id', $tags_id_array);
            if($tags->delete()) {
                return response()->json(['success' => __('Tag deleted successfully.')]);
            } else {
                return response()->json(['error' => __('Tag deleted not successfully.')]);
            }
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }
}
