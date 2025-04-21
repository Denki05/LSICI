<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Guest;
use App\Models\Customer;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    // Menampilkan halaman utama dengan QR Code
    public function showQrCode()
    {
        return view('welcome');
    }

    // Menampilkan halaman formulir tamu
    public function showForm(Request $request)
    {
        $name = '';
        $company = '';

        if ($request->has('slug')) {
            $customer = Customer::where('slug', $request->slug)->first();
            if ($customer) {
                $name = $customer->name;
                $company = $customer->company;
            }
        }

        return view('guest-form', compact('name', 'company'));
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
    
            // Menghilangkan prefix base64 header jika ada
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'guest_' . time() . '_' . Str::random(6) . '.png';
    
            // Path ke folder public/photos
            $imagePath = public_path('photos/' . $imageName);
    
            // Simpan gambar ke public/photos/
            file_put_contents($imagePath, base64_decode($image));
    
            // Simpan path relatif di database
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

    public function storeFromQr(Request $request)
    {
        $request->validate([
            'slug' => 'required|string'
        ]);

        $slug = $request->input('slug');

        // Cari customer berdasarkan slug
        $customer = Customer::where('slug', $slug)->first();

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Cek apakah guest dengan slug ini sudah pernah dimasukkan
        $existingGuest = Guest::where('slug', $slug)->first();
        if ($existingGuest) {
            return response()->json(['message' => 'Guest already registered'], 200);
        }

        // Simpan sebagai guest baru
        Guest::create([
            'name' => $customer->name,
            'slug' => $slug,
            'is_invitation' => 1,
        ]);

        return response()->json(['message' => 'Guest created successfully']);
    }
}