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
<section class="site-section" id="next-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mb-5 mb-lg-0">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
        <form method="POST" action="{{ route('contact.store') }}">
        @csrf
          <form action="#" class="">

            <div class="row form-group">
              <div class="col-md-6 mb-3 mb-md-0">
                <label class="text-black" for="fname">Nama depan</label>
                <input type="text" id="fname" class="form-control" name="fname">
              </div>
              <div class="col-md-6">
                <label class="text-black" for="lname">Nama belakang</label>
                <input type="text" id="lname" class="form-control" name="lname">
              </div>
            </div>

            <div class="row form-group">

              <div class="col-md-12">
                <label class="text-black" for="email">Email</label>
                <input type="email" id="email" class="form-control" name="email">
              </div>
            </div>

            <div class="row form-group">

              <div class="col-md-12">
                <label class="text-black" for="subject">Judul</label>
                <input type="subject" id="subject" class="form-control" name="subject">
              </div>
            </div>

            <div class="row form-group">
              <div class="col-md-12">
                <label class="text-black" for="message">Pesan</label>
                <textarea name="message" id="message" cols="30" rows="7" class="form-control" placeholder="Tuliskan pesan anda disini.." name="message"></textarea>
              </div>
            </div>

            <div class="row form-group">
              <div class="col-md-12">
                <input type="submit" value="Kirim" class="btn btn-primary btn-md text-white">
              </div>
            </div>
            </form>


          </form>
        </div>
        <div class="col-lg-5 ml-auto">
          <div class="p-4 mb-3 bg-white">
            <p class="mb-0 font-weight-bold">Alamat</p>
            <p class="mb-4">Gedung Masindo Lt 3, Mampang Prapatan Raya No.73A - Gedung Masindo Lt.3, Jakarta selatan</p>

            <p class="mb-0 font-weight-bold">Phone</p>
            <p class="mb-4"><a href="#">+62 813 1456 9045</a></p>

            <p class="mb-0 font-weight-bold">Email</p>
            <p class="mb-0"><a href="#">admin@donasikita.com</a></p>

          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
