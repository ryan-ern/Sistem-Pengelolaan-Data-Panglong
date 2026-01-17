<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Login | Panglong</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        {{-- Icons Bootstrap --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

        @notifyCss
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>

    <body>
        <x-notify::notify />
        <div class="container-fluid vh-100">
            <div class="row h-100">

                <!-- LEFT PANEL -->
                <div
                    class="col-lg-4 col-md-7 d-none d-md-flex
                    bg-timber text-white
                    flex-column justify-content-center align-items-center">

                    <div class="logo-wrapper">
                        <div class="text-center">
                            <img src="{{ asset('logo.png') }}" alt="Logo Panglong" width="200" class="logo-img">
                        </div>
                        <div class="logo-text">
                            <span class="wood-text">PANG</span><span class="dark-text">LONG</span>
                        </div>

                        <span class="logo-subtext opacity-75">Data Kayu Digital</span>
                    </div>

                    <p class="text-center px-4 py-2 login-text">
                        Masuk dengan akun terdaftar<br>
                    </p>

                    <form method="POST" action="{{ route('login') }}" class="w-75 mt-3">
                        @csrf

                        <div class="form-floating text-dark mb-4">
                            <input type="text" class="form-control" name="username" id="floatingUsername"
                                placeholder="akun_contoh" required>
                            <label for="floatingUsername">Username</label>
                        </div>

                        <div class="mb-4 text-dark">
                            <div class="input-group">

                                <div class="form-floating flex-grow-1">
                                    <input type="password" class="form-control" id="floatingPassword" name="password"
                                        placeholder="Password" required>
                                    <label for="floatingPassword">Password</label>
                                </div>

                                <button class="btn btn-outline-forest" type="button" onclick="togglePassword()"
                                    aria-label="Lihat Password">

                                    <i id="toggleIcon" class="bi bi-lock-fill"></i>
                                </button>

                            </div>
                        </div>


                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Ingat perangkat ini
                            </label>
                        </div>

                        <button class="btn btn-forest w-100 fw-semibold">
                            Masuk
                        </button>
                    </form>

                    <small class="mt-5 text-center px-3 opacity-75">
                        © 2025 - {{ now()->year }} | Sistem Pengelolaan Data Kayu
                    </small>
                </div>

                <!-- RIGHT PANEL -->
                <div class="col-lg-8 col-md-5 d-none d-md-flex dark-bg right-panel">
                    <div id="carouselExampleSlidesOnly" class="carousel slide w-100 h-100" data-bs-ride="carousel">
                        <div class="carousel-inner h-100">

                            <!-- SLIDE 1 -->
                            <div class="carousel-item active h-100">
                                <img src="{{ asset('images/slide1.jpg') }}" alt="Slide 1">

                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Kayu Kuat Dibentuk Waktu</h5>
                                    <p>
                                        Setiap proses kerja adalah investasi.
                                        Ketelitian hari ini akan menjadi fondasi
                                        kekuatan perusahaan di masa depan.
                                    </p>
                                </div>
                            </div>

                            <!-- SLIDE 2 -->
                            <div class="carousel-item h-100">
                                <img src="{{ asset('images/slide2.jpg') }}" alt="Slide 2">

                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Presisi Menentukan Nilai</h5>
                                    <p>
                                        Dari hutan hingga produk akhir,
                                        kualitas lahir dari komitmen dan
                                        tanggung jawab setiap individu.
                                    </p>
                                </div>
                            </div>

                            <!-- SLIDE 3 -->
                            <div class="carousel-item h-100">
                                <img src="{{ asset('images/slide3.jpg') }}" alt="Slide 3">

                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Berkembang Bersama Alam</h5>
                                    <p>
                                        Keberlanjutan bukan pilihan,
                                        melainkan cara kita bekerja,
                                        bertumbuh, dan memberi dampak.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- MOBILE VIEW -->
                <div
                    class="col-12 d-flex d-md-none
                    flex-column justify-content-center px-4 bg-timber text-white">

                    <div class="logo-wrapper">
                        <div class="text-center">
                            <img src="{{ asset('logo.png') }}" alt="Logo Panglong" width="200" class="logo-img">
                        </div>
                        <div class="logo-text">
                            <span class="wood-text">PANG</span><span class="dark-text">LONG</span>
                        </div>

                        <span class="logo-subtext opacity-75">Data Kayu Digital</span>
                    </div>

                    <p class="text-center px-4 py-2 login-text">
                        Masuk dengan akun terdaftar<br>
                    </p>


                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-floating text-dark mb-4">
                            <input type="text" class="form-control" name="username" id="floatingUsernameMobile"
                                placeholder="akun_contoh" required>
                            <label for="floatingUsernameMobile">Username</label>
                        </div>

                        <div class="mb-4 text-dark">
                            <div class="input-group">

                                <div class="form-floating flex-grow-1">
                                    <input type="password" class="form-control" id="floatingPasswordMobile"
                                        name="password" placeholder="Password" required>
                                    <label for="floatingPassword">Password</label>
                                </div>

                                <button class="btn btn-outline-forest" type="button" onclick="togglePassword()"
                                    aria-label="Lihat Password">

                                    <i id="toggleIconMobile" class="bi bi-lock-fill"></i>
                                </button>

                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_mobile">
                            <label class="form-check-label" for="remember_mobile">
                                Ingat perangkat ini
                            </label>
                        </div>

                        <button class="btn btn-forest w-100 fw-semibold">
                            Masuk
                        </button>
                    </form>

                    <small class="text-center mt-4 text-white">
                        © 2025 - {{ now()->year }} | Sistem Pengelolaan Data Kayu
                    </small>
                </div>

            </div>
        </div>
        <script>
            function togglePassword() {
                const input = document.getElementById('floatingPassword');
                const icon = document.getElementById('toggleIcon');
                const inputM = document.getElementById('floatingPasswordMobile');
                const iconM = document.getElementById('toggleIconMobile');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-lock-fill', 'bi-unlock-fill');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-unlock-fill', 'bi-lock-fill');
                }

                if (inputM.type === 'password') {
                    inputM.type = 'text';
                    iconM.classList.replace('bi-lock-fill', 'bi-unlock-fill');
                } else {
                    inputM.type = 'password';
                    iconM.classList.replace('bi-unlock-fill', 'bi-lock-fill');
                }


            }
        </script>

        @notifyJs
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</html>
