<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('payment.failed.title') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }
        .failed-container {
            max-width: 300px;
            width: 90%;
            text-align: center;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .failed-container h1 {
            font-size: 24px;
            color: #f44336;
            margin-bottom: 10px;
        }
        .failed-container p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .failed-container .error-icon {
            font-size: 48px;
            color: #f44336;
            margin-bottom: 20px;
        }
        .failed-container a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            background: #007bff;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        .failed-container a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="failed-container">
        <div class="error-icon">❌</div>
        <h1>Pembayaran Gagal</h1>
        <p>Anda akan diarahkan ke Beranda dalam</p>
        <p class="countdown">
            <span id="countdown">3</span> Detik...
        </p>
        {{-- <a href="{{ route('pos') }}">{{ __('payment.failed.button') }}</a> --}}
    </div>
    <script>
        let countdownElement = document.getElementById('countdown');
        let countdown = 3; // In seconds
        let interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('home') }}";
            }
        }, 1000);
    </script>
</body>
</html>
