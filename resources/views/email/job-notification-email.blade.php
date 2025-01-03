<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemberitahuan Pembayaran</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-body {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .email-footer {
            background-color: #f5f5f5;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666666;
        }
        .contentpayment {
            text-align: center;
            margin: 20px 0;
        }
        .contentpayment img {
            max-width: 200px;
            height: auto;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .payment-summary {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .payment-summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .payment-summary th,
        .payment-summary td {
            text-align: left;
            padding: 10px;
        }
        .payment-summary th {
            background-color: #4CAF50;
            color: white;
        }
        .payment-summary td {
            border-bottom: 1px solid #ddd;
        }
        .payment-icon {
            width: 100px;
            height: 50px;
            margin-right: 10px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Pemberitahuan Pembayaran</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Halo, {{ $mailData['name'] }},</p>
            <p>Berikut adalah ringkasan pembayaran Anda yang harus dibayarkan menggunakan {{ $mailData['payment_method']->name }}:</p>

            <div class="payment-summary">
                <table>
                    <tr>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                    </tr>
                    <tr>
                        <td>Nominal</td>
                        <td>Rp {{ number_format($mailData['nominal_response'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Metode Pembayaran</td>
                        <td>{{ $mailData['payment_method']->name }}</td>
                    </tr>
                    <tr>
                        <td>Tenggat Waktu</td>
                        <td>{{ $mailData['expired_response'] }}</td>
                    </tr>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center border p-3 rounded mb-3">
                <div>
                    <h6 class="mb-1">{{ $mailData['payment_method']['name'] }}</h6>
                </div>
                <div>
                    <img src="{{ $mailData['payment_method']->image_url }}" alt="{{ $mailData['payment_method']->name }}" class="payment-icon">
                </div>
            </div>

            <div class="contentpayment">
                    <!-- Informasi Metode Pembayaran -->
                    @if ( $mailData['payment_method']->action === 'account')
                    <div class="border p-3 rounded mb-3 d-flex flex-column align-items-center justify-content-center text-center" id="methodva" name="methodva">
                        <div>
                            <span class="text-muted">Atas Nama</span>
                        </div>
                        <div>
                            <h2 class="text-success">{{ $mailData['name_response'] }}</h2>
                        </div>
                        <div>

                            <span class="text-muted">Nomor Virtual Account</span>
                        </div>
                        <div>
                            <h2 class="text-success">{{ $mailData['value_response'] }}</h2>
                        </div>
                    </div>
                    @elseif ( $mailData['payment_method']->action === 'input')
                    <!-- Informasi Metode Pembayaran -->
                    <div class="border p-3 rounded mb-3 d-flex flex-column align-items-center justify-content-center text-center" id="methodinput" name="methodinput">

                        <div>
                            <h4 class="text-success">Silakan cek tagihan dan selesaikan proses pembayaran pada akun Anda</h4>
                        </div>
                    </div>
                    @elseif ( $mailData['payment_method']->action === 'qrcode')

                    <!-- Informasi Metode Pembayaran -->
                    <div class="border p-3 rounded mb-3 d-flex flex-column align-items-center justify-content-center text-center" id="methodqr" name="methodqr">
                        <div>
                            <span class="text-muted">Scan Kode QR untuk proses pembayaran</span>
                            <br>
                        </div>
                        <div>
                            <img id="qrCodeImage" src="data:image/png;base64, {{ $mailData['value_response'] }}" alt="QR Code" class="img-fluid img-thumbnail" style="width: 200px; height: 200px;background-color: white;">
                        </div>
                    </div>
                    @else
                    <div class="border p-3 rounded mb-3 d-flex flex-column align-items-center justify-content-center text-center" id="methodinput" name="methodinput">

                        <div>
                            <h4 class="text-success">Selesaikan pembayaran anda</h4>
                            <a href="{{ $mailData['value_response'] }}" class="btn">Selesaikan pembayaran</a>
                        </div>
                    </div>

                    @endif
            </div>



            <p>Jika Anda memiliki pertanyaan atau butuh bantuan, jangan ragu untuk menghubungi kami.</p>
            <p>Terima kasih!</p>

            <a href="{{ route('contacts') }}" class="btn">Hubungi Kami</a>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} www.DonasiKita.com. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
