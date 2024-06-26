<?php

namespace App\Http\Controllers;

use App\Models\ProfilLulusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ProfilLulusanController extends Controller
{
    private function checkSuperadminAdminKajur()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Superadmin') || $userAuth->memilikiperan('Admin') || $userAuth->	memilikiperan('Kajur'); 
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
                $profilLulusans = ProfilLulusan::with('createdBy')->orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi()) {
                $profilLulusans = ProfilLulusan::with('createdBy')
                    ->where('program_studi', Auth::user()->dosen->program_studi)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            // transformasi data ke bentuk array
            $profilLulusans = $profilLulusans->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($profilLulusans)
                ->addColumn('aksi', function ($profilLulusans) {
                    return view('admin.pages.akademik.profil-lulusan.tombol-aksi', compact('profilLulusans'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.akademik.profil-lulusan.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Profil Lulusan',
            'subtitle' => 'Daftar Profil Lulusan',
            'active' => 'profil-lulusan',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.akademik.profil-lulusan.form', [
            'icon' => 'plus',
            'title' => 'Profil Lulusan',
            'subtitle' => 'Tambah Profil Lulusan',
            'active' => 'profil-lulusan',
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'judul' => 'required',
            'subjudul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    
        $validationMessages = [
            'judul.required' => 'Judul harus diisi!',
            'subjudul.required' => 'Subjudul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'gambar.required' => 'Gambar harus diisi!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.mimes' => 'File harus berupa jpeg, png, jpg!',
            'gambar.max' => 'File tidak boleh lebih dari 2 MB!',
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
            return redirect()->route('profilLulusan.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika data valid
            if ($request->hasFile('gambar')) {
                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('gambar')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'akademik/profil-lulusan';
                $request->file('gambar')->storeAs($storePath, $nameFile);

                // simpan data
                ProfilLulusan::create([
                    'judul' => $request->judul,
                    'subjudul' => $request->subjudul,
                    'deskripsi' => $request->deskripsi,
                    'gambar' => $nameFile,
                    'program_studi' => $program_studi,
                    'created_by' => Auth::user()->id,
                ]);

                return redirect()->route('profilLulusan.index')->with('success', 'Data berhasil ditambahkan.');
            } else {
                return redirect()->route('profilLulusan.index')->with('error', 'Data gagal ditambahkan. File gambar tidak boleh kosong!');
            }
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('profilLulusan.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model ProfilLulusan berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $profilLulusan = ProfilLulusan::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $profilLulusan = ProfilLulusan::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // tampilkan halaman
            return view('admin.pages.akademik.profil-lulusan.form', [
                'icon' => 'edit',
                'title' => 'Profil Lulusan',
                'subtitle' => 'Edit Profil Lulusan',
                'active' => 'profil-lulusan',
                'profilLulusan' => $profilLulusan,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('profilLulusan.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('profilLulusan.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'judul' => 'required',
            'subjudul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    
        $validationMessages = [
            'judul.required' => 'Judul harus diisi!',
            'subjudul.required' => 'Subjudul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.mimes' => 'File harus berupa jpeg, png, jpg!',
            'gambar.max' => 'File tidak boleh lebih dari 2 MB!',
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
            return redirect()->route('profilLulusan.index')->with('error', 'Data gagal diperbarui!. Anda tidak mempunyai hak akses.');
        }

        try { // jika data valid
            // ambil data dari model ProfilLulusan berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $profilLulusan = ProfilLulusan::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $profilLulusan = ProfilLulusan::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            if ($request->hasFile('gambar')) {
                // cek apakah ada file yang lama
                if (Storage::exists('akademik/profil-lulusan/' . $profilLulusan->gambar)) {
                    // hapus file
                    Storage::delete('akademik/profil-lulusan/' . $profilLulusan->gambar);
                }

                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('gambar')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'akademik/profil-lulusan';
                $request->file('gambar')->storeAs($storePath, $nameFile);

                $data = [
                    'judul' => $request->judul,
                    'subjudul' => $request->subjudul,
                    'deskripsi' => $request->deskripsi,
                    'gambar' => $nameFile,
                    'program_studi' => $request->program_studi,
                    'updated_by' => Auth::user()->id,
                ];
            } else {
                $data = [
                    'judul' => $request->judul,
                    'subjudul' => $request->subjudul,
                    'deskripsi' => $request->deskripsi,
                    'program_studi' => $request->program_studi,
                    'updated_by' => Auth::user()->id,
                ];
            }

            $profilLulusan->update($data);

            return redirect()->route('profilLulusan.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('profilLulusan.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal mengupdate data
            return redirect()->route('profilLulusan.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model ProfilLulusan berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $profilLulusan = ProfilLulusan::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $profilLulusan = ProfilLulusan::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus file dari storage/penyimpanan
            if (Storage::exists('akademik/profil-lulusan/' . $profilLulusan->gambar)) {
                Storage::delete('akademik/profil-lulusan/' . $profilLulusan->gambar);
            }

            // hapus data dari table profilLulusan
            $profilLulusan->delete();

            return redirect()->route('profilLulusan.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('profilLulusan.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('profilLulusan.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
