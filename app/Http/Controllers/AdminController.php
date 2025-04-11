<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;

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
}