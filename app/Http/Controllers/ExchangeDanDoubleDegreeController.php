<?php

namespace App\Http\Controllers;

use App\Models\ExchangeDanDoubleDegree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class ExchangeDanDoubleDegreeController extends Controller
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

    private function checkDosen()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Dosen');
    }

    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            if ($this->checkSuperadminAdminKajur()) {
                $edds = ExchangeDanDoubleDegree::with('createdBy')->orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                $edds = ExchangeDanDoubleDegree::with('createdBy')
                ->where('created_by', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();
            }

            // transformasi data ke bentuk array
            $edds = $edds->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($edds)
                ->addColumn('aksi', function ($edds) {
                    return view('admin.pages.mahasiswa-dan-alumni.peluang-mahasiswa.exchange-dan-double-degree.tombol-aksi', compact('edds'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.peluang-mahasiswa.exchange-dan-double-degree.index', [
            'icon' => 'fas fa-users-rays',
            'title' => 'Exchange dan Double Degree',
            'subtitle' => 'Daftar Informasi Exchange dan Double Degree',
            'active' => 'exchange-dan-double-degree',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.peluang-mahasiswa.exchange-dan-double-degree.form', [
            'icon' => 'plus',
            'title' => 'Exchange dan Double Degree',
            'subtitle' => 'Tambah Informasi Exchange dan Double Degree',
            'active' => 'exchange-dan-double-degree',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'gambar.required' => 'Gambar harus diisi!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.mimes' => 'File harus berupa jpeg, png, jpg!',
            'gambar.max' => 'File tidak boleh lebih dari 2 MB!',
        ]);

        try { // jika data valid
            if ($request->hasFile('gambar')) {
                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('gambar')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'mahasiswa-dan-alumni/peluang-mahasiswa/exchange-dan-double-degree/';
                $request->file('gambar')->storeAs($storePath, $nameFile);

                // simpan data
                ExchangeDanDoubleDegree::create([
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'gambar' => $nameFile,
                    'created_by' => auth()->user()->id,
                ]);

                return redirect()->route('edd.index')->with('success', 'Data berhasil ditambahkan.');
            } else {
                return redirect()->route('edd.index')->with('error', 'Data gagal ditambahkan. File gambar tidak boleh kosong!');
            }
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('edd.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            if ($this->checkSuperadminAdminKajur()) {
                // ambil data dari model ExchangeDanDoubleDegree berdasarkan id
                $edd = ExchangeDanDoubleDegree::findOrFail($id);
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                // ambil data dari model ExchangeDanDoubleDegree berdasarkan id dan created_by
                $edd = ExchangeDanDoubleDegree::where('id', $id)->where('created_by', auth()->user()->id)->firstOrFail();
            }

            // tampilkan halaman
            return view('admin.pages.mahasiswa-dan-alumni.peluang-mahasiswa.exchange-dan-double-degree.form', [
                'icon' => 'edit',
                'title' => 'Exchange dan Double Degree',
                'subtitle' => 'Edit Informasi Exchange dan Double Degree',
                'active' => 'exchange-dan-double-degree',
                'edd' => $edd,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('edd.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('edd.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.mimes' => 'File harus berupa jpeg, png, jpg!',
            'gambar.max' => 'File tidak boleh lebih dari 2 MB!',
        ]);

        try { // jika data valid
            if ($this->checkSuperadminAdminKajur()) {
                // ambil data dari model ExchangeDanDoubleDegree berdasarkan id
                $edd = ExchangeDanDoubleDegree::findOrFail($id);
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                // ambil data dari model ExchangeDanDoubleDegree berdasarkan id dan created_by
                $edd = ExchangeDanDoubleDegree::where('id', $id)->where('created_by', auth()->user()->id)->firstOrFail();
            }

            if ($request->hasFile('gambar')) {
                // cek apakah ada file yang lama
                if (Storage::exists('mahasiswa-dan-alumni/peluang-mahasiswa/exchange-dan-double-degree/' . $edd->gambar)) {
                    // hapus file
                    Storage::delete('mahasiswa-dan-alumni/peluang-mahasiswa/exchange-dan-double-degree/' . $edd->gambar);
                }

                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('gambar')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'mahasiswa-dan-alumni/peluang-mahasiswa/exchange-dan-double-degree';
                $request->file('gambar')->storeAs($storePath, $nameFile);

                $data = [
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'gambar' => $nameFile,
                    'updated_by' => auth()->user()->id,
                ];
            } else {
                $data = [
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'updated_by' => auth()->user()->id,
                ];
            }

            $edd->update($data);

            return redirect()->route('edd.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('edd.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal mengupdate data
            return redirect()->route('edd.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            if ($this->checkSuperadminAdminKajur()) {
                // ambil data dari model ExchangeDanDoubleDegree berdasarkan id
                $edd = ExchangeDanDoubleDegree::findOrFail($id);
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                // ambil data dari model ExchangeDanDoubleDegree berdasarkan id dan created_by
                $edd = ExchangeDanDoubleDegree::where('id', $id)->where('created_by', auth()->user()->id)->firstOrFail();
            }

            // hapus file dari storage/penyimpanan
            if (Storage::exists('mahasiswa-dan-alumni/peluang-mahasiswa/exchange-dan-double-degree/' . $edd->gambar)) {
                Storage::delete('mahasiswa-dan-alumni/peluang-mahasiswa/exchange-dan-double-degree/' . $edd->gambar);
            }

            // hapus data dari table exchange dan double degree
            $edd->delete();

            return redirect()->route('edd.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('edd.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('edd.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
