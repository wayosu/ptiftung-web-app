<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class PublikasiController extends Controller
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
                $publikasis = Publikasi::with('dosen.user')
                ->orderBy('created_at', 'desc')
                ->get();
            } else if ($this->checkKaprodi()) {
                $publikasis = Publikasi::with('dosen.user')
                ->whereHas('dosen', function ($q) {
                    $q->where('program_studi', Auth::user()->dosen->program_studi);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            }  else if ($this->checkDosen()) {
                $publikasis = Publikasi::with('dosen.user')
                ->where('dosen_id', Auth::user()->dosen->id)
                ->orderBy('created_at', 'desc')
                ->get();
            }

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
            return view('admin.pages.penelitian-dan-pkm.publikasi.form', [
                'icon' => 'plus',
                'title' => 'Publikasi',
                'subtitle' => 'Tambah Publikasi',
                'active' => 'publikasi',
                'namaDosen' => $namaDosen,
            ]);
        } else if ($this->checkDosen()) {
            // tampilkan halaman
            return view('admin.pages.penelitian-dan-pkm.publikasi.form', [
                'icon' => 'plus',
                'title' => 'Publikasi',
                'subtitle' => 'Tambah Publikasi',
                'active' => 'publikasi',
            ]);
        }
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'judul' => 'required',
            'link_publikasi' => 'required',
        ];

        $validationMessages = [
            'judul.required' => 'Judul harus diisi.',
            'link_publikasi.required' => 'Link Publikasi harus diisi.',
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
            return redirect()->route('publikasi.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses menambahkan data
            // simpan data
            Publikasi::create([
                'dosen_id' => $dosen_id,
                'judul' => $request->judul,
                'link_publikasi' => $request->link_publikasi,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('publikasi.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('publikasi.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
                if ($this->checkKaprodi()) {
                    // ambil data dari model Publikasi berdasarkan id
                    $publikasi = Publikasi::where('id', $id)
                        ->whereHas('dosen', function ($q) {
                            $q->where('program_studi', Auth::user()->dosen->program_studi);
                        })
                        ->firstOrFail();
                    // ambil data dosen
                    $dosen = Dosen::with('user')->where('program_studi', Auth::user()->dosen->program_studi)->get();
                } else {
                    // ambil data dari model Publikasi berdasarkan id
                    $publikasi = Publikasi::findOrFail($id);

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
                return view('admin.pages.penelitian-dan-pkm.publikasi.form', [
                    'icon' => 'edit',
                    'title' => 'Publikasi',
                    'subtitle' => 'Edit Publikasi',
                    'active' => 'publikasi',
                    'publikasi' => $publikasi,
                    'namaDosen' => $namaDosen,
                ]);
            } else if ($this->checkDosen()) {
                // ambil data Publikasi berdasarkan id dan dosen_id
                $publikasi = Publikasi::where('id', $id)
                ->where('dosen_id', Auth::user()->dosen->id)
                ->firstOrFail();

                // tampilkan halaman
                return view('admin.pages.penelitian-dan-pkm.publikasi.form', [
                    'icon' => 'edit',
                    'title' => 'Publikasi',
                    'subtitle' => 'Edit Publikasi',
                    'active' => 'publikasi',
                    'publikasi' => $publikasi,
                ]);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('publikasi.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('publikasi.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'judul' => 'required',
            'link_publikasi' => 'required',
        ];

        $validationMessages = [
            'judul.required' => 'Judul harus diisi.',
            'link_publikasi.required' => 'Link Publikasi harus diisi.',
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
            return redirect()->route('publikasi.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika id ditemukan
            if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
                // ambil data dari model Publikasi berdasarkan id
                if ($this->checkKaprodi()) {
                    $publikasi = Publikasi::where('id', $id)
                        ->whereHas('dosen', function ($q) {
                            $q->where('program_studi', Auth::user()->dosen->program_studi);
                        })
                        ->firstOrFail();
                } else {
                    $publikasi = Publikasi::findOrFail($id);
                }
            } else if ($this->checkDosen()) {
                // ambil data dari model Publikasi berdasarkan id dan dosen_id
                $publikasi = Publikasi::where('id', $id)
                ->where('dosen_id', $dosen_id)
                ->firstOrFail();
            }

            $publikasi->update([
                'dosen_id' => $dosen_id,
                'judul' => $request->judul,
                'link_publikasi' => $request->link_publikasi,
                'updated_by' => Auth::user()->id,
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
            if ($this->checkSuperadminAdminKajur() || $this->checkKaprodi()) {
                // ambil data dari model Publikasi berdasarkan id
                if ($this->checkKaprodi()) {
                    $publikasi = Publikasi::where('id', $id)
                        ->whereHas('dosen', function ($q) {
                            $q->where('program_studi', Auth::user()->dosen->program_studi);
                        })
                        ->firstOrFail();
                } else {
                    $publikasi = Publikasi::findOrFail($id);
                }
            } else if ($this->checkDosen()) {
                // ambil data dari model Publikasi berdasarkan id dan dosen_id
                $publikasi = Publikasi::where('id', $id)
                ->where('dosen_id', Auth::user()->dosen->id)
                ->firstOrFail();
            }

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
