@extends('layouts.user-portal')

@section('title', 'Profil User')
@section('eyebrow', 'Data customer')
@section('page-description', 'Lengkapi data akun, data pribadi, alamat, dan kontak darurat untuk kebutuhan pengajuan kredit.')

@section('page-actions')
    <button type="submit" form="profile-form" class="btn btn-primary">Simpan Perubahan</button>
@endsection

@section('content')
    @php
        $profile = $user->profile;
    @endphp

    <form id="profile-form" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="content-stack">
        @csrf

        <section class="grid-2">
            <article class="profile-card">
                <div class="eyebrow">Data akun</div>
                <div class="form-grid" style="margin-top: 16px;">
                    <div class="field">
                        <label for="name">Nama lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="field">
                        <label for="password">Password baru</label>
                        <input id="password" type="password" name="password">
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Konfirmasi password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation">
                    </div>
                    <div class="field">
                        <label for="no_telp">No telepon</label>
                        <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp', $profile?->no_telp) }}">
                    </div>
                    <div class="field">
                        <label for="foto_profil">Foto profil</label>
                        <input id="foto_profil" type="file" name="foto_profil" accept=".jpg,.jpeg,.png">
                    </div>
                </div>
            </article>

            <article class="profile-card">
                <div class="eyebrow">Data pribadi</div>
                <div class="form-grid" style="margin-top: 16px;">
                    <div class="field">
                        <label for="nik">NIK</label>
                        <input id="nik" type="text" name="nik" value="{{ old('nik', $profile?->nik) }}">
                    </div>
                    <div class="field">
                        <label for="tempat_lahir">Tempat lahir</label>
                        <input id="tempat_lahir" type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $profile?->tempat_lahir) }}">
                    </div>
                    <div class="field">
                        <label for="tanggal_lahir">Tanggal lahir</label>
                        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($profile?->tanggal_lahir)->toDateString()) }}">
                    </div>
                    <div class="field">
                        <label for="jenis_kelamin">Jenis kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">Pilih</option>
                            @foreach (['laki-laki' => 'Laki-laki', 'perempuan' => 'Perempuan'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('jenis_kelamin', $profile?->jenis_kelamin) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="pekerjaan">Pekerjaan</label>
                        <input id="pekerjaan" type="text" name="pekerjaan" value="{{ old('pekerjaan', $profile?->pekerjaan) }}">
                    </div>
                    <div class="field">
                        <label for="nama_perusahaan">Nama perusahaan</label>
                        <input id="nama_perusahaan" type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $profile?->nama_perusahaan) }}">
                    </div>
                    <div class="field">
                        <label for="lama_bekerja_bulan">Lama bekerja (bulan)</label>
                        <input id="lama_bekerja_bulan" type="number" min="0" name="lama_bekerja_bulan" value="{{ old('lama_bekerja_bulan', $profile?->lama_bekerja_bulan) }}">
                    </div>
                    <div class="field">
                        <label for="penghasilan_bulanan">Penghasilan bulanan</label>
                        <input id="penghasilan_bulanan" type="number" min="0" name="penghasilan_bulanan" value="{{ old('penghasilan_bulanan', $profile?->penghasilan_bulanan) }}">
                    </div>
                    <div class="field">
                        <label for="status_pernikahan">Status pernikahan</label>
                        <input id="status_pernikahan" type="text" name="status_pernikahan" value="{{ old('status_pernikahan', $profile?->status_pernikahan) }}">
                    </div>
                </div>
            </article>
        </section>

        <section class="grid-2">
            <article class="profile-card">
                <div class="eyebrow">Alamat KTP</div>
                <div class="form-grid" style="margin-top: 16px;">
                    <div class="field" style="grid-column: 1 / -1;">
                        <label for="alamat_ktp">Alamat KTP</label>
                        <textarea id="alamat_ktp" name="alamat_ktp">{{ old('alamat_ktp', $profile?->alamat_ktp) }}</textarea>
                    </div>
                    <div class="field">
                        <label for="kota_ktp">Kota</label>
                        <input id="kota_ktp" type="text" name="kota_ktp" value="{{ old('kota_ktp', $profile?->kota_ktp) }}">
                    </div>
                    <div class="field">
                        <label for="provinsi_ktp">Provinsi</label>
                        <input id="provinsi_ktp" type="text" name="provinsi_ktp" value="{{ old('provinsi_ktp', $profile?->provinsi_ktp) }}">
                    </div>
                    <div class="field">
                        <label for="kodepos_ktp">Kode pos</label>
                        <input id="kodepos_ktp" type="text" name="kodepos_ktp" value="{{ old('kodepos_ktp', $profile?->kodepos_ktp) }}">
                    </div>
                </div>
            </article>

            <article class="profile-card">
                <div class="eyebrow">Alamat domisili</div>
                <div class="form-grid" style="margin-top: 16px;">
                    <div class="field" style="grid-column: 1 / -1;">
                        <label for="alamat_domisili">Alamat domisili</label>
                        <textarea id="alamat_domisili" name="alamat_domisili">{{ old('alamat_domisili', $profile?->alamat_domisili) }}</textarea>
                    </div>
                    <div class="field">
                        <label for="kota_domisili">Kota</label>
                        <input id="kota_domisili" type="text" name="kota_domisili" value="{{ old('kota_domisili', $profile?->kota_domisili) }}">
                    </div>
                    <div class="field">
                        <label for="provinsi_domisili">Provinsi</label>
                        <input id="provinsi_domisili" type="text" name="provinsi_domisili" value="{{ old('provinsi_domisili', $profile?->provinsi_domisili) }}">
                    </div>
                    <div class="field">
                        <label for="kodepos_domisili">Kode pos</label>
                        <input id="kodepos_domisili" type="text" name="kodepos_domisili" value="{{ old('kodepos_domisili', $profile?->kodepos_domisili) }}">
                    </div>
                </div>
            </article>
        </section>

        <section class="profile-card">
            <div class="eyebrow">Kontak darurat</div>
            <div class="form-grid" style="margin-top: 16px;">
                <div class="field">
                    <label for="nama_kontak_darurat">Nama kontak darurat</label>
                    <input id="nama_kontak_darurat" type="text" name="nama_kontak_darurat" value="{{ old('nama_kontak_darurat', $profile?->nama_kontak_darurat) }}">
                </div>
                <div class="field">
                    <label for="hubungan_kontak_darurat">Hubungan</label>
                    <input id="hubungan_kontak_darurat" type="text" name="hubungan_kontak_darurat" value="{{ old('hubungan_kontak_darurat', $profile?->hubungan_kontak_darurat) }}">
                </div>
                <div class="field">
                    <label for="no_telp_kontak_darurat">No telepon kontak darurat</label>
                    <input id="no_telp_kontak_darurat" type="text" name="no_telp_kontak_darurat" value="{{ old('no_telp_kontak_darurat', $profile?->no_telp_kontak_darurat) }}">
                </div>
                <div class="field">
                    <label>Login terakhir</label>
                    <input type="text" value="{{ optional($user->last_login_at)->format('d M Y H:i') ?: '-' }}" disabled>
                </div>
            </div>
        </section>
    </form>
@endsection
