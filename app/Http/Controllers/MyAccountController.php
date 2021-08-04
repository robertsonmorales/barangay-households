<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Rules\PasswordRequirement;
use App\Rules\CheckCurrentPassword;
use App\Rules\ValidateOldToNewPassword;
use App\Rules\ValidateCurrentToNewPassword;
use App\Rules\CheckCurrentEmailAddress;

use App\Models\User;

class MyAccountController extends Controller
{
    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function validator(Request $request){
        $input = [
            'profile_image' => $request->file('profile_image'),
            'first_name' => $this->safeInputs($request->input('first_name')),
            'last_name' => $this->safeInputs($request->input('last_name')),
            'email' => $this->safeInputs($request->input('email')),
            'username' => $this->safeInputs($request->input('username')),
            'contact_number' => $this->safeInputs($request->input('contact_number')),
            'address' => $this->safeInputs($request->input('address'))
        ];

        $rules = [
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp',
            'first_name' => 'required|string|max:55',
            'last_name' => 'required|string|max:55',
            'email' => 'nullable|string|max:50|email|unique:users,email,'.Auth::id(),
            'username' => 'required|string|max:30|unique:users,username,'.Auth::id(),
            'contact_number' => 'required|numeric|digits:11',
            'address' => 'required|string|max:255'
        ];

        $messages = [];

        $customAttributes = [
            'profile_image' => 'file',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 'email',
            'username' => 'username',
            'contact_number' => 'contact number',
            'address' => 'address'
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
        $name = ['Account Settings', 'Profile Information'];
        $mode = [route('account_settings.index'), route('account_settings.index')];

        $action_mode = 'update';
        $user = $this->user->find(Auth::user()->id);

        $this->audit_trail_logs('','','','');
        
        return view('pages.account_settings.profile_information.index', [
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Account Settings',
            'users' => $user,
            'mode' => $action_mode
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $validated = $this->validator($request);
        if($validated){
            $path = ('images/user_accounts');

            $data = $this->user->findOrFail($id);
            $data->update([
                'profile_image' => (is_null($validated['profile_image']))
                    ? $data->profile_image
                    : $this->uploadImage($validated['profile_image'], $path, '', ''),
                'profile_image_updated_at' => now(),
                'profile_image_expiration_date' => now()->addDays(60),
                'first_name' => $this->encrypts($validated['first_name']),
                'last_name' => $this->encrypts($validated['last_name']),
                'username' => $validated['username'],
                'contact_number' => $this->encrypts($validated['contact_number']),
                'address' => $validated['address'],
                'ip' => $this->ipAddress(),
                'updated_by' => Auth::id()
            ]);

            $this->audit_trail_logs('', 'updated', 'user_accounts: '.$validated['username'], $id);
            
            return back()->with('success', 'You have successfully updated your profile');
        }

        // return back()->withErrors($validated)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->audit_trail_logs('', 'deleted', 'user account: '.Auth::user()->username, $id);

        $delete = $this->user->findOrFail($id)->delete();
        if ($delete) {
            Auth::logout();
            return redirect('/login');
        }
    }

    public function password(){
        $name = ['Account Settings', 'Password'];
        $mode = [route('account_settings.index'), route('account_settings.password')];

        $this->audit_trail_logs('', '', '', '');

        return view('pages.account_settings.password.index', [
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Account Settings',
        ]);
    }

    public function passwordUpdate(Request $request){
        $request->validate([
            'current_password' => ['required', 'string', new CheckCurrentPassword],
            'password' => ['bail', 'required', 'string', 'unique:users,password,'.Auth::id(), 'confirmed', new PasswordRequirement, new ValidateOldToNewPassword, new ValidateCurrentToNewPassword],
            'password_confirmation' => ['required', 'string']
        ]);        
        
        $currentPassword = $request->get('current_password');
        $newPassword = $request->get('password');
        $user = $this->user->findOrFail(Auth::id())
        ->update([
            'password' => $this->hashMake($newPassword),
            'password_updated_at' => now(),
            'password_expiration_date' => now()->addDays(60),
            'old_password' => $this->hashMake($currentPassword)
        ]);

        $this->audit_trail_logs('', 'updated', 'user_accounts: password', Auth::id());

        Auth::logout();
        $request->session()->flush();
        return redirect('/login')->with('password-success','Your password is now updated.');
    }

    public function email(){
        $name = ['Account Settings', 'Email'];
        $mode = [route('account_settings.index'), route('account_settings.email')];

        $this->audit_trail_logs('', '', '', '');

        return view('pages.account_settings.email.index', [
            'breadcrumbs' => $this->breadcrumbs($name, $mode),
            'title' => 'Account Settings'
        ]);
    }

    public function emailUpdate(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email', 'unique:users,email,'.Auth::id().'', new CheckCurrentEmailAddress],
        ]);
        
        $newEmailAddress = $this->safeInputs($request->get('email'));
        $data = $this->user->findOrFail(Auth::id())->update([
            'email' => $this->encrypts($newEmailAddress),
            'email_updated_at' => now(),
            'updated_by' => Auth::id()
        ]);

        $this->audit_trail_logs('', 'updated', 'user_accounts: email', Auth::id());
        return back()->with('success', 'You have successfully updated your email');
    }

    public function deleteAccount(){
        $name = ['Account Settings', 'Delete Account'];
        $mode = [route('account_settings.index'), route('account_settings.delete_account')];

        $this->audit_trail_logs('', '', '', '');

        return view('pages.account_settings.delete_account.index', [
            'breadcrumbs' => $this->breadcrumbs($name, $mode),            
            'title' => 'Account Settings',
        ]);
    }

    public function changeProfile(Request $request){
        $image = $request->file('profile-image');
        if ($request->file('profile-image')->isValid()) {
            $username = Auth::user()->username;
            $userId = Auth::id();

            $publicFolder = public_path('images/user_profiles/'.$username.$userId);

            if (!file_exists($publicFolder)) {
                mkdir($publicFolder);
            }

            $data = $this->user->findOrFail($userId);
            $profileImage = $image->getClientOriginalName(); // returns original name
            $extension = $image->getclientoriginalextension(); // returns the file extension
            $newProfileImage = strtoupper(Str::random(20)).'.'.$extension;
            $image->move($publicFolder, $newProfileImage);

            $data->profile_image = $newProfileImage;
            $data->profile_image_updated_at = now();
            $data->profile_image_expiration_date = now()->addDays(15);
            $data->save();

            return back()->with('success', 'You have successfully updated your profile picture');

        }else{
            return back()->with('error', "Something wrong with the image, please try again..");
        }
    }
}
