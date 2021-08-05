<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator, Arr;

use App\Models\Family;
use App\Models\Household;
use App\Models\House;
use App\Models\Barangay;

class FamilyController extends Controller
{
    protected $family, $household, $house, $barangay;
    public function __construct(Family $family, Household $household, House $house, Barangay $barangay){
        config('app.timezone', 'Asia/Manila');

        $this->family = $family;
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
        $name = ['Families'];
        $mode = [route('families.index')];
        
        $pagesize = [25, 50, 75, 100, 125];
        
        $rows = array();
        $rows = $this->family->latest()->get();
        $rows = $this->changeValue($rows);
        $rows = $this->__change_values($rows);

        $columnDefs = array(
            array('headerName'=>'BARANGAY','field'=> 'barangay_name', 'floatingFilter'=>false),
            array('headerName'=>'HOUSE NO.','field'=> 'house_no', 'floatingFilter'=>false),
            array('headerName'=>'HOUSEHOLD NO.','field'=> 'household_id', 'floatingFilter'=>false),
            array('headerName'=>'FAMILY NO.','field'=> 'family_no', 'floatingFilter'=>false),
            array('headerName'=>'FAMILY NAME','field'=> 'family_name', 'floatingFilter'=>false),
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

        return view('pages.families.index', [
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'data' => $data,
            'pagesize' => $pagesize,
            'create' => "families.create",
            'title' => 'Families'
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
        $name = ['Families', 'Create'];
        $mode = [route('families.index'), route('families.create')];

        $this->audit_trail_logs('','','Creating new record','');

        $barangays = $this->barangay->active()->get();
        $houses = $this->house->active()->get();
        $households = $this->household->active()->get();

        return view('pages.families.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Families',
            'barangays' => $barangays,
            'houses' => $houses,
            'households' => $households
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

        $data = $this->family->create($validated)->id;

        $this->audit_trail_logs('', 'created', 'family: '.$validated['family_no'], $data);

        return redirect()
            ->route('families.index')
            ->with('success', 'You have successfully added: '.$validated['family_no']);
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
        $data = $this->family->findOrFail($id);
        $data['family_no'] = @explode('-', $data['family_no'])[3];

        $house_id = $this->household->find($data['household_id'])->house->id;
        $barangay_id = $this->house->find($house_id)->barangay->id;

        $mode_action = 'update';
        $name = ['Families', 'Edit', $data->family_no];
        $mode = [route('families.index'), route('families.edit', $id), route('families.edit', $id)];

        $this->audit_trail_logs('', '', 'families: '.$data->family_no, $id);

        $barangays = $this->barangay->active()->get();
        $houses = $this->house->active()->get();
        $households = $this->household->active()->get();

        return view('pages.families.form', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Families',
            'data' => $data,
            'barangays' => $barangays,
            'houses' => $houses,
            'households' => $households,

            'barangay_id' => $barangay_id,
            'house_id' => $house_id
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

        $data = $this->family->findOrFail($id)->update($validated);

        $this->audit_trail_logs('', 'updated', 'family: '.$validated['family_no'], $id);

        return redirect()
            ->route('families.index')
            ->with('success', 'You have successfully updated: '.$validated['family_no']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->family->findOrFail($id);
        $data->deleted_by = auth()->user()->id;
        $data->save();

        $data->delete();

        $this->audit_trail_logs('', 'deleted', 'family: '.$data->family_no, $id);

        return redirect()
            ->route('families.index')
            ->with('success', 'You have successfully removed: '.$data->family_no);
    }

    public function __change_values($rows){
        foreach ($rows as $key => $value) {
            if (Arr::exists($value, 'household_id')) {
                $household = $this->household->find($value['household_id']);
                $value['household_id'] = $household->household_no;

                $house = $household->house;
                $value['house_no'] = $house->house_no;

                $barangay = $this->barangay->find($house->barangay_id);
                $value['barangay_name'] = $barangay->barangay_name.' ('.$barangay->barangay_code.')';
            }
        }

        return $rows;
    }

    public function validator(Request $request)
    {

        $id = $this->safeInputs($request->input('id'));
        $household_no = $this->household->find($this->safeInputs($request->input('household_id')))->household_no;

        $input = [
            'household_id' => $this->safeInputs($request->input('household_id')),
            'family_no' => $household_no.'-'.$this->safeInputs($request->input('family_no')),
            'family_name' => $this->safeInputs($request->input('family_name')),
            'have_cell_radio_tv' => $this->safeInputs($request->input('have_cell_radio_tv')),
            'have_vehicle' => $this->safeInputs($request->input('have_vehicle')),
            'have_bicycle' => $this->safeInputs($request->input('have_bicycle')),
            'have_pedicab' => $this->safeInputs($request->input('have_pedicab')),
            'have_motorcycle' => $this->safeInputs($request->input('have_motorcycle')),
            'have_tricycle' => $this->safeInputs($request->input('have_tricycle')),
            'have_four_wheeled' => $this->safeInputs($request->input('have_four_wheeled')),
            'have_truck' => $this->safeInputs($request->input('have_truck')),
            'have_motor_boat' => $this->safeInputs($request->input('have_motor_boat')),
            'have_boat' => $this->safeInputs($request->input('have_boat')),
            'status' => $this->safeInputs($request->input('status'))
        ];

        $rules = [
            'household_id' => 'required|numeric',
            'family_no' => 'required|string|unique:families,family_no,'.$id,
            'family_name' => 'required|string',
            'have_cell_radio_tv' => 'nullable|string',
            'have_vehicle' => 'nullable|string',
            'have_bicycle' => 'nullable|string',
            'have_pedicab' => 'nullable|string',
            'have_motorcycle' => 'nullable|string',
            'have_tricycle' => 'nullable|string',
            'have_four_wheeled' => 'nullable|string',
            'have_truck' => 'nullable|string',
            'have_motor_boat' => 'nullable|string',
            'have_boat' => 'nullable|string',
            'status' => 'required|numeric',
        ];

        $messages = [];

        $customAttributes = [
            'household_id' => 'household no.',
            'family_no' => 'family no.',
            'family_name' => 'family name',
            'have_cell_radio_tv' => 'land ownership',
            'have_vehicle' => 'vehicle',
            'have_bicycle' => 'bicycle',
            'have_pedicab' => 'pedicab',
            'have_motorcycle' => 'motorcycle',
            'have_tricycle' => 'tricycle',
            'have_four_wheeled' => 'four_wheeled',
            'have_truck' => 'truck',
            'have_motor_boat' => 'motor_boat',
            'have_boat' => 'boat',
            'status' => 'status',
        ];

        $validator = Validator::make($input, $rules, $messages,$customAttributes);
        return $validator->validate();
    } 
}
