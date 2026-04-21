@extends('layouts.user-portal')

@section('title', 'Pengajuan Kredit Baru')
@section('eyebrow', 'Wizard pengajuan')
@section('page-description', 'Isi data pengajuan, simulasi kredit, dan dokumen pendukung dalam satu form.')

@section('page-actions')
    <button type="submit" form="pengajuan-form" class="btn btn-primary">Kirim Pengajuan</button>
@endsection

@section('content')
    <form id="pengajuan-form" action="{{ route('user.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="content-stack">
        @csrf

        <section class="grid-2">
            <article class="profile-card">
                <div class="eyebrow">Langkah 1</div>
                <h2 style="margin: 12px 0 8px;">Pilih motor</h2>
                <div class="form-grid">
                    <div class="field">
                        <label for="id_motor">Motor</label>
                        <select id="id_motor" name="id_motor">
                            @foreach ($motors as $motor)
                                <option value="{{ $motor->id }}" @selected((int) old('id_motor', $selectedMotor?->id) === $motor->id)>
                                    {{ $motor->nama_motor }} - Rp{{ number_format((float) $motor->harga_jual, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Motor terpilih</label>
                        <input type="text" value="{{ $selectedMotor?->nama_motor }}" disabled>
                    </div>
                </div>
            </article>

            <article class="profile-card">
                <div class="eyebrow">Langkah 2</div>
                <h2 style="margin: 12px 0 8px;">Simulasi & paket kredit</h2>
                <div class="form-grid">
                    <div class="field">
                        <label for="dp">DP</label>
                        <input id="dp" type="number" min="0" name="dp" value="{{ old('dp', $downPayment) }}" required>
                    </div>
                    <div class="field">
                        <label for="id_jenis_cicilan">Tenor</label>
                        <select id="id_jenis_cicilan" name="id_jenis_cicilan">
                            @foreach ($tenors as $tenor)
                                <option value="{{ $tenor->id }}" @selected((int) old('id_jenis_cicilan', $selectedTenor?->id) === $tenor->id)>
                                    {{ $tenor->lama_cicilan }} bulan
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="id_asuransi">Asuransi</label>
                        <select id="id_asuransi" name="id_asuransi">
                            @foreach ($insurances as $insurance)
                                <option value="{{ $insurance->id }}" @selected((int) old('id_asuransi', $selectedInsurance?->id) === $insurance->id)>
                                    {{ $insurance->nama_asuransi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if ($simulation)
                    <div class="summary-list" style="margin-top: 18px;">
                        <div class="summary-list__item">
                            <span>DP minimal</span>
                            <strong>Rp{{ number_format($simulation['minimum_dp'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Cicilan per bulan</span>
                            <strong>Rp{{ number_format($simulation['cicilan_perbulan'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Total kewajiban</span>
                            <strong>Rp{{ number_format($simulation['total_kewajiban'], 0, ',', '.') }}</strong>
                        </div>
                    </div>
                @endif
            </article>
        </section>

        <section class="grid-2">
            <article class="profile-card">
                <div class="eyebrow">Langkah 3</div>
                <h2 style="margin: 12px 0 8px;">Data pribadi dan pekerjaan</h2>
                <div class="form-grid">
                    <div class="field">
                        <label for="name">Nama lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="field">
                        <label for="no_telp">No telepon</label>
                        <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp', $user->profile?->no_telp) }}" required>
                    </div>
                    <div class="field">
                        <label for="nik">NIK</label>
                        <input id="nik" type="text" name="nik" value="{{ old('nik', $user->profile?->nik) }}" required>
                    </div>
                    <div class="field">
                        <label for="tempat_lahir">Tempat lahir</label>
                        <input id="tempat_lahir" type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->profile?->tempat_lahir) }}">
                    </div>
                    <div class="field">
                        <label for="tanggal_lahir">Tanggal lahir</label>
                        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($user->profile?->tanggal_lahir)->toDateString()) }}">
                    </div>
                    <div class="field">
                        <label for="jenis_kelamin">Jenis kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">Pilih</option>
                            @foreach (['laki-laki' => 'Laki-laki', 'perempuan' => 'Perempuan'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('jenis_kelamin', $user->profile?->jenis_kelamin) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="pekerjaan">Pekerjaan</label>
                        <input id="pekerjaan" type="text" name="pekerjaan" value="{{ old('pekerjaan', $user->profile?->pekerjaan) }}" required>
                    </div>
                    <div class="field">
                        <label for="nama_perusahaan">Perusahaan</label>
                        <input id="nama_perusahaan" type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $user->profile?->nama_perusahaan) }}">
                    </div>
                    <div class="field">
                        <label for="lama_bekerja_bulan">Lama bekerja (bulan)</label>
                        <input id="lama_bekerja_bulan" type="number" min="0" name="lama_bekerja_bulan" value="{{ old('lama_bekerja_bulan', $user->profile?->lama_bekerja_bulan) }}">
                    </div>
                    <div class="field">
                        <label for="penghasilan_bulanan">Penghasilan bulanan</label>
                        <input id="penghasilan_bulanan" type="number" min="0" name="penghasilan_bulanan" value="{{ old('penghasilan_bulanan', $user->profile?->penghasilan_bulanan) }}" required>
                    </div>
                    <div class="field">
                        <label for="status_pernikahan">Status pernikahan</label>
                        <input id="status_pernikahan" type="text" name="status_pernikahan" value="{{ old('status_pernikahan', $user->profile?->status_pernikahan) }}">
                    </div>
                </div>
            </article>

            <article class="profile-card">
                <div class="eyebrow">Langkah 3 lanjutan</div>
                <h2 style="margin: 12px 0 8px;">Alamat dan kontak darurat</h2>
                <div class="form-grid">
                    <div class="field" style="grid-column: 1 / -1;">
                        <label for="alamat_ktp">Alamat KTP</label>
                        <textarea id="alamat_ktp" name="alamat_ktp" required>{{ old('alamat_ktp', $user->profile?->alamat_ktp) }}</textarea>
                    </div>
                    <div class="field">
                        <label for="kota_ktp">Kota KTP</label>
                        <input id="kota_ktp" type="text" name="kota_ktp" value="{{ old('kota_ktp', $user->profile?->kota_ktp) }}" required>
                    </div>
                    <div class="field">
                        <label for="provinsi_ktp">Provinsi KTP</label>
                        <input id="provinsi_ktp" type="text" name="provinsi_ktp" value="{{ old('provinsi_ktp', $user->profile?->provinsi_ktp) }}" required>
                    </div>
                    <div class="field">
                        <label for="kodepos_ktp">Kode pos KTP</label>
                        <input id="kodepos_ktp" type="text" name="kodepos_ktp" value="{{ old('kodepos_ktp', $user->profile?->kodepos_ktp) }}" required>
                    </div>
                    <div class="field" style="grid-column: 1 / -1;">
                        <label for="alamat_domisili">Alamat domisili</label>
                        <textarea id="alamat_domisili" name="alamat_domisili" required>{{ old('alamat_domisili', $user->profile?->alamat_domisili) }}</textarea>
                    </div>
                    <div class="field">
                        <label for="kota_domisili">Kota domisili</label>
                        <input id="kota_domisili" type="text" name="kota_domisili" value="{{ old('kota_domisili', $user->profile?->kota_domisili) }}" required>
                    </div>
                    <div class="field">
                        <label for="provinsi_domisili">Provinsi domisili</label>
                        <input id="provinsi_domisili" type="text" name="provinsi_domisili" value="{{ old('provinsi_domisili', $user->profile?->provinsi_domisili) }}" required>
                    </div>
                    <div class="field">
                        <label for="kodepos_domisili">Kode pos domisili</label>
                        <input id="kodepos_domisili" type="text" name="kodepos_domisili" value="{{ old('kodepos_domisili', $user->profile?->kodepos_domisili) }}" required>
                    </div>
                    <div class="field">
                        <label for="nama_kontak_darurat">Nama kontak darurat</label>
                        <input id="nama_kontak_darurat" type="text" name="nama_kontak_darurat" value="{{ old('nama_kontak_darurat', $user->profile?->nama_kontak_darurat) }}">
                    </div>
                    <div class="field">
                        <label for="hubungan_kontak_darurat">Hubungan</label>
                        <input id="hubungan_kontak_darurat" type="text" name="hubungan_kontak_darurat" value="{{ old('hubungan_kontak_darurat', $user->profile?->hubungan_kontak_darurat) }}">
                    </div>
                    <div class="field">
                        <label for="no_telp_kontak_darurat">No telepon kontak darurat</label>
                        <input id="no_telp_kontak_darurat" type="text" name="no_telp_kontak_darurat" value="{{ old('no_telp_kontak_darurat', $user->profile?->no_telp_kontak_darurat) }}">
                    </div>
                </div>
            </article>
        </section>

        <section class="grid-2">
            <article class="profile-card">
                <div class="eyebrow">Langkah 4</div>
                <h2 style="margin: 12px 0 8px;">Upload dokumen</h2>
                <div class="form-grid">
                    <div class="field">
                        <label for="url_ktp">KTP</label>
                        <input id="url_ktp" type="file" name="url_ktp" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                    <div class="field">
                        <label for="url_kk">KK</label>
                        <input id="url_kk" type="file" name="url_kk" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                    <div class="field">
                        <label for="url_npwp">NPWP</label>
                        <input id="url_npwp" type="file" name="url_npwp" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div class="field">
                        <label for="url_slip_gaji">Slip gaji</label>
                        <input id="url_slip_gaji" type="file" name="url_slip_gaji" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                    <div class="field">
                        <label for="url_foto">Foto diri / selfie</label>
                        <input id="url_foto" type="file" name="url_foto" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                </div>
            </article>

            <article class="profile-card">
                <div class="eyebrow">Langkah 5</div>
                <h2 style="margin: 12px 0 8px;">Review & kirim</h2>
                @if ($simulation)
                    <div class="summary-list">
                        <div class="summary-list__item">
                            <span>Motor</span>
                            <strong>{{ $selectedMotor?->nama_motor }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Tenor</span>
                            <strong>{{ $selectedTenor?->lama_cicilan }} bulan</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Asuransi</span>
                            <strong>{{ $selectedInsurance?->nama_asuransi }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Cicilan per bulan</span>
                            <strong>Rp{{ number_format($simulation['cicilan_perbulan'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Total kewajiban</span>
                            <strong>Rp{{ number_format($simulation['total_kewajiban'], 0, ',', '.') }}</strong>
                        </div>
                    </div>
                @endif

                <label class="checkbox-line" style="margin-top: 18px;">
                    <input type="checkbox" name="agree_terms" value="1" {{ old('agree_terms') ? 'checked' : '' }}>
                    <span>Saya menyatakan data yang diisi benar dan siap diverifikasi.</span>
                </label>
            </article>
        </section>
    </form>
@endsection
