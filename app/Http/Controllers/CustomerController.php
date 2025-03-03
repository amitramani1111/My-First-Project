<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    public function showCustomers()
    {
        return view('customers');
    }
    public function addCustomer(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required',
            'email' => 'required|email|lowercase|unique:customers,email',
            'gender' => 'required',
            'mobile' => 'required|numeric|unique:customers,mobile',
            'dob' => 'required|date'
        ]);

        if ($validate) {
            $data = new Customer;

            $data->username = $request->username;
            $data->email = $request->email;
            $data->gender = $request->gender;
            $data->mobile = $request->mobile;
            $data->dob = $request->dob;

            if ($data->save()) {
                return response()->json(['message' => 'Customer Added Successfully.']);
            } else {
                return response()->json(['message' => 'Failed to add Customer.']);
            }
        }

        // $customer = Customer::create($validate);

        // if ($customer) {
        //     session()->flash('message', 'Customer Added Successfully.');
        //     return redirect()->route('customers');
        // }
    }

    public function updateCustomer(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'email' => ['required', 'email', 'lowercase', 'unique:customers,email,' . $request->id],
            'gender' => ['required'],
            'mobile' => ['required', 'numeric', 'unique:customers,mobile,' . $request->id],
            'dob' => ['required', 'date']
        ]);

        $customer = DB::table('customers')->where('id', $request->id)
            ->update([
                'username' => $request->username,
                'email' => $request->email,
                'gender' => $request->gender,
                'mobile' => $request->mobile,
                'dob' => $request->dob
            ]);

        if ($customer) {
            return response()->json(['message' => 'Customer Updated Successfully.']);
        } else {
            return response()->json(['message' => 'Failed to Update Customer.']);
        }
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::find($id)->delete();

        if ($customer) {
            return response()->json([
                'success' => true,
                "message" => 'Customer Detailes Deleted Successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                "message" => 'Failed to delete customer.'
            ]);
        }
    }

    public function customerreport(Request $request)
    {

        $draw = $request->input('draw', 1);
        $perPage = $request->input('length'); // Records per page
        $start = $request->input('start', 0); // Records per page
        $search = $request->input('search.value', ''); // Search keyword
        $columnIndex = $request->input('order.0.column'); //column index
        $columnName = $request->input('columns.' . $columnIndex . '.data'); //Column Name
        $SortOrder = $request->input('order.0.dir', ''); // asc or desc

        $whereFunction = function ($q) use ($search) {
            $q->where('email', 'like', '%' . $search . '%')
                ->orWhere('username', 'like', '%' . $search . '%')
                ->orWhere('mobile', 'like', '%' . $search . '%');
        };

        $filterRecordsCount = Customer::query()
            ->where($whereFunction)
            ->count();

        // Get the data based on search and pagination
        $query = Customer::query()
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
                "username" => $value->username,
                "expense" => $value->Expense->sum('amount'),
                "gender" => $value->gender,
                "dob" => $value->dob,
                "email" => $value->email,
                "mobile" => $value->mobile,
                "action" => "<a href='/invoice/" . $value->id . "'><button class='btn btn-ghost-success'><i class='fa-solid fa-file-invoice'></i></button></a>
                                 <button type='button' data-id='$value->id' data-username='$value->username' data-email='$value->email' data-gender='$value->gender' data-mobile='$value->mobile' data-dob='$value->dob' class='btn btn-ghost-primary edit-customer-button' data-bs-toggle='modal' data-bs-target='#customer-modal'><i class='fa-solid fa-pen-to-square'></i></button>
                                 <a href='javascript:void();' class='delete-btn' data-bs-toggle='modal' data-bs-target='#delete-modal' data-id='$value->id'><button type='button' class='btn btn-ghost-danger'><i class='fa-solid fa-trash'></i></button></a>",
            ];
        }

        // Prepare the response data for DataTables
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => Customer::count(),
            'recordsFiltered' => $filterRecordsCount,
            'data' => $data,
        ];

        return response()->json($response);

    }
}
