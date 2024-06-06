<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class AgendaController extends Controller
{
    private function checkSuperadminAdminKajur()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Superadmin') || $userAuth->memilikiperan('Admin') || $userAuth->memilikiperan('Kajur'); 
    }

    private function checkKaprodi()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Kaprodi');
    }
    
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            if ($this->checkSuperadminAdminKajur()) {
                $agendas = Agenda::orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi()) {
                $agendas = Agenda::where('program_studi', Auth::user()->dosen->program_studi)->orderBy('created_at', 'desc')->get();
            }

            // transformasi data ke bentuk array
            $agendas = $agendas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($agendas)
                ->addColumn('aksi', function ($agendas) {
                    return view('admin.pages.konten.agenda.tombol-aksi', compact('agendas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.konten.agenda.index', [
            'icon' => 'layout',
            'title' => 'Agenda',
            'subtitle' => 'Daftar Agenda',
            'active' => 'agenda',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.konten.agenda.form', [
            'icon' => 'plus',
            'title' => 'Agenda',
            'subtitle' => 'Tambah Agenda',
            'active' => 'agenda',
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'judul' => 'required',
            'deskripsi' => 'required',
            'penyelenggara' => 'required',
            'tanggal_kegiatan' => 'nullable|date',
            'dari_jam' => 'nullable|date_format:H:i',
            'sampai_jam' => 'nullable|date_format:H:i',
            'thumbnail' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    
        $validationMessages = [
            'judul.required' => 'Judul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'penyelenggara.required' => 'Penyelenggara harus diisi!',
            'tanggal_kegiatan.date' => 'Format tanggal kegiatan harus sesuai!',
            'dari_jam.date_format' => 'Format jam harus sesuai!',
            'sampai_jam.date_format' => 'Format jam harus sesuai!',
            'thumbnail.image' => 'File harus berupa gambar!',
            'thumbnail.mimes' => 'File harus berupa jpeg, png, jpg!',
            'thumbnail.max' => 'File tidak boleh lebih dari 2 MB!',
        ];
    
        if ($this->checkSuperadminAdminKajur()) {
            $validationRules['program_studi'] = 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI';
            $validationMessages['program_studi.required'] = 'Program Studi harus dipilih!';
            $validationMessages['program_studi.in'] = 'Program Studi tidak valid!';
        }
    
        $request->validate($validationRules, $validationMessages);

        if ($this->checkSuperadminAdminKajur()) {
            $program_studi = $request->program_studi;
        } else if ($this->checkKaprodi()) {
            $program_studi = Auth::user()->dosen->program_studi;
        } else {
            return redirect()->route('agenda.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try {
            // Jika ada file thumbnail yang diunggah, simpan ke penyimpanan
            if ($request->hasFile('thumbnail')) {
                // Namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
    
                // Simpan file ke penyimpanan
                $storePath = 'konten/agenda';
                $request->file('thumbnail')->storeAs($storePath, $nameFile);
            } else {
                $nameFile = null;
            }
    
            // Buat agenda baru dan simpan ke database
            Agenda::create([
                'program_studi' => $program_studi,
                'judul' => $request->judul,
                'slug' => Str::slug($request->judul),
                'deskripsi' => $request->deskripsi,
                'penyelenggara' => $request->penyelenggara,
                'tanggal_kegiatan' => $request->tanggal_kegiatan ? $request->tanggal_kegiatan : null,
                'dari_jam' => $request->dari_jam ? $request->dari_jam : null,
                'sampai_jam' => $request->sampai_jam ? $request->sampai_jam : null,
                'thumbnail' => $nameFile,
                'created_by' => Auth::user()->id
            ]);
    
            return redirect()->route('agenda.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika bermasalah dalam menyimpan data
            return redirect()->route('agenda.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model Agenda berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $agenda = Agenda::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $agenda = Agenda::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            } else {
                return redirect()->route('agenda.index')->with('error', 'Halaman bermasalah!. Anda tidak mempunyai hak akses.');
            }

            // tampilkan halaman
            return view('admin.pages.konten.agenda.form', [
                'icon' => 'edit',
                'title' => 'Agenda',
                'subtitle' => 'Edit Agenda',
                'active' => 'agenda',
                'agenda' => $agenda,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('agenda.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('agenda.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'judul' => 'required',
            'deskripsi' => 'required',
            'penyelenggara' => 'required',
            'tanggal_kegiatan' => 'nullable|date',
            'dari_jam' => 'nullable|date_format:H:i',
            'sampai_jam' => 'nullable|date_format:H:i',
            'thumbnail' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    
        $validationMessages = [
            'judul.required' => 'Judul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'penyelenggara.required' => 'Penyelenggara harus diisi!',
            'tanggal_kegiatan.date' => 'Format tanggal kegiatan harus sesuai!',
            'dari_jam.date_format' => 'Format jam harus sesuai!',
            'sampai_jam.date_format' => 'Format jam harus sesuai!',
            'thumbnail.image' => 'File harus berupa gambar!',
            'thumbnail.mimes' => 'File harus berupa jpeg, png, jpg!',
            'thumbnail.max' => 'File tidak boleh lebih dari 2 MB!',
        ];
    
        if ($this->checkSuperadminAdminKajur()) {
            $validationRules['program_studi'] = 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI';
            $validationMessages['program_studi.required'] = 'Program Studi harus dipilih!';
            $validationMessages['program_studi.in'] = 'Program Studi tidak valid!';
        }
    
        $request->validate($validationRules, $validationMessages);

        if ($this->checkSuperadminAdminKajur()) {
            $program_studi = $request->program_studi;
        } else if ($this->checkKaprodi()) {
            $program_studi = Auth::user()->dosen->program_studi;
        } else {
            return redirect()->route('agenda.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika id ditemukan
            // ambil data dari model Agenda berdasarkan id
            $agenda = Agenda::findOrFail($id);

            // Jika ada file thumbnail yang diunggah, simpan ke penyimpanan
            if ($request->hasFile('thumbnail')) {
                // Namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();

                // Simpan file ke penyimpanan
                $storePath = 'konten/agenda';
                $request->file('thumbnail')->storeAs($storePath, $nameFile);

                // Hapus gambar lama jika ada
                if ($agenda->thumbnail) {
                    // cek apakah ada file di storage
                    if (Storage::exists('konten/agenda/' . $agenda->thumbnail)) {
                        // hapus file
                        Storage::delete('konten/agenda/' . $agenda->thumbnail);
                    }
                }

                // Perbarui thumbnail dengan yang baru
                $agenda->thumbnail = $nameFile;
            }

            // Perbarui data agenda
            $agenda->program_studi = $program_studi;
            $agenda->judul = $request->judul;
            $agenda->slug = Str::slug($request->judul);
            $agenda->deskripsi = $request->deskripsi;
            $agenda->penyelenggara = $request->penyelenggara;
            $agenda->tanggal_kegiatan = $request->tanggal_kegiatan ? $request->tanggal_kegiatan : null;
            $agenda->dari_jam = $request->dari_jam ? $request->dari_jam : null;
            $agenda->sampai_jam = $request->sampai_jam ? $request->sampai_jam : null;
            $agenda->updated_by = Auth::user()->id;
            $agenda->save();

            return redirect()->route('agenda.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) { // jika bermasalah dalam menyimpan data
            return redirect()->route('agenda.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model Agenda berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $agenda = Agenda::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $agenda = Agenda::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus file dari storage/penyimpanan
            if (Storage::exists('konten/agenda/' . $agenda->thumbnail)) {
                Storage::delete('konten/agenda/' . $agenda->thumbnail);
            }

            // hapus data dari table agenda
            $agenda->delete();

            return redirect()->route('agenda.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('agenda.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('agenda.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
