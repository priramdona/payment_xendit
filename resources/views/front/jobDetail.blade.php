@extends('front.layouts.app')

@section('main')
<section class="section-4 bg-2">
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Kembali ke Program</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="container job_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                @include('front.message')
                <div class="card shadow border-0">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">

                                <div class="jobs_conetent">
                                    <a href="#">
                                        <h4>{{ $job->title }}</h4>
                                    </a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                        </div>
                                        <div class="location">
                                            <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                        </div>
                                    </div>

                                    <div class="pt-3 text-end">
                                        <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start ">

                                            <div class="d-flex mb-3">
                                                @if (Auth::check())
                                                    <a class="btn btn-light btn-square me-3" href="" onclick="saveJob({{ $job->id }});"><i class="far fa-bookmark text-primary"></i></a>
                                                    <a href="{{ route('payment.job', $job->id) }}"  class="btn btn-primary btn-donasi" href="">Kirim Donasi</a>
                                                    {{-- <a  onclick="applyJob({{ $job->id }})" class="btn btn-primary" href="">Donasi</a> --}}
                                                @else
                                                    <a class="btn btn-light btn-square me-3" href="{{ route('account.login') }}"><i class="far fa-bookmark text-primary"></i></a>
                                                    <a href="{{ route('payment.job', $job->id) }}"  class="btn btn-primary btn-donasi" href="">Kirim Donasi</a>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    <span style="font-size: 10px; ">Terkumpul  <span style="font-size: 12px;font-weight: bold ">Rp. {{ number_format($job->applications->where('status','Paid')->sum('amount'), 0, ',', '.') }}</span></span>
                                    <span style="font-size: 10px; ">Dari <span style="font-size: 12px;font-weight: bold ">Rp. {{  number_format($job->salary, 0, ',', '.') }}</span></span>

                                   <div class="progress" style="height: 10px;">
                                        <div class="progress-bar" role="progressbar" style="width:  {{ ($job->applications->where('status','Paid')->sum('amount') / $job->salary) * 100 }}%;"
                                            aria-valuenow={{ $job->applications->where('status','Paid')->sum('amount') }} aria-valuemin="0" aria-valuemax={{ $job->salary }}>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="jobs_right">
                                <div class="apply_now {{ ($count == 1) ? 'saved-job' : '' }}">
                                    <a class="heart_mark " href="javascript:void(0);" onclick="saveJob({{ $job->id }})"> <i class="fa fa-bookmark-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="single_wrap">
                            <h4>Cerita Penggalangan Dana</h4>
                            {!! nl2br($job->description) !!}
                        </div>

                        <div class="border-bottom"></div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Informasi Penggalangan Dana</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Di buat <span>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</span></li>
                                <li>Jumlah Team Penggalang <span>{{ $job->vacancy }}</span></li>


                                @if (!empty($job->salary))
                                <li>Target Dana <span>Rp. {{  number_format($job->salary, 0, ',', '.') }}</span></li>
                                @endif
                                @if (!empty($job->salary))
                                <li>Dana Terkumpul <span>Rp. {{ number_format($job->applications->where('status','Paid')->sum('amount'), 0, ',', '.') }} </span></li>
                                @endif
                                <li>Lokasi <span>{{ $job->location }}</span></li>
                                <li>Tipe <span> {{ $job->jobType->name }}</span></li>
                            </ul>
                            <p></p>

                            {{-- <span style="font-size: 10px; ">Terkumpul {{ number_format($job->applications->sum('amount'), 0, ',', '.') }} dari {{  number_format($job->salary, 0, ',', '.') }}</span> --}}
                            <span style="font-size: 10px; ">Terkumpul  <span style="font-size: 12px;font-weight: bold ">Rp. {{ number_format($job->applications->where('status','Paid')->sum('amount'), 0, ',', '.') }}</span></span>

                            <span style="font-size: 10px; ">Dari <span style="font-size: 12px;font-weight: bold ">Rp. {{  number_format($job->salary, 0, ',', '.') }}</span></span>

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width:  {{ ($job->applications->where('status','Paid')->sum('amount') / $job->salary) * 100 }}%;"
                                    aria-valuenow={{ $job->applications->where('status','Paid')->sum('amount') }} aria-valuemin="0" aria-valuemax={{ $job->salary }}>
                                </div>
                            </div>
                            <div class="pt-3 text-end">
                                <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start ">

                                    <div class="d-flex mb-3">
                                        @if (Auth::check())
                                            <a class="btn btn-light btn-square me-3" href="" onclick="saveJob({{ $job->id }});"><i class="far fa-bookmark text-primary"></i></a>
                                            <a href="{{ route('payment.job', $job->id) }}"  class="btn btn-primary btn-donasi" href="">Kirim Donasi</a>
                                            {{-- <a  onclick="applyJob({{ $job->id }})" class="btn btn-primary" href="">Donasi</a> --}}
                                        @else
                                            <a class="btn btn-light btn-square me-3" href="{{ route('account.login') }}"><i class="far fa-bookmark text-primary"></i></a>
                                            <a href="{{ route('payment.job', $job->id) }}"  class="btn btn-primary btn-donasi" href="">Kirim Donasi</a>
                                        @endif

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow border-0 mt-4">
                <div class="job_details_header">
                    <div class="single_jobs white-bg d-flex justify-content-between">
                        <div class="jobs_left d-flex align-items-center">
                            <div class="jobs_conetent">
                                <h4>Dukungan terbaru</h4>
                            </div>
                        </div>
                        <div class="jobs_right"></div>
                    </div>
                </div>
                <div class="descript_wrap white-bg">

                        @if ($applications->isNotEmpty())
                            @foreach ($applications as $application)
                            <div class="testimonial-item bg-light rounded p-4">
                                {{-- <i class="fa fa-quote-left fa-2x text-primary mb-3"></i> --}}
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
                            <hr class="my-4">

                            @endforeach
                            @else
                            <tr>
                                <td colspan="3">Tidak ditemukan Donasi</td>
                            </tr>
                        @endif

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script type="text/javascript">
function applyJob(id){
    if (confirm("Are you sure you want to apply on this job?")) {
        $.ajax({
            url : '{{ route("applyJob") }}',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success: function(response) {
                window.location.href = "{{ url()->current() }}";
            }
        });
    }
}

function saveJob(id){
    $.ajax({
        url : '{{ route("saveJob") }}',
        type: 'post',
        data: {id:id},
        dataType: 'json',
        success: function(response) {
            window.location.href = "{{ url()->current() }}";
        }
    });
}
</script>
@endsection
