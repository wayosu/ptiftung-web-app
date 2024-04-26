<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\PengabdianMasyarakat;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PengabdianMasyarakatController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $pengabdianMasyarakats = PengabdianMasyarakat::with(['dosen.user', 'createdBy'])->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $pengabdianMasyarakats = $pengabdianMasyarakats->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($pengabdianMasyarakats)
                ->addColumn('aksi', function ($pengabdianMasyarakats) {
                    return view('admin.pages.penelitian-dan-pkm.pengabdian-masyarakat.tombol-aksi', compact('pengabdianMasyarakats'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.penelitian-dan-pkm.pengabdian-masyarakat.index', [
            'icon' => 'search',
            'title' => 'Pengabdian Masyarakat',
            'subtitle' => 'Daftar Pengabdian Masyarakat',
            'active' => 'pengabdian-masyarakat',
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
        return view('admin.pages.penelitian-dan-pkm.pengabdian-masyarakat.form', [
            'icon' => 'plus',
            'title' => 'Pengabdian Masyarakat',
            'subtitle' => 'Tambah Pengabdian Masyarakat',
            'active' => 'pengabdian-masyarakat',
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
            'skim.required' => 'SKIM harus diisi.',
            'judul.required' => 'Judul harus diisi.',
            'sumber_dana.required' => 'Sumber Dana harus diisi.',
            'jumlah_dana.required' => 'Jumlah Dana harus diisi.',
        ]);

        try { // jika sukses menambahkan data

            // simpan data
            PengabdianMasyarakat::create([
                'tahun' => $request->tahun,
                'dosen_id' => $request->dosen_id,
                'jabatan' => $request->jabatan,
                'skim' => $request->skim,
                'judul' => $request->judul,
                'sumber_dana' => $request->sumber_dana,
                'jumlah_dana' => $request->jumlah_dana,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('pengabdianMasyarakat.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('pengabdianMasyarakat.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model PengabdianMasyarakat berdasarkan id
            $pengabdianMasyarakat = PengabdianMasyarakat::findOrFail($id);

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
            return view('admin.pages.penelitian-dan-pkm.pengabdian-masyarakat.form', [
                'icon' => 'edit',
                'title' => 'Pengabdian Masyarakat',
                'subtitle' => 'Edit Pengabdian Masyarakat',
                'active' => 'pengabdian-masyarakat',
                'pengabdianMasyarakat' => $pengabdianMasyarakat,
                'namaDosen' => $namaDosen,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('pengabdianMasyarakat.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('pengabdianMasyarakat.index')->with('error', 'Halaman sedang bermasalah!');
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
            'skim.required' => 'SKIM harus diisi.',
            'judul.required' => 'Judul harus diisi.',
            'sumber_dana.required' => 'Sumber Dana harus diisi.',
            'jumlah_dana.required' => 'Jumlah Dana harus diisi.',
        ]);

        try { // jika id ditemukan
            // ambil data dari model PengabdianMasyarakat berdasarkan id
            $pengabdianMasyarakat = PengabdianMasyarakat::findOrFail($id);

            $tahun = $request->tahun;
            $tahun_lama = $request->tahun_lama;

            if ($tahun == null) {
                $pengabdianMasyarakat->update([
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
                $pengabdianMasyarakat->update([
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

            return redirect()->route('pengabdianMasyarakat.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('pengabdianMasyarakat.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('pengabdianMasyarakat.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model PengabdianMasyarakat berdasarkan id
            $pengabdianMasyarakat = PengabdianMasyarakat::findOrFail($id);

            // hapus data dari table pengabdian_masyarakat
            $pengabdianMasyarakat->delete();

            return redirect()->route('pengabdianMasyarakat.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('pengabdianMasyarakat.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('pengabdianMasyarakat.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
