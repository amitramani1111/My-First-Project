@extends('layouts.masterlayout')

@section('title', 'Admins'){{-- Title --}}
@section('page_heading', 'Admins') {{-- Page Heading --}}

{{-- Modal Button --}}
@section('modal_button')
    <button type="button" id="add-user" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
        data-bs-target="#user-modal">
        <i class="fa-solid fa-plus"></i>
        Add new User
    </button>
@endsection

@section('modal')
    {{-- Delete Modal --}}
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
                                <button type="button" class="btn btn-3 w-100" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                            <div class="col">
                                <button type="button" id="user-delete-btn" class="btn btn-danger btn-4 w-100"
                                    data-bs-dismiss="modal">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Modal -->
    <div class="modal modal-blur fade" id="user-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form action="" id="user-form" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="title"></span> User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- Name --}}
                            <div class="col-md-8" id="size">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="hidden" name="id" class="id">
                                    <input type="name" value=""
                                        class="form-control name @error('name') is-invalid @enderror" name="name"
                                        placeholder="Your Name">
                                    @error('name')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Role --}}
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label role">Role</label>
                                    <select class="form-select role @error('role') is-invalid @enderror" name="role">
                                        <option value="reader">Reader</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                    @error('role')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Profile Image --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="profile" class="form-label">Profile Image</label>
                                    <input type="file" name="profile" id="profile"
                                        class="form-control profile @error('profile') is-invalid @enderror">
                                    @error('profile')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- Email --}}
                            <div class="col-md-6">
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
                            {{-- Mobile --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" value="" maxlength="10" minlength="10"
                                        class="form-control mobile @error('mobile') is-invalid @enderror" name="mobile"
                                        placeholder="Your Mobile">
                                    @error('mobile')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- Password --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label password">Password</label>
                                    <input type="password" value=""
                                        class="form-control password @error('password') is-invalid @enderror"
                                        placeholder="Your Password" name="password">
                                    @error('password')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Confirm Password --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label conpassword">Confirm Password</label>
                                    <input type="password" value=""
                                        class="form-control conpassword @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Confirm Your Password" name="password_confirmation">
                                    @error('password_confirmation')
                                        <span class="error">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Footer Buttons --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto submit" data-bs-dismiss="modal">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

{{-- Page Body --}}
@section('content')
    {{-- Table --}}
    <div class="col-12">
        <div class="card">
            <div class="table-responsive m-3">
                {{-- Select Role --}}
                <div class="col-md-4 mb-3">
                    <div class="form-label">Select Role</div>
                    <select class="form-control role" name="role" id="role">
                        <option value="0" selected>All</option>
                        <option value="admin">Admin</option>
                        <option value="reader">Reader</option>
                    </select>
                    @error('role')
                        <span class="error">{{$message}}</span>
                    @enderror
                </div>
                {{-- DataTable --}}
                <table id="example" class="table text-center table-vcenter card-table table-striped display">
                    <thead>
                        <tr>
                            <th class="text-center" width="%">id</th>
                            <th class="text-center" width="%">name</th>
                            <th class="text-center" width="%">role</th>
                            <th class="text-center" width="%">email</th>
                            <th class="text-center" width="%">number</th>
                            <th class="text-center" width="%">action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- User Ajax DataTable --}}
    <script>
        var table = null;
        $(document).ready(function () {

            table = $('#example').DataTable({
                'processing': false,
                'serverSide': true,
                'serverMethod': 'POST',
                'dataType': 'json',
                'ajax': {
                    url: '{{route('userreport')}}',
                    data: function (e) {
                        e.role = $("#role").val();
                        return e;
                    }
                },
                columns: [
                    { data: "id" },
                    { data: "name" },
                    { data: "role" },
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

        $(document).on('change', '.role', function () {
            table.ajax.reload();
        });


    </script>

    {{-- Soft Delete User --}}
    <script>
        $(document).ready(function () {
            const deleteForm = $("#delete-form");
            $(document).on("click", '.delete-btn', function () {
                const id = $(this).data('id');
                window.id = id;
            });

            $("#user-delete-btn").click(function () {
                $.ajax({
                    url: `{{url('deleteuser/${window.id}')}}`,
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
        $(document).on("click", ".edit-user-button", function (e) {
            const { id, name, role, email, mobile } = $(this).data();

            $(".id").val(id);
            $(".name").val(name);
            $(".role").val(role);
            $(".mobile").val(mobile);
            $(".email").val(email);
            $('.title').text('Edit');
            $('.password').hide();
            $('.conpassword').hide();

            // Update Data Using Ajax
            $('#user-form').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('.submit').text('Please Wait').attr('disabled', 'disabled');
                $.ajax({
                    url: '{{url('updateuser')}}',
                    data: formData,
                    type: 'post',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        sweetAlert('success', res?.message)
                        table.ajax.reload();
                        $('input').val('');
                        $("#user-modal form").trigger("reset");
                    },
                })
            });

        });

        // Add User Query
        $(document).on('click', '#add-user', function (e) {

            e.preventDefault();

            $('.title').text('New');
            $('input').val('');
            $('.password').show();
            $('.conpassword').show();
            $('.role').show();
            $('#size').attr('class', 'col-md-8');
            $("#user-modal form").trigger("reset");

            // Insert New Data Using Ajax
            $('#user-form').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('.submit').text('Please Wait').attr('disabled', 'disabled');
                $.ajax({
                    url: '{{url('adduser')}}',
                    data: formData,
                    type: 'post',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        table.ajax.reload();
                        sweetAlert('success', res?.message);
                        $('input').val('');
                        $("#user-modal form").trigger("reset");
                        $('#user-modal').modal('hide');
                        $('.modal-backdrop').remove();
                    },
                    error: function (err) {
                        sweetAlert('error', err?.message);
                    }
                })
            });
        });
    </script>

@endsection
