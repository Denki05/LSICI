<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Master\CustomersImport;
use App\Exports\Master\CustomerImportTemplate;
use App\Exports\Master\GuestExport;
use App\Models\Customer;
use App\Http\Controllers\ApiConsumerController;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class RsvpController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%");
        }

        if ($request->filled('customer_id')) {
            $query->where('id', $request->input('customer_id'));
        }

        if ($request->filled('officer')) {
            $query->where('officer', $request->input('officer'));
        }

        if ($request->filled('attendance')) {
            $query->where('attendance', $request->input('attendance'));
        }

        if ($request->filled('invitation_status')) {
            $query->where('is_invitation_generated', $request->input('invitation_status'));
        }

        $customers = $query->get();
        $officers = Customer::select('officer')->whereNotNull('officer')->distinct()->get();

        return view('rsvp.index', compact('customers', 'officers'));
    }

    public function generateInvitation($id)
    { 
        $guest = Customer::findOrFail($id);
        if (!$guest->slug) {
            $guest->slug = Str::slug($guest->name) . '-' . Str::random(5);
            $guest->is_invitation_generated = true;

            // Buat QR code yang hanya berisi slug
            $qrDirectory = public_path('qrcodes');
            if (!File::exists($qrDirectory)) {
                File::makeDirectory($qrDirectory, 0755, true);
            }

            $fileName = $guest->slug . '.svg';
            $filePath = $qrDirectory . '/' . $fileName;

            QrCode::format('svg')->size(300)->generate($guest->slug, $filePath); // simpan sebagai .svg

            // Simpan path QR code jika ingin ditampilkan kembali
            $guest->qr_code_path = 'qrcodes/' . $fileName;

            $guest->save();
        }

        return redirect()->back()->with('success', 'Undangan berhasil digenerate!');
    }

    public function page_invitation($slug)
    {
        $decode = base64_decode($slug);

        $data['guest'] = Customer::where('slug', $decode)->firstOrFail();

        return view('rsvp.page', $data);
    }

    public function updateInvitation(Request $request, $slug)
    {
        $guest = Customer::where('slug', $slug)->firstOrFail();

        $guest->update([
            'attendance' => $request->input('is_attending')
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui & QR code disiapkan');
    }

    public  function export()
    {
        return Excel::download(new CustomerImportTemplate, 'customers_template.xlsx');
    }

    public function import(Request $request, ApiConsumerController $apiConsumer)
    {
        $reference = $apiConsumer->getCustomerNameReferences();
        $names = $reference['names'];
        $namesWithCity = $reference['names_with_city'];

        $importer = new CustomersImport($names, $namesWithCity);
        Excel::import($importer, $request->file('import_file'));

        return redirect()->route('admin.rsvp')->with([
            'import_success' => $importer->successData,
            'import_failed'  => $importer->failedData,
        ]);
    }
    
    public function export_guests()
    {
        return Excel::download(new GuestExport, 'guestbook.xlsx');
    }

    public function delete($id)
    {
        $customer = Customer::findOrFail($id);

        // Delete the QR code file if it exists
        if ($customer->slug && $customer->qr_code_path) {
            $qrFilePath = public_path($customer->qr_code_path);
            if (File::exists($qrFilePath)) {
                File::delete($qrFilePath);
            }
        }

        $customer->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}