<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kecamatans = Kecamatan::all();

        return view('kecamatan.index', compact('kecamatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'geojson' => 'required|file',
        ]);

        $file = $request->file('geojson');

        if ($file) {
            $fileName = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('File-Geojson'), $fileName); // Pindahkan file ke direktori public/File-Geojson
            $filePath = 'File-Geojson/' . $fileName; // Path relatif dari file yang disimpan

            // Baca dan dekode konten file GeoJSON
            $geojsonContent = file_get_contents(public_path($filePath));
            $geojsonData = json_decode($geojsonContent, true);

            // Memeriksa apakah data GeoJSON valid dan memiliki fitur
            if ($geojsonData !== null && isset($geojsonData['features'])) {
                foreach ($geojsonData['features'] as $feature) {
                    // Memeriksa apakah kunci 'properties' dan 'KECAMATAN' ada
                    if (isset($feature['properties']['KECAMATAN'])) {
                        $kecamatan = $feature['properties']['KECAMATAN'];

                        if (empty($kecamatan)) {
                            return redirect()->back()->with('error', 'Gagal mengupload data: Tidak ada data KECAMATAN');
                        }

                        // Cari atau buat district berdasarkan KECAMATAN
                        $district = Kecamatan::firstOrCreate(
                            ['nama_kecamatan' => $kecamatan],
                            ['user_id' => Auth::user()->id, 'geojson' => $filePath]
                        );
                    } else {
                        return redirect()->back()->with('error', 'Gagal mengupload data: Tidak ada data KECAMATAN');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Gagal mengupload data: Data GeoJSON tidak valid');
            }
        } else {
            return redirect()->back()->with('error', 'Gagal mengupload data: File tidak ditemukan');
        }

        return redirect()->route('data-kecamatan.index')
                        ->with('success', 'Data kecamatan berhasil ditambahkan');
    }


    /**
     * Display the specified resource.
     */
    public function show(Kecamatan $kecamatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kecamatan $kecamatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();

        return redirect()->route('data-kecamatan.index')->with('success', 'Data kecamatan berhasil dihapus.');
    }
}
