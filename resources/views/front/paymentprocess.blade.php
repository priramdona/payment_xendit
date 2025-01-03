@extends('front.layouts.app')

@section('main')

<section class="section-4 bg-2">

    <div class="payment-options">
        <div class="container mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3"><strong>Silakan melakukan Pembayaran</strong></h5>
                    <div>
                        <div class="d-flex justify-content-between align-items-center border p-3 rounded mb-3">
                            <div>
                                <h6 class="mb-1">{{ $transaction['payment_method']['name'] }}</h6>
                            </div>
                            <div>
                                <img src="{{ $transaction['payment_method']['image_url'] }}" alt="Permata" class="payment-icon">
                            </div>
                        </div>
                        <!-- Informasi Metode Pembayaran -->
                        @if ( $transaction['payment_method']['action'] === 'account')
                        <div class="border p-3 rounded mb-3 d-flex flex-column align-items-center justify-content-center text-center" id="methodva" name="methodva">
                            <div>
                                <span class="text-muted">Atas Nama</span>
                            </div>
                            <div>
                                <h6 class="text-success">{{ $transaction['name_response'] }}</h6>
                            </div>
                            <div>

                                <span class="text-muted">Nomor Virtual Account</span>
                            </div>
                            <div>
                                {{-- <strong class="d-block text-primary">{{ $transaction['virtual_account'] }}</strong> --}}
                                <h2 class="text-success">{{ $transaction['value_response'] }}</h2>
                                <button class="btn btn-outline-primary btn-sm mt-2" onclick="copyToClipboard('{{ $transaction['value_response'] }}')">Salin</button>
                            </div>
                        </div>
                        @elseif ( $transaction['payment_method']['action'] === 'input')
                        <!-- Informasi Metode Pembayaran -->
                        <div class="border p-3 rounded mb-3 d-flex flex-column align-items-center justify-content-center text-center" id="methodinput" name="methodinput">

                            <div>
                                {{-- <strong class="d-block text-primary">{{ $transaction['virtual_account'] }}</strong> --}}
                                <h4 class="text-success">Silakan cek tagihan dan selesaikan proses pembayaran pada akun Anda</h4>
                            </div>
                        </div>
                        @elseif ( $transaction['payment_method']['action'] === 'qrcode')

                        <!-- Informasi Metode Pembayaran -->
                        <div class="border p-3 rounded mb-3 d-flex flex-column align-items-center justify-content-center text-center" id="methodqr" name="methodqr">
                            <div>
                                <span class="text-muted">Scan Kode QR untuk proses pembayaran</span>
                                <br>
                            </div>
                            <div>
                                <img id="qrCodeImage" src="data:image/png;base64, {{ $transaction['value_response'] }}" alt="QR Code" class="img-fluid img-thumbnail" style="width: 200px; height: 200px;background-color: white;">
                            </div>
                        </div>

                        @endif
                    </div>
                    <!-- QR Code -->

                    <!-- Total Donasi -->
                    <div class="border p-3 rounded mb-3">
                        <h6>Total Donasi</h6>
                        <h5 class="text-success">Rp {{ number_format($transaction['nominal_response'], 0, ',', '.') }}</h5>
                    </div>
                    <!-- Batas Waktu -->
                    <div class="border p-3 rounded mb-3">
                        <h6>Batas Waktu Pembayaran</h6>
                        <span class="text-danger">{{ $transaction['expired_response'] }}</span>
                    </div>

                    <!-- Tombol Cek Status -->
                    {{-- <div class="d-grid mb-3">
                        <button class="btn btn-primary">Cek Status Pembayaran</button>
                    </div> --}}


                    <!-- Tombol Donasi Penggalangan Lain -->
                    {{-- <div class="d-grid mt-4">
                        <a href="{{ route('jobs') }}" class="btn btn-outline-primary">Donasi ke penggalangan lain</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
<script>
    //  var startautosave;

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Nomor Virtual Account berhasil disalin!');
        }, function(err) {
            alert('Gagal menyalin teks.');
        });
    }

       // Interval untuk mengecek status pembayaran
       let startautosave = setInterval(function() {
        $.ajax({
            url: "{{ url('/check-payment') }}/",
            method: "GET",
            data: {
                'payment_request_id': '{{ $transaction['createPaymentId'] }}',
            },
            dataType: 'json',
            success: function(paymentinfo) {
                console.log("checkPayment");
                if (paymentinfo.status == "Paid") {
                    clearInterval(startautosave);
                    Swal.fire({
                        title: "Pembayaran Berhasil",
                        text: "Pembayaran telah berhasil. Silakan cek Transaksi AKUN Xendit Anda.",
                        icon: 'success',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            newtransaction();
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }, 1000);

    // Fungsi untuk menangani transaksi baru
    function newtransaction() {
        clearInterval(startautosave);
        window.location.href = '{{ route('home') }}';
    }
</script>
