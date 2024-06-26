<?php

namespace App\Http\Controllers;

use App\Models\ProfilProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProfilProgramStudiController extends Controller
{
    public function sejarah($prodi)
    {
        if (Auth::user()->memilikiPeran('Kaprodi')) {
            if ($prodi === 'pti' && Auth::user()->dosen->program_studi === 'PEND. TEKNOLOGI INFORMASI') {
                // ambil data (hanya nama_program_studi dan sejarah)
                $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first([
                    'nama_program_studi', 
                    'sejarah', 
                    'updated_by', 
                    'updated_at'
                ]);
            } else if ($prodi === 'si' && Auth::user()->dosen->program_studi === 'SISTEM INFORMASI') {
                // ambil data (hanya nama_program_studi dan sejarah)
                $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'SISTEM INFORMASI')->first([
                    'nama_program_studi', 
                    'sejarah', 
                    'updated_by', 
                    'updated_at'
                ]);
            } else {
                abort(404);
            }
        } else {
            if ($prodi === 'pti') {
                // ambil data (hanya nama_program_studi dan sejarah)
                $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first([
                    'nama_program_studi', 
                    'sejarah', 
                    'updated_by', 
                    'updated_at'
                ]);
            } else if ($prodi === 'si') {
                // ambil data (hanya nama_program_studi dan sejarah)
                $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'SISTEM INFORMASI')->first([
                    'nama_program_studi', 
                    'sejarah', 
                    'updated_by', 
                    'updated_at'
                ]);
            } else {
                abort(404);
            }
        }

        // tampilkan halaman
        return view('admin.pages.profil.sejarah', [
            'icon' => 'fa-solid fa-landmark',
            'title' => 'Sejarah',
            'subtitle' => 'Sejarah Program Studi ' . $profil->nama_program_studi,
            'active' => 'sejarah-' . $prodi,
            'activeForm' => $prodi,
            'sejarah' => $profil->sejarah,
            'updatedBy' => $profil->updatedBy->name ?? null,
            'updatedAt' => $profil->updated_at
        ]);
    }

    public function updateSejarah(Request $request, $prodi)
    {
        // validasi request data yang dikirim
        $request->validate([
            'sejarah' => 'required',
        ], [
            'sejarah.required' => 'Sejarah harus diisi',
        ]);

        try { // jika id ditemukan lakukan update
            // ambil data
            if ($prodi === 'pti') {
                $sejarah = ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first();
            } else if ($prodi === 'si') {
                $sejarah = ProfilProgramStudi::where('program_studi', 'SISTEM INFORMASI')->first();
            } else {
                abort(404);
            }

            // update data
            $sejarah->update([
                'sejarah' => $request->sejarah,
                'updated_by' => auth()->user()->id
            ]);

            return redirect()->route('sejarah.index', $prodi)->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('sejarah.index', $prodi)->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('sejarah.index', $prodi)->with('error', 'Data gagal diperbarui!');
        }
    }

    public function visiKeilmuanTujuanStrategi($prodi)
    {
        // ambil data (hanya nama_program_studi, visi_keilmuan, tujuan, dan strategi)
        if ($prodi === 'pti') {
            $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first([
                'nama_program_studi',
                'visi_keilmuan',
                'tujuan',
                'strategi',
                'updated_by',
                'updated_at'
            ]);
        } else if ($prodi === 'si') {
            $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'SISTEM INFORMASI')->first([
                'nama_program_studi',
                'visi_keilmuan',
                'tujuan',
                'strategi',
                'updated_by',
                'updated_at'
            ]);
        } else {
            abort(404);
        }

        // tampilkan halaman
        return view('admin.pages.profil.visi-tujuan-strategi', [
            'icon' => 'fa-solid fa-landmark',
            'title' => 'Visi Keilmuan, Tujuan, dan Strategi',
            'subtitle' => 'Visi Keilmuan, Tujuan, dan Strategi Program Studi ' . $profil->nama_program_studi,
            'active' => 'visi-keilmuan-tujuan-strategi-' . $prodi,
            'activeForm' => $prodi,
            'visiKeilmuan' => $profil->visi_keilmuan,
            'tujuan' => $profil->tujuan,
            'strategi' => $profil->strategi,
            'updatedBy' => $profil->updatedBy->name ?? null,
            'updatedAt' => $profil->updated_at
        ]);
    }

    public function updateVisiKeilmuanTujuanStrategi(Request $request, $prodi)
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
            if ($prodi === 'pti') {
                $visiKeilmuanTujuanStrategi = ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first();
            } else if ($prodi === 'si') {
                $visiKeilmuanTujuanStrategi = ProfilProgramStudi::where('program_studi', 'SISTEM INFORMASI')->first();
            } else {
                abort(404);
            }

            // update data
            $visiKeilmuanTujuanStrategi->update([
                'visi_keilmuan' => $request->visi_keilmuan,
                'tujuan' => $request->tujuan,
                'strategi' => $request->strategi,
                'updated_by' => auth()->user()->id
            ]);

            return redirect()->route('visiKeilmuanTujuanStrategi.index', $prodi)->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('visiKeilmuanTujuanStrategi.index', $prodi)->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('visiKeilmuanTujuanStrategi.index', $prodi)->with('error', 'Data gagal diperbarui!');
        }
    }

    public function strukturOrganisasi($prodi)
    {
        // ambil data (hanya nama_program_studi dan struktur_organisasi)
        if ($prodi === 'pti') {
            $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first([
                'nama_program_studi', 
                'struktur_organisasi',
                'updated_by',
                'updated_at'
            ]);
        } else if ($prodi === 'si') {
            $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'SISTEM INFORMASI')->first([
                'nama_program_studi', 
                'struktur_organisasi',
                'updated_by',
                'updated_at'
            ]);
        } else {
            abort(404);
        }

        // tampilkan halaman
        return view('admin.pages.profil.struktur-organisasi', [
            'icon' => 'fa-solid fa-landmark',
            'title' => 'Struktur Organisasi',
            'subtitle' => 'Struktur Organisasi Program Studi ' . $profil->nama_program_studi,
            'active' => 'struktur-organisasi-' . $prodi,
            'activeForm' => $prodi,
            'strukturOrganisasi' => $profil->struktur_organisasi,
            'updatedBy' => $profil->updatedBy->name ?? null,
            'updatedAt' => $profil->updated_at
        ]);
    }

    public function updateStrukturOrganisasi(Request $request, $prodi)
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
            // ambil data
            if ($prodi === 'pti') {
                $strukturOrganisasi = ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first();
            } else if ($prodi === 'si') {
                $strukturOrganisasi = ProfilProgramStudi::where('program_studi', 'SISTEM INFORMASI')->first();
            } else {
                abort(404);
            }

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

                return redirect()->route('strukturOrganisasi.index', $prodi)->with('success', 'Data berhasil diperbarui.');
            } else {
                return redirect()->route('strukturOrganisasi.index', $prodi)->with('error', 'Data gagal diperbarui. Gambar harus diisi!');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('strukturOrganisasi.index', $prodi)->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('strukturOrganisasi.index', $prodi)->with('error', 'Data gagal diperbarui!');
        }
    }

    public function kontakLokasi($prodi)
    {
        // ambil data (hanya nama_program_studi, alamat, link_embed_gmaps, nomor_telepon, email, link_facebook, link_instagram)
        if ($prodi === 'pti') {
            $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first([
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
        } else if ($prodi === 'si') {
            $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'SISTEM INFORMASI')->first([
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
        } else {
            abort(404);
        }

        // tampilkan halaman
        return view('admin.pages.profil.kontak-lokasi', [
            'icon' => 'fa-solid fa-landmark',
            'title' => 'Kontak dan Lokasi',
            'subtitle' => 'Kontak dan Lokasi Program Studi ' . $profil->nama_program_studi,
            'active' => 'kontak-lokasi-' . $prodi,
            'activeForm' => $prodi,
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

    public function updateKontakLokasi(Request $request, $prodi)
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
            // ambil data
            if ($prodi === 'pti') {
                $kontakLokasi = ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first();
            } else if ($prodi === 'si') {
                $kontakLokasi = ProfilProgramStudi::where('program_studi', 'SISTEM INFORMASI')->first();
            } else {
                abort(404);
            }

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

            return redirect()->route('kontakLokasi.index', $prodi)->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kontakLokasi.index', $prodi)->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('kontakLokasi.index', $prodi)->with('error', 'Data gagal diperbarui!');
        }
    }

    public function videoProfil($prodi)
    {
        // ambil data (hanya nama_program_studi dan link embed video profil)
        if ($prodi === 'pti') {
            $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first([
                'nama_program_studi', 
                'link_embed_video_profil', 
                'updated_by', 
                'updated_at'
            ]);
        } else if ($prodi === 'si') {
            $profil = ProfilProgramStudi::with('updatedBy')->where('program_studi', 'SISTEM INFORMASI')->first([
                'nama_program_studi', 
                'link_embed_video_profil', 
                'updated_by', 
                'updated_at'
            ]);
        } else {
            abort(404);
        }

        // tampilkan halaman
        return view('admin.pages.profil.video-profil', [
            'icon' => 'fa-solid fa-landmark',
            'title' => 'Video Profil',
            'subtitle' => 'Video Profil Program Studi ' . $profil->nama_program_studi,
            'active' => 'video-profil-' . $prodi,
            'activeForm' => $prodi,
            'videoProfil' => $profil->link_embed_video_profil,
            'updatedBy' => $profil->updatedBy->name ?? null,
            'updatedAt' => $profil->updated_at
        ]);
    }

    public function updateVideoProfil(Request $request, $prodi)
    {
        // validasi request data yang dikirim
        $request->validate([
            'link_embed_video_profil' => 'required',
        ], [
            'link_embed_video_profil.required' => 'Link Embed Video Profil harus diisi',
        ]);

        try { // jika id ditemukan lakukan update
            // ambil data
            if ($prodi === 'pti') {
                $videoProfil = ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->first();
            } else if ($prodi === 'si') {
                $videoProfil = ProfilProgramStudi::where('program_studi', 'SISTEM INFORMASI')->first();
            } else {
                abort(404);
            }

            // update data
            $videoProfil->update([
                'link_embed_video_profil' => $request->link_embed_video_profil,
                'updated_by' => auth()->user()->id
            ]);

            return redirect()->route('videoProfil.index', $prodi)->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('videoProfil.index', $prodi)->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('videoProfil.index', $prodi)->with('error', 'Data gagal diperbarui!');
        }
    }
}
