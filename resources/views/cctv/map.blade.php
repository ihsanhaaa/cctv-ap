@extends('layouts.app')

@section('title')
    Peta CCTV
@endsection

@section('content')
    @push('css-plugins')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <style>
            .carousel-image {
                width: 100%;
                height: auto;
                max-width: 320px;
                max-height: 320px;
                object-fit: cover;
            }
        </style>
    @endpush

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- header -->
        @include('components.navbar_admin')
        
        <!-- Start right Content here -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Peta CCTV</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">CCTV</a></li>
                                        <li class="breadcrumb-item active">Peta</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach ($errors->all() as $error)
                                <strong>{{ $error }}</strong><br>
                            @endforeach
                        </div>
                    @endif

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>Success!</strong> {{ $message }}.
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div>
                                        <div id="map" style="width: 100%; height: 500px;"></div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <!-- end row -->

                </div>
                
            </div>
            <!-- End Page-content -->
           
            <!-- footer -->
            @include('components.footer_admin')
            
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    @push('javascript-plugins')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Inisialisasi peta
                var map = L.map('map').setView([-0.05571, 109.34964], 13); // Koordinat default
        
                // Tambahkan tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
        
                // Definisikan ikon marker
                var activeIcon = L.icon({
                    iconUrl: 'icon-aktif.png',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    popupAnchor: [0, -30]
                });
        
                var inactiveIcon = L.icon({
                    iconUrl: 'icon-tidak-aktif.png',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    popupAnchor: [0, -30]
                });
        
                var unknownIcon = L.icon({
                    iconUrl: 'icon-tanpa-kondisi.png',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    popupAnchor: [0, -30]
                });
        
                // Layer Groups untuk status CCTV
                var activeLayer = L.layerGroup().addTo(map);
                var inactiveLayer = L.layerGroup();
                var unknownLayer = L.layerGroup();
        
                // Data CCTV dari backend
                var cctvs = @json($markaJalans);
        
                // Tambahkan marker untuk setiap CCTV
                cctvs.forEach(function (cctv) {
                    if (cctv.geojson) {
                        // Parse geojson data
                        var geojson = JSON.parse(cctv.geojson);
        
                        // Pastikan koordinat tersedia
                        if (geojson.type === "Point") {
                            var coordinates = geojson.coordinates;
        
                            // Tentukan ikon dan layer berdasarkan status_penanganan
                            var markerIcon;
                            var targetLayer;
        
                            if (cctv.status_penanganan === 'Aktif') {
                                markerIcon = activeIcon;
                                targetLayer = activeLayer;
                            } else if (cctv.status_penanganan === 'Tidak Aktif') {
                                markerIcon = inactiveIcon;
                                targetLayer = inactiveLayer;
                            } else {
                                markerIcon = unknownIcon;
                                targetLayer = unknownLayer;
                            }
        
                            // Tombol detail
                            var detailButton = `<a href="/data-cctv/${cctv.id}" target="_blank" class="btn btn-sm btn-info text-white mx-1">
                                <i class="fas fa-eye"></i> Lihat Detail</a>`;
        
                            // Konten popup dengan struktur tabel
                            var popupContent = `
                                <table class="table table-bordered">
                                    <tr><th>Nama CCTV</th><td>${cctv.nama_cctv}</td></tr>
                                    <tr><th>Tahun Pemasangan</th><td>${cctv.tahun_pemasangan}</td></tr>
                                    <tr><th>Status</th><td>${cctv.status_penanganan || 'Tidak Diketahui'}</td></tr>
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                ${detailButton}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            `;
        
                            // Tambahkan marker ke layer grup yang sesuai
                            L.marker([coordinates[1], coordinates[0]], { icon: markerIcon })
                                .bindPopup(popupContent)
                                .addTo(targetLayer);
                        }
                    }
                });
        
                // Tambahkan kontrol layer ke peta
                var overlays = {
                    "CCTV Aktif": activeLayer,
                    "CCTV Tidak Aktif": inactiveLayer,
                    "CCTV Tidak Memiliki Status": unknownLayer
                };
        
                L.control.layers(null, overlays, { collapsed: false }).addTo(map);
            });
        </script>
                   
        
    @endpush
@endsection