<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk ke akun anda</title>
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
                                <h4 class="text-center py-1" style="font-weight: bold; color: rgb(255, 72, 0);">MASUK
                                </h4>
                                @if (isset($currentPeriod) && $currentPeriod)
                                    <p style="font-size: 12px" class="text-center">
                                        {{ $currentPeriod->formatted_period }}</p>
                                @else
                                    <p style="font-size: 12px" class="text-center">Tidak ada periode yang berlaku
                                        saat ini</p>
                                @endif

                                @if (isset($periods) && $periods == 'Masa periode')
                                    <div class="alert alert-danger text-center">
                                        Tidak boleh daftar, Anda berada di masa periode, silahkan login!
                                    </div>
                                @endif

                                @if (isset($HKIDorPhoneReady) && $HKIDorPhoneReady == 'Ya')
                                    <div class="alert alert-danger text-center">
                                        HKID atau Nomor HP sudah tercatat di sistem, silahkan login!
                                    </div>
                                @endif

                                <form action="{{ route('login') }}" method="POST">
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


                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <label for="password" class="form-label">Kata Sandi</label>
                                        <p id="char-count"
                                            style="font-size: 12px; text-align: right; margin-bottom: 5px">
                                            <span id="current-count">0</span> / 16
                                        </p>
                                    </div>
                                    <div style="position: relative;">
                                        <input type="password" class="form-control" id="password" name="password"
                                            required>

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

                                    <button type="submit"
                                        class="btn btn-danger w-100 py-8 fs-4 mb-3 rounded-2">Masuk</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-bold me-1    ">Belum punya akun?</p>
                                        <a class="text-danger fw-bold" href="{{ route('register') }}">Daftar</a>
                                    </div>
                                    <p style="font-size: 12px; color: rgb(212, 212, 212);"
                                        class="text-center mb-0 mt-1">Powered by Haoti Sistema Hokindo</p>
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
