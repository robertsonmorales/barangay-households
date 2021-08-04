<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator, Arr;

use App\Models\household;
use App\Models\House;
use App\Models\Barangay;

class HouseholdController extends Controller
{
    protected $household, $house, $barangay;
    public function __construct(household $household, House $house, Barangay $barangay){
        config('app.timezone', 'Asia/Manila');

        $this->household = $household;
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
        $name = ['Households'];
        $mode = [route('households.index')];
        
        $pagesize = [25, 50, 75, 100, 125];
        
        $rows = array();
        $rows = $this->household->latest()->get();
        $rows = $this->changeValue($rows);
        $rows = $this->__change_values($rows);

        $columnDefs = array(
            array('headerName'=>'BARANGAY NAME','field'=> 'barangay_id', 'floatingFilter'=>false),
            array('headerName'=>'HOUSE NO.','field'=> 'house_id', 'floatingFilter'=>false),
            array('headerName'=>'HOUSEHOLD NO.','field'=> 'household_no', 'floatingFilter'=>false),
            array('headerName'=>'LAND OWNERSHIP','field'=> 'land_ownership', 'floatingFilter'=>false),
            array('headerName'=>'CR','field'=> 'cr', 'floatingFilter'=>false),
            array('headerName'=>'SHARED TO','field'=> 'shared_to', 'floatingFilter'=>false),
            array('headerName'=>'ELECTRICITY CONN.','field'=> 'electricity_connection', 'floatingFilter'=>false),
            array('headerName'=>'DISASTER KIT','field'=> 'disaster_kit', 'floatingFilter'=>false),
            array('headerName'=>'PRACTICING WASTE SEGREGATION','field'=> 'praticing_waste_segregation', 'floatingFilter'=>false),
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

        return view('pages.households.index', [
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'data' => $data,
            'pagesize' => $pagesize,
            'create' => "households.create",
            'title' => 'Households'
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
        $name = ['Households', 'Create'];
        $mode = [route('households.index'), route('households.create')];

        $this->audit_trail_logs('','','Creating new record','');

        $barangays = $this->barangay->active()->get();
        $houses = $this->house->active()->get();

        return view('pages.households.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Households',
            'barangays' => $barangays,
            'houses' => $houses
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

        $data = $this->household->create($validated)->id;

        $this->audit_trail_logs('', 'created', 'household: '.$validated['household_no'], $data);

        return redirect()
            ->route('households.index')
            ->with('success', 'You have successfully added: '.$validated['household_no']);
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
        $data = $this->household->findOrFail($id);
        $data['household_no'] = @explode('-', $data['household_no'])[2];

        $barangay_id = $this->house->find($data['house_id'])->barangay->id;

        $mode_action = 'update';
        $name = ['Household', 'Edit', $data->household_no];
        $mode = [route('households.index'), route('households.edit', $id), route('households.edit', $id)];

        $this->audit_trail_logs('', '', 'households: '.$data->household_no, $id);

        $barangays = $this->barangay->active()->get();
        $houses = $this->house->active()->get();

        return view('pages.households.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Household',
            'data' => $data,
            'barangays' => $barangays,
            'houses' => $houses,
            'barangay_id' => $barangay_id
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

        $data = $this->household->findOrFail($id)->update($validated);

        $this->audit_trail_logs('', 'updated', 'household: '.$validated['household_no'], $id);

        return redirect()
            ->route('households.index')
            ->with('success', 'You have successfully updated: '.$validated['household_no']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->household->findOrFail($id);
        $data->deleted_by = auth()->user()->id;
        $data->save();

        $data->delete();

        $this->audit_trail_logs('', 'deleted', 'household: '.$data->household_no, $id);

        return redirect()
            ->route('households.index')
            ->with('success', 'You have successfully removed: '.$data->household_no);
    }

    public function __change_values($rows){
        foreach ($rows as $key => $value) {
            if (Arr::exists($value, 'house_id')) {
                $house = $this->house->find($value['house_id']);
                $value['house_id'] = $house->house_no;
                $value['barangay_id'] = $house->barangay->barangay_name;
            }
        }

        return $rows;
    }

    public function validator(Request $request)
    {
        $id = $this->safeInputs($request->input('id'));
        $house_no = $this->house->find($this->safeInputs($request->input('house_id')))->house_no;

        $input = [
            'house_id' => $this->safeInputs($request->input('house_id')),
            'household_no' => $house_no.'-'.$this->safeInputs($request->input('household_no')),
            'land_ownership' => $this->safeInputs($request->input('land_ownership')),
            'cr' => $this->safeInputs($request->input('cr')),
            'shared_to' => $this->safeInputs($request->input('shared_to')),
            'electricity_connection' => $this->safeInputs($request->input('electricity_connection')),
            'disaster_kit' => $this->safeInputs($request->input('disaster_kit')),
            'praticing_waste_segregation' => $this->safeInputs($request->input('praticing_waste_segregation')),
            'status' => $this->safeInputs($request->input('status'))
        ];

        $rules = [
            'house_id' => 'required|numeric',
            'household_no' => 'required|string|unique:households,household_no,'.$id,
            'land_ownership' => 'required|string',
            'cr' => 'nullable|string',
            'shared_to' => 'nullable|string',
            'electricity_connection' => 'required|string',
            'disaster_kit' => 'nullable|string',
            'praticing_waste_segregation' => 'nullable|string',
            'status' => 'required|numeric',
        ];

        $messages = [];

        $customAttributes = [
            'house_id' => 'house no.',
            'household_no' => 'household no.',
            'land_ownership' => 'land ownership',
            'cr' => 'cr',
            'shared_to' => 'shared to',
            'electricity_connection' => 'electricity connection',
            'disaster_kit' => 'disaster kit',
            'praticing_waste_segregation' => 'praticing waste segregation',
            'status' => 'status',
        ];

        $validator = Validator::make($input, $rules, $messages,$customAttributes);
        return $validator->validate();
    } 
}
