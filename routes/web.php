<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/mail', [MailController::class, 'sentMail'])->name('sendMail');

Route::controller(AdminController::class)->group(function () {
    Route::get('/home', 'indexPage')->name('index'); // Redirect Index
    Route::get('/login', 'showLogin')->name('login'); // Login Page
    Route::post('/loginMatch', 'login')->name('loginMatch'); // Match Login
    Route::get('/otp', 'showOtp')->name('otp'); // Otp 

    Route::get('/register', 'showRegister')->name('register'); // Register Page
    Route::post('/registerSave', 'register')->name('registerSave'); // Register Save

    Route::get('/logout', 'logout')->name('logout'); // Logout

    Route::middleware(['IsloggedIn:admin'])->group(function () {
        Route::get('/users', 'showUsers')->name('showUsers'); // Show Users Page
        Route::post('/userreport', 'userreport')->name('userreport'); // Show Users
        Route::post('/adduser', 'addUser')->name('addUser'); // Add User
        Route::post('/updateuser', 'updateUser')->name('updateUser'); // Update User
        Route::get('/deleteuser/{id}', 'deleteUser')->name('deleteUser'); // Delete User
    });
});



Route::middleware(['IsloggedIn:admin'])->group(function () {


    //Customers Route
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customers', 'showCustomers')->name('customers');
        Route::post('/update', 'updateCustomer')->name('updateCustomer');
        Route::post('/addcustomer', 'addCustomer')->name('addCustomer');
        Route::get('/deletecustomer/{id}', 'deleteCustomer')->name('deleteCustomer');
        Route::post('/customerreport', 'customerreport')->name('customerreport');
    });


    // Expenses Route
});
Route::middleware(['IsloggedIn:admin,reader'])->group(function () {
    Route::controller(ExpenseController::class)->group(function () {
        Route::get('/expenses', 'showExpenses')->name('showExpenses');
        Route::post('/addexpense', 'addExpense')->name('addExpense');
        Route::post('/updateexpense', 'editExpense')->name('updateExpense');
        Route::post('/trashexpense/{id}', 'trashExpense')->name('trashExpense');
        Route::post('/expensesreport', 'expensesreport')->name('expensesreport');
        Route::get('/selectcustomerreport', 'selectCustomer')->name('selectCustomer');
    });
});



// Invoice Route
Route::controller(InvoiceController::class)->group(function () {
    Route::get('/invoice/{id}', 'showInvoice')->name('invoice');
});