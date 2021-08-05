<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator, Arr;

use App\Models\Individual;
use App\Models\Family;
use App\Models\Household;
use App\Models\House;
use App\Models\Barangay;

set_time_limit(0);

class IndividualController extends Controller
{
    protected $individual, $family, $household, $house, $barangay;
    public function __construct(Individual $individual, Family $family, Household $household, House $house, Barangay $barangay){
        config('app.timezone', 'Asia/Manila');

        $this->individual = $individual;
        $this->family = $family;
        $this->household = $household;
        $this->house = $house;
        $this->barangay = $barangay;

        $this->ethnicity = array(
            'aeta' => 'Aeta',
            'agta' => 'Agta',
            'item' => 'Itim',
            'puti' => 'Puti'
        );

        $this->relationship = array(
            'father-in-law' => 'Father',
            'mother' => 'Mother',
            'son' => 'Son',
            'daughter' => 'Daughter',
            'grandfather' => 'Grandfather',
            'grandmother' => 'Grandmother',
            'grandson' => 'Grandson',
            'granddaughter' => 'Granddaughter',
            'step-son' => 'Step-son',
            'step-daughter' => 'Step-daughter',
            'step-mother' => 'Step-mother',
            'step-father' => 'Step-father',
            'relative' => 'Relative'
        );

        $this->marital_status = array(
            'single' => 'Single',
            'married' => 'Married', 
            'separated' => 'Separated',
            'widowed' => 'Widowed',
            'live-in' => 'Live-in'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = ['Individuals'];
        $mode = [route('individuals.index')];
        
        $pagesize = [25, 50, 75, 100, 125];
        
        $rows = array();
        $rows = $this->individual->latest()->get();
        $rows = $this->changeValue($rows);
        $rows = $this->__change_values($rows);

        $columnDefs = array(
            array('headerName'=>'BARANGAY','field'=> 'barangay_name', 'floatingFilter'=>false),
            array('headerName'=>'HOUSE NO.','field'=> 'house_no', 'floatingFilter'=>false),
            array('headerName'=>'HOUSEHOLD NO.','field'=> 'household_no', 'floatingFilter'=>false),
            array('headerName'=>'FAMILY NO.','field'=> 'family_id', 'floatingFilter'=>false),
            array('headerName'=>'INDIVIDUAL NO.','field'=> 'individual_no', 'floatingFilter'=>false),
            array('headerName'=>'NAME','field'=> 'name', 'floatingFilter'=>false),
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

        return view('pages.individuals.index', [
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'data' => $data,
            'pagesize' => $pagesize,
            'create' => "individuals.create",
            'title' => 'Individuals'
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
        $name = ['Individuals', 'Create'];
        $mode = [route('individuals.index'), route('individuals.create')];

        $this->audit_trail_logs('','','Creating new record','');

        $barangays = $this->barangay->active()->get();
        $houses = $this->house->active()->get();
        $households = $this->household->active()->get();
        $families = $this->family->active()->get();

        return view('pages.individuals.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Individuals',
            'barangays' => $barangays,
            'houses' => $houses,
            'households' => $households,
            'families' => $families,
            'ethnicity' => $this->ethnicity,
            'marital_status' => $this->marital_status,
            'relationship' => $this->relationship
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

        $data = $this->individual->create($validated)->id;

        $this->audit_trail_logs('', 'created', 'individual: '.$validated['individual_no'], $data);

        return redirect()
            ->route('individuals.index')
            ->with('success', 'You have successfully added: '.$validated['individual_no']);
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
        $data = $this->individual->findOrFail($id);
        $data['individual_no'] = @explode('-', $data['individual_no'])[4];

        $mode_action = 'update';
        $name = ['Individuals', 'Edit', $data->individual_no];
        $mode = [route('individuals.index'), route('individuals.edit', $id), route('individuals.edit', $id)];

        $this->audit_trail_logs('', '', 'individuals: '.$data->individual_no, $id);

        $barangays = $this->barangay->active()->get();
        $houses = $this->house->active()->get();
        $households = $this->household->active()->get();
        $family = $this->family->active()->get();

        return view('pages.individuals.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Individuals',
            'data' => $data,
            'barangays' => $barangays,
            'houses' => $houses,
            'households' => $households,
            'families' => $family,
            'ethnicity' => $this->ethnicity,
            'marital_status' => $this->marital_status,
            'relationship' => $this->relationship
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

        $data = $this->individual->findOrFail($id)->update($validated);

        $this->audit_trail_logs('', 'updated', 'individual: '.$validated['individual_no'], $id);

        return redirect()
            ->route('individuals.index')
            ->with('success', 'You have successfully updated: '.$validated['individual_no']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->individual->findOrFail($id);
        $data->deleted_by = auth()->user()->id;
        $data->save();

        $data->delete();

        $this->audit_trail_logs('', 'deleted', 'individual: '.$data->individual_no, $id);

        return redirect()
            ->route('individuals.index')
            ->with('success', 'You have successfully removed: '.$data->individual_no);
    }

    public function __change_values($rows){
        foreach ($rows as $key => $value) {
            if (Arr::exists($value, 'family_id')) {
                $family = $this->family->find($value['family_id']);
                $value['family_id'] = $family->family_no;
                $value['household_no'] = $family->household->household_no;
                $value['house_no'] = $family->household->house->house_no;
                $value['barangay_name'] = $family->household->house->barangay->barangay_name;
            }

            $value['name'] = $value['last_name'].', '.$value['first_name'].' '.$value['middle_name'];

            if (Arr::exists($value, 'suffix') && $value['suffix'] != "") {
                $value['name'] = $value['name'].' '.$value['suffix'];
            }
        }

        return $rows;
    }

    public function validator(Request $request)
    {
        $id = $this->safeInputs($request->input('id'));
        $family_no = $this->family->find($this->safeInputs($request->input('family_id')))->family_no;

        $input = [
            'family_id' => $this->safeInputs($request->input('family_id')),
            'individual_no' => $family_no.'-'.$this->safeInputs($request->input('individual_no')),
            'last_name' => $this->safeInputs($request->input('last_name')),
            'first_name' => $this->safeInputs($request->input('first_name')),
            'middle_name' => $this->safeInputs($request->input('middle_name')),
            'suffix' => $this->safeInputs($request->input('suffix')),
            'gender' => $this->safeInputs($request->input('gender')),
            'birthdate' => $this->safeInputs($request->input('birthdate')),
            'ethnicity' => $this->safeInputs($request->input('ethnicity')),
            'relationship' => $this->safeInputs($request->input('relationship')),
            'marital_status' => $this->safeInputs($request->input('marital_status')),
            'status' => $this->safeInputs($request->input('status'))
        ];

        $rules = [
            'family_id' => 'required|numeric',
            'individual_no' => 'required|string|unique:individuals,individual_no,'.$id,
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'gender' => 'required|string',
            'birthdate' => 'required|string',
            'ethnicity' => 'required|string',
            'relationship' => 'required|string',
            'marital_status' => 'required|string',
            'status' => 'required|numeric',
        ];

        $messages = [];

        $customAttributes = [
            'family_id' => 'family no.',
            'individual_no' => 'individual no.',
            'first_name' => 'first name',
            'middle_name' => 'middle name',
            'last_name' => 'last name',
            'suffix' => 'suffix',
            'gender' => 'gender',
            'birthdate' => 'birthdate',
            'ethnicity' => 'ethnicity',
            'relationship' => 'relationship',
            'marital_status' => 'marital status',
            'status' => 'status',
        ];

        $validator = Validator::make($input, $rules, $messages,$customAttributes);
        return $validator->validate();
    } 
}
