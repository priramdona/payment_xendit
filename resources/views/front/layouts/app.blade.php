<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Pembayaran XENDIT | Xendit Payment Maker</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />
	<meta name="HandheldFriendly" content="True" />
	<meta name="pinterest" content="nopin" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" integrity="sha512-Fm8kRNVGCBZn0sPmwJbVXlqfJmPC13zRsMElZenX6v721g/H7OukJd8XzDEBRQ2FSATK8xNF9UYvzsCtUpfeJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js" integrity="sha512-YJgZG+6o3xSc0k5wv774GS+W1gx0vuSI/kr0E0UylL/Qg/noNspPtYwHPN9q6n59CTR/uhgXfjDXLTRI+uIryg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

    <link rel="stylesheet" type="text/css" href="{{asset('css/welcome.css')}}">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->

    <link href="{{asset('lib/animate/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{asset('css/welcome.css')}}" rel="stylesheet">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" /> --}}

    <style>
        .cat-item {
            width: 180px;       /* Lebar tetap */
            height: 180px;      /* Tinggi tetap */
            display: flex;      /* Agar konten di tengah */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;

            box-shadow: 0 0 45px rgba(0, 0, 0, 0.08);
            text-align: center;
            overflow: hidden;   /* Menghindari konten keluar dari box */
            margin: auto;       /* Agar berada di tengah jika diperlukan */
        }

        .cat-item i {
            font-size: 50px;  /* Ukuran ikon */
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .cat-item h6 {
            font-size: 0.8rem;    /* Ukuran judul kategori */
            margin-bottom: 5px;
        }

        .cat-item p {
            font-size: 0.5rem; /* Ukuran teks kecil */
            margin: 0;
        }

        .row.g-4 {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem; /* Jarak antar kategori */
            justify-content: center; /* Pusatkan kategori */
        }
    </style>
</head>
<body >

<div class="container-xxl bg-white p-0">
    <div class="menubar">
        <div class="menubar-content">
            <h1 class="m-0 text-white">Xendit Payment Maker</h1>
        </div>
    </div>
</div>

@yield('main')

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pb-0" id="exampleModalLabel">Ganti Foto Profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="profilePicForm" name="profilePicForm" action="" method="post">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Gambar Profil</label>
                <input type="file" class="form-control" id="image" name="image">
				<p class="text-danger" id="image-error"></p>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mx-3">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

@extends('front.layouts.footer')

<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('assets/js/instantpages.5.1.0.min.js') }}"></script>
<script src="{{ asset('assets/js/lazyload.17.6.0.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js" integrity="sha512-YJgZG+6o3xSc0k5wv774GS+W1gx0vuSI/kr0E0UylL/Qg/noNspPtYwHPN9q6n59CTR/uhgXfjDXLTRI+uIryg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>

let selectedMethod = null;
let selectedChannel = null;

$('.textarea').trumbowyg({
    btns: [
        ['undo', 'redo'],
        ['formatting'],
        ['bold', 'italic', 'underline'],
        ['superscript', 'subscript'],
        ['removeformat'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['insertImage', 'insertVideo', 'table'],
        ['horizontalRule'],
        ['fullscreen']
    ]
});

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$("#profilePicForm").submit(function(e){
		e.preventDefault();

		var formData = new FormData(this);

		$.ajax({
			url: '{{ route("account.updateProfilePic") }}',
			type: 'post',
			data: formData,
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function(response) {
				if(response.status == false) {
					var errors = response.errors;
					if (errors.image) {
						$("#image-error").html(errors.image)
					}
				} else {
					window.location.href = '{{ url()->current() }}';
				}
			}
		});
	});

    // Add click event listener to payment methods
    document.querySelectorAll('.payment-table tr[data-method]').forEach(row => {
        if (!row.classList.contains('disabled-method')) {
            row.addEventListener('click', function() {
                document.querySelectorAll('.payment-table tr').forEach(r => r.classList.remove('selected'));
                this.classList.add('selected');
                selectedMethod = this.getAttribute('data-method');
                selectedChannel = this.getAttribute('data-channel');

                // Enable the proceed button
                const proceedButton = document.getElementById('proceed-button');
                proceedButton.disabled = false;
            });
        }
    });

// Proceed button click logic
// document.getElementById('proceed-button').addEventListener('click', function() {
//     if (selectedMethod) {
//         window.location.href = `/payment-summary?method=${selectedMethod}&channel=${selectedChannel}`;
//     }
// });

    function selectPayment(paymentMethodId,jobId) {
    // Gunakan URL yang dihasilkan dari Blade
    const baseUrl = "{{ route('account.payment-summary') }}";
    const fullUrl = `${baseUrl}?methodId=${paymentMethodId}&jobId=${jobId}`;
    window.location.href = fullUrl;
    }


    function selectNominal(button, amount) {
        document.getElementById('nominal').value = amount;
        document.getElementById('customAmount').value = ''; // Kosongkan input nominal lain

        document.querySelectorAll('.btn-nominal').forEach(function(btn) {
            btn.classList.remove('btn-inline-primary');
            btn.classList.add('btn-outline-primary');
        });

        // Tambahkan kelas 'btn-primary' ke tombol yang diklik
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-inline-primary');

        // Set nilai nominal yang dipilih (opsional)
        console.log("Nominal dipilih:", nominal);
    }

    function updateCustomNominal(amount) {
        document.querySelectorAll('.btn-nominal').forEach(function(btn) {
            btn.classList.remove('btn-inline-primary');
            btn.classList.add('btn-outline-primary');
        });
        document.getElementById('nominal').value = amount;
    }
</script>

<!-- JavaScript Libraries -->
{{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ asset('lib/wow/wow.min.js') }}"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
{{-- <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script> --}}
<script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

<!-- Template Javascript -->
<script src="{{ asset('js/main.js') }}"></script>

@yield('customJs')
</body>
</html>
