 <!-- Footer Start -->
 <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">

                <h5 class="text-white mb-4">Payment Xendit Maker</h5>
                {{-- <a class="btn btn-link text-white-50" href="">Tentang Kami</a> --}}
                {{-- <a class="btn btn-link text-white-50" href="">Kebijakan & Privasi</a>
                <a class="btn btn-link text-white-50" href="">Syarat & Ketentuan</a> --}}

            {{-- <div class="d-flex pt-2">
              <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
              <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
              <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
              <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
          </div> --}}

          <br>
          <br>
          <br>
          <br>

  </div>
  <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

  <div class="taskbar">
      <div class="taskbar-content">


          <a href="{{ url('/') }}" class="taskbar-item" >
            <i>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                      <rect width="24" height="24" fill="white"/>
                    </mask>
                    <g mask="url(#mask0)">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M7 1.5C4.79086 1.5 3 3.29086 3 5.5V18.5C3 20.7091 4.79086 22.5 7 22.5H17C19.2091 22.5 21 20.7091 21 18.5V5.5C21 3.29086 19.2091 1.5 17 1.5H7ZM6.75 12.375C6.33579 12.375 6 12.7108 6 13.125C6 13.5392 6.33579 13.875 6.75 13.875H14.25C14.6642 13.875 15 13.5392 15 13.125C15 12.7108 14.6642 12.375 14.25 12.375H6.75ZM6 16.875C6 16.4608 6.33579 16.125 6.75 16.125H17.25C17.6642 16.125 18 16.4608 18 16.875C18 17.2892 17.6642 17.625 17.25 17.625H6.75C6.33579 17.625 6 17.2892 6 16.875ZM9.59129 5.2858C9.29075 5.35283 9.03859 5.50794 8.82334 5.7276C8.77736 5.77452 8.72832 5.81842 8.68248 5.85946C8.66257 5.87729 8.64325 5.89458 8.62505 5.91134C8.60207 5.88986 8.57821 5.86769 8.55379 5.84501C8.50066 5.79564 8.44488 5.74382 8.38976 5.69131C8.07257 5.38923 7.70049 5.22817 7.25908 5.25399C6.95424 5.27181 6.68442 5.38448 6.46873 5.60197C6.04928 6.02492 5.90916 6.5304 6.05718 7.11181C6.13285 7.40892 6.27351 7.67584 6.4429 7.92839C6.70708 8.32206 7.03142 8.66227 7.37804 8.98168C7.76419 9.33739 8.17945 9.6568 8.60266 9.96617C8.62312 9.98108 8.6351 9.97513 8.65105 9.96293C8.73007 9.90229 8.80942 9.84207 8.88878 9.78185C9.05653 9.65453 9.22429 9.52722 9.38898 9.39599C9.78984 9.07652 10.1643 8.72805 10.4971 8.33627C10.7152 8.07961 10.9083 7.80595 11.0517 7.49928C11.1566 7.27525 11.2291 7.04182 11.2463 6.79343C11.2697 6.45398 11.1823 6.14429 10.9964 5.86133C10.6753 5.37238 10.1591 5.1592 9.59129 5.2858Z" fill="white"/>
                    </g>
                  </svg>

            </i>
            <span>Pembayaran</span>
        </a>


          @if (!Auth::check())
          <a href="{{ route('account.login') }}" class="taskbar-item">
            <i class="fa fa-user"></i>
            <span>Masuk</span>
            </a>
          @else
                <a href="{{ route('account.profile') }}" class="taskbar-item">
                    <i class="fa fa-user"></i>
                    <span>Akun</span>
                </a>
          @endif


      </div>
  </div>
