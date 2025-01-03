@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Beranda</a></li>
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
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Daftar Donasi Saya</h3>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Tipe</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Nominal</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($jobApplications->isNotEmpty())
                                        @foreach ($jobApplications as $jobApplication)
                                        <tr class="active">
                                            <td>{{ $jobApplication->type }}</td>
                                            <td>{{ \Carbon\Carbon::parse($jobApplication->applied_date)->format('d M, Y') }}</td>
                                            <td>{{ number_format($jobApplication->amount, 2, ',', '.') }}</td>

                                            <td>
                                                @if ($jobApplication->status == 1)
                                                    <div class="job-status text-capitalize">Berhasil</div>
                                                @else
                                                    <div class="job-status text-capitalize">Menunggu</div>
                                                @endif

                                            </td>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5">Belum ada Donasi</td>
                                    </tr>
                                    @endif


                                </tbody>
                            </table>
                        </div>
                        <div>
                            {{ $jobApplications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
<script type="text/javascript">
function removeJob(id) {
    if (confirm("Are you sure you want to remove?")) {
        $.ajax({
            url : '{{ route("account.removeJobs") }}',
            type: 'post',
            data: {id: id},
            dataType: 'json',
            success: function(response) {
                window.location.href='{{ route("account.myJobApplications") }}';
            }
        });
    }
}
</script>
@endsection
