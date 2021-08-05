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
            array('headerName'=>'HOUSEHOLD NO.','field'=> 'household_id', 'floatingFilter'=>false),
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
            'families' => $families
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
}
