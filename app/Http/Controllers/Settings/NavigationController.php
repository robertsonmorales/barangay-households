<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Str;

use App\Models\Navigation;
use Carbon\Carbon;

class NavigationController extends Controller
{
    protected $navs;
    public function __construct(Navigation $navs){
        $this->nav = $navs;
    }

    public function validator(Request $request)
    {
        $type = $this->safeInputs($request->input('type'));
        $mode_main = ($type == "main") ? "nullable" : "required";
        $mode_single = ($type == "single") ? "nullable" : "required";

        $input = [
            'name' => $this->safeInputs($request->input('name')),
            'controller' => $this->safeInputs($request->input('controller')),
            'route' => $this->safeInputs($request->input('route')),
            'icon' => $this->safeInputs($request->input('icon')),
            'type' => $this->safeInputs($request->input('type')),
            'status' => $this->safeInputs($request->input('status')),

            'sub_name' => $this->safeInputs($request->input('sub_name')),
            'sub_route' => $this->safeInputs($request->input('sub_route')),
            'sub_controller' => $this->safeInputs($request->input('sub_controller')),
            'sub_order' => $this->safeInputs($request->input('sub_order')),
        ];

        $rules = [
            'name' => 'required|string|max:255|unique:navigations,nav_name,'.$this->safeInputs($request->input('id')),
            'controller' => $mode_main.'|max:100|unique:navigations,nav_icon,'.$this->safeInputs($request->input('id')),
            'route' => $mode_main.'|max:50|unique:navigations,nav_route,'.$this->safeInputs($request->input('id')),
            'icon' => 'required|max:50|unique:navigations,nav_icon,'.$this->safeInputs($request->input('id')),
            'type' => 'required|string',
            'status' => 'required|numeric',

            'sub_name.*' => $mode_single.'|string',
            'sub_route.*' => $mode_single.'|string',
            'sub_controller.*' => $mode_single.'|string',
            'sub_order.*' => $mode_single.'|string',
        ];

        $messages = [];

        $customAttributes = [
            'name' => 'name',
            'controller' => 'controller',
            'route' => 'route',
            'icon' => 'icon',
            'type' => 'type',
            'status' => 'status',

            'sub_name' => 'sub-name',
            'sub_route' => 'sub-route',
            'sub_controller' => 'sub-controller',
            'sub_order' => 'sub-order',
        ];                

        $validator = Validator::make($input, $rules, $messages,$customAttributes);
        return $validator->validate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $name = ['Navigations'];
        $mode = [route('navigations.index')];

        $pagesize = [25, 50, 75, 100, 125];
    
        $rows = array();        
        $rows = $this->nav->whereIn('nav_type', ['main', 'single'])->latest()->get();
        $rows = $this->changeValue($rows);

        $columnDefs = array(
            array('headerName'=>'NAME','field'=>'nav_name', 'floatingFilter'=> false),
            array('headerName'=>'TYPE','field'=>'nav_type', 'floatingFilter'=> false),
            array('headerName'=>'ROUTE','field'=>'nav_route', 'floatingFilter'=> false),
            array('headerName'=>'CONTROLLER','field'=>'nav_controller', 'floatingFilter'=> false),
            array('headerName'=>'STATUS','field'=>'status', 'floatingFilter'=>false),
            // array('headerName'=>'CREATED BY','field'=>'created_by', 'floatingFilter'=>false),
            // array('headerName'=>'UPDATED BY','field'=>'updated_by', 'floatingFilter'=>false),
            // array('headerName'=>'CREATED AT','field'=>'created_at', 'floatingFilter'=>false),
            // array('headerName'=>'UPDATED AT','field'=>'updated_at', 'floatingFilter'=>false)
        );

        $data = json_encode(array(
            'column' => $columnDefs,
            'rows' => $rows
        ));        

        $this->audit_trail_logs('','','','');

        return view('pages.navigations.index', [ 
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'data' => $data,
            'pagesize' => $pagesize,
            'create' => "navigations.create",
            'title' => 'Navigations'
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
        $name = ['Navigations', 'Create'];
        $mode = [route('navigations.index'), route('navigations.create')];

        $this->audit_trail_logs('','','Creating new record','');

        return view('pages.navigations.create', [            
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Navigations'
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

        $getLastOrder = $this->nav->whereIn('nav_type', ['main', 'single'])->where('status', 1)->latest('nav_order')->first();

        $type = $validated['type'];
        $controller = $this->adjustModelController($validated['controller']);
        
        $nav = $this->nav->create([
            'nav_type' => $validated['type'],
            'nav_name' => $validated['name'],
            'nav_controller' => ($validated['type'] == "main") ? null : $controller,
            'nav_route' => $this->adjustRoute($validated['route']),
            'nav_icon' => $validated['icon'],
            'nav_order' => $getLastOrder['nav_order'] + 1,
            'status' => $validated['status'],
            'created_by' => Auth::id(),
            'created_at' => now()
        ]);

        $parent_id = $nav->id;

        if($validated['type'] == "single"){
            $model = $this->adjustModelController($validated['name']);
            $this->generateNavigationFiles($model, $controller, $type, '');
        }else if ($validated['type'] == "main"){
            $rows = $this->safeInputs($request->input('rows'));

            for ($i=0; $i < $rows; $i++) {
                $sub_name = $validated['sub_name'][$i];
                $sub_controller = $this->adjustModelController($validated['sub_controller'][$i]);
                $sub_route = $this->adjustRoute($validated['sub_route'][$i]);
                $sub_order = $validated['sub_order'][$i];

                $navName = $this->adjustModelController($validated['name']);
                $model = $this->adjustModelController($sub_name);
                
                $sub_nav = $this->nav->insert([
                    'nav_type' => 'sub',
                    'nav_name' => $sub_name,
                    'nav_controller' => $navName.'\\'.$sub_controller,
                    'nav_route' => $sub_route,
                    'nav_icon' => 'circle',
                    'nav_suborder' => $sub_order,
                    'nav_childs_parent_id' => $parent_id,
                    'status' => 1,
                    'created_by' => Auth::id(),
                    'created_at' => now()
                ]);

                $this->generateNavigationFiles($model, $sub_controller, $type, $navName);
            }
        }

        $this->audit_trail_logs('', 'created', 'navigation: '.$validated['name'], $nav['id']);

        return redirect()->route('navigations.index')->with('success', 'You have successfully added '.$validated['name']);

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
        $data = $this->nav->findOrFail($id);
        $sub = @$this->nav->where(array(
            'nav_type' => 'sub',
            'nav_childs_parent_id' => $data->id,
            'status' => 1,
        ))->get();

        $rows = @$sub->count();
    
        $mode_action = 'update';
        $name = ['Navigations', 'Edit', $data->name];
        $mode = [route('navigations.index'), route('navigations.edit', $id), route('navigations.edit', $id)];

        $this->audit_trail_logs('', '', 'navigations: '.$data->name, $id);

        return view('pages.navigations.create', [
            'mode' => $mode_action,
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Navigations',
            'data' => $data,
            'sub' => $sub,
            'rows' => $rows,
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
        $data = $this->nav->findOrFail($id);
        $data->delete();
        $this->audit_trail_logs('', 'deleted', 'navigation '.$data->nav_name, $id);
        
        return redirect()->route('navigations.index')
            ->with('success', 'You have successfully removed '.$data->nav_name);
    }

    public function adjustModelController($string){
        return str_replace(' ', '', ucwords(Str::singular($string)));
    }

    public function adjustRoute($string){
        return str_replace(' ', '_', strtolower(Str::plural($string)));
    }
}
