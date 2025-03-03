<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function showExpenses()
    {
        $customers = DB::table('customers')->orderBy('username', 'asc')->get();
        return view('expenses', compact('customers'));
    }

    public function addExpense(Request $request)
    {
        $validate = $request->validate([
            'customer_id' => ['required'],
            'item' => ['required'],
            'date' => ['required'],
            'payment_type' => ['required'],
            'amount' => ['required', 'numeric']
        ]);

        if ($validate) {
            $data = new Expense;
            $data->customer_id = $request->customer_id;
            $data->item = $request->item;
            $data->date = $request->date;
            $data->payment_type = $request->payment_type;
            $data->amount = $request->amount;

            if ($data->save()) {
                return response()->json(['message' => 'Expense Added Successfully.']);
            } else {
                return response()->json(['message' => 'Failed to add Expense.']);
            }
        }
    }

    public function editExpense(Request $request)
    {
        $validate = $request->validate([
            'item' => ['required'],
            'date' => ['required'],
            'payment_type' => ['required'],
            'amount' => ['required', 'numeric']
        ]);

        $expense = DB::table('expenses')->where('id', $request->id)
            ->update([
                'item' => $request->item,
                'date' => $request->date,
                'payment_type' => $request->payment_type,
                'amount' => $request->amount
            ]);

        // $expense = Expense::find($request->id)
        //     ->update([
        //         'item' => $request->item,
        //         'date' => $request->date,
        //         'payment_type' => $request->payment_type,
        //         'amount' => $request->amount
        //     ]);

        if ($expense) {
            return response()->json(['message' => 'Expense Updated Successfully.']);
        } else {
            return response()->json(['message' => 'Failed to Update Expense.']);
        }
    }

    public function trashExpense($id)
    {
        Expense::find($id)->delete();
        session()->flash('message', 'Expense  Detailes Deleted Successfully.');
        return redirect()->route('showExpenses');
    }

    public function expensesreport(Request $request)
    {
        $draw = $request->input('draw', 1);
        $perPage = $request->input('length'); // Records per page
        $start = $request->input('start', 0); // Records per page
        $search = $request->input('search.value', ''); // Search keyword
        $columnIndex = $request->input('order.0.column'); //column index
        $columnName = $request->input('columns.' . $columnIndex . '.data'); //Column Name
        $SortOrder = $request->input('order.0.dir', ''); // asc or desc
        $customerName = $request->input('customer_id', ''); // Customer ID
        $fromDate = $request->input('from_date'); // From Date
        $toDate = $request->input('to_date'); // To Date


        if (empty($customerName) && empty($fromDate) && empty($toDate)) {
            $whereFunction = function ($q) use ($search) {
                $q->where('item', 'like', '%' . $search . '%')
                    ->orWhere('payment_type', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('customer_id', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%');
            };

        } else if (!empty($customerName) && (empty($fromDate) && empty($toDate))) {
            $whereFunction = function ($q) use ($customerName) {
                $q->where('customer_id', '=', $customerName);
            };
        } else if (empty($customerName && (!empty($fromDate) && !empty($toDate)))) {
            $whereFunction = function ($q) use ($fromDate, $toDate) {
                $q->where('date', '>=', $fromDate)
                    ->where('date', '<=', $toDate);
            };

        } else {
            $whereFunction = function ($q) use ($customerName, $fromDate, $toDate) {
                $q->where('customer_id', '=', $customerName)
                    ->where('date', '>=', $fromDate)
                    ->where('date', '<=', $toDate);
            };
        }


        $filterRecordsCount = Expense::query()
            ->where($whereFunction)
            ->count();


        // Get the data based on search and pagination
        $query = Expense::query()
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
                "customer_id" => $value->customer->username,
                "item" => $value->item,
                "date" => $value->date,
                "payment_type" => $value->payment_type,
                "amount" => $value->amount,
                "status" => $value->status,
                "action" => "<button type='button' data-id='$value->id' data-customer_id='$value->customer_id' data-customer_name='" . $value->customer->username . "' data-item='$value->item' data-date='$value->date' data-payment_type='$value->payment_type' data-amount='$value->amount' class='btn btn-ghost-primary edit-expense-btn' data-bs-toggle='modal' data-bs-target='#expense-modal'><i class='fa-solid fa-pen-to-square'></i></button>
                                 <a href='javascript:void();' class='delete-btn' data-bs-toggle='modal' data-bs-target='#delete-modal' data-id='$value->id'><button type='button' class='btn btn-ghost-danger'><i class='fa-solid fa-trash'></i></button></a>",
            ];
        }


        // Prepare the response data for DataTables
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => Expense::count(),
            'recordsFiltered' => $filterRecordsCount,
            'data' => $data,
        ];


        return response()->json($response);

    }

    public function selectCustomer(Request $request)
    {
        $customers['results'] = Customer::select(['id', 'username as text'])
            ->where(function ($q) use ($request) {

                if (!empty($request->q)) {
                    $q->where('username', 'like', '%' . $request->q . '%');
                }

            })
            ->get();
        return response()->json($customers);
    }
}
