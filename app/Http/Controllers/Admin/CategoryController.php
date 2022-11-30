<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\TermTaxonomy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:read-categories', ['except' => ['ajaxSearch']]);
        $this->middleware('permission:add-categories', ['only' => ['store']]);
        $this->middleware('permission:update-categories', ['only' => ['update']]);
    }

    /**
     * Search
     *
     * @return Response
     */
    public function ajaxSearch(){
        $keyword = strip_tags(request()->get('q'));
        return Term::select('id','name')->category()->searchName($keyword)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param CategoryDataTable $dataTable
     * @return Response
     */
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render( 'admin.category.index' );
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:3|regex:/^[a-zA-Z0-9 ]+$/'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $check_category = Term::category()->ofName(request('name'))->exists();

        if ($check_category) {
            return response()->json(['error_exists' => "The name has already been taken."]);
        }

        $new_taxonomy = new TermTaxonomy([
            'taxonomy' => 'category'
        ]);

        $terms = Term::create([
            'name' => Str::title(request('name')),
            'slug' => Str::slug(request('name'))
        ]);
        $terms->taxonomy()->save($new_taxonomy);

        return response()->json(['success' => Str::title(request('name')).' '.__('Category saving successfully!')]);
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
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:3|regex:/^[a-zA-Z0-9 ]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $check_exists_category = Term::category()->whereName(request('name'))->exists();

        if ($check_exists_category) {
            return response()->json(['errors' => ['name' => __('The name has already been taken.')]]);
        } else {
            $category       = Term::find($id);
            $category->name = Str::title(request('name'));
            $category->slug = Str::slug(request('name'));
            $category->save();
            TermTaxonomy::where('term_id', $id)->update(['taxonomy' => 'category']);
        }

        return response()->json(['success' => __("Updating successfully!")]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::allows('delete-categories')) {
            if (TermTaxonomy::whereParent($id)->exists()) {
                $TermTaxonmy         = TermTaxonomy::whereParent($id)->first();
                $TermTaxonmy->parent = 0;
                $TermTaxonmy->save();
            }
            $termId = TermTaxonomy::whereTermId($id)->first()->id;
            TermTaxonomy::find($termId)->post()->detach();
            Term::destroy($id);
            return response()->json(['success' => __('Category deleted successfully.')]);
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
        if (Gate::allows('delete-categories')) {
            $categories_id_array = request('id');
            TermTaxonomy::whereIn('parent',$categories_id_array)
                ->update(['parent' => '0']);
            $categories = Term::whereIn('id', $categories_id_array);

            $termtaxonomy = TermTaxonomy::whereIn('term_id',$categories_id_array)->get();

            foreach($termtaxonomy as $term){
                TermTaxonomy::find($term->id)->post()->detach();
            }

            if($categories->delete()) {
                return response()->json(['success' => __('Category deleted successfully.')]);
            } else {
                return response()->json(['error' => __('Category deleted not successfully.')]);
            }
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }
}
