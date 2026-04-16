<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MagangController extends Controller
{
    // ==========================================
    // BAGIAN 1: FITUR USER (MAHASISWA)
    // ==========================================

    public function landing() {
        return view('magang.landing');
    }

    public function form() {
        return view('magang.form'); 
    }

    public function status() {
        return view('magang.status');
    }

    // PROSES DAFTAR (Simpan Data)
    public function submit(Request $request) {
        
        // 1. SETUP ATURAN VALIDASI
        $rules = [
            'jenis_magang'     => 'required|in:fresh_graduate,mahasiswa',
            'nama'             => 'required|string|max:255',
            'email'            => 'required|email|unique:pendaftars,email',
            'no_hp'            => 'required|numeric',
            'alamat'           => 'required|string',
            'asal_kampus'      => 'required|string',
            'jurusan'          => 'required|string',
            'ipk'              => 'required|numeric|min:0|max:4', 
            'tgl_mulai'        => 'required|date',
            'tgl_selesai'      => 'required|date|after:tgl_mulai',
            
            'cv'               => 'required|mimes:pdf|max:2048',
            'transkrip'        => 'required|mimes:pdf|max:2048',
            'surat_permohonan' => 'required|mimes:pdf|max:2048',
            
            // Validasi untuk multiple file upload
            'dokumen_pendukung'   => 'nullable|array|max:5', 
            'dokumen_pendukung.*' => 'mimes:pdf|max:5120',
        ];

        // Validasi khusus jika memilih Mahasiswa Aktif
        if ($request->jenis_magang == 'mahasiswa') {
            $rules['semester'] = 'required|integer';
            $rules['surat_pengantar'] = 'required|mimes:pdf|max:2048';
            $rules['tipe_mahasiswa'] = 'required|in:pkl,riset'; 
        }

        $request->validate($rules);
        
        // 2. FORMAT DATA
        $start = Carbon::parse($request->tgl_mulai)->translatedFormat('d F Y');
        $end   = Carbon::parse($request->tgl_selesai)->translatedFormat('d F Y');
        $periodeString = "$start s/d $end";

        // 3. PROSES UPLOAD FILE
        $cvPath = $request->file('cv')->store('dokumen_magang', 'public');
        $transkripPath = $request->file('transkrip')->store('dokumen_magang', 'public');
        $suratPermohonanPath = $request->file('surat_permohonan')->store('dokumen_magang', 'public');

        $suratPengantarPath = null;
        if ($request->hasFile('surat_pengantar')) {
            $suratPengantarPath = $request->file('surat_pengantar')->store('dokumen_magang', 'public');
        }

        // Logika simpan array path untuk multiple file
        $dokumenPendukungPaths = []; 
        if ($request->hasFile('dokumen_pendukung')) {
            foreach ($request->file('dokumen_pendukung') as $file) {
                $dokumenPendukungPaths[] = $file->store('dokumen_magang', 'public');
            }
        }

        // 4. SIMPAN KE DATABASE
        Pendaftar::create([
            'jenis_magang'   => $request->jenis_magang,
            'tipe_mahasiswa' => ($request->jenis_magang == 'mahasiswa') ? $request->tipe_mahasiswa : null,
            'nama'           => $request->nama,
            'email'          => $request->email,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'asal_kampus'    => $request->asal_kampus,
            'jurusan'        => $request->jurusan,
            'ipk'            => $request->ipk,
            'semester'       => ($request->jenis_magang == 'mahasiswa') ? $request->semester : null,
            'periode'        => $periodeString,
            'status'         => 'Menunggu',
            
            'cv_path'                => $cvPath,
            'transkrip_path'         => $transkripPath,
            'surat_permohonan_path'  => $suratPermohonanPath,
            'surat_path'             => $suratPengantarPath,
            // Simpan array paths
            'dokumen_pendukung_path' => !empty($dokumenPendukungPaths) ? $dokumenPendukungPaths : null
        ]);

        return redirect()->route('magang.status')
            ->with('success', 'Pendaftaran berhasil! Berkas Anda sedang kami verifikasi.');
    }

    public function cekStatusForm() {
        return view('magang.cek_status_form');
    }

    public function cekStatusResult(Request $request) {
        $request->validate(['email' => 'required|email']);
        $pendaftar = Pendaftar::where('email', $request->email)->first();

        if (!$pendaftar) {
            return back()->with('error', 'Email tidak ditemukan. Pastikan Anda sudah mendaftar.');
        }
        return view('magang.status', compact('pendaftar'));
    }


    // ==========================================
    // BAGIAN 2: FITUR ADMIN (BACKEND)
    // ==========================================

    public function adminDashboard(Request $request) {
        $query = Pendaftar::latest();

        // 1. Filter Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('asal_kampus', 'LIKE', "%{$search}%");
            });
        }
        
        // 2. Filter Status
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        // 3. Filter Jenis Magang
        if ($request->filled('filter_jenis')) {
            $query->where('jenis_magang', $request->filter_jenis);
        }

        $pendaftars = $query->paginate(10)->withQueryString();

        // --- STATISTIK ---
        $total_pelamar   = Pendaftar::count();
        $total_menunggu  = Pendaftar::where('status', 'Menunggu')->count();
        $total_diterima  = Pendaftar::where('status', 'Diterima')->count();
        $total_ditolak   = Pendaftar::where('status', 'Ditolak')->count(); 
        
        $total_fg        = Pendaftar::where('jenis_magang', 'fresh_graduate')->count();
        $total_mhs       = Pendaftar::where('jenis_magang', 'mahasiswa')->count();
        $total_wawancara = Pendaftar::where('status', 'Wawancara')->count();

        return view('magang.admin.dashboard', compact(
            'pendaftars', 
            'total_pelamar', 'total_menunggu', 'total_diterima', 'total_ditolak',
            'total_fg', 'total_mhs', 'total_wawancara'
        ));
    }

    // FITUR EXPORT EXCEL (CSV)
    public function exportExcel(Request $request)
    {
        $query = Pendaftar::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('asal_kampus', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        if ($request->filled('filter_jenis')) {
            $query->where('jenis_magang', $request->filter_jenis);
        }

        $pendaftars = $query->get(); 

        $filename = "Data-Pelamar-Magang-" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($pendaftars) {
            $file = fopen('php://output', 'w');
            
            // Menambahkan kolom IPK ke CSV
            fputcsv($file, ['No', 'Nama Lengkap', 'Jenis Magang', 'Tipe Mahasiswa', 'Email', 'No HP', 'Asal Kampus', 'Jurusan', 'IPK', 'Semester', 'Periode', 'Status']);

            foreach ($pendaftars as $index => $row) {
                $tipeMhs = '-';
                if ($row->tipe_mahasiswa == 'pkl') $tipeMhs = 'PKL';
                if ($row->tipe_mahasiswa == 'riset') $tipeMhs = 'Riset/Penelitian';

                fputcsv($file, [
                    $index + 1,
                    $row->nama,
                    $row->jenis_magang == 'fresh_graduate' ? 'Fresh Graduate' : 'Mahasiswa',
                    $tipeMhs,
                    $row->email,
                    $row->no_hp ? "'".$row->no_hp : '-',
                    $row->asal_kampus,
                    $row->jurusan,
                    $row->ipk ?? '-', 
                    $row->semester ?? '-',
                    $row->periode,
                    $row->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function adminDetail($id) {
        $pendaftar = Pendaftar::findOrFail($id);
        return view('magang.admin.detail', compact('pendaftar'));
    }

    public function loginForm() {
        return view('magang.admin.login');
    }

    public function loginSubmit(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($request->email == 'admin@pln.co.id' && $request->password == 'admin') {
            session(['is_admin' => true]); 
            return redirect()->route('magang.admin.dashboard'); 
        }

        return back()->with('error', 'Email atau Password salah!');
    }

    public function logout() {
        session()->forget('is_admin');
        return redirect()->route('magang.admin.login')->with('success', 'Berhasil logout.');
    }

    public function updateStatus(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        if ($request->status == 'Wawancara') {
            $request->validate([
                'tgl_wawancara' => 'required|date',
                'lokasi'        => 'required|string',
                'pesan'         => 'nullable|string'
            ]);

            $pendaftar->wawancara_waktu  = $request->tgl_wawancara;
            $pendaftar->wawancara_lokasi = $request->lokasi;
            $pendaftar->pesan            = $request->pesan;
        }

        $pendaftar->status = $request->status;
        
        if ($request->has('pesan') && $request->status != 'Wawancara') {
            $pendaftar->pesan = $request->pesan;
        }

        $pendaftar->save(); 

        return back()->with('success', "Status berhasil diperbarui menjadi {$request->status}.");
    }
}