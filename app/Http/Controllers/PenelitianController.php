<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class PenelitianController extends Controller
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
                $penelitians = Penelitian::with('dosen.user')
                ->orderBy('created_at', 'desc')
                ->get();
            } else if ($this->checkKaprodi()) {
                $penelitians = Penelitian::with('dosen.user')
                ->whereHas('dosen', function ($q) {
                    $q->where('program_studi', Auth::user()->dosen->program_studi);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            } else if ($this->checkDosen()) {
                $penelitians = Penelitian::with('dosen.user')
                ->where('dosen_id', Auth::user()->dosen->id)
                ->orderBy('created_at', 'desc')
                ->get();
            }

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
        if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
            // ambil data dosen
            if ($this->checkKaprodi()) {
                $dosen = Dosen::with('user')->where('program_studi', Auth::user()->dosen->program_studi)->get();
            } else {
                $dosen = Dosen::with('user')->get();
            }
    
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
        } else if ($this->checkDosen()) {
            // tampilkan halaman
            return view('admin.pages.penelitian-dan-pkm.penelitian.form', [
                'icon' => 'plus',
                'title' => 'Penelitian',
                'subtitle' => 'Tambah Penelitian',
                'active' => 'penelitian',
            ]);
        }
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'tahun' => 'required|digits:4|numeric|between:2012,' . date('Y'),
            'jabatan' => 'required',
            'skim' => 'required',
            'judul' => 'required',
            'sumber_dana' => 'required',
            'jumlah_dana' => 'required',
        ];
    
        $validationMessages = [
            'tahun.required' => 'Tahun harus diisi.',
            'tahun.digits' => 'Tahun harus 4 digit angka.',
            'tahun.numeric' => 'Tahun harus berupa angka.',
            'tahun.between' => 'Tahun harus antara 2012 sampai ' . date('Y'),
            'jabatan.required' => 'Jabatan harus diisi.',
            'skim.required' => 'SKIM harus diisi.',
            'judul.required' => 'Judul harus diisi.',
            'sumber_dana.required' => 'Sumber Dana harus diisi.',
            'jumlah_dana.required' => 'Jumlah Dana harus diisi.',
        ];
    
        if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
            $validationRules['dosen_id'] = 'required';
            $validationMessages['dosen_id.required'] = 'Dosen harus dipilih.';
        }
    
        $request->validate($validationRules, $validationMessages);

        if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
            $dosen_id = $request->dosen_id;
        } else if ($this->checkDosen()) {
            $dosen_id = Auth::user()->dosen->id;
        } else {
            return redirect()->route('penelitian.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }
    
        try { // Jika sukses menambahkan data
            // Simpan data
            Penelitian::create([
                'tahun' => $request->tahun,
                'dosen_id' => $dosen_id,
                'jabatan' => $request->jabatan,
                'skim' => $request->skim,
                'judul' => $request->judul,
                'sumber_dana' => $request->sumber_dana,
                'jumlah_dana' => $request->jumlah_dana,
                'created_by' => Auth::user()->id,
            ]);
    
            return redirect()->route('penelitian.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // Jika gagal menambahkan data
            return redirect()->route('penelitian.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan'
            if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
                if ($this->checkKaprodi()) {
                    // ambil data dari model Penelitian berdasarkan id
                    $penelitian = Penelitian::where('id', $id)
                        ->whereHas('dosen', function ($q) {
                            $q->where('program_studi', Auth::user()->dosen->program_studi);
                        })
                        ->firstOrFail();
                    // ambil data dosen
                    $dosen = Dosen::with('user')->where('program_studi', Auth::user()->dosen->program_studi)->get();
                } else {
                    // ambil data dari model Penelitian berdasarkan id
                    $penelitian = Penelitian::findOrFail($id);

                    // ambil data dosen
                    $dosen = Dosen::with('user')->get();
                }
    
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
            } else if ($this->checkDosen()) {
                // ambil data Penelitian berdasarkan id dan created_by
                $penelitian = Penelitian::where('id', $id)
                ->where('dosen_id', Auth::user()->dosen->id)
                ->firstOrFail();

                // tampilkan halaman
                return view('admin.pages.penelitian-dan-pkm.penelitian.form', [
                    'icon' => 'edit',
                    'title' => 'Penelitian',
                    'subtitle' => 'Edit Penelitian',
                    'active' => 'penelitian',
                    'penelitian' => $penelitian,
                ]);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('penelitian.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('penelitian.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'jabatan' => 'required',
            'skim' => 'required',
            'judul' => 'required',
            'sumber_dana' => 'required',
            'jumlah_dana' => 'required',
        ];
    
        $validationMessages = [
            'jabatan.required' => 'Jabatan harus diisi.',
            'skim.required' => 'SKIM harus diisi.',
            'judul.required' => 'Judul harus diisi.',
            'sumber_dana.required' => 'Sumber Dana harus diisi.',
            'jumlah_dana.required' => 'Jumlah Dana harus diisi.',
        ];
    
        if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
            $validationRules['dosen_id'] = 'required';
            $validationMessages['dosen_id.required'] = 'Dosen harus dipilih.';
        }

        if ($request->tahun != null) {
            $validationRules['tahun'] = 'digits:4|numeric|between:2012,' . date('Y');
            $validationMessages['tahun.digits'] = 'Tahun harus 4 digit angka.';
            $validationMessages['tahun.numeric'] = 'Tahun harus angka.';
            $validationMessages['tahun.between'] = 'Tahun harus antara 2012 sampai ' . date('Y');
        }
    
        $request->validate($validationRules, $validationMessages);

        if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
            $dosen_id = $request->dosen_id;
        } else if ($this->checkDosen()) {
            $dosen_id = Auth::user()->dosen->id;
        } else {
            return redirect()->route('penelitian.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika id ditemukan
            if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
                // ambil data dari model Penelitian berdasarkan id
                if ($this->checkKaprodi()) {
                    $penelitian = Penelitian::where('id', $id)
                        ->whereHas('dosen', function ($q) {
                            $q->where('program_studi', Auth::user()->dosen->program_studi);
                        })
                        ->firstOrFail();
                } else {
                    $penelitian = Penelitian::findOrFail($id);
                }
            } else if ($this->checkDosen()) {
                // ambil data dari model Penelitian berdasarkan id dan dosen_id
                $penelitian = Penelitian::where('id', $id)
                ->where('dosen_id', $dosen_id)
                ->firstOrFail();
            }

            $tahun = $request->tahun;
            $tahun_lama = $request->tahun_lama;

            if ($tahun == null) {
                $penelitian->update([
                    'tahun' => $tahun_lama,
                    'dosen_id' => $dosen_id,
                    'jabatan' => $request->jabatan,
                    'skim' => $request->skim,
                    'judul' => $request->judul,
                    'sumber_dana' => $request->sumber_dana,
                    'jumlah_dana' => $request->jumlah_dana,
                    'updated_by' => Auth::user()->id,
                ]);
            } else {
                $penelitian->update([
                    'tahun' => $request->tahun,
                    'dosen_id' => $dosen_id,
                    'jabatan' => $request->jabatan,
                    'skim' => $request->skim,
                    'judul' => $request->judul,
                    'sumber_dana' => $request->sumber_dana,
                    'jumlah_dana' => $request->jumlah_dana,
                    'updated_by' => Auth::user()->id,
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
            if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
                // ambil data dari model Penelitian berdasarkan id
                if ($this->checkKaprodi()) {
                    $penelitian = Penelitian::where('id', $id)
                        ->whereHas('dosen', function ($q) {
                            $q->where('program_studi', Auth::user()->dosen->program_studi);
                        })
                        ->firstOrFail();
                } else {
                    $penelitian = Penelitian::findOrFail($id);
                }
            } else if ($this->checkDosen()) {
                // ambil data dari model Penelitian berdasarkan id dan dosen_id
                $penelitian = Penelitian::where('id', $id)
                ->where('dosen_id', Auth::user()->dosen->id)
                ->firstOrFail();
            }

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
