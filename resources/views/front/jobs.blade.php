@extends('front.layouts.app')

@section('main')
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a  href="{{ route('home') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Kembali ke Beranda</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <div class="col-6 col-md-10 ">

            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    <select name="sort" id="sort" class="form-control">
                        <option value="1" {{ (Request::get('sort') == '1') ? 'selected' : '' }}>Terbaru</option>
                        <option value="0" {{ (Request::get('sort') == '0') ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row pt-5">

            <div class="col-md-4 col-lg-3 sidebar mb-4">
                <form action="" name="searchForm" id="searchForm">
                    <div class="card border-0 shadow p-4">
                        <div class="mb-4">
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" id="keyword" placeholder="Cari" class="form-control">
                        </div>

                        <div class="mb-4">
                            <h5>Kategori</h5>
                            <select name="category" id="category" class="form-control">
                                <option value="">Semua</option>
                                @if ($categories)
                                    @foreach ($categories as $category)
                                    <option {{ (Request::get('category') == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-4">
                            <h5>Tipe</h5>

                            @if ($jobTypes->isNotEmpty())
                                @foreach ($jobTypes as $jobType)
                                <div class="form-check mb-2">
                                    <input {{ (in_array($jobType->id,$jobTypeArray)) ? 'checked' : ''}} class="form-check-input " name="job_type" type="checkbox" value="{{ $jobType->id }}" id="job-type-{{ $jobType->id }}">
                                    <label class="form-check-label " for="job-type-{{ $jobType->id }}">{{ $jobType->name }}</label>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Cari</button>
                        <a href="{{ route("jobs") }}" class="btn btn-secondary mt-3">Reset</a>
                    </div>
                </form>
            </div>
            <div class="col-md-8 col-lg-9">
                <div class="job_listing_area">
                    <div class="job_lists">
                        <div class="row">
                            @if ($jobs->isNotEmpty())
                                <div class="card shadow mb-4">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <tbody id="job-table-body">
                                                    @foreach ($jobs as $job)
                                                        <tr onclick="window.location='{{ route('jobDetail', $job->id) }}'"
                                                            style="cursor: pointer; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                                                            <td style="width: 50%;">
                                                                <img src="img/carousel-1.jpg" alt="Thumbnail" class="img-fluid rounded" style="width: 100%;">
                                                            </td>
                                                            <td>

                                                                <span style="font-size: 14px;font-weight: bold ">{{ $job->title }}</span>
                                                                <br>
                                                                <span style="font-size: 12px; ">{{ Str::words(strip_tags($job->description), $words=12, '...') }}</span>
                                                                <hr>
                                                                <div class="progress" style="height: 10px;">
                                                                    <div class="progress-bar" role="progressbar" style="width:  {{ ($job->applications->where('status','Paid')->sum('amount') / $job->salary) * 100 }}%;"
                                                                        aria-valuenow={{ $job->applications->where('status','Paid')->sum('amount') }} aria-valuemin="0" aria-valuemax={{ $job->salary }}>
                                                                    </div>
                                                                </div>
                                                                <span style="font-size: 10px; ">Terkumpul  <span style="font-size: 12px;font-weight: bold ">Rp. {{ number_format($job->applications->where('status','Paid')->sum('amount'), 0, ',', '.') }}</span></span>
                                                                <br>
                                                                <span style="font-size: 10px; ">Dari <span style="font-size: 12px;font-weight: bold ">Rp. {{  number_format($job->salary, 0, ',', '.') }}</span></span>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {{ $jobs->withQueryString()->links() }}
                                </div>
                            @else
                                <div class="col-md-12">Tidak ada Data</div>
                            @endif
                        </div>
                        <div id="loading" style="text-align: center; display: none;">
                            <p>Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customJs')
<script>


    $("#searchForm").submit(function(e){
        e.preventDefault();

        var url = '{{ route("jobs") }}?';

        var keyword = $("#keyword").val();
        var location = $("#location").val();
        var category = $("#category").val();
        var experience = $("#experience").val();
        var sort = $("#sort").val();

        var checkedJobTypes = $("input:checkbox[name='job_type']:checked").map(function(){
            return $(this).val();
        }).get();

        // If keyword has a value
        if (keyword != "") {
            url += '&keyword='+keyword;
        }

        // If location has a value
        if (location != "") {
            url += '&location='+location;
        }

        // If category has a value
        if (category != "") {
            url += '&category='+category;
        }

        // If experience has a value
        if (experience != "") {
            url += '&experience='+experience;
        }

        // If user has checked job types
        if (checkedJobTypes.length > 0) {
            url += '&jobType='+checkedJobTypes;
        }

        url += '&sort='+sort;

        window.location.href=url;

    });

    $("#sort").change(function(){
        $("#searchForm").submit();
    });

</script>
@endsection
