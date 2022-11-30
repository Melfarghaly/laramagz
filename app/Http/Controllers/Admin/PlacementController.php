<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PlacementDataTable;
use App\Http\Controllers\Controller;
use App\Models\AdPlacement;
use App\Models\Advertisement;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlacementController extends Controller
{
    /**
     * AdvertisementController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:read-ads');
        $this->middleware('permission:update-ads', ['only' => ['edit', 'changeStatus']]);
    }

    /**
     * @return JsonResponse
     */
    public function changePlacementActive()
    {
        $placement         = AdPlacement::find(request('id'));
        $status = $placement->active;
        $placement->active = request('active');

        $message = '';

        if ($status == 'y' && request('active') == 'n') {
            $message = $placement->name .' '. __('disabled successfully.');
        } else if ($status == 'n' && request('active') == 'y'){
            $message = $placement->name .' '. __('activated successfully.');
        }

        $placement->save();

        return response()->json(['success' => $message]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PlacementDataTable $dataTable
     * @return Response
     */
    public function index(PlacementDataTable $dataTable)
    {
        return $dataTable->render('admin.placement.index');
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
     * @return void
     */
    public function store(Request $request)
    {
        //
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
     * @return Application|Factory|Response|View|Application|Factory|View
     */
    public function edit($id)
    {
        $placement = AdPlacement::findOrFail($id);
        $ad        = Advertisement::findOrFail($placement->ad_id);
        return view('admin.placement.edit', compact('placement', 'ad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
        $placement = AdPlacement::findOrFail($id);
        $placement->ad_id = request('ad_unit');
        $placement->save();
        return redirect()->route('placements.index')
            ->withSuccess(__('Updating successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
