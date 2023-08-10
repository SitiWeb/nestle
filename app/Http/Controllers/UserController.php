<?php

namespace App\Http\Controllers;
use App\Mail\AdminAccessRequestEmail;
use App\Mail\AccessRequestEmail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::with('role')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);

        // Create the user
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->role_id = $validatedData['role'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update the user's basic information
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role_id = $validatedData['role'];
        $user->save();

        // Update the password if provided
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function request_login(){
        return view('users.request_login');
    }

    public function request_admin(){
        return view('users.request_admin');
    }

    public function request_login_action(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'position' => 'required',
            'office' => 'required',
            'email' => 'required|email',
        ]);

        // Send an email to the admin
        Mail::to('roberto@sitiweb.nl')->send(new AccessRequestEmail($validatedData));

        // Perform additional actions if needed

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Access request submitted successfully!');
    }

    public function request_admin_action(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'position' => 'required',
            'office' => 'required',
            'email' => 'required|email',
        ]);

        // Send an email to the admin
        Mail::to('roberto@sitiweb.nl')->send(new AdminAccessRequestEmail($validatedData));

        // Perform additional actions if needed

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Access request submitted successfully!');
    }
}
