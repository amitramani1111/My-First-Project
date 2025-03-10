<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    // show users
    public function showUsers()
    {
        return view('admins');
    }

    // Users Report
    public function userreport(Request $request)
    {
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
                $q->where('role', 'like', '%' . $role . '%');
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
                "action" => "<button type='button' data-id='$value->id' data-name='$value->name' data-role='$value->role' data-email='$value->email' data-mobile='$value->mobile' class='btn btn-ghost-primary edit-user-button' data-bs-toggle='modal' data-bs-target='#user-modal'><i class='fa-solid fa-pen-to-square'></i></button>
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
    }

    // Add User
    public function addUser(Request $request)
    {
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
                return response()->json([
                    'success' => true,
                    "message" => 'User Added Successfully.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    "message" => "User Can't Added."
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                "message" => "Invalid Action."
            ]);
        }
    }

    // Update User
    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'role' => ['required'],
            'email' => ['required', 'email', 'lowercase', 'unique:admins,email,' . $request->id],
            'mobile' => ['required', 'numeric', 'unique:admins,mobile,' . $request->id]
        ]);

        $user = DB::table('admins')->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'role' => $request->role,
                'email' => $request->email,
                'mobile' => $request->mobile
            ]);

        if ($user) {
            return response()->json(['message' => 'User Updated Successfully.']);
        } else {
            return response()->json(['message' => 'Failed to Update User.']);
        }
    }

    // Soft Delete User
    public function deleteUser($id)
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
