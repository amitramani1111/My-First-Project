@extends('layouts.masterlayout')

@section('title', 'Customers'){{-- Title --}}
@section('page_heading', 'Customers') {{-- Page Heading --}}

@section('modal_button')
    <button type="button" id="add-customer" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
        data-bs-target="#customer-modal">
        <i class="fa-solid fa-plus"></i>
        Add new Customer
    </button>
@endsection

@section('modal')
    <div class="modal modal-blur fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <i class="fa-solid fa-triangle-exclamation icon mb-2 text-danger icon-lg"></i>
                    <h3>Are you sure?</h3>
                    <div class="text-secondary">Do you really want to remove this Customer Detailes?</div>
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
                                <button type="button" id="modal-btn" class="btn btn-danger btn-4 w-100"
                                    data-bs-dismiss="modal">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Modal -->
    <div class="modal modal-blur fade" id="customer-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form action="" id="customer-form" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="title"></span> Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Customer Name --}}
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="hidden" name="id" class="id" id="id">
                            <input type="text" value=""
                                class="form-control username @error('username') is-invalid @enderror" name="username"
                                placeholder="Your Name">
                            @error('username')
                                <span class="error">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="row">
                            {{-- Email --}}
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" value=""
                                        class="form-control email @error('email') is-invalid @enderror" name="email"
                                        placeholder="Your Email">
                                    @error('email')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Gender --}}
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select gender @error('gender') is-invalid @enderror" name="gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    @error('gender')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- Mobile --}}
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Number</label>
                                    <input type="text" value="" pattern="[0-9]*"
                                        class="form-control mobile @error('mobile') is-invalid @enderror"
                                        placeholder="Your Mobile Number" name="mobile" maxlength="10">
                                    @error('mobile')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Date of Birth --}}
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" value="{{date('Y-m-d')}}"
                                        class="form-control dob @error('dob') is-invalid @enderror" name="dob">
                                    @error('dob')
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
                {{-- DataTable --}}
                <table id="example" class="table text-center table-vcenter card-table table-striped display">
                    <thead>
                        <tr>
                            <th class="text-center" width="%">id</th>
                            <th class="text-center" width="%">name</th>
                            <th class="text-center" width="%">expenses</th>
                            <th class="text-center" width="%">gender</th>
                            <th class="text-center" width="10%">dob</th>
                            <th class="text-center" width="%">email</th>
                            <th class="text-center" width="%">mobile</th>
                            <th class="text-center" width="%">action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Customer Ajax DataTable --}}
    <script>
        var table = null;
        $(document).ready(function () {

            table = $('#example').DataTable({
                'processing': false,
                'serverSide': true,
                'serverMethod': 'POST',
                'dataType': 'json',
                'ajax': '{{route('customerreport')}}',
                columns: [
                    { data: "id" },
                    { data: "username" },
                    { data: "expense" },
                    { data: "gender" },
                    { data: "dob" },
                    { data: "email" },
                    { data: "mobile" },
                    { data: "action" }
                ],
                'layout': {
                    topStart: {
                        buttons: ['pageLength', 'copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });


    </script>

    {{-- Customer Delete in Modal --}}
    <script>
        $(document).ready(function () {
            const deleteForm = $("#delete-form");
            $(document).on("click", '.delete-btn', function () {
                const id = $(this).data('id');
                window.customer_id = id;
            });

            $("#modal-btn").click(function () {
                $.ajax({
                    url: `{{url('deletecustomer/${window.customer_id}')}}`,
                    type: "get"
                }).then(res => {
                    if (res?.success) {
                        table.ajax.reload();
                        $('#delete-modal').modal('hide');
                        $('.modal-backdrop').remove();
                    }
                })
            })
        });
    </script>

    {{-- Add-Update Modal Data Show --}}
    <script>
        $(document).on("click", ".edit-customer-button", function (e) {
            const { id, username, email, mobile, dob, gender } = $(this).data();

            $(".id").val(id);
            $(".username").val(username);
            $(".gender").val(gender);
            $(".mobile").val(mobile);
            $(".email").val(email);
            $(".dob").val(dob);
            $('.title').text('Edit');
            $('input').val('');

            // Update Data Using Ajax
            $('#customer-form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{url('update')}}',
                    data: $('#customer-form').serialize(),
                    type: 'post',
                    success: function (res) {
                        sweetAlert('success', res?.message)
                        table.ajax.reload();
                        $('input').val('');
                        $("#customer-modal form").trigger("reset");
                    },
                })
            });

        });

        // Add Customer Query
        $(document).on('click', '#add-customer', function (e) {

            e.preventDefault();

            $('.title').text('New');
            $('input').val('');

            $("#customer-modal form").trigger("reset");

            // Insert New Data Using Ajax
            $('#customer-form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{url('addcustomer')}}',
                    data: $('#customer-form').serialize(),
                    type: 'post',
                    success: function (res) {
                        table.ajax.reload();
                        sweetAlert('success', res?.message)
                        $('input').val('');
                        $("#customer-modal form").trigger("reset");
                        $('#customer-modal').modal('hide');
                        $('.modal-backdrop').remove();
                    },
                })
            });
        });
    </script>

@endsection