<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ContactDataTable;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    /**
     * ContactController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:read-contacts', ['only' => ['index', 'show']]);
        $this->middleware('permission:delete-contacts', ['only' => ['destroy', 'massdestroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param ContactDataTable $dataTable
     * @return Response
     */
    public function index(ContactDataTable $dataTable)
    {
        return $dataTable->render('admin.contact.index');
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
     * @param  int  $id
     * @return Application|Factory|View|Response
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => 'read']);
        return view('admin.contact.show', compact('contact'));
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
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::allows('delete-contacts')) {
            Contact::destroy($id);
            return response()->json(['success' => __('Message Contact Deleted successfully.')]);
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
        if (Gate::allows('delete-contacts')) {
            $contacts_id_array = request('id');
            $contacts = Contact::whereIn('id', $contacts_id_array);
            if($contacts->delete()) {
                return response()->json(['success' => __('Deleted successfully.')]);
            } else {
                return response()->json(['error' => __('Deleted not successfully.')]);
            }
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }
}
