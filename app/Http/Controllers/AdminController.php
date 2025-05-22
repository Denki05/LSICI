<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Master\GuestImportTemplate;
use App\Imports\Master\GuestImport;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data['guests'] = Guest::all();

        return view('admin.dashboard', $data);
    }

    public function deleteGuest($id)
    {
        Guest::findOrFail($id)->delete();
        
        return redirect()->route('admin.dashboard')->with('success', 'Data berhasil dihapus');
    }

    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $guest = Guest::findOrFail($id);

        $image = $request->file('photo');
        $imageName = 'guest_' . time() . '_' . Str::random(6) . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('photos'), $imageName);

        // Simpan path relatif
        $guest->photo = 'photos/' . $imageName;
        $guest->save();

        return redirect()->back()->with('success', 'Foto berhasil diunggah.');
    }

    public  function export()
    {
        return Excel::download(new GuestImportTemplate, 'guest_template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new GuestImport, $request->file('import_file'));
            return redirect()->back()->with('success', 'Data tamu berhasil diimport.');
        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data.');
        }
    }
}