<!DOCTYPE html>
<html lang="id-ID">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Dasbor WEB PTI - SI" />
    <meta name="author" content="" />
    <title>
        @if (isset($title))
            @role('Superadmin|Admin|Kajur')
                {{ $title . ' | Dasbor WEB PTI - SI' ?? '' }}
            @endrole
            @role('Kaprodi|Dosen')
                @php
                    $dosenTitleProdi = Auth::user()->dosen->program_studi == "PEND. TEKNOLOGI INFORMASI" ? "PTI" : "SI";
                @endphp
                {{ $title . ' | Dasbor WEB ' . $dosenTitleProdi ?? '' }}
            @endrole
            @role('Mahasiswa')
                @php
                    $mahasiswaTitleProdi = Auth::user()->mahasiswa->program_studi == "PEND. TEKNOLOGI INFORMASI" ? "PTI" : "SI";
                @endphp
                {{ $title . ' | Dasbor WEB ' . $mahasiswaTitleProdi ?? '' }}
            @endrole
        @else
            Dasbor WEB
        @endif
    </title>

    @stack('css')

    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" />
    {{-- <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" /> --}}
    <script data-search-pseudo-elements="" defer=""
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
</head>

<body class="nav-fixed">
    @include('admin.layouts.header')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('admin.layouts.sidebar')
        </div>
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            @include('admin.layouts.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    <script src="{{ asset('assets/admin/js/scripts.js') }}"></script>

    @stack('js')
</body>

</html>
