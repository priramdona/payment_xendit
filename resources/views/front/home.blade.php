@extends('front.layouts.app')

@section('main')
{{-- <section class="section-0 lazy d-flex bg-image-style dark align-items-center "  class="" data-bg="{{ asset('assets/images/banner5.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-12 col-xl-8">
                <h1>Find your dream</h1>
                <p>Thounsands of jobs available.</p>
                <div class="banner-btn mt-5"><a href="#" class="btn btn-primary mb-4 mb-sm-0">Explore Now</a></div>
            </div>
        </div>
    </div>
</section> --}}

{{-- <section class="section-1 py-5 "> --}}
    <div class="container-xxl py-5">
        <div class="container">
            <br>
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Kategori</h1>
            <div class="row g-4">

                {{-- <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s"> --}}
                    <a class="cat-item rounded p-4  wow fadeInUp" href="{{ route('payment.method', 'DONASI') }}">
                        <i class="fa fa-3x fa-heart text-primary mb-4"></i>
                        <h6 class="mb-3">Donasi</h6>
                        <span style="font-size: 8px; color: #5a5963;">Bersedekah / Amal kasih /Daan </span>

                        <span style="font-size: 10px; color: #5a5963;">Terkumpul</span>
                        <p class="mb-0">Rp. {{ number_format($amountDonasi, 2, ',', '.') }}</p>

                    </a>
                {{-- </div> --}}
                {{-- <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s"> --}}
                    <a class="cat-item rounded p-4  wow fadeInUp" href="{{route('account.createJob')}}">
                        <i class="fa fa-3x fa-bullhorn text-primary mb-4"></i>
                        <h6 class="mb-3">Galang Donasi</h6>
                        <p class="mb-0">{{ $countProgram }} Program berlangsung</p>
                        <span style="font-size: 10px; color: #5a5963;">Terkumpul</span>
                        <p class="mb-0">Rp. {{ number_format($amountProgram, 2, ',', '.') }}</p>
                    </a>
                {{-- </div> --}}
                <a class="cat-item rounded p-4  wow fadeInUp" href="{{ route('payment.method', 'SAVING') }}">
                    <i class="fa fa-3x fa-book text-primary mb-4"></i>
                    <h6 class="mb-3">Tabungan Donasi Siaga</h6>
                    <p class="mb-0">{{ $countSaving }} Bergabung</p>
                    <span style="font-size: 10px; color: #5a5963;">Terkumpul</span>
                    <p class="mb-0">Rp. {{ number_format($amountSaving, 2, ',', '.') }}</p>
                </a>
                <a class="cat-item rounded p-4  wow fadeInUp" href="{{route('underprocess')}}">
                    <i class="fa fa-3x fa-leaf text-primary mb-4"></i>
                    <h6 class="mb-3">Permohonan Donasi Siaga</h6>
                    <p class="mb-0">0 Tersalurkan</p>
                    <span style="font-size: 10px; color: #5a5963;">Total penyaluran</span>
                    <p class="mb-0">Rp. 0</p>
                </a>

                <a class="cat-item rounded p-4  wow fadeInUp" href="{{url('underprocess')}}">
                    <i class="fa fa-3x fa-balance-scale text-primary mb-4"></i>
                    <h6 class="mb-3">Zakat</h6>
                    <p class="mb-0">0 Yayasan terdaftar</p>
                </a>

                <a class="cat-item rounded p-4  wow fadeInUp" href="{{url('contacts')}}">
                    <i class="fa fa-3x fa-headset text-primary mb-4"></i>
                    <h6 class="mb-3">Hubungi Kami</h6>
                </a>
            </div>
        </div>
    </div>
{{-- </section> --}}

{{-- <section class="section-2 bg-2 py-5"> --}}
    <div class="container-xxl py-5">
        <div class="container">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="row g-0 about-bg rounded overflow-hidden">
                                <div class="col-6 text-start">
                                    <img class="img-fluid w-100" src="img/about-1.jpg">
                                </div>
                                <div class="col-6 text-start">
                                    <img class="img-fluid" src="img/about-2.jpg" style="width: 85%; margin-top: 15%;">
                                </div>
                                <div class="col-6 text-end">
                                    <img class="img-fluid" src="img/about-3.jpg" style="width: 85%;">
                                </div>
                                <div class="col-6 text-end">
                                    <img class="img-fluid w-100" src="img/about-4.jpg">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                                <h3 class="mb-4">Wujudkan Kebaikan Kita</h3>
                                <p class="mb-4">Fasilitas www.donasikita.com sedang dalam proses, Meliputi :</p>
                                <p><i class="fa fa-check text-primary me-3"></i>Pengembangan Web Application</p>
                                <p><i class="fa fa-check text-primary me-3"></i>Pengajuan Payment Gateway</p>
                                <p><i class="fa fa-check text-primary me-3"></i>Integrasi Payment Gateway</p>
                                <p><i class="fa fa-check text-primary me-3"></i>Pengajuan Server www.donasikita.com</p>
                                <p><i class="fa fa-check text-primary me-3"></i>Integrasi Server www.donasikita.com</p>
                                <p><i class="fa fa-check text-primary me-3"></i>Pengujian www.donasikita.com</p>
                                <p><i class="fa fa-hourglass-start text-warning me-3"></i>Legalitas organisasi</p>
                                <p><i class="fa fa-times text-danger me-3"></i>Izin (PUB)</p>
                                <p><i class="fa fa-times text-danger me-3"></i>Kantor offline organisasi</p>
                                <p><i class="fa fa-times text-danger me-3"></i>Pengajuan kerjasama dengan Yayasan</p>

                                    <a href="{{ route('payment.method', 'DONASI') }}" class="btn btn-primary py-3 px-5 mt-3" href="">Dukung kami</a>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- </section> --}}

{{-- <section class="section-3 py-5"> --}}
    @if ($featuredJobs->isNotEmpty())
    <div class="container-xxl">
        <h1 class="text-center mb-5">Program Donasi unggulan</h1>
        <div class="owl-carousel header-carousel position-relative">
            @foreach($featuredJobs as $featuredJob)
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="{{ $featuredJob->image_url ?? asset('images/default-banner.jpg') }}" alt="Program Donasi unggulan">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(43, 57, 64, .5);">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <h1 class="display-3 text-white animated slideInDown mb-4">{{ Str::words(strip_tags($featuredJob->title), $words=8, '...') }}</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-2">{{ Str::words(strip_tags($featuredJob->description), $words=12, '...') }}</p>
                                {{-- <a href="{{url('joblisting')}}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Donasi Sekarang</a> --}}
                                <a href="{{ route('jobDetail', $featuredJob->id) }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Selengkapnya</a>
                                <a href="{{ route('payment.job', $featuredJob->id) }}" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Donasi</a>
                                {{-- Progress Bar --}}
                                <div>
                                   <div class="progress mt-4 animated slideInUp" style="height: 20px;">

                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                            role="progressbar"
                                            style="width: {{ ($featuredJob->applications->sum('amount') / $featuredJob->salary) * 100 }}%;"
                                            aria-valuenow={{ $featuredJob->applications->sum('amount') }}
                                            aria-valuemin="0"
                                            aria-valuemax={{ $featuredJob->salary }}>
                                            {{ ($featuredJob->applications->sum('amount') / $featuredJob->salary) * 100 }}%
                                        </div>
                                    </div>
                                    <p class="fs-5 fw-medium text-white mb-4 pb-2">Terkumpul  <span style="font-weight: bold">{{ number_format($featuredJob->applications->sum('amount'), 2, ',', '.') }}</span> dari {{ number_format($featuredJob->salary, 2, ',', '.') }}</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

{{-- </section> --}}

{{-- <section class="section-3 bg-2 py-5"> --}}
    @if ($latestJobs->isNotEmpty())
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">

            <h2 class="text-center mb-5">Program Lainnya</h2>
            <div class="owl-carousel featured-carousel">
                @foreach ($latestJobs as $latestJob)

                <div class="featured-item bg-light rounded p-4">
                    <img class="img-fluid" src="{{ $latestJob->image_url ?? asset('images/default-banner.jpg') }}" alt="Program Donasi lainnya"  style="max-width: 350px;max-height: 197px;">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-10 col-lg-8">
                                    <p class="fs-5 fw-medium text-white mb-4 pb-2">{{ Str::words(strip_tags($latestJob->title), $words=8, '...') }}</p>
                                    <a class="btn btn-primary btn-donasi" href="{{ route('jobDetail', $latestJob->id) }}">Selengkapnya</a>
                                    <div class="progress mt-4 animated slideInUp" style="height: 20px;">


                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                            role="progressbar"
                                            style="width: {{ ($latestJob->applications->sum('amount') / $latestJob->salary) * 100 }}%;"
                                            aria-valuenow={{ $latestJob->applications->sum('amount') }}
                                            aria-valuemin="0"
                                            aria-valuemax={{ $latestJob->salary }}>
                                            {{ ($latestJob->applications->sum('amount') / $latestJob->salary) * 100 }}%
                                        </div>
                                    </div>
                                    <span style="font-size: 10px; ">Terkumpul {{ number_format($latestJob->applications->sum('amount'), 2, ',', '.') }} dari {{ number_format($latestJob->salary, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
    @endif

{{-- </section> --}}
<section class="section-3 bg-2 py-5">
    <div class="container-xxl py-0 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <h2 class="text-center mb-5">Dukungan 10 Besar</h2>
            <div class="owl-carousel testimonial-carousel">
                @if ($applications10->isNotEmpty())
                @foreach ($applications10 as $application)
                    <div class="testimonial-item bg-light rounded p-4">
                        <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                        <p>“{{ $application->message  }}”</p>
                        <div class="d-flex align-items-center">
                            @if ($application->user_id != '')
                                @if ($application->user->image != '')
                                    <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('profile_pic/thumb/'.$application->user->image) }}" style="width: 50px; height: 50px;">
                                @else
                                    <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('assets/images/avatar7.png') }}" style="width: 50px; height: 50px;">
                                @endif
                            @else
                                <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('assets/images/avatar7.png') }}" style="width: 50px; height: 50px;">
                            @endif
                            <div class="ps-3">
                                {{-- <h8 class="mb-1">{{ $application->name }}</h8> --}}
                                <h8 class="mb-1">{{ $application->hide_name == 1 ? 'Orang Baik' : $application->name }}</h8>
                                <p style="font-size: 12px; ">Donasi : Rp. {{ number_format($application->amount, 2, ',', '.') }}</p>
                                <span style="font-size: 12px; ">{{
                                    \Carbon\Carbon::parse($application->created_at)->diffInHours() < 24
                                        ? \Carbon\Carbon::parse($application->created_at)->diffForHumans()
                                        : \Carbon\Carbon::parse($application->created_at)->format('d M Y, H:i')
                                }}</span>

                            </div>
                        </div>
                    </div>
                @endforeach

                @else
                <div class="testimonial-item bg-light rounded p-4">
                    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                    <p>Belum ada donasi</p>
                </div>
            @endif
            </div>
        </div>
    </div>
<section>
<section class="section-3 bg-2 py-5">
    <div class="container-xxl py-0 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <h2 class="text-center mb-5">Doa dan Dukungan terbaru</h2>
            <div class="owl-carousel testimonial-carousel">
                @if ($applications->isNotEmpty())
                @foreach ($applications as $application)
                    <div class="testimonial-item bg-light rounded p-4">
                        <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                        <p>“{{ $application->message  }}”</p>
                        <div class="d-flex align-items-center">
                            @if ($application->user_id != '')
                                @if ($application->user->image != '')
                                    <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('profile_pic/thumb/'.$application->user->image) }}" style="width: 50px; height: 50px;">
                                @else
                                    <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('assets/images/avatar7.png') }}" style="width: 50px; height: 50px;">
                                @endif
                            @else
                                <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('assets/images/avatar7.png') }}" style="width: 50px; height: 50px;">
                            @endif
                            <div class="ps-3">
                                <h8 class="mb-1">{{ $application->hide_name == 1 ? 'Orang Baik' : $application->name }}</h8>
                                <p style="font-size: 12px; ">Donasi : Rp. {{ number_format($application->amount, 2, ',', '.') }}</p>
                                <span style="font-size: 12px; ">{{
                                    \Carbon\Carbon::parse($application->created_at)->diffInHours() < 24
                                        ? \Carbon\Carbon::parse($application->created_at)->diffForHumans()
                                        : \Carbon\Carbon::parse($application->created_at)->format('d M Y, H:i')
                                }}</span>

                            </div>
                        </div>
                    </div>
                @endforeach

                @else
                <div class="testimonial-item bg-light rounded p-4">
                    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                    <p>Belum ada donasi</p>
                </div>
            @endif
            </div>
        </div>
    </div>
</section>


@endsection
