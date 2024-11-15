<?php

namespace App\Http\Controllers;

use App\Models\Cctv;
use App\Models\Foto;
use App\Models\Kecamatan;
use App\Models\LokasiCctv;
use App\Models\StatusCctv;
use Illuminate\Http\Request;

class CctvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cctvs = Cctv::with('statusCctvTerbaru', 'kecamatan')->get();

        return view('cctv.index', compact('cctvs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,geojson',
        ]);

        $file = $request->file('file');

        // Save the uploaded GeoJSON file to the 'File-Geojson' directory
        if ($file) {
            $fileName = time() . '-' . $file->getClientOriginalName();
            $path = public_path('File-Geojson/' . $fileName);
            $file->move(public_path('File-Geojson'), $fileName);
        }

        // Use the saved file path for file_get_contents
        $data = json_decode(file_get_contents($path), true);

        foreach ($data['features'] as $feature) {
            $properties = $feature['properties'] ?? [];

            // Parse and save CCTV data
            $cctv = Cctv::create([
                'nama_cctv' => $properties['Name'] ?? 'Unnamed CCTV',
                'tahun_pemasangan' => $properties['timestamp'] ?? null,
                'domain' => $properties['panel cctv'] ?? null,
            ]);

            // Save location data with the CCTV ID
            LokasiCctv::create([
                'cctv_id' => $cctv->id,
                'nama_jalan' => $properties['Photo Loca'] ?? 'Unknown Location',
                'geojson' => json_encode($feature['geometry']),
            ]);
        }

        return redirect()->back()->with('success', 'Data CCTV berhasil diimport');
    }

    public function storeStatusCctv(Request $request, $id)
    {
        $request->validate([
            'tgl_temuan' => 'required|date',
            'status_penanganan' => 'required',
            'deskripsi' => 'nullable|string'
        ]);

        $cctv = Cctv::findOrFail($id);

        // Buat instance Status baru
        $status = new StatusCctv();
        $status->cctv_id = $cctv->id;
        $status->tgl_temuan = $request->tgl_temuan;
        $status->status_penanganan = $request->status_penanganan;
        $status->deskripsi = $request->deskripsi;
        $status->save();

        return redirect()->back()->with('success', 'Data status CCTV berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cctv = Cctv::findOrFail($id);

        return view('cctv.show', compact('cctv'));
    }

    public function showMap()
    {
        $markaJalans = Cctv::with(['lokasi', 'fotos' => function($query) {
            $query->latest()->take(1);
        }])->get();

        $kecamatans = Kecamatan::all();

        // dd($markaJalans);

        return view('cctv.map', compact('markaJalans', 'kecamatans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cctv = Cctv::findOrFail($id);
        $kecamatans = Kecamatan::all();
        
        return view('cctv.edit', compact('cctv', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_cctv' => 'required|string|max:255',
            'jenis_cctv' => 'nullable|string|max:255',
            'panel_cctv' => 'nullable|string|max:255',
            'tahun_pemasangan' => 'nullable|date',
            'domain' => 'nullable|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        $cctv = Cctv::findOrFail($id);
        $cctv->update([
            'nama_cctv' => $request->nama_cctv,
            'jenis_cctv' => $request->jenis_cctv,
            'panel_cctv' => $request->panel_cctv,
            'tahun_pemasangan' => $request->tahun_pemasangan,
            'domain' => $request->domain,
            'kecamatan_id' => $request->kecamatan_id,
        ]);

        return redirect()->route('data-cctv.show', $id)->with('success', 'Data CCTV berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cctv = Cctv::findOrFail($id);
        $cctv->delete();

        return redirect()->route('data-cctv.peta')->with('success', 'Data berhasil dihapus.');
    }

    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $file = $request->file('photo');

        if ($file) {
            // Menentukan path untuk penyimpanan
            $path = 'foto-cctv/';
            $new_name = 'cctv-' . $id . '-' . date('Ymd') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
    
            // Memindahkan file ke folder yang ditentukan
            $file->move(public_path($path), $new_name);
    
            // Menyimpan path ke database
            Foto::create([
                'cctv_id' => $id,
                'foto_path' => $path . $new_name
            ]);
    
            return response()->json(['message' => 'Foto berhasil diunggah.']);
        }

        return response()->json(['message' => 'Gagal mengunggah foto.'], 500);
    }

    public function uploadPhotoDetail(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required',
            'photo.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $cctv = Cctv::findOrFail($id);
        
        // Proses setiap file yang diunggah
        foreach ($request->file('photo') as $file) {
            // Menentukan path dan nama unik untuk setiap file
            $path = 'foto-cctv/';
            $new_name = 'cctv-' . $id . '-' . date('Ymd') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        
            // Memindahkan file ke folder yang ditentukan
            $file->move(public_path($path), $new_name);
        
            // Menyimpan path ke database
            Foto::create([
                'cctv_id' => $id,
                'foto_path' => $path . $new_name
            ]);
        }
    
        return redirect()->route('data-cctv.show', $cctv->id)->with('success', 'Foto berhasil diupload');
    }
}
