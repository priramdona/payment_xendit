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
                <div class="card border-0 shadow mb-4">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-primary p-4 mfe-3 rounded-left">
                            <i class="bi bi-cash font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-primary" style="font-weight: bold; font-size: 20px;">{{ number_format($balance, 2, ',', '.') }}</div>
                            <div class="text-muted text-uppercase font-weight-bold large">Saldo Anda</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Penarikan Dana</h5>
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
                        <form action="{{ route('financial.management.withdraw.process') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label name="label_transaction_amount" id="label_transaction_amount" for="transaction_amount">Minimal Nominal<span class="text-danger"> Rp. 10.000</span><span class="text-info"> (Biaya Aplikasi 10% + Biaya Xendit 2.500)</span></label>

                                        <input onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }"
                                    type="number" class="form-control" name="transaction_amount" id="transaction_amount" value="" required>
                                </div>
                            </div>
                            <div class="form-row">
                                    <div class="form-group">
                                        <label for="disbursement_method">Metode<span class="text-danger">*</span></label>
                                        <select name="disbursement_method" id="disbursement_method" class="form-control" required>
                                            <option value="" selected disabled>Pilih Metode</option>
                                            @foreach(App\Models\XenditDisbursementMethod::where('status',true)->get() as $disbursement_method)
                                                <option value="{{ $disbursement_method->id }}">{{ $disbursement_method->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                            <div class="form-row">
                                    <div class="form-group">
                                        <label name="label_disbursement_channel" id="label_disbursement_channel" for="disbursement_channel">Akun<span class="text-danger">*</span></label>
                                        <select name="disbursement_channel" id="disbursement_channel" class="form-control" required>
                                            <option value="" selected disabled>Akun</option>
                                            <!-- Options akan diisi berdasarkan pilihan disbursement_method -->
                                        </select>
                                    </div>
                            </div>
                            <div class="form-row">
                                    <div class="form-group">
                                        <label for="account_name">Nama Akun<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="account_name" id="account_name" value="" required>
                                    </div>
                            </div>

                            <div class="form-row">
                                    <div class="form-group">
                                        <label name="label_account_number" id="label_account_number" for="account_number">Nomor Akun <span class="text-danger">*</span></label>
                                        <input onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }"
                                        type="number" class="form-control" name="account_number" id="account_number" value="" required>
                                    </div>
                            </div>

                            <div class="form-row">
                                    <div class="form-group">
                                        <label name="label_transaction_info" id="label_transaction_info" for="transaction_info">Nominal Terima : </label>
                                        <label class="text-info font-weight-bold col-lg-6" id="transaction_info" name="transaction_info" style="font-weight: bold; font-size: 20px;">Rp. 0.00</label>


                                        <input type="hidden" class="form-control" name="amount" id="amount" value="" readonly>
                                    </div>
                                    <div class="form-group">

                                        <label name="label_amount" id="label_amount" for="amount">Nominal Potong : </label>
                                        <label class="text-primary font-weight-bold col-lg-6" id="amount_info" name="amount_info" style="font-weight: bold; font-size: 20px;">Rp. 0.00</label>

                                        <input type="hidden" class="form-control" name="amount" id="amount" value="" readonly>
                                    </div>
                                    <br>
                            </div>

                            <div class="form-row">
                                    <div class="form-group">
                                        <label for="company_address">Catatan Penarikan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="notes" id="notes" value="">
                                    </div>
                            </div>
                            <br>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Tarik Dana</button>
                            </div>
                        </form>
                    </div>
                </div>
                @include('front.message')
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Mutasi</h3>
                            </div>

                        </div>
                        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                            <table id="preview-table" class="table table-bordered" style="table-layout: auto; width: 100%;">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="background: #96cbff; white-space: nowrap;" scope="col">Tipe</th>
                                        <th style="background: #96cbff; white-space: nowrap;"scope="col">Tanggal</th>
                                        <th style="background: #96cbff; white-space: nowrap;"scope="col">Nominal</th>
                                        <th style="background: #96cbff; white-space: nowrap;"scope="col">Diterima</th>
                                        <th style="background: #96cbff; white-space: nowrap;"scope="col">Dipotong</th>
                                        <th style="background: #96cbff; white-space: nowrap;"scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($jobApplications->isNotEmpty())
                                        @foreach ($jobApplications as $jobApplication)
                                        <tr class="active">
                                            <td  style="white-space: nowrap;">{{ $jobApplication->code }}</td>
                                            <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($jobApplication->applied_date)->format('d-m-Y h:m') }}</td>
                                            <td style="white-space: nowrap;">{{ number_format($jobApplication->amount, 2, ',', '.') }}</td>
                                            <td style="white-space: nowrap;">{{ number_format($jobApplication->received_amount, 2, ',', '.') }}</td>
                                            <td style="white-space: nowrap;">{{ number_format($jobApplication->deduction_amount, 2, ',', '.') }}</td>

                                            <td style="white-space: nowrap;">{{ strtolower($jobApplication->status)  }}</td>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5">Belum Pembayaran</td>
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
<script>
    // $(document).ready(function () {
        $('#disbursement_method').on('change', function () {

            var xdmId = document.getElementById('disbursement_method').value;
            var xdmName = $('#disbursement_method option:selected').text();
            var income_amount = parseFloat(document.getElementById('transaction_amount').value) || 0;
            var app_amount = (income_amount * 10) / 100;
            var net_amount = income_amount + 2500 + app_amount;
            if (income_amount >= 10000){
                $('input[name=amount]').val(net_amount);
                var infoamount = document.getElementById('amount').value;
                var infotransaction = document.getElementById('transaction_amount').value;
                $('#amount_info').text(formatRupiah(infoamount,'Rp. '));
                $('#transaction_info').text(formatRupiah(infotransaction,'Rp. '));
            }else{
                $('input[name=amount]').val(0);
                var infoamount = document.getElementById('amount').value;
                $('#amount_info').text(formatRupiah(infoamount,'Rp. '));
                $('#transaction_info').text(formatRupiah(0,'Rp. '));
            }

            $('#label_disbursement_channel').text(xdmName);

            // Hapus semua opsi sebelumnya dari dropdown disbursement_channel
            $('#disbursement_channel').empty().append('<option value="" selected disabled>Pilih Akun</option>');

            if (xdmId) {
                // Kirim permintaan AJAX ke server untuk mendapatkan channel berdasarkan xdm_id
                $.ajax({
                    url: '/account/get-disbursement-channels', // Endpoint untuk mengambil channel berdasarkan xdm_id
                    type: 'GET',
                    data: { xdm_id: xdmId },
                    success: function (channels) {
                        // Loop melalui hasil dan tambahkan ke dropdown disbursement_channel
                        $.each(channels, function (index, channel) {
                            $('#disbursement_channel').append(`<option value="${channel.id}">${channel.name}</option>`);
                        });
                    },
                    error: function () {
                        alert('Error.. Please try again..');
                    }
                });
            }
        });
    // });
    $(document).on('input', '#transaction_amount', function() {
        var income_amount = parseFloat(document.getElementById('transaction_amount').value) || 0;
        var app_amount = (income_amount * 10) / 100;
        var net_amount = income_amount + 2500 + app_amount;
        if (income_amount >= 10000){
            $('input[name=amount]').val(net_amount);
            var infoamount = document.getElementById('amount').value;
            var infotransaction = document.getElementById('transaction_amount').value;
            $('#amount_info').text(formatRupiah(infoamount,'Rp. '));
            $('#transaction_info').text(formatRupiah(infotransaction,'Rp. '));
        }else{
            $('input[name=amount]').val(0);
            var infoamount = document.getElementById('amount').value;
            $('#amount_info').text(formatRupiah(infoamount,'Rp. '));
        }
    });

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        //tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? ',' : '';
            rupiah += separator + ribuan.join(',');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        rupiahDecial = rupiah + '.00'
        return prefix == undefined ? rupiahDecial : (rupiahDecial ? 'Rp. ' + rupiahDecial : '');
    }
</script>
@endsection
