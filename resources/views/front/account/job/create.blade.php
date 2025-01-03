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

                <form action="" method="post" id="createJobForm" name="createJobForm" enctype="multipart/form-data">
                    <div class="card border-0 shadow mb-4 ">
                        <div class="card-body card-form p-4">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                <label for="image">Foto Banner</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                </div>
                                <div class="col-md-6 mb-4">

                                    <img id="imagePreview" src="{{ asset('images/default-banner.jpg') }}" alt="Image Preview" style="max-width: 350px;max-height: 197px;">
                                </div>
                            </div>
                            {{-- <h3 class="fs-4 mb-1">Detail Penggalangan Dana</h3> --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Judul<span class="req">*</span></label>
                                    <input type="text" placeholder="Judul Penggalangan Dana" id="title" name="title" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Kategori<span class="req">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Pilih</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Tipe<span class="req">*</span></label>
                                    <select name="jobType" id="jobType" class="form-select">
                                        <option value="">Pilih</option>
                                        @if ($jobTypes->isNotEmpty())
                                            @foreach ($jobTypes as $jobType)
                                            <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Jumlah Tim Penggalang<span class="req">*</span></label>
                                    <input type="number" min="1" placeholder="Jumlah Tim Penggalang" id="vacancy" name="vacancy" class="form-control">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Target Penggalangan Dana</label>
                                    <input type="text" placeholder="Target Dana" id="salary" name="salary" class="form-control">
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Kota<span class="req">*</span></label>
                                    <input type="text" placeholder="Kota" id="location" name="location" class="form-control">
                                    <p></p>
                                </div>
                            </div>

                            {{-- <div class="mb-4"> --}}
                                <label for="" class="mb-2">Ceritakan Penggalangan Dana <span class="req">*</span></label>
                                <textarea class="textarea" name="description" id="description" cols="5" rows="5" placeholder="Cerita Penggalangan Dana"></textarea>
                                <p></p>
                            {{-- </div> --}}
                            {{-- <div class="mb-4">
                                <label for="" class="mb-2">Benefits</label>
                                <textarea class="textarea" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Responsibility</label>
                                <textarea class="textarea" name="responsibility" id="responsibility" cols="5" rows="5" placeholder="Responsibility"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Qualifications</label>
                                <textarea class="textarea" name="qualifications" id="qualifications" cols="5" rows="5" placeholder="Qualifications"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Experience <span class="req">*</span></label>
                                <select name="experience" id="experience" class="form-control">
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                    <option value="4">4 Years</option>
                                    <option value="5">5 Years</option>
                                    <option value="6">6 Years</option>
                                    <option value="7">7 Years</option>
                                    <option value="8">8 Years</option>
                                    <option value="9">9 Years</option>
                                    <option value="10">10 Years</option>
                                    <option value="10_plus">10+ Years</option>
                                </select>
                                <p></p>
                            </div> --}}



                            <div class="mb-4">
                                <label for="" class="mb-2">Kata Kunci</label>
                                <input type="text" placeholder="Gunakan , (Koma) untuk Pemisah Kata Kunci" id="keywords" name="keywords" class="form-control">
                            </div>

                            {{-- <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Name<span class="req">*</span></label>
                                    <input type="text" placeholder="Company Name" id="company_name" name="company_name" class="form-control">
                                    <p></p>
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Location</label>
                                    <input type="text" placeholder="Location" id="company_location" name="company_location" class="form-control">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Website</label>
                                <input type="text" placeholder="Website" id="website" name="website" class="form-control">
                            </div> --}}
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
$("#createJobForm").submit(function(e){
    e.preventDefault();
    $("button[type='submit']").prop('disabled',true);
    // Gunakan FormData untuk menangani file
    const formData = new FormData(this);
    $.ajax({
        url: '{{ route("account.saveJob") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        processData: false, // Hindari proses data otomatis
        contentType: false, // Hindari pengaturan konten otomatis
        success: function(response) {
            $("button[type='submit']").prop('disabled',false);

            if(response.status == true) {

                $("#title").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                $("#salary").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#jobType").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#vacancy").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#location").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')


                $("#description").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                // $("#company_name").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback')
                //     .html('')

                window.location.href="{{ route('account.myJobs') }}";

            } else {
                var errors = response.errors;
                console.log(errors);
                if (errors.title) {
                    $("#title").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('Masukan Judul')
                } else {
                    $("#title").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }
                if (errors.image) {
                    $("#image").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('Banner harus dipilih')
                } else {
                    $("#image").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.salary) {
                    $("#salary").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('Target Dana harus diisi Nominal')
                } else {
                    $("#salary").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.category) {
                    $("#category").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('Kategori harus dipilih')
                } else {
                    $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.jobType) {
                    $("#jobType").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('Tipe harus dipilih')
                } else {
                    $("#jobType").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.vacancy) {
                    $("#vacancy").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('Jumlah tim harus diisi jumlah')
                } else {
                    $("#vacancy").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.location) {
                    $("#location").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('Kota harus diisi')
                } else {
                    $("#location").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.description) {
                    $("#description").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('Isi galang donasi harus diisi lengkap')
                } else {
                    $("#description").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                // if (errors.company_name) {
                //     $("#company_name").addClass('is-invalid')
                //     .siblings('p')
                //     .addClass('invalid-feedback')
                //     .html(errors.company_name)
                // } else {
                //     $("#company_name").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback')
                //     .html('')
                // }
            }

        }
    });
});

function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                const preview = document.getElementById('imagePreview');
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

</script>
@endsection

