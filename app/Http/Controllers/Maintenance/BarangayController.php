<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Validator;

use App\Models\Barangay;

class BarangayController extends Controller
{
    protected $barangay;
    public function __construct(Barangay $barangay){
        config('app.timezone', 'Asia/Manila');

        $this->barangay = $barangay;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = ['Barangays'];
        $mode = [route('barangays.index')];
        
        $pagesize = [25, 50, 75, 100, 125];
        
        $rows = array();
        $rows = $this->barangay->latest()->get();
        $rows = $this->changeValue($rows);

        $columnDefs = array(            
            array('headerName'=>'BARANGAY CODE','field'=> 'barangay_code', 'floatingFilter'=>false),
            array('headerName'=>'BARANGAY NAME','field'=> 'barangay_name', 'floatingFilter'=>false),
            array('headerName'=>'STATUS','field' => 'status', 'floatingFilter'=>false),
            array('headerName'=>'CREATED BY','field' => 'created_by', 'floatingFilter'=>false),
            array('headerName'=>'CREATED AT','field' => 'created_at', 'floatingFilter'=>false),
            array('headerName'=>'UPDATED BY','field' => 'updated_by', 'floatingFilter'=>false),
            array('headerName'=>'UPDATED AT','field' => 'updated_at', 'floatingFilter'=>false)
        );

        $data = json_encode(array(
            'rows' => $rows,
            'column' => $columnDefs
        ));

        $this->audit_trail_logs('','','','');

        return view('pages.barangays.index', [
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'data' => $data,
            'pagesize' => $pagesize,
            'create' => "barangays.create",
            'title' => 'Barangays'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mode_action = 'create';
        $name = ['Barangay', 'Create'];
        $mode = [route('barangays.index'), route('barangays.create')];

        $this->audit_trail_logs('','','Creating new record','');

        return view('pages.barangays.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Barangay'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validator($request);

        $validated['created_by'] = auth()->user()->id;

        $data = $this->barangay->create($validated)->id;

        $this->audit_trail_logs('', 'created', 'barangay: '.$validated['barangay_name'], $data);

        return redirect()
            ->route('barangays.index')
            ->with('success', 'You have successfully added: '.$validated['barangay_name']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->barangay->findOrFail($id);

        $mode_action = 'update';
        $name = ['Barangay', 'Edit', $data->barangay_name];
        $mode = [route('barangays.index'), route('barangays.edit', $id), route('barangays.edit', $id)];

        $this->audit_trail_logs('', '', 'barangays: '.$data->barangay_name, $id);

        return view('pages.barangays.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Barangay',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validator($request);

        $validated['updated_by'] = auth()->user()->id;
        $data = $this->barangay->findOrFail($id)->update($validated);

        $this->audit_trail_logs('', 'updated', 'barangays: '.$validated['barangay_name'], $id);

        return redirect()
            ->route('barangays.index')
            ->with('success', 'You have successfully updated: '.$validated['barangay_name']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->barangay->findOrFail($id);
        $data->deleted_by = auth()->user()->id;
        $data->save();

        $data->delete();

        $this->audit_trail_logs('', 'deleted', 'barangays: '.$data->barangay_name, $id);

        return redirect()
            ->route('barangays.index')
            ->with('success', 'You have successfully removed: '.$data->barangay_name);
    }

    public function validator(Request $request)
    {
        $id = $this->safeInputs($request->input('id'));

        $input = [
            'barangay_code' => $this->safeInputs($request->input('barangay_code')),
            'barangay_name' => $this->safeInputs($request->input('barangay_name')),
            'status' => $this->safeInputs($request->input('status'))
        ];

        $rules = [
            'barangay_code' => 'required|string|unique:barangays,barangay_code,'.$id,
            'barangay_name' => 'required|string|unique:barangays,barangay_name,'.$id,
            'status' => 'required|numeric',
        ];

        $messages = [];

        $customAttributes = [
            'barangay_code' => 'barangay code',
            'barangay_name' => 'barangay name',
            'status' => 'status',
        ];

        $validator = Validator::make($input, $rules, $messages,$customAttributes);
        return $validator->validate();
    } 
}
