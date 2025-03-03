@extends('layouts.masterlayout')

@section('title', 'Expenses'){{-- Title --}}
@section('page_heading', 'Expenses'){{-- Page Heading --}}

@section('modal_button')
    <button type="button" id="add-expense" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
        data-bs-target="#expense-modal">
        <i class="fa-solid fa-plus"></i>
        Add new Expense
    </button>
@endsection

@section('modal')
    <form action="javascript:void(0)" id="delete-form" method="POST">
        @method('post')
        @csrf
        <div class="modal modal-blur fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <i class="fa-solid fa-triangle-exclamation icon mb-2 text-danger icon-lg"></i>
                        <h3>Are you sure?</h3>
                        <div class="text-secondary">Do you really want to remove this Expense Detaiels?</div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <a href="#" class="btn btn-3 w-100" data-bs-dismiss="modal">
                                        Cancel
                                    </a>
                                </div>
                                <div class="col">
                                    <button type="submit" id="modal-btn" class="btn btn-danger btn-4 w-100">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Start Modal -->
    <div class="modal modal-blur fade" id="expense-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form action="" id="expense-form" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="title"></span> Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Select Customer --}}
                        <div class="mb-3" id="size">
                            <label class="form-label select-label">Select Customer</label>
                            <input type="hidden" name="id" class="id">
                            <select
                                class="select2 form-select form-control customer_id @error('customer_id') is-invalid @enderror"
                                name="customer_id" id="select-customer">
                                <option value="" disabled selected>Select Customer</option>
                            </select>
                            @error('customer_id')
                                <span class="error">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="row">
                            {{-- Item --}}
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label">Item</label>
                                    <input type="text" value=""
                                        class="form-control item @error('item') is-invalid @enderror" name="item"
                                        placeholder="Your Item">
                                    @error('item')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Payment Type --}}
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Payment Type</label>
                                    <select class="form-select payment_type @error('payment_type') is-invalid @enderror"
                                        name="payment_type">
                                        <option value="debit">Debit</option>
                                        <option value="credit">Credit</option>
                                    </select>
                                    @error('payment_type')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- Amount --}}
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" value=""
                                        class="form-control amount @error('amount') is-invalid @enderror" name="amount"
                                        placeholder="Amount">
                                    @error('amount')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Date --}}
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" value="{{date('Y-m-d')}}"
                                        class="form-control date @error('date') is-invalid @enderror" name="date">
                                    @error('date')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Footer Buttons --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content') {{-- Page Body --}}

    <div class="col-12">
        <div class="card">
            <div class="table-responsive m-3">
                <div class="row">
                    {{-- Select Customer --}}
                    <div class="col-md-4 mb-3">
                        <div class="form-label">Select Customer</div>
                        <select class="select2 form-select form-control" name="customer_id" id="customer">
                            <option value="" disabled selected>Select Customer</option>
                            <option value="0" selected>All</option>
                        </select>
                        @error('customers')
                            <span class="error">{{$message}}</span>
                        @enderror
                    </div>
                    {{-- From Date --}}
                    <div class="col-md-3">
                        <label class="form-label">From</label>
                        <input type="date" id="from-date" value="" class="form-control" name="date">
                    </div>
                    {{-- To Date --}}
                    <div class="col-md-3">
                        <label class="form-label">To</label>
                        <input type="date" id="to-date" value="" class="form-control" name="date">
                    </div>
                    {{-- Filter Button --}}
                    <div class="col-md-2 mt-5">
                        <button type="submit" class="btn btn-success search-btn px-5 mx-5">Submit</button>
                    </div>
                    {{-- Data Table --}}
                    <div class="col-md-12 col-sm-4">
                        <table id="example" class="table text-center table-vcenter card-table table-striped display">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">id</th>
                                    <th class="text-center">customer name</th>
                                    <th class="text-center" width="13%">expense name</th>
                                    <th class="text-center" width="10%">date</th>
                                    <th class="text-center" width="13%">payment type</th>
                                    <th class="text-center" width="8%">amout(₹)</th>
                                    <th class="text-center" width="8%">status</th>
                                    <th class="text-center" width="11%">action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Expenses DataTable --}}
    <script>
        var table = null;
        $(document).ready(function () {
            table = $('#example').DataTable({
                'processing': false,
                'serverSide': true,
                'serverMethod': 'POST',
                'dataType': 'json',
                'ajax': {
                    url: '{{route('expensesreport')}}',
                    data: function (e) {
                        e.customer_id = $("#customer").val();
                        e.from_date = $("#from-date").val();
                        e.to_date = $("#to-date").val();
                        return e;
                    }
                },
                columns: [
                    { data: "id" },
                    { data: "customer_id" },
                    { data: "item" },
                    { data: "date" },
                    { data: "payment_type" },
                    { data: "amount" },
                    { data: "status" },
                    { data: "action" }
                ],
                'layout': {
                    topStart: {
                        buttons: ['pageLength', 'copy', 'csv', 'excel', 'pdf', 'print']
                    },
                },
            });

            $(document).on('click', '.search-btn', function () {
                table.ajax.reload();
            });

        });
    </script>

    {{-- Expense Soft Delete --}}
    <script>
        $(document).ready(function () {
            const deleteForm = $("#delete-form");
            $(document).on("click", '.delete-btn', function () {
                const id = $(this).data('id');

                deleteForm.attr({
                    "action": `{{url('trashexpense/${id}')}}`
                })

            })
        });
    </script>

    {{-- Add-Update Modal Data Insert --}}
    <script>
        $(document).on("click", ".edit-expense-btn", function (e) {
            const { id, customer_id, item, payment_type, amount, date } = $(this).data();

            $(".id").val(id);
            $(".customer_id").val(customer_id);
            $(".item").val(item);
            $(`.payment_type`).val(payment_type);
            $(".amount").val(amount);
            $(".date").val(date);
            $('.title').text('Edit');
            $(".customer_id").prop("disabled", true);
        });

        // Update Data Using Ajax
        $('#expense-form').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{route('updateExpense')}}',
                data: $('#expense-form').serialize(),
                type: 'post',
                success: function (res) {
                    sweetAlert('success', res?.message);
                    $('input').val('');
                    table.ajax.reload();
                    $("#expense-modal form").trigger("reset");
                },
            })
        });

        // Add expense Query
        $(document).on('click', '#add-expense', function (e) {
            e.preventDefault();
            $('.title').text('New');
            $(".customer_id").prop("disabled", false);
            $('input').val('');

            // Insert New Data Using Ajax
            $('#expense-form').on('submit', function (c) {
                c.preventDefault();
                $.ajax({
                    url: '{{route('addExpense')}}',
                    data: $('#expense-form').serialize(),
                    type: 'post',
                    success: function (res) {
                        $('input').val('');
                        table.ajax.reload();
                        sweetAlert('success', res?.message)
                        $("#expense-modal form").trigger("reset");
                    },
                })
            });
        });
    </script>
@endsection