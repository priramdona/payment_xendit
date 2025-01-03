@extends('front.layouts.app')

@section('main')
    <div class="container">

        <div class="card text-center p-5">
            <div class="icon mb-4">
                <i class="fa fa-clock-o"></i>
            </div>
            <h1 class="text-primary mb-3">Mohon Maaf</h1>
            <p class="mb-4">
                Layanan ini saat ini sedang dalam proses pengajuan izin. Kami sedang bekerja keras untuk memenuhi semua persyaratan dan memastikan layanan ini dapat tersedia untuk Anda secepat mungkin.
            </p>
        </div>

        <div class="card p-5">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <h3 class="mb-4">Wujudkan Kebaikan Kita</h3>
                <p class="mb-4">Fasilitas www.donasikita.com sedang dalam proses, Meliputi :</p>
                <p><i class="fa fa-check text-primary me-3"></i>Pengembangan Web Application</p>
                <p><i class="fa fa-check text-primary me-3"></i>Pengajuan Payment Gateway</p>
                <p><i class="fa fa-check text-primary me-3"></i>Integrasi Payment Gateway</p>
                <p><i class="fa fa-check text-primary me-3"></i>Pengajuan Server www.donasikita.com</p>
                <p><i class="fa fa-check text-primary me-3"></i>Integrasi Server www.donasikita.com</p>
                <p><i class="fa fa-hourglass-start text-warning me-3"></i>Legalitas organisasi</p>
                <p><i class="fa fa-times text-danger me-3"></i>Izin (PUB)</p>
                <p><i class="fa fa-times text-danger me-3"></i>Kantor offline organisasi</p>
                <p><i class="fa fa-times text-danger me-3"></i>Pengajuan kerjasama dengan Yayasan</p>

                <div class="text-center">
                    {{-- <a href="{{ route('payment.method', 'DONASI') }}" class="btn btn-primary py-3 px-5 mt-3" href="">Dukung kami</a> --}}
                    <a href="{{ route('payment.method', 'DONASI') }}" class="btn btn-primary mt-4 px-5">Dukung Kami</a>
                </div>

            </div>
        </div>
        <div class="container py-5">
            <div class="row justify-content-center">
                {{-- <div class="col-md-8"> --}}
                    <div class="card p-4 text-center">
                        <h1 class="text-primary mb-3">Terima Kasih atas Dukungan Anda!</h1>
                        <p class="mb-4">
                            Kami ingin mengucapkan rasa terima kasih yang sebesar-besarnya atas dukungan luar biasa yang telah Anda berikan kepada kami.
                        </p>
                        <div class="mb-4">
                            <h3 class="mb-4">10 besar pendukung terbaik</h3>
                            <p>Akan menerima <b>Penawaran Khusus</b> yang telah kami siapkan dengan sepenuh hati untuk menjadi bagian dari www.donasikita.com.</p>
                        </div>
                        <div class="mb-4">

                            <h3 class="mb-4">30 besar dukungan terbaik</h3>
                            <p>Akan mendapatkan <b>Akses Khusus</b> yang dirancang untuk memberikan pengalaman istimewa.</p>
                        </div>
                        <p>
                            Ini adalah cara kami untuk menunjukkan betapa berarti dukungan Anda bagi kami. Pantau terus email atau pesan Anda untuk detail lebih lanjut!
                        </p>

                    <div class="text-center">
                        <a href="{{ route('home') }}" class="btn btn-primary mt-4 px-5">Kembali ke Beranda</a>
                    </div>

                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


    @endsection
