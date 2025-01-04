@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/">Pembayaran</a></li>
                        <li class="breadcrumb-item active">Akun</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="s-body text-center mt-3">

                        @if (Auth::user()->image != '')
                            <img src="{{ asset('profile_pic/thumb/'.Auth::user()->image) }}" alt="avatar"  class="rounded-circle img-fluid" style="width: 150px;">
                        @else
                            <img src="{{ asset('assets/images/avatar7.png') }}" alt="avatar"  class="rounded-circle img-fluid" style="width: 150px;">
                        @endif

                        <h5 class="mt-3 pb-0">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-1 fs-6">{{ Auth::user()->designation }}</p>
                        <div class="d-flex justify-content-center mb-2">
                            <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="btn btn-primary">Atur Gambar</button>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow mb-4">
                    <form action="" method="post" id="userForm" name="userForm">
                        <div class="card-body  p-4">
                            <h3 class="fs-4 mb-1">Akun Saya</h3>
                            <div class="mb-4">
                                <label for="" class="mb-2">Nama</label>
                                <input type="text" name="name" id="name" placeholder="Nama lengkap" class="form-control" value="{{ $user->name }}">
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Email</label>
                                <input type="text" name="email" id="email"  placeholder="Email" class="form-control" value="{{ $user->email }}" readonly>
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Penamaan</label>
                                <input type="text" name="designation" id="designation"  placeholder="Penamaan Panggilan" class="form-control" value="{{ $user->designation }}">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Handphone</label>
                                <input type="text" name="mobile" id="mobile" placeholder="Nomor Handphone" class="form-control" value="{{ $user->mobile }}">
                            </div>
                        </div>
                        <div class="card-body  p-4">
                            <h3 class="fs-4 mb-1">Informasi XENDIT</h3>
                            <span style="font-size: 12px; color: #5a5963;">Pastikan Anda memasukan Informasi yang Benar.</span>
                            <span style="font-size: 12px; color: #5a5963;">Jika Pengaturan Whitelist "Aktif" Silakan masukan IP 103.102.0.154</span>

                            <div class="mb-4">
                                <label for="" class="mb-2">API Key</label>
                                <input type="text" name="keyprivate" id="keyprivate" placeholder="Biarkan default jika Key bawaan dengan Program ini" class="form-control" value="{{ $user->keyprivate }}">
                                <span style="font-size: 8px; color: #ff0000;">Biarkan "default" jika bawaan dengan Program ini, Atau Silakan masukan API Key jika ingin menggunakan Akun Xendit Lain.</span>
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">ID Bisnis/ ID User</label>
                                <input type="text" name="foruserid" id="foruserid" placeholder="Biarkan default jika User ID Platform atau yang Utama" class="form-control" value="{{ $user->foruserid }}">
                                <span style="font-size: 8px; color: #ff0000;">Biarkan "default" jika tidak menggunakan XenPlatform atau SubAccount.</span>

                                <p></p>
                            </div>
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>

                <div class="card border-0 shadow mb-4">
                    <form action="" method="post" id="changePasswordForm" name="changePasswordForm">
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-1">Ganti sandi</h3>
                            <div class="mb-4">
                                <label for="" class="mb-2">Sandi lama</label>
                                <input type="password" name="old_password" id="old_password" placeholder="Masukan kata sandi lama" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Sandi baru</label>
                                <input type="password" name="new_password" id="new_password" placeholder="Kata sandi baru" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Konfirmasi sandi</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmasi kata sandi baru" class="form-control">
                                <p></p>
                            </div>
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
$("#userForm").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: '{{ route("account.updateProfile") }}',
        type: 'get',
        dataType: 'json',
        data: $("#userForm").serializeArray(),
        success: function(response) {

            if(response.status == true) {

                $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#email").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                window.location.href="{{ route('account.profile') }}";

            } else {
                var errors = response.errors;

                if (errors.name) {
                    $("#name").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.name)
                } else {
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.email) {
                    $("#email").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.email)
                } else {
                    $("#email").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }
            }

        }
    });
});


$("#changePasswordForm").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: '{{ route("account.updatePassword") }}',
        type: 'post',
        dataType: 'json',
        data: $("#changePasswordForm").serializeArray(),
        success: function(response) {

            if(response.status == true) {

                $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#email").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                window.location.href="{{ route('account.profile') }}";

            } else {
                var errors = response.errors;

                if (errors.old_password) {
                    $("#old_password").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.old_password)
                } else {
                    $("#old_password").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.new_password) {
                    $("#new_password").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.new_password)
                } else {
                    $("#new_password").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.confirm_password) {
                    $("#confirm_password").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.confirm_password)
                } else {
                    $("#confirm_password").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }
            }

        }
    });
});
</script>
@endsection
