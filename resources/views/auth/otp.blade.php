<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('mot/assets/images/logos/chandra-peduli.png') }}" />
    <link rel="stylesheet" href="{{ asset('mot/assets/css/styles.min.css') }}" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <h4 class="text-center py-1">Verifikasi Nomor HP</h4>
                                    <p class="text-center">Masukkan kode OTP dari WhatsApp</p>
                                    <form action="{{ route('register.otp.check') }}" method="POST">
                                        @csrf
                                        <div>
                                            <input type="number" class="form-control mb-2" id="otp"
                                                name="otp" style="text-transform: uppercase;" required>
                                            <button type="submit"
                                                class="btn btn-danger w-100 py-8 fs-4 mb-4 rounded-2">Kirim</button>
                                            @if (isset($otp) && $otp == 'OTP salah')
                                                <p style="font-size: 12px; color: red" class="text-center">OTP salah.
                                                    Silahkan daftar ulang. <span><a href="{{ route('register') }}">Klik
                                                            disini</a></span></p>
                                            @endif
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('mot/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('mot/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
