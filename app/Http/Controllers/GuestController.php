<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Guest;

class GuestController extends Controller
{
    // Menampilkan halaman utama dengan QR Code
    public function showQrCode()
    {
        return view('welcome');
    }

    // Menampilkan halaman formulir tamu
    public function showForm()
    {
        return view('guest-form');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'company' => 'required|string',
            'photo' => 'nullable|string', // Base64 image
        ]);
    
        $data = $request->all();
    
        // Jika ada foto yang dikirim dalam format Base64
        if (!empty($request->photo)) {
            $image = $request->photo;
    
            // Menghilangkan prefix "data:image/png;base64,"
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'guest_' . time() . '.png'; // Nama file unik
    
            // Simpan gambar ke storage Laravel (public/photos/)
            Storage::disk('public')->put('photos/' . $imageName, base64_decode($image));
    
            // Simpan path gambar di database
            $data['photo'] = 'photos/' . $imageName;
        }
    
        Guest::create($data);
    
        return redirect('/guest-form')->with('success', 'Data berhasil disimpan!');
    }    

    // Menampilkan daftar tamu di dashboard admin
    public function index()
    {
        $guests = Guest::all();
        return view('admin.guests', compact('guests'));
    }
}