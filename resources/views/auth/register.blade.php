<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendafataran Peserta</title>
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
                    <div class="col-md-8 col-lg-6 col-xxl-6">
                        <div class="card mb-0">
                            <div class="card-body">
                                <h4 class="text-center py-1">Form Pendaftaran Peserta <span
                                        style="font-weight: bold; color: rgb(255, 72, 0);">CHANDRA PEDULI</span></h4>

                                <!-- Link SweetAlert2 -->
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                @if ($aktif == 'Ya' && $canRegister)
                                    <p style="font-size: 12px" class="text-center">
                                        @isset($currentPeriod)
                                            {{ $currentPeriod->formatted_period }} | Syarat dan Ketentuan
                                            <a href="http://localhost/web-service/apikko/reward/file_pdf/s&k.pdf"
                                                target="_blank">Klik disini</a>
                                        @endisset
                                    </p>

                                    <form action="{{ route('register.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <!-- Formulir input data -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="hkid" class="form-label">Nomor HKID</label>
                                                <input type="text" class="form-control mb-2" id="hkid"
                                                    name="hkid" style="text-transform: uppercase;" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="file_hkid" class="form-label">Upload Foto HKID</label>
                                                <input type="file" class="form-control mb-2" id="file_hkid"
                                                    name="file_hkid" accept="image/*" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Nama</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    style="text-transform: uppercase;" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="phone_number" class="form-label">Nomor HP / WhatsApp</label>
                                                <div class="input-group mb-2">
                                                    <select class="form-select" id="country_code" name="country_code"
                                                        required style="width: auto; max-width: 100px;">
                                                        <option value="852" selected>+852</option>
                                                        <option value="62">+62</option>
                                                    </select>
                                                    <input type="number" class="form-control" id="phone_number"
                                                        name="phone_number" required pattern="^[1-9][0-9]*$"
                                                        oninput="validatePhoneNumber()">
                                                </div>
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

                                        <!-- Formulir upload file dan pemilihan bank -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="file_bank_book" class="form-label">Upload Foto Buku
                                                    Tabungan</label>
                                                <input type="file" class="form-control" id="file_bank_book"
                                                    name="file_bank_book" accept="image/*" required>
                                                <p style="font-size: 12px; margin-left: auto;" class="mb-2 text-danger">
                                                    Harus terlihat saldo awal dibuku tabungan
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="banks_id" class="form-label">Nama Bank</label>
                                                <select class="form-control mb-2" id="banks_id" name="banks_id"
                                                    required>
                                                    <option value="">Pilih Nama Bank</option>
                                                    @foreach ($bank as $data)
                                                        @if ($data->name != 'Tidak tersedia')
                                                            <option value="{{ $data->id }}">{{ $data->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Formulir detail rekening -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="bank_account_number" class="form-label">Nomor
                                                    Rekening</label>
                                                <input type="number" class="form-control mb-2" id="bank_account_number"
                                                    name="bank_account_number" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="bank_holder_name" class="form-label">Nama Pemilik
                                                    Tabungan</label>
                                                <input type="text" class="form-control mb-2" id="bank_holder_name"
                                                    name="bank_holder_name" style="text-transform: uppercase;"
                                                    required>
                                            </div>
                                        </div>

                                        <!-- Formulir kata sandi -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Keterangan Kata Sandi</label>
                                                <p style="font-size: 12px">1. Kata sandi hanya untuk akun
                                                    <strong>CHANDRA PEDULI</strong> <br> 2. Mengandung huruf dan angka 8
                                                    sampai 16 karakter
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    style="display: flex; align-items: center; justify-content: space-between;">
                                                    <label for="password" class="form-label">Kata Sandi <span
                                                            style="font-weight: normal; color: rgb(131, 131, 131); font-size: 12px;">(Akun
                                                            CHANDRA PEDULI)</span></label>
                                                    <p id="char-count"
                                                        style="font-size: 12px; text-align: right; margin-bottom: 5px">
                                                        <span id="current-count">0</span> / 16
                                                    </p>
                                                </div>
                                                <div style="position: relative;">
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" required>
                                                    <button type="button" id="toggle-password"
                                                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; font-size: 16px; cursor: pointer;">
                                                        <i id="eye-icon" class="fas fa-eye-slash"></i>
                                                    </button>
                                                </div>
                                                <div
                                                    style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                                    <p id="password-warning"
                                                        style="font-size: 12px; text-align: right; color: red; display: none;">
                                                        Min. 8 - 16 karakter (huruf & angka)
                                                    </p>
                                                    <p style="font-size: 12px; text-align: right; margin-left: auto;"
                                                        class="mb-3">
                                                        <a href="{{ route('register.forgot.password') }}">Lupa kata
                                                            sandi</a>
                                                    </p>
                                                </div>

                                                <script>
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const passwordInput = document.getElementById("password");
                                                        const passwordWarning = document.getElementById("password-warning");
                                                        const charCount = document.getElementById("current-count");
                                                        const togglePasswordButton = document.getElementById("toggle-password");
                                                        const eyeIcon = document.getElementById("eye-icon");

                                                        passwordInput.addEventListener("input", function() {
                                                            const password = passwordInput.value;
                                                            charCount.textContent = password.length;

                                                            if (password.length > 16) {
                                                                passwordInput.value = password.substring(0, 16);
                                                                charCount.textContent = 16;
                                                            }

                                                            const isValidPassword = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!@#$%^&*(),.?":{}|<>-_+]{8,16}$/;

                                                            if (!isValidPassword.test(password)) {
                                                                passwordWarning.style.display = "block";
                                                            } else {
                                                                passwordWarning.style.display = "none";
                                                            }
                                                        });

                                                        togglePasswordButton.addEventListener("click", function() {
                                                            const type = passwordInput.type === "password" ? "text" : "password";
                                                            passwordInput.type = type;

                                                            if (type === "password") {
                                                                eyeIcon.classList.remove("fa-eye");
                                                                eyeIcon.classList.add("fa-eye-slash");
                                                                eyeIcon.style.color = "#888";
                                                            } else {
                                                                eyeIcon.classList.remove("fa-eye-slash");
                                                                eyeIcon.classList.add("fa-eye");
                                                                eyeIcon.style.color = "#888";
                                                            }
                                                        });
                                                    });
                                                </script>




                                                <link rel="stylesheet"
                                                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

                                                <style>
                                                    #eye-icon {
                                                        color: #888;
                                                    }

                                                    #toggle-password:hover #eye-icon {
                                                        color: #555
                                                    }
                                                </style>
                                            </div>
                                        </div>

                                        <!-- Tombol daftar -->
                                        <button id="submitBtn" type="submit"
                                            class="btn btn-danger w-100 py-8 fs-4 mb-3 rounded-2">Daftar</button>

                                        <!-- Tautan login -->
                                        <div class="d-flex align-items-center justify-content-center">
                                            <p class="fs-4 mb-0 fw-bold me-1">Sudah punya akun?</p>
                                            <a class="text-danger fw-bold" href="{{ route('login') }}">Masuk</a>
                                        </div>
                                        <p style="font-size: 12px; color: rgb(212, 212, 212);"
                                            class="text-center mb-0 mt-1">Powered by Haoti Sistema Hokindo</p>
                                    </form>
                                @elseif ($aktif == 'Ya' && !$canRegister)
                                    <p style="font-size: 12px" class="text-center mb-5">Pendaftaran ditutup (Kuota
                                        peserta sudah penuh)</p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-bold me-1">Ingin login?</p>
                                        <a class="text-danger fw-bold" href="{{ route('login') }}">Klik disini</a>
                                    </div>
                                    <p style="font-size: 12px; color: rgb(212, 212, 212);"
                                        class="text-center mb-0 mt-1">Powered by Haoti Sistema Hokindo</p>
                                @else
                                    <p style="font-size: 12px" class="text-center mb-5">Pendaftaran ditutup (Lewat
                                        masa periode)</p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-bold me-1">Ingin login?</p>
                                        <a class="text-danger fw-bold" href="{{ route('login') }}">Klik disini</a>
                                    </div>
                                    <p style="font-size: 12px; color: rgb(212, 212, 212);"
                                        class="text-center mb-0 mt-1">Powered by Haoti Sistema Hokindo</p>
                                @endif
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
