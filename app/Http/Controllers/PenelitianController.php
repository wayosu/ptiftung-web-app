<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PenelitianController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $penelitians = Penelitian::with(['dosen.user', 'createdBy'])->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $penelitians = $penelitians->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($penelitians)
                ->addColumn('aksi', function ($penelitians) {
                    return view('admin.pages.penelitian-dan-pkm.penelitian.tombol-aksi', compact('penelitians'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.penelitian-dan-pkm.penelitian.index', [
            'icon' => 'search',
            'title' => 'Penelitian',
            'subtitle' => 'Daftar Penelitian',
            'active' => 'penelitian',
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
        return view('admin.pages.penelitian-dan-pkm.penelitian.form', [
            'icon' => 'plus',
            'title' => 'Penelitian',
            'subtitle' => 'Tambah Penelitian',
            'active' => 'penelitian',
            'namaDosen' => $namaDosen,
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'tahun' => 'required',
            'dosen_id' => 'required',
            'jabatan' => 'required',
            'skim' => 'required',
            'judul' => 'required',
            'sumber_dana' => 'required',
            'jumlah_dana' => 'required',
        ], [
            'tahun.required' => 'Tahun harus diisi.',
            'dosen_id.required' => 'Dosen harus dipilih.',
            'jabatan.required' => 'Jabatan harus diisi.',
            'skim.required' => 'SKIM harus dipilih.',
            'judul.required' => 'Judul harus diisi.',
            'sumber_dana.required' => 'Sumber Dana harus dipilih.',
            'jumlah_dana.required' => 'Jumlah Dana harus diisi.',
        ]);

        try { // jika sukses menambahkan data

            // simpan data
            Penelitian::create([
                'tahun' => $request->tahun,
                'dosen_id' => $request->dosen_id,
                'jabatan' => $request->jabatan,
                'skim' => $request->skim,
                'judul' => $request->judul,
                'sumber_dana' => $request->sumber_dana,
                'jumlah_dana' => $request->jumlah_dana,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('penelitian.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('penelitian.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model Penelitian berdasarkan id
            $penelitian = Penelitian::findOrFail($id);

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
            return view('admin.pages.penelitian-dan-pkm.penelitian.form', [
                'icon' => 'edit',
                'title' => 'Penelitian',
                'subtitle' => 'Edit Penelitian',
                'active' => 'penelitian',
                'penelitian' => $penelitian,
                'namaDosen' => $namaDosen,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('penelitian.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('penelitian.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'dosen_id' => 'required',
            'jabatan' => 'required',
            'skim' => 'required',
            'judul' => 'required',
            'sumber_dana' => 'required',
            'jumlah_dana' => 'required',
        ], [
            'dosen_id.required' => 'Dosen harus dipilih.',
            'jabatan.required' => 'Jabatan harus diisi.',
            'skim.required' => 'SKIM harus dipilih.',
            'judul.required' => 'Judul harus diisi.',
            'sumber_dana.required' => 'Sumber Dana harus dipilih.',
            'jumlah_dana.required' => 'Jumlah Dana harus diisi.',
        ]);

        try { // jika id ditemukan
            // ambil data dari model Penelitian berdasarkan id
            $penelitian = Penelitian::findOrFail($id);

            $tahun = $request->tahun;
            $tahun_lama = $request->tahun_lama;

            if ($tahun == null) {
                $penelitian->update([
                    'tahun' => $tahun_lama,
                    'dosen_id' => $request->dosen_id,
                    'jabatan' => $request->jabatan,
                    'skim' => $request->skim,
                    'judul' => $request->judul,
                    'sumber_dana' => $request->sumber_dana,
                    'jumlah_dana' => $request->jumlah_dana,
                    'updated_by' => auth()->user()->id,
                ]);
            } else {
                $penelitian->update([
                    'tahun' => $request->tahun,
                    'dosen_id' => $request->dosen_id,
                    'jabatan' => $request->jabatan,
                    'skim' => $request->skim,
                    'judul' => $request->judul,
                    'sumber_dana' => $request->sumber_dana,
                    'jumlah_dana' => $request->jumlah_dana,
                    'updated_by' => auth()->user()->id,
                ]);
            }

            return redirect()->route('penelitian.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('penelitian.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('penelitian.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model Penelitian berdasarkan id
            $penelitian = Penelitian::findOrFail($id);

            // hapus data dari table penelitian
            $penelitian->delete();

            return redirect()->route('penelitian.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('penelitian.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('penelitian.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
