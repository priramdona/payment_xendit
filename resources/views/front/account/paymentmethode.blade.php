
@extends('front.layouts.app')

@section('main')

<div class="payment-options">
    <h3>Pilih Metode Pembayaran</h3>
    <div class="card shadow mb-4">
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
                                        data-method="{{ $paymentMethod->code }}" onclick="selectPayment('{{ $paymentMethod->id }}','{{ $type }}')"
                                    @else
                                        class="disabled-method"
                                    @endif
                                    style="cursor: pointer; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;"  >
                                    <td>
                                        <img src="{{ $paymentMethod->image_url }}" alt="QRIS" class="payment-icon">
                                        {{ $paymentMethod->name }}
                                        {!! $paymentMethod->status ? '' : '<span class="inactive-payment">(Sedang gangguan)</span>' !!}
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

