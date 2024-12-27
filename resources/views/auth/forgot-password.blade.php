<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password</title>
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
                                <h4 class="text-center py-1">Lupa Password</h4>
                                <p class="text-center">Masukkan nomor HP Anda yang terdaftar di <span
                                        style="font-weight: bold; color: rgb(255, 72, 0);">CHANDRA PEDULI</span></p>
                                <form action="{{ route('register.forgot.password.check') }}" method="POST">
                                    @csrf
                                    <div>
                                        <label for="phone_number" class="form-label">Nomor HP / WhatsApp</label>
                                        <div class="input-group mb-2">
                                            <select class="form-select" id="country_code" name="country_code" required
                                                style="width: auto; max-width: 100px;">
                                                <option value="852" selected>+852</option>
                                                <option value="62">+62</option>
                                            </select>

                                            <input type="number" class="form-control" id="phone_number"
                                                name="phone_number" required pattern="^[1-9][0-9]*$"
                                                oninput="validatePhoneNumber()">
                                        </div>
                                    </div>

                                    <script>
                                        function validatePhoneNumber() {
                                            const phoneInput = document.getElementById('phone_number');
                                            let phoneValue = phoneInput.value;

                                            // Hapus angka pertama jika dimulai dengan 0
                                            if (phoneValue.startsWith('0')) {
                                                phoneValue = phoneValue.slice(1);
                                            }
                                            phoneInput.value = phoneValue;
                                        }
                                    </script>

                                    <button type="submit"
                                        class="btn btn-danger w-100 py-8 fs-4 mb-3 rounded-2">Kirim</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-bold me-1">Kembali?</p>
                                        <a class="text-danger fw-bold" href="{{ route('login') }}">Klik Disini</a>
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
