<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function showLogin()
    {
        if (Auth::guard('admins')->check()) {
            return redirect()->route('index');
        }
        return view('admin.auth.login');
    }

    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function showOtp()
    {
        return view('admin.auth.otp');
    }

    public function showUsers()
    {
        return view('admins');
    }


    // Register 
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'mobile' => 'required|size:10|unique:admins,mobile',
            'password' => 'required|confirmed',
            'role' => 'required'
        ]);

        $admin = Admin::create($data);

        if ($admin) {
            return redirect()->route('login');
        }
    }

    // Login
    public function login(Request $request)
    {

        $login = $request->input('email');
        $admin = Admin::where('email', $login)->orWhere('mobile', $login)->first();

        if (!$admin) {
            return redirect()->back()->withErrors(['email' => 'Please Enter Valid Email or Number']);
        }

        $request->validate([
            'password' => 'required',
            // 'password' => 'required|same:password|min:8',
        ]);

        if (
            Auth::guard('admins')->attempt(['email' => $admin->email, 'password' => $request->password]) ||
            Auth::guard('admins')->attempt(['mobile' => $admin->mobile, 'password' => $request->password])
        ) {
            Auth::loginUsingId($admin->id);
            return redirect()->route('index');
        } else {
            return redirect()->back()->withErrors(['password' => 'Please Enter Valid Password']);
        }

        // $validation = $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required'
        // ]);


        // if (Auth::guard('admins')->attempt($validation)) {
        //     $request->session()->regenerate();
        //     return redirect()->route('index');
        // } else {
        //     return redirect()->route('login');
        // }
    }


    // Go to Home Page
    public function indexPage()
    {
        if (Auth::guard('admins')->check()) {
            return view('index');
        } else {
            return redirect()->route('login');
        }
    }

    // Go to Logout Page
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Users Report
    public function userreport(Request $request)
    {

        if (Auth::guard('admins')->check()) {
            $draw = $request->input('draw', 1);
            $perPage = $request->input('length'); // Records per page
            $start = $request->input('start', 0); // Records per page
            $search = $request->input('search.value', ''); // Search keyword
            $columnIndex = $request->input('order.0.column'); //column index
            $columnName = $request->input('columns.' . $columnIndex . '.data'); //Column Name
            $SortOrder = $request->input('order.0.dir', ''); // asc or desc
            $role = $request->input('role'); // Role


            if (empty($role)) {
                $whereFunction = function ($q) use ($search) {
                    $q->where('email', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('mobile', 'like', '%' . $search . '%');
                };
            } else {
                $whereFunction = function ($q) use ($role) {
                    $q->where('role ', 'like', '%' . $role . '%');
                };
            }

            $filterRecordsCount = Admin::query()
                ->where($whereFunction)
                ->count();

            // Get the data based on search and pagination
            $query = Admin::query()
                ->where($whereFunction)
                ->orderBy($columnName, $SortOrder)
                ->take($perPage)
                ->skip($start)
                ->get();

            // Apply pagination
            $data = [];

            foreach ($query as $key => $value) {
                $data[] = [
                    "id" => $value->id,
                    "role" => $value->role,
                    "name" => $value->name,
                    "email" => $value->email,
                    "mobile" => $value->mobile,
                    "action" => "<button type='button' data-id='$value->id' data-name='$value->name' data-email='$value->email' data-mobile='$value->mobile' class='btn btn-ghost-primary edit-user-button' data-bs-toggle='modal' data-bs-target='#user-modal'><i class='fa-solid fa-pen-to-square'></i></button>
                                 <button type='button' data-bs-toggle='modal' data-bs-target='#delete-modal' data-id='$value->id' class='btn btn-ghost-danger delete-btn'><i class='fa-solid fa-trash'></i></button>",
                ];
            }

            // Prepare the response data for DataTables
            $response = [
                'draw' => intval($draw),
                'recordsTotal' => Admin::count(),
                'recordsFiltered' => $filterRecordsCount,
                'data' => $data,
            ];

            return response()->json($response);
        } else {
            return redirect()->route('login');
        }

    }

    // Add User
    public function addUser(Request $request)
    {
        if (Auth::guard('admins')->check()) {

            $validate = $request->validate([
                'name' => 'required',
                'email' => 'required|email|lowercase|unique:admins,email',
                'role' => 'required',
                'mobile' => 'required|numeric|unique:admins,mobile',
                'password' => 'required|confirmed'
            ]);

            if ($validate) {
                $data = new Admin;

                $data->name = $request->name;
                $data->email = $request->email;
                $data->role = $request->role;
                $data->mobile = $request->mobile;
                $data->password = $request->password;

                if ($data->save()) {
                    return response()->json(['message' => 'User Added Successfully.']);
                } else {
                    return response()->json(['message' => 'Failed to add User.']);
                }
            }

            // $customer = Customer::create($validate);

            // if ($customer) {
            //     session()->flash('message', 'Customer Added Successfully.');
            //     return redirect()->route('customers');
            // }
        } else {
            return redirect()->route('login');
        }
    }

    // Update User
    public function updateUser(Request $request)
    {
        if (Auth::guard('admins')->check()) {
            $request->validate([
                'name' => ['required'],
                'email' => ['required', 'email', 'lowercase', 'unique:admins,email,' . $request->id],
                'mobile' => ['required', 'numeric', 'unique:admins,mobile,' . $request->id]
            ]);

            $user = DB::table('admins')->where('id', $request->id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile
                ]);

            if ($user) {
                return response()->json(['message' => 'User Updated Successfully.']);
            } else {
                return response()->json(['message' => 'Failed to Update User.']);
            }

        } else {
            return redirect()->route('login');
        }
    }

    // Soft Delete User
    public function trashUser($id)
    {
        $delete = Admin::find($id)->delete();

        if ($delete) {
            return response()->json([
                'success' => true,
                "message" => 'User Detailes Deleted Successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                "message" => 'Failed to delete User.'
            ]);
        }

    }
}
