
@extends('front.layouts.app')

@section('main')

<section class="section-4 bg-2">
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        @if ($jobId === 'DONASI' || $jobId === 'SAVING')
                            <li class="breadcrumb-item"><a  href="{{ route('payment.method', $jobId) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Kembali ke Metode Pembayaran</a></li>
                        @else
                            <li class="breadcrumb-item"><a   href="{{ route('payment.job', $jobId) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Kembali ke Metode Pembayaran</a></li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="payment-options">
        <div class="container mt-4">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h4><strong>Pembayaran</strong></h4>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endforeach
                    @endif
                    <form id="paymentForm"  action="{{ route('payment-process', $jobId) }}" method="POST">
                        @csrf <!-- Token CSRF untuk keamanan -->
                        <!-- Input Nominal Lain -->
                        {{-- <div class="mb-3">
                            <label for="foruserid" class="form-label"><strong>ID User / ID Bisnis</strong></label>
                            <input type="text" class="form-control" id="foruserid" name="foruserid" placeholder="User Id">
                        </div> --}}

                        <!-- Metode Pembayaran -->
                        <div class="mb-3">
                            @php
                                $typeMapping = [
                                    'QR_CODE' => 'QR Code',
                                    'EWALLET' => 'E-Wallet',
                                    'VIRTUAL_ACCOUNT' => 'Virtual Account',
                                ];

                                $formattedType = $typeMapping[$paymentMethods->type] ?? $paymentMethods->type;
                            @endphp
                            <label class="form-label"><strong>Metode Pembayaran {{ $formattedType }}</strong></label>
                            <div class="d-flex justify-content-between align-items-center border p-3 rounded">
                                <div class="d-flex align-items-center">
                                <img src="{{ $paymentMethods->image_url }}" alt="Pembayaran" class="payment-icon">
                                    <span><strong>{{ ($paymentMethods->name) }}</strong></span>
                                </div>
                                <input type="hidden" name="payment_method" value="{{ ($paymentMethods->id) }}">
                                <a href="{{ route('payment.method', $jobId) }}" class="btn btn-link text-decoration-none">Ganti</a>
                            </div>
                            @if ($paymentMethods->code === 'OVO')

                            <div class="mb-3">
                                <label for="customAmount" class="form-label"><strong>Masukan nomor OVO Anda</strong></label>
                                <input type="number" class="form-control" id="nomorovo" name="nomorovo" placeholder="Masukkan nomor OVO">
                            </div>
                            @endif
                        </div>
                        <!-- Input Nominal Lain -->
                        @if ($paymentMethods->type === 'VIRTUAL_ACCOUNT')
                        <div class="mb-3">
                            <label for="customva" class="form-label"><strong>Nama Virtual Account</strong></label>
                            <input type="text" class="form-control" id="customva" name="customva" placeholder="Masukkan nama Custom VA">
                        </div>
                        @endif
                        <!-- Pilihan Nominal Donasi -->
                        <div class="mb-3">
                            <label class="form-label"><strong>Nominal Pembayaran</strong></label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary btn-nominal" onclick="selectNominal(this, 10000)">Rp 10.000</button>
                                <button type="button" class="btn btn-outline-primary btn-nominal" onclick="selectNominal(this, 20000)">Rp 20.000</button>
                                <button type="button" class="btn btn-outline-primary btn-nominal" onclick="selectNominal(this, 25000)">Rp 25.000</button>
                                <button type="button" class="btn btn-outline-primary btn-nominal" onclick="selectNominal(this, 50000)">Rp 50.000</button>
                                <button type="button" class="btn btn-outline-primary btn-nominal" onclick="selectNominal(this, 100000)">Rp 100.000</button>
                                <button type="button" class="btn btn-outline-primary btn-nominal" onclick="selectNominal(this, 300000)">Rp 300.000</button>
                            </div>
                            <input type="hidden" name="nominal" id="nominal" value=""> <!-- Hidden input untuk nominal -->
                        </div>

                        <!-- Input Nominal Lain -->
                        <div class="mb-3">
                            <label for="customAmount" class="form-label"><strong>Nominal Lain</strong></label>
                            <input type="number" class="form-control" id="customAmount" placeholder="Masukkan nominal lain" oninput="updateCustomNominal(this.value)">
                        </div>

                        <!-- Form Informasi Donatur -->
                            <div class="mb-3">
                                @if (Auth::check())
                                    {{-- <a class="btn btn-light btn-square me-3" href="" onclick="saveJob({{ $job->id }});"><i class="far fa-heart text-primary"></i></a> --}}
                                    <a>Informasi sebagai : {{ auth::user()->name }} </a>
                                @else
                            <!-- Form Informasi Donatur -->

                                    <label class="form-label"><strong>Isi form di bawah</strong></label>
                                    <input type="text" name="name" id="name" class="form-control mb-2" placeholder="Nama">
                                    <input type="email" name="email" id="email" class="form-control mb-2" placeholder="Email">
                                    <input type="number" name="phone" id="phone" class="form-control mb-2" placeholder="Nomor HP (opsional)">

                                @endif
                                <div class="form-check" hidden>
                                    <input type="checkbox" name="hide_name" id="hide_name" >
                                    <label class="form-check-label" for="hide_name">Sembunyikan nama saya</label>
                                </div>
                            </div>

                        <!-- Pesan -->
                        <div class="mb-3">
                            <label for="message" class="form-label"><strong>Pesan untuk program ini (opsional)</strong></label>
                            <textarea name="message" class="form-control" id="message" rows="3"></textarea>
                        </div>

                        <!-- Tombol Konfirmasi -->
                        <div class="d-grid">
                            <button type="submit" id="submitButton" class="btn btn-success btn-lg">
                                Lanjutkan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
    @endsection

    @section('customJs')
    <script>
        $(document).ready(function () {
            $('#paymentForm').on('submit', function (e) {
                // Nonaktifkan tombol setelah ditekan
                $('#submitButton').prop('disabled', true).text('Memproses...');

                // Opsional: Jika Anda ingin menghentikan submit untuk pengujian, uncomment baris di bawah
                // e.preventDefault();
            });
        });
    </script>
@endsection
