<?php

namespace App\Http\Controllers;

use App\Models\ProfilProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProfilProgramStudiController extends Controller
{
    public function sejarah()
    {
        // ambil data (hanya nama_program_studi dan sejarah)
        $profil = ProfilProgramStudi::with('updatedBy')->where('id', 1)->first([
            'nama_program_studi', 
            'sejarah', 
            'updated_by', 
            'updated_at'
        ]);

        // tampilkan halaman
        return view('admin.pages.profil.sejarah', [
            'icon' => 'fa-solid fa-landmark',
            'title' => 'Sejarah',
            'subtitle' => 'Sejarah Program Studi ' . $profil->nama_program_studi,
            'active' => 'sejarah',
            'sejarah' => $profil->sejarah,
            'updatedBy' => $profil->updatedBy->name ?? null,
            'updatedAt' => $profil->updated_at
        ]);
    }

    public function updateSejarah(Request $request)
    {
        // validasi request data yang dikirim
        $request->validate([
            'sejarah' => 'required',
        ], [
            'sejarah.required' => 'Sejarah harus diisi',
        ]);

        try { // jika id ditemukan lakukan update
            // ambil data
            $sejarah = ProfilProgramStudi::findOrFail(1);

            // update data
            $sejarah->update([
                'sejarah' => $request->sejarah,
                'updated_by' => auth()->user()->id
            ]);

            return redirect()->route('sejarah.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('sejarah.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('sejarah.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function visiKeilmuanTujuanStrategi()
    {
        // ambil data (hanya nama_program_studi, visi_keilmuan, tujuan, dan strategi)
        $profil = ProfilProgramStudi::with('updatedBy')->where('id', 1)->first([
            'nama_program_studi',
            'visi_keilmuan',
            'tujuan',
            'strategi',
            'updated_by',
            'updated_at'
        ]);

        // tampilkan halaman
        return view('admin.pages.profil.visi-tujuan-strategi', [
            'icon' => 'fa-solid fa-landmark',
            'title' => 'Visi Keilmuan, Tujuan, dan Strategi',
            'subtitle' => 'Visi Keilmuan, Tujuan, dan Strategi Program Studi ' . $profil->nama_program_studi,
            'active' => 'visi-keilmuan-tujuan-strategi',
            'visiKeilmuan' => $profil->visi_keilmuan,
            'tujuan' => $profil->tujuan,
            'strategi' => $profil->strategi,
            'updatedBy' => $profil->updatedBy->name ?? null,
            'updatedAt' => $profil->updated_at
        ]);
    }

    public function updateVisiKeilmuanTujuanStrategi(Request $request)
    {
        // validasi request data yang dikirim
        $request->validate([
            'visi_keilmuan' => 'required',
            'tujuan' => 'required',
            'strategi' => 'required',
        ], [
            'visi_keilmuan.required' => 'Visi Keilmuan harus diisi',
            'tujuan.required' => 'Tujuan harus diisi',
            'strategi.required' => 'Strategi harus diisi',
        ]);

        try { // jika id ditemukan lakukan update
            // ambil data
            $visiKeilmuanTujuanStrategi = ProfilProgramStudi::findOrFail(1);

            // update data
            $visiKeilmuanTujuanStrategi->update([
                'visi_keilmuan' => $request->visi_keilmuan,
                'tujuan' => $request->tujuan,
                'strategi' => $request->strategi,
                'updated_by' => auth()->user()->id
            ]);

            return redirect()->route('visiKeilmuanTujuanStrategi.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('visiKeilmuanTujuanStrategi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('visiKeilmuanTujuanStrategi.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function strukturOrganisasi()
    {
        // ambil data (hanya nama_program_studi dan struktur_organisasi)
        $profil = ProfilProgramStudi::with('updatedBy')->where('id', 1)->first([
            'nama_program_studi', 
            'struktur_organisasi',
            'updated_by',
            'updated_at'
        ]);

        // tampilkan halaman
        return view('admin.pages.profil.struktur-organisasi', [
            'icon' => 'fa-solid fa-landmark',
            'title' => 'Struktur Organisasi',
            'subtitle' => 'Struktur Organisasi Program Studi ' . $profil->nama_program_studi,
            'active' => 'struktur-organisasi',
            'strukturOrganisasi' => $profil->struktur_organisasi,
            'updatedBy' => $profil->updatedBy->name ?? null,
            'updatedAt' => $profil->updated_at
        ]);
    }

    public function updateStrukturOrganisasi(Request $request)
    {
        // validasi request data yang dikirim
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'gambar.required' => 'Gambar harus diisi',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'File harus berupa jpeg, png, jpg',
            'gambar.max' => 'File melebihi batas ukuran 2MB',
        ]);

        try { // jika id ditemukan lakukan update
            $strukturOrganisasi = ProfilProgramStudi::findOrFail(1);

            // jika ada gambar
            if ($request->hasFile('gambar')) {
                // cek apakah ada file yang lama
                if (Storage::exists('profilProgramStudi/' . $strukturOrganisasi->struktur_organisasi)) {
                    // hapus file
                    Storage::delete('profilProgramStudi/' . $strukturOrganisasi->struktur_organisasi);
                }

                // namakan file
                $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('gambar')->extension();

                // simpan file ke storage/penyimpanan
                $request->file('gambar')->storeAs('profilProgramStudi', $nameFile);

                // update data
                $strukturOrganisasi->update([
                    'struktur_organisasi' => $nameFile,
                    'updated_by' => auth()->user()->id
                ]);

                return redirect()->route('strukturOrganisasi.index')->with('success', 'Data berhasil diperbarui.');
            } else {
                return redirect()->route('strukturOrganisasi.index')->with('error', 'Data gagal diperbarui. Gambar harus diisi!');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('strukturOrganisasi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('strukturOrganisasi.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function kontakLokasi()
    {
        // ambil data (hanya nama_program_studi, alamat, link_embed_gmaps, nomor_telepon, email, link_facebook, link_instagram)
        $profil = ProfilProgramStudi::with('updatedBy')->where('id', 1)->first([
            'nama_program_studi',
            'alamat',
            'link_embed_gmaps',
            'nomor_telepon',
            'email',
            'link_facebook',
            'link_instagram',
            'updated_by',
            'updated_at'
        ]);

        // tampilkan halaman
        return view('admin.pages.profil.kontak-lokasi', [
            'icon' => 'fa-solid fa-landmark',
            'title' => 'Kontak dan Lokasi',
            'subtitle' => 'Kontak dan Lokasi Program Studi ' . $profil->nama_program_studi,
            'active' => 'kontak-lokasi',
            'nomorTelepon' => $profil->nomor_telepon,
            'email' => $profil->email,
            'linkFacebook' => $profil->link_facebook,
            'linkInstagram' => $profil->link_instagram,
            'alamat' => $profil->alamat,
            'linkEmbedGmaps' => $profil->link_embed_gmaps,
            'updatedBy' => $profil->updatedBy->name ?? null,
            'updatedAt' => $profil->updated_at
        ]);
    }

    public function updateKontakLokasi(Request $request)
    {
        // validasi request data yang dikirim
        $request->validate([
            'nomor_telepon' => 'nullable',
            'email' => 'email|nullable',
            'link_facebook' => 'nullable',
            'link_instagram' => 'nullable',
            'alamat' => 'nullable',
            'link_embed_gmaps' => 'nullable',
        ], [
            'email.email' => 'Email harus valid'
        ]);

        try { // jika id ditemukan lakukan update
            $kontakLokasi = ProfilProgramStudi::findOrFail(1);

            // update data
            $kontakLokasi->update([
                'nomor_telepon' => $request->nomor_telepon,
                'email' => $request->email,
                'link_facebook' => $request->link_facebook,
                'link_instagram' => $request->link_instagram,
                'alamat' => $request->alamat,
                'link_embed_gmaps' => $request->link_embed_gmaps,
                'updated_by' => auth()->user()->id
            ]);

            return redirect()->route('kontakLokasi.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kontakLokasi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('kontakLokasi.index')->with('error', 'Data gagal diperbarui!');
        }
    }
}
