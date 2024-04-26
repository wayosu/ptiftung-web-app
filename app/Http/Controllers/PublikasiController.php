<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $publikasis = Publikasi::with(['dosen.user', 'createdBy'])->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $publikasis = $publikasis->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($publikasis)
                ->addColumn('aksi', function ($publikasis) {
                    return view('admin.pages.penelitian-dan-pkm.publikasi.tombol-aksi', compact('publikasis'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.penelitian-dan-pkm.publikasi.index', [
            'icon' => 'search',
            'title' => 'Publikasi',
            'subtitle' => 'Daftar Publikasi',
            'active' => 'publikasi',
        ]);
    }

    public function create()
    {
        // ambil data dosen
        $dosen = Dosen::with('user')->get();

        // ambil data nama dosen dengan id
        $namaDosen = $dosen->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->user->name
            ];
        });

        // ordering data by nama dosen -> asc
        $namaDosen = $namaDosen->sortBy('name')->all();

        // tampilkan halaman
        return view('admin.pages.penelitian-dan-pkm.publikasi.form', [
            'icon' => 'plus',
            'title' => 'Publikasi',
            'subtitle' => 'Tambah Publikasi',
            'active' => 'publikasi',
            'namaDosen' => $namaDosen,
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'dosen_id' => 'required',
            'judul' => 'required',
            'link_publikasi' => 'required',
        ], [
            'dosen_id.required' => 'Dosen harus dipilih.',
            'judul.required' => 'Judul harus diisi.',
            'link_publikasi.required' => 'Link Publikasi harus diisi.',
        ]);

        try { // jika sukses menambahkan data

            // simpan data
            Publikasi::create([
                'dosen_id' => $request->dosen_id,
                'judul' => $request->judul,
                'link_publikasi' => $request->link_publikasi,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('publikasi.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('publikasi.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model Publikasi berdasarkan id
            $publikasi = Publikasi::findOrFail($id);

            // ambil data dosen
            $dosen = Dosen::with('user')->get();

            // ambil data nama dosen dengan id
            $namaDosen = $dosen->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->user->name
                ];
            });

            // ordering data by nama dosen -> asc
            $namaDosen = $namaDosen->sortBy('name')->all();

            // tampilkan halaman
            return view('admin.pages.penelitian-dan-pkm.publikasi.form', [
                'icon' => 'edit',
                'title' => 'Publikasi',
                'subtitle' => 'Edit Publikasi',
                'active' => 'publikasi',
                'publikasi' => $publikasi,
                'namaDosen' => $namaDosen,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('publikasi.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('publikasi.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'dosen_id' => 'required',
            'judul' => 'required',
            'link_publikasi' => 'required',
        ], [
            'dosen_id.required' => 'Dosen harus dipilih.',
            'judul.required' => 'Judul harus diisi.',
            'link_publikasi.required' => 'Link Publikasi harus diisi.',
        ]);

        try { // jika id ditemukan
            // ambil data dari model Publikasi berdasarkan id
            $publikasi = Publikasi::findOrFail($id);

            $publikasi->update([
                'dosen_id' => $request->dosen_id,
                'judul' => $request->judul,
                'link_publikasi' => $request->link_publikasi,
                'updated_by' => auth()->user()->id,
            ]);
            
            return redirect()->route('publikasi.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('publikasi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('publikasi.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model Publikasi berdasarkan id
            $publikasi = Publikasi::findOrFail($id);

            // hapus data dari table publikasi
            $publikasi->delete();

            return redirect()->route('publikasi.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('publikasi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('publikasi.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
