@extends('layouts.app')

@section('title')
    Edit Data CCTV
@endsection

@section('content')
    @push('css-plugins')
        
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
                                <h4 class="mb-sm-0">Edit Data CCTV</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('data-cctv.index') }}">CCTV</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Detail CCTV</a></li>
                                        <li class="breadcrumb-item active">Edit CCTV</li>
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
        
                                    <h4 class="card-title">Edit Data CCTV {{ $cctv->nama_cctv }}</h4>

                                    <form action="{{ route('data-cctv.update', $cctv->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row mb-3">
                                            <label for="nama_cctv" class="col-sm-2 col-form-label">Nama CCTV</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('nama_cctv') is-invalid @enderror" type="text" id="nama_cctv" name="nama_cctv" value="{{ old('nama_cctv', $cctv->nama_cctv ?? '') }}" required>
                                                @error('nama_cctv')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="jenis_cctv" class="col-sm-2 col-form-label">Jenis CCTV</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('jenis_cctv') is-invalid @enderror" type="text" id="jenis_cctv" name="jenis_cctv" value="{{ old('jenis_cctv', $cctv->jenis_cctv ?? '') }}" required>
                                                @error('jenis_cctv')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="panel_cctv" class="col-sm-2 col-form-label">Panel CCTV</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('panel_cctv') is-invalid @enderror" type="text" id="panel_cctv" name="panel_cctv" value="{{ old('panel_cctv', $cctv->panel_cctv ?? '') }}" required>
                                                @error('panel_cctv')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="tahun_pemasangan" class="col-sm-2 col-form-label">Tahun Pemasangan</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('tahun_pemasangan') is-invalid @enderror" type="date" id="tahun_pemasangan" name="tahun_pemasangan" value="{{ old('tahun_pemasangan', $cctv->tahun_pemasangan ?? '') }}" required>
                                                @error('tahun_pemasangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="domain" class="col-sm-2 col-form-label">Domain</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('domain') is-invalid @enderror" type="text" id="domain" name="domain" value="{{ old('domain', $cctv->domain ?? '') }}" required>
                                                @error('domain')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="kecamatan_id" class="col-sm-2 col-form-label">Kecamatan</label>
                                            <div class="col-sm-10">
                                                <select class="form-control @error('kecamatan_id') is-invalid @enderror" id="kecamatan_id" name="kecamatan_id" required>
                                                    <option value="">-- Pilih Kecamatan --</option>
                                                    @foreach($kecamatans as $kecamatan)
                                                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $cctv->kecamatan_id ?? '') == $kecamatan->id ? 'selected' : '' }}>
                                                            {{ $kecamatan->nama_kecamatan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('kecamatan_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update Data CCTV</button>
                                    </form>


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
        
    @endpush
@endsection