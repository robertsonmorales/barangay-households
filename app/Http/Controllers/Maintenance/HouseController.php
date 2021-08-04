<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator, Arr;

use App\Models\House;
use App\Models\Barangay;

class HouseController extends Controller
{
    protected $house, $barangay;
    public function __construct(House $house, Barangay $barangay){
        config('app.timezone', 'Asia/Manila');

        $this->house = $house;
        $this->barangay = $barangay;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = ['Houses'];
        $mode = [route('houses.index')];
        
        $pagesize = [25, 50, 75, 100, 125];
        
        $rows = array();
        $rows = $this->house->latest()->get();
        $rows = $this->changeValue($rows);
        $rows = $this->__change_values($rows);

        $columnDefs = array(            
            array('headerName'=>'BARANGAY NAME','field'=> 'barangay_id', 'floatingFilter'=>false),
            array('headerName'=>'HOUSE NO.','field'=> 'house_no', 'floatingFilter'=>false),
            array('headerName'=>'HOUSE ROOF','field'=> 'house_roof', 'floatingFilter'=>false),
            array('headerName'=>'HOUSE WALL','field'=> 'house_wall', 'floatingFilter'=>false),
            array('headerName'=>'BUILDING PERMIT','field'=> 'building_permit', 'floatingFilter'=>false),
            array('headerName'=>'OCCUPANCY PERMIT','field'=> 'occupancy_permit', 'floatingFilter'=>false),
            array('headerName'=>'DATE CONSTRUCTED','field'=> 'date_constructed', 'floatingFilter'=>false),
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

        return view('pages.houses.index', [
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'data' => $data,
            'pagesize' => $pagesize,
            'create' => "houses.create",
            'title' => 'Houses'
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
        $name = ['House', 'Create'];
        $mode = [route('houses.index'), route('houses.create')];

        $this->audit_trail_logs('','','Creating new record','');

        $barangays = $this->barangay->active()->get();

        $house_details = array(
            'strong_materials' => 'Strong Materials',
            'weak_materials' => 'Weak Materials',
            'mixed_but_strong' => 'Mixed, but pre dominantly strong',
            'mixed_but_weak' => 'Mixed, but pre dominantly weak',
            'shanty' => 'Shanty',
        );

        return view('pages.houses.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'House',
            'barangays' => $barangays,
            'house_details' => $house_details
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

        $data = $this->house->create($validated)->id;

        $this->audit_trail_logs('', 'created', 'house: '.$validated['house_no'], $data);

        return redirect()
            ->route('houses.index')
            ->with('success', 'You have successfully added: '.$validated['house_no']);
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
        $data = $this->house->findOrFail($id);
        $data['house_no'] = explode('-', $data['house_no'])[1];

        $mode_action = 'update';
        $name = ['House', 'Edit', $data->house_no];
        $mode = [route('houses.index'), route('houses.edit', $id), route('houses.edit', $id)];

        $this->audit_trail_logs('', '', 'houses: '.$data->house_no, $id);

        $barangays = $this->barangay->active()->get();

        $house_details = array(
            'strong_materials' => 'Strong Materials',
            'weak_materials' => 'Weak Materials',
            'mixed_but_strong' => 'Mixed, but pre dominantly strong',
            'mixed_but_weak' => 'Mixed, but pre dominantly weak',
            'shanty' => 'Shanty',
        );

        return view('pages.houses.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'House',
            'data' => $data,
            'barangays' => $barangays,
            'house_details' => $house_details
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

        $data = $this->house->findOrFail($id)->update($validated);

        $this->audit_trail_logs('', 'updated', 'house: '.$validated['house_no'], $id);

        return redirect()
            ->route('houses.index')
            ->with('success', 'You have successfully updated: '.$validated['house_no']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->house->findOrFail($id);
        $data->deleted_by = auth()->user()->id;
        $data->save();

        $data->delete();

        $this->audit_trail_logs('', 'deleted', 'house: '.$data->house_no, $id);

        return redirect()
            ->route('houses.index')
            ->with('success', 'You have successfully removed: '.$data->house_no);
    }

    public function __change_values($rows){
        foreach ($rows as $key => $value) {
            if (Arr::exists($value, 'barangay_id')) {
                $barangay = $this->barangay->find($value['barangay_id']);
                $value['barangay_id'] = $barangay->barangay_name;
            }
        }

        return $rows;
    }

    public function validator(Request $request)
    {
        $id = $this->safeInputs($request->input('id'));
        $barangay_code = $this->barangay->find($this->safeInputs($request->input('barangay_id')))->barangay_code;

        $input = [
            'barangay_id' => $this->safeInputs($request->input('barangay_id')),
            'house_no' => $barangay_code.'-'.$this->safeInputs($request->input('house_no')),
            'house_roof' => $this->safeInputs($request->input('house_roof')),
            'house_wall' => $this->safeInputs($request->input('house_wall')),
            'building_permit' => $this->safeInputs($request->input('building_permit')),
            'occupancy_permit' => $this->safeInputs($request->input('occupancy_permit')),
            'date_constructed' => $this->safeInputs($request->input('date_constructed')),
            'status' => $this->safeInputs($request->input('status'))
        ];

        $rules = [
            'barangay_id' => 'required',
            'house_no' => 'required|string|unique:houses,house_no,'.$id,
            'house_roof' => 'required|string',
            'house_wall' => 'required|string',
            'building_permit' => 'nullable|string',
            'occupancy_permit' => 'nullable|string',
            'date_constructed' => 'required|string',
            'status' => 'required|numeric',
        ];

        $messages = [];

        $customAttributes = [
            'barangay_id' => 'barangay',
            'house_no' => 'house no.',
            'house_roof' => 'house roof',
            'house_wall' => 'house wall',
            'building_permit' => 'building permit',
            'occupancy_permit' => 'occupancy permit',
            'date_constructed' => 'date constructed',
            'status' => 'status',
        ];

        $validator = Validator::make($input, $rules, $messages,$customAttributes);
        return $validator->validate();
    } 
}
