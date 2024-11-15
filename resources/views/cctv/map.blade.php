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
            // Initialize the map
            var map = L.map('map').setView([-0.03568, 109.33296], 13);
        
            // Add OpenStreetMap tile layer
            var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
        
            // Add Esri World Imagery tile layer
            var esriLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles © Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });
        
            // Data from server
            var markaJalans = @json($markaJalans);

            var customIcon = L.icon({
                iconUrl: 'cctv_5695715.png',  // Ganti dengan path icon yang ingin digunakan
                iconSize: [32, 32],               // Sesuaikan ukuran icon
                iconAnchor: [16, 32],             // Anchor point untuk icon
                popupAnchor: [0, -32]             // Posisi popup relatif terhadap icon
            });
        
            // Iterate through markaJalans data
            markaJalans.forEach(function(marka) {
                if (marka.lokasi) {
                    var geojson = JSON.parse(marka.lokasi.geojson);
        
                    // Display picture if available
                    var pictureUrl = marka.fotos.length > 0 ? `{{ asset('') }}${marka.fotos[0].foto_path}` : 'Tidak ada foto';
        
                    // Form to upload photo
                    var addPhotoForm = `
                        <form id="uploadForm${marka.id}" enctype="multipart/form-data" class="mt-2">
                            <input type="file" name="photo" accept="image/*" class="form-control form-control-sm mb-2">
                            <button type="button" class="btn btn-sm btn-primary" onclick="uploadPhoto(${marka.id})">Upload Foto</button>
                            <div id="uploadStatus${marka.id}" class="mt-2"></div>
                        </form>
                    `;
        
                    // Delete form and detail button
                    var deleteForm = `
                        <form action="/data-cctv/${marka.id}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" class="mx-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    `;
                    var detailButton = `<a href="/data-cctv/${marka.id}" target="_blank" class="btn btn-sm btn-info text-white mx-1"><i class="fas fa-eye"></i> Lihat Detail</a>`;
        
                    // Popup content with table structure
                    var popupContent = `
                        <div class="carousel-container mb-3">
                            ${marka.fotos.length === 0 ? '<p class="text-center">Tidak ada foto</p>' : `<img src="${pictureUrl}" class="carousel-image" alt="Foto ${marka.nama_marka}">`}
                        </div>
                        <table class="table table-bordered">
                            <tr><th>Nama CCTV</th><td>${marka.nama_cctv}</td></tr>
                            <tr><th>Tahun Pemasangan</th><td>${marka.tahun_pemasangan}</td></tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        ${detailButton}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    `;
        
                    // Create GeoJSON layer and add it directly to the map
                    L.geoJSON(geojson, {
                        pointToLayer: function(feature, latlng) {
                            return L.marker(latlng, { icon: customIcon });
                        },
                        onEachFeature: function(feature, layer) {
                            layer.bindPopup(popupContent);
                        }
                    }).addTo(map);
                }
            });

            var layerBatasKecamatanData = @json($kecamatans);
            layerBatasKecamatanData.forEach(data => {
                var geojsonUrl = data.geojson;

                fetch(geojsonUrl)
                .then(response => response.json())
                .then(geojsonData => {
                    // Add GeoJSON layer with popup for each feature
                    L.geoJSON(geojsonData, {
                        style: {
                            color: 'green',
                            weight: 1
                        },
                        onEachFeature: function (feature, layer) {
                            // Get Kecamatan name from properties
                            var kecamatanName = feature.properties.KECAMATAN;

                            // Bind a popup to each feature with the Kecamatan name
                            layer.bindPopup(`<strong>Nama Kecamatan:</strong> ${kecamatanName}`);
                        }
                    }).addTo(map);
                })
                .catch(error => {
                    console.error("Error fetching or processing GeoJSON data:", error);
                });
            });
        
            // Base layers
            var baseLayers = {
                "OpenStreetMap": osmLayer,
                "Esri World Imagery": esriLayer
            };
        
            // Layer control
            L.control.layers(baseLayers).addTo(map);
        
            // Function to upload photo
            function uploadPhoto(markaJalanId) {
                var formData = new FormData(document.getElementById('uploadForm' + markaJalanId));
        
                fetch(`/cctv/${markaJalanId}/upload_photo`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('uploadStatus' + markaJalanId).innerText = data.message;
                })
                .catch(error => {
                    document.getElementById('uploadStatus' + markaJalanId).innerText = 'Upload gagal. Coba lagi.';
                });
            }
        </script>
    @endpush
@endsection