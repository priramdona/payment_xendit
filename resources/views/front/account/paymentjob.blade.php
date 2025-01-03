
@extends('front.layouts.app')

@section('main')
<div class="container pt-5">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a  href="{{ route('jobDetail', $job->id) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Kembali ke Detail</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="payment-options">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h3>{{ Str::words(strip_tags($job->title), $words=8, '...') }}</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-10 col-lg-8">
                    <img class="img-fluid" src="{{ $job->image_url ?? asset('images/default-banner.jpg') }}" alt="Program Donasi unggulan">
                    <div class="progress mt-4 animated slideInUp" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                            role="progressbar"
                            style="width: {{ (1000000 / $job->salary) * 100 }}%;"
                            aria-valuenow={{ 1000000 }}
                            aria-valuemin="0"
                            aria-valuemax={{ $job->salary }}>
                            {{ (1000000 / $job->salary) * 100 }}%
                        </div>
                    </div>
                    <span style="font-size: 10px; ">Terkumpul {{ number_format(1000000, 2, ',', '.') }} dari {{ number_format($job->salary, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="payment-options">


    <div class="card shadow mb-4">
        <div class="card-header">
            <h3>Pilih Metode Pembayaran</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Payment Table -->
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>Metode Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($paymentMethods->isNotEmpty())
                            @php
                                $typeMapping = [
                                    'QR_CODE' => 'QR Code',
                                    'EWALLET' => 'E-Wallet',
                                    'VIRTUAL_ACCOUNT' => 'Virtual Account',
                                ];
                                $categoryData = "";
                            @endphp

                            @foreach ($paymentMethods as $paymentMethod)
                                @php
                                    $formattedType = $typeMapping[$paymentMethod->type] ?? $paymentMethod->type;
                                @endphp
                                @if (!empty($categoryData))

                                    @if ($paymentMethod->type != $categoryData)
                                        <tr class="category-header" style="cursor: pointer; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                                            <td>{{ $formattedType }}  <span style="font-size: 14px; ">(verifikasi otomatis)</span></td>
                                        </tr>
                                        @php
                                            $categoryData = $paymentMethod->type;
                                        @endphp
                                    @endif
                                @else
                                    <tr class="category-header" style="cursor: pointer; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                                        <td>{{ $formattedType }}  <span style="font-size: 14px; ">(verifikasi otomatis)</span></td>
                                    </tr>

                                    @php
                                        $categoryData = $paymentMethod->type;
                                    @endphp
                                @endif

                                <tr @if ($paymentMethod->status)
                                        data-method="{{ $paymentMethod->code }}" onclick="selectPayment('{{ $paymentMethod->id }}','{{ $job->id }}')"
                                    @else
                                        class="disabled-method"
                                    @endif
                                    style="cursor: pointer; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;"  >
                                    <td>
                                        <img src="{{ $paymentMethod->image_url }}" alt="QRIS" class="payment-icon">
                                        {{ $paymentMethod->name }}
                                        {!! $paymentMethod->status ? '' : '<span class="inactive-payment">(Belum aktif)</span>' !!}
                                        <span class="checkmark"></span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</section>
@endsection

