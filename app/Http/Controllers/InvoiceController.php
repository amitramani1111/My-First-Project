<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Expense;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function showInvoice($id)
    {
        $data = Customer::find($id);
        $expenses = Expense::where('customer_id', $id)->get();
        return view('invoice', compact('data', 'expenses'));
    }
}
