<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Masuk - Dasbor Web Pendidikan Teknologi Informasi</title>
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" />

    <style>
        .bg-navy {
            background-color: #041c3b !important;
        }

        .bg-navy:hover {
            background-color: #042f6e !important;
        }
    </style>
    <script data-search-pseudo-elements="" defer=""
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
</head>

<body class="bg-light">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main class="vh-100">
                <div class="container-xl h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col col-xl-10">
                            <div class="card shadow border-0 overflow-hidden" style="border-radius: 1rem;">
                                <div class="row g-0">
                                    <div class="col-md-6 col-lg-5 d-none d-md-block">
                                        <img src="{{ asset('assets/admin/img/login-image.jpg') }}" alt="login form"
                                            class="img-fluid"
                                            style="border-radius: 1rem 0 0 1rem; height: 100%; object-fit: cover" />
                                    </div>
                                    <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                        <div class="card-body p-4 p-lg-5 text-black w-100">
                                            <div class="d-flex align-items-center mb-3 pb-1">
                                                <img src="{{ asset('assets/admin/img/logo-pti.png') }}" alt="logo"
                                                    class="img-fluid">
                                            </div>
                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf

                                                {{-- <div class="mb-3">
                                                    <label class="small mb-1" for="fieldJenisPengguna">
                                                        Jenis Pengguna
                                                    </label>
                                                    <select
                                                        class="form-control @error('jenis_pengguna') is-invalid @enderror"
                                                        name="jenis_pengguna" id="fieldJenisPengguna">
                                                        <option value="" hidden selected>-- Pilih Jenis Pengguna
                                                            --
                                                        </option>
                                                        <option value="mahasiswa"
                                                            {{ old('jenis_pengguna') == 'mahasiswa' ? 'selected' : '' }}>
                                                            Mahasiswa</option>
                                                        <option value="dosen"
                                                            {{ old('jenis_pengguna') == 'dosen' ? 'selected' : '' }}>
                                                            Dosen</option>
                                                        <option value="admin"
                                                            {{ old('jenis_pengguna') == 'admin' ? 'selected' : '' }}>
                                                            Admin</option>
                                                    </select>
                                                    @error('jenis_pengguna')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div> --}}
                                                <div class="mb-3">
                                                    <label class="small mb-1" for="fieldIdPengguna">
                                                        ID Pengguna <sup>1</sup>
                                                    </label>
                                                    <input
                                                        class="form-control @error('id_pengguna') is-invalid @enderror"
                                                        name="id_pengguna" id="fieldIdPengguna" type="text"
                                                        placeholder="Masukkan ID Pengguna"
                                                        value="{{ old('id_pengguna') }}" required />
                                                    <span class="d-block text-xs text-muted mt-1">
                                                        <sup>1</sup>
                                                        Gunakan Email atau NIM untuk mahasiswa dan NIP untuk dosen.
                                                    </span>
                                                    @error('id_pengguna')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small mb-1" for="fieldPassword">Password</label>
                                                    <input class="form-control @error('password') is-invalid @enderror"
                                                        name="password" id="fieldPassword" type="password"
                                                        placeholder="Masukkan Password" value="{{ old('password') }}"
                                                        required />
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="float-end">
                                                    <button type="submit" class="btn bg-navy text-white">
                                                        <span class="me-1">Masuk</span>
                                                        <i class="fa-solid fa-right-to-bracket"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/admin/js/scripts.js') }}"></script>
</body>

</html>
