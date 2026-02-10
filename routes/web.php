<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SiswaMiddleware;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaDataController;
use App\Http\Controllers\KakomTAController;
use App\Http\Controllers\GuruPageController;
use App\Http\Controllers\KepsekTAController;
use App\Http\Controllers\LoginGtkController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashAdminController;
use App\Http\Controllers\HomeVisitController;
use App\Http\Controllers\JadwalKBMController;
use App\Http\Controllers\KakomDataController;
use App\Http\Controllers\MapelPageController;
use App\Http\Controllers\PageAdminController;
use App\Http\Controllers\SiswaPageController;
use App\Http\Controllers\KakomWalasController;
use App\Http\Controllers\KaprogPageController;
use App\Http\Controllers\KepsekPageController;
use App\Http\Controllers\LoginSiswaController;
use App\Http\Controllers\RombelDataController;
use App\Http\Controllers\RombelPageController;
use App\Http\Controllers\JadwalPiketController;
use App\Http\Controllers\KakomRombelController;
use App\Http\Controllers\KepsekIndexController;
use App\Http\Controllers\KepsekWalasController;
use App\Http\Controllers\KinerjaGuruController;
use App\Http\Controllers\LoginKaprogController;
use App\Http\Controllers\LoginKepsekController;
use App\Http\Controllers\ProfilePageController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\AdmWalasViewController;
use App\Http\Controllers\BukuTamuOrtuController;
use App\Http\Controllers\CatatanKasusController;
use App\Http\Controllers\DataDiriDataController;
use App\Http\Controllers\DataDiriPageController;
use App\Http\Controllers\KepsekRombelController;
use App\Http\Controllers\ProfileAdminController;
use App\Http\Controllers\ProfileKakomController;
use App\Http\Controllers\WargaSekolahController;
use App\Http\Controllers\HomePageWalasController;
use App\Http\Controllers\KurikulumPageController;
use App\Http\Controllers\PrestasiSiswaController;
use App\Http\Controllers\WaliKelasPageController;
use App\Http\Controllers\DataSiswaWalasController;
use App\Http\Controllers\DetailPresensiController;
use App\Http\Controllers\IdentitasKelasController;
use App\Http\Controllers\KurikulumIndexController;
use App\Http\Controllers\KurikulumWalasController;
use App\Http\Controllers\LoginKurikulumController;
use App\Http\Controllers\PendapatanOrtuController;
use App\Http\Controllers\LembarPengesahanController;
use App\Http\Controllers\ProfilePageWalasController;
use App\Http\Controllers\ShowDetailRombelController;
use App\Http\Controllers\AdministrasiWalasController;
use App\Http\Controllers\CatatanKasusSiswaController;
use App\Http\Controllers\DetailKelasKaprogController;
use App\Http\Controllers\GrafikJarakTempuhController;
use App\Http\Controllers\ProfileKepsekPageController;
use App\Http\Controllers\AdmWalasViewKepsekController;
use App\Http\Controllers\DaftarPesertaDidikController;
use App\Http\Controllers\InputDataDiriSiswaController;
use App\Http\Controllers\PrestasiSiswaInputController;
use App\Http\Controllers\StrukturOrganisasiController;
use App\Http\Controllers\ViewAdmWalasKaprogController;
use App\Http\Controllers\ViewAdmWalasKepsekController;
use App\Http\Controllers\AgendaKegiatanWalasController;
use App\Http\Controllers\BeritaAcaraKenaikanController;
use App\Http\Controllers\BeritaAcaraKelulusanController;
use App\Http\Controllers\ProfilePageKurikulumController;
use App\Http\Controllers\RencanaKegiatanWalasController;
use App\Http\Controllers\AdmWalasKurikulumViewController;
use App\Http\Controllers\DaftarPenyerahanRapotController;
use App\Http\Controllers\ViewAdmWalasKurikulumController;
use App\Http\Controllers\BeritaAcaraSerahTerimaController;
use App\Http\Controllers\DenahKerjaKelompokSiswaController;
use App\Http\Controllers\PersentasePekerjaanOrtuController;
use App\Http\Controllers\RekapitulasiJumlahSiswaController;
use App\Http\Controllers\KeluarRombelController;
use App\Http\Controllers\KeluarRombelViewController;
use App\Http\Controllers\SiswaDataPageAdminController;
use App\Http\Controllers\AlumniDataController;
use App\Http\Controllers\KeluarRombelViewKepsek;
use App\Http\Controllers\KeluarRombelViewKurikulumController;
use App\Http\Controllers\KeluarRombelViewKakomController;
use App\Http\Controllers\AbsenController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('loginadmin', LoginController::class);
Route::post('/loginadmin', [LoginController::class, 'store'])->name('login.store');

Route::get('/logingtk', [LoginGtkController::class, 'index'])->name('logingtk.index');
Route::post('/logingtk', [LoginGtkController::class, 'store'])->name('logingtk.store');

Route::resource('loginkepsek', LoginKepsekController::class);
Route::post('/loginkepsek', [LoginKepsekController::class, 'store'])->name('loginkepsek.store');

Route::resource('logingtk', LoginGtkController::class);
Route::resource('loginkepsek', LoginKepsekController::class);
Route::resource('loginkaprog', LoginKaprogController::class);
Route::resource('loginkurikulum', LoginKurikulumController::class);
// Route untuk menampilkan form login siswa
Route::get('/loginsiswa', [LoginSiswaController::class, 'index'])->name('loginsiswa');

// Route untuk menangani proses login siswa
Route::post('/loginsiswa', [LoginSiswaController::class, 'store'])->name('loginsiswa.store');


// Halaman Utama Controller

// Route halaman admin
Route::resource('adminpage', PageAdminController::class)->name('index', 'homepageadmin.index');
Route::resource('wargasekolah', WargaSekolahController::class);
Route::resource('detailkelas', SiswaDataPageAdminController::class);
Route::get('/rombeldetail/{rombel_id}', [ShowDetailRombelController::class, 'showDetail'])->name('detail.kelas');

// CRUD WALAS
Route::resource('walas', WaliKelasPageController::class);
Route::get('/hapuswalas/{id}', [WaliKelasPageController::class, 'hapuswalas'])->name('hapuswalas');
Route::get('/walas/hapuswalas/{id}', [WaliKelasPageController::class, 'hapuswalas']);
Route::put('/walas/{id}', [WaliKelasPageController::class, 'update'])->name('walas.update');
Route::get('/walas/{id}/edit', [WaliKelasPageController::class, 'edit'])->name('walas.edit');
Route::get ('/walas_search', [WaliKelasPageController::class,'walas_search']);

// CRUD KAKOM
Route::resource('kakom', KakomDataController::class);
Route::get('/hapuskakom/{id}', [KakomDataController::class, 'hapuskakom'])->name('hapuskakom');
Route::get('/kakom/hapuskakom/{id}', [KakomDataController::class, 'hapuskakom']);
Route::put('/kakom/{id}', [KakomDataController::class, 'update'])->name('kakom.update');
Route::get('/kakom/{id}/edit', [KakomDataController::class, 'edit'])->name('kakom.edit');
Route::get ('/kakom_search', [KakomDataController::class,'kakom_search']);

// CRUD KURIKULUM
Route::resource('kurikulum', KurikulumPageController::class);
Route::get('/hapuskurikulum/{id}', [KurikulumPageController::class, 'hapuskurikulum'])->name('hapuskurikulum');
Route::get('/kurikulum/hapuskurikulum/{id}', [KurikulumPageController::class, 'hapuskurikulum']);
Route::put('/kurikulum/{id}', [KurikulumPageController::class, 'update'])->name('kurikulum.update');
Route::get('/kurikulum/{id}/edit', [KurikulumPageController::class, 'edit'])->name('kurikulum.edit');
Route::get ('/kurikulum_search', [KurikulumPageController::class,'kurikulum_search']);

// CRUD KEPSEK
Route::resource('kepalasekolah', KepsekPageController::class);
Route::get('/hapuskepsek/{id}', [KepsekPageController::class, 'hapuskepsek'])->name('hapuskepsek');
Route::get('/kepsek/hapuskepsek/{id}', [KepsekPageController::class, 'hapuskepsek']);
Route::put('/kepsek/{id}', [KepsekPageController::class, 'update'])->name('kepsek.update');
Route::get('/kepsek/{id}/edit', [KepsekPageController::class, 'edit'])->name('kepsek.edit');
Route::post('/kepsek/rombel/store', [KepsekPageController::class, 'store'])->name('kepsek.store');
Route::get ('/kepsek_search', [KepsekPageController::class,'kepsek_search']);

// CRUD GURU
Route::resource('guru', GuruPageController::class);
Route::get('/hapusguru/{id}', [GuruPageController::class, 'hapusguru'])->name('hapusguru');
Route::get('/guru/hapusguru/{id}', [GuruPageController::class, 'hapusguru']);
Route::put('/guru/{id}', [GuruPageController::class, 'update'])->name('guru.update');
Route::get('/guru/{id}/edit', [GuruPageController::class, 'edit'])->name('guru.edit');
Route::get ('/guru_search', [GuruPageController::class,'guru_search']);


Route::resource('tahunajaran', TahunAjaranController::class);

Route::post('/siswa-import', [DataSiswaWalasController::class, 'import']);
Route::post('/siswa-import-admin', [ShowDetailRombelController::class, 'importsiswaadmin']);
Route::get('/siswa-download-template-admin', [ShowDetailRombelController::class, 'downloadTemplateAdmin'])->name('siswa.download-template-admin');
Route::get('/siswa-download-template', [DataSiswaWalasController::class, 'downloadTemplate'])->name('siswa.download-template');
Route::post('/walas-import', [WaliKelasPageController::class, 'import']);
Route::get('/walas-download-template', [WaliKelasPageController::class, 'downloadTemplate'])->name('walas.download-template');
Route::post('/guru-import', [GuruPageController::class, 'import']);
Route::get('/guru-download-template', [GuruPageController::class, 'downloadTemplate'])->name('guru.download-template');
Route::post('/kakom-import', [KakomDataController::class, 'import']);
Route::get('/kakom-download-template', [KakomDataController::class, 'downloadTemplate'])->name('kakom.download-template');
Route::post('/kurikulum-import', [KurikulumPageController::class, 'import']);
Route::get('/kurikulum-download-template', [KurikulumPageController::class, 'downloadTemplate'])->name('kurikulum.download-template');
Route::post('/kepsek-import', [KepsekPageController::class, 'import']);
Route::get('/kepsek-download-template', [KepsekPageController::class, 'downloadTemplate'])->name('kepsek.download-template');
Route::post('/mapel-import', [MapelPageController::class, 'import']);
Route::get('/mapel-download-template', [MapelPageController::class, 'downloadTemplate'])->name('mapel.download-template');
Route::post('/rombel-import', [RombelPageController::class, 'import']);
Route::get('/rombel-download-template', [RombelPageController::class, 'downloadTemplate'])->name('rombel.download-template');

Route::get('/mapel-export', [MapelPageController::class, 'export']);
Route::get('/guru-export', [GuruPageController::class, 'export']);

// CRUD ROMBEL
Route::resource('rombel', RombelPageController::class); // Ini sudah mencakup semua route CRUD, termasuk edit
Route::post('/rombel/store', [RombelPageController::class, 'store'])->name('rombels.store');
Route::put('/rombels/{id}', [RombelPageController::class, 'update'])->name('rombels.update');
Route::get('/rombels/{id}/edit', [RombelPageController::class, 'edit'])->name('rombels.edit'); // Pastikan edit menggunakan GET
Route::get('/hapusrombel/{id}', [RombelPageController::class, 'hapusrombel'])->name('hapusrombel');
Route::get('/rombel/hapusrombel/{id}', [RombelPageController::class, 'hapusrombel']);
Route::get ('/rombel_search', [RombelPageController::class,'rombel_search']);
Route::get('/test-naik-kelas', [RombelPageController::class, 'naikKelas']);

// CRUD MAPEL
Route::resource('datamapel', MapelPageController::class);
Route::get('/hapusmapel/{id}', [MapelPageController::class, 'hapusmapel'])->name('hapusmapel');
Route::get('/mapel/hapusmapel/{id}', [MapelPageController::class, 'hapusmapel']);
Route::put('/mapel/{id}', [MapelPageController::class, 'update'])->name('mapel.update');
Route::get('/mapel/{id}/edit', [MapelPageController::class, 'edit'])->name('mapel.edit');
Route::post('/mapel/tambah/store', [MapelPageController::class, 'store'])->name('mapel.store');
Route::get ('/mapel_search', [MapelPageController::class,'mapel_search']);

Route::get('/walaspage', [HomePageWalasController::class, 'index'])->name('homepagegtk.index');

Route::resource('siswadata', DataSiswaWalasController::class);
Route::get('/siswa/biodata/{id}', [DataSiswaWalasController::class, 'biodata'])->name('homepagegtk.biodatasiswa');
Route::get('/siswabiodata/{id}/edit', [DataSiswaWalasController::class, 'editbiodata'])->name('homepagegtk.editbiodata');
Route::put('/siswabiodata/{id}', [DataSiswaWalasController::class, 'updatebiodata'])->name('homepagegtk.updatebiodata');

Route::resource('profilekepsek', ProfileKepsekPageController::class);
Route::get('/profilekepsek/{id}/edit', [ProfileKepsekPageController::class, 'edit'])->name('profilekepsek.edit');
Route::put('/profilekepsek/{id}', [ProfileKepsekPageController::class, 'update'])->name('profilekepsek.update');

Route::resource('profileadmin', ProfileAdminController::class);
Route::get('/profileadmin/{id}/edit', [ProfileAdminController::class, 'edit'])->name('profileadmin.edit');
Route::put('/profileadmin/{id}', [ProfileAdminController::class, 'update'])->name('profileadmin.update');

Route::resource('profilewalas', ProfilePageWalasController::class);
Route::get('/profilewalas/{id}/edit', [ProfilePageWalasController::class, 'edit'])->name('profilewalas.edit');
Route::put('/profilewalas/{id}', [ProfilePageWalasController::class, 'update'])->name('profilewalas.update');

Route::resource('profilesiswa', ProfilePageController::class);
Route::get('/profilesiswa/{id}/edit', [ProfilePageController::class, 'edit'])->name('profilesiswa.edit');
Route::put('/profilesiswa/{id}', [ProfilePageController::class, 'update'])->name('profilesiswa.update');

Route::resource('profilekurikulum', ProfilePageKurikulumController::class);
Route::get('/profilekurikulum/{id}/edit', [ProfilePageKurikulumController::class, 'edit'])->name('profilekurikulum.edit');
Route::put('/profilekurikulum/{id}', [ProfilePageKurikulumController::class, 'update'])->name('profilekurikulum.update');

Route::resource('profilekakom', ProfileKakomController::class);
Route::get('/profilekakom/{id}/edit', [ProfileKakomController::class, 'edit'])->name('profilekaprog.edit');
Route::put('/profilekakom/{id}', [ProfileKakomController::class, 'update'])->name('profilekaprog.update');

Route::get ('/siswadata_search', [DataSiswaWalasController::class,'siswadata_search']);

Route::post('/siswa/tambah-ke-rombel', [SiswaDataPageAdminController::class, 'tambahKeRombel'])->name('siswa.tambah.ke.rombel');

Route::post('/siswadata/tambah/store', [DataSiswaWalasController::class, 'store'])
// Menambahkan middleware auth:walas
    ->name('siswa.store');

Route::get('/hapussiswa/{id}', [DataSiswaWalasController::class, 'hapussiswa'])
// Menambahkan middleware auth:walas
    ->name('hapussiswa');

Route::get('/siswa/{id}/edit', [DataSiswaWalasController::class, 'edit'])
// Menambahkan middleware auth:walas
    ->name('siswa.edit');

Route::put('/siswa/{id}', [DataSiswaWalasController::class, 'update'])
// Menambahkan middleware auth:walas
    ->name('siswa.update');
Route::resource('adminwalas', AdministrasiWalasController::class);

//administrasi walas
Route::resource('identitaskelas', IdentitasKelasController::class);
Route::get('/identitaskelas/{id}/edit', [IdentitasKelasController::class, 'edit'])->name('identitaskelas.edit');
Route::resource('beritaacarakenaikan', BeritaAcaraKenaikanController::class);
Route::resource('beritaacarakelulusan', BeritaAcaraKelulusanController::class);
Route::resource('beritaacaraserahterima', BeritaAcaraSerahTerimaController::class);
Route::get('/beritaacaraserahterima-download-template', [BeritaAcaraSerahTerimaController::class, 'downloadTemplate'])->name('beritaacaraserahterima.download-template');
Route::resource('lembarpengesahan', LembarPengesahanController::class);
Route::get('/lembarpengesahan-download-template', [LembarPengesahanController::class, 'downloadTemplate'])->name('lembarpengesahan.download-template');
Route::resource('strukturorganisasi', StrukturOrganisasiController::class);
Route::resource('jadwalkbm', JadwalKBMController::class);
Route::resource('pendapatanortu', PendapatanOrtuController::class);
Route::post('/pendapatanortu/generatepdf', [PendapatanOrtuController::class, 'generatePDF'])->name('pendapatanortu.generatepdf');
Route::resource('grafikjaraktempuh', GrafikJarakTempuhController::class);
Route::post('/grafikjaraktempuh/generatepdf', [GrafikJarakTempuhController::class, 'generatePDF'])->name('grafikjaraktempuh.generatepdf');
Route::resource('rencana_kegiatan_walas', RencanaKegiatanWalasController::class)->except(['index']);
Route::get('/rencana_kegiatan/{semester}', [RencanaKegiatanWalasController::class, 'index'])->name('rencana_kegiatan_walas.index');
Route::get('/rencana_kegiatan/{semester}/create', [RencanaKegiatanWalasController::class, 'create'])->name('rencana_kegiatan_walas.create');
Route::post('/rencana_kegiatan/{semester}', [RencanaKegiatanWalasController::class, 'store'])->name('rencana_kegiatan_walas.store');
Route::put('/rencana_kegiatan_walas/{semester}/update/{id}', [RencanaKegiatanWalasController::class, 'update'])->name('rencana_kegiatan_walas.update');
Route::get('/rencana_kegiatan_walas/{semester}/edit/{id}', [RencanaKegiatanWalasController::class, 'edit'])->name('rencana_kegiatan_walas.edit');
Route::resource('presensi', PresensiController::class);
Route::get('/presensi/export-pdf', [PresensiController::class, 'exportPdf'])->name('presensi.exportPdf');
  // Detail Presensi
  Route::prefix('{presensi}/detail')->group(function () {
    Route::get('/', [DetailPresensiController::class, 'index'])->name('detailpresensi.index');
    Route::get('/create', [DetailPresensiController::class, 'create'])->name('detailpresensi.create');
    Route::post('/', [DetailPresensiController::class, 'store'])->name('detailpresensi.store');
    Route::get('/{detail}/edit', [DetailPresensiController::class, 'edit'])->name('detailpresensi.edit');
    Route::put('/{detail}', [DetailPresensiController::class, 'update'])->name('detailpresensi.update');
    Route::delete('/{detail}', [DetailPresensiController::class, 'destroy'])->name('detailpresensi.destroy');
});

// Route Halaman Kepsek
Route::resource('kepsekpage', KepsekIndexController::class)->name('index', 'homepagekepsek.index');
Route::resource('kepsekta', KepsekTAController::class);
Route::resource('kepsekwalas', KepsekWalasController::class);
Route::resource('kepsekrombel', KepsekRombelController::class);

// Route Halaman Kaprog
Route::resource('homepagekaprog', KaprogPageController::class)->name('index', 'homepagekaprog.index');
Route::resource('kakomta', KakomTAController::class);
Route::resource('kakomwalas', KakomWalasController::class);
Route::resource('kakomrombel', KakomRombelController::class);

// Route Halaman Kurikulum
Route::resource('kurikulumpage', KurikulumIndexController::class)->name('index', 'homepagekurikulum.index');;
Route::resource('tahunajarandata', TaDataController::class);
Route::resource('rombelpage', RombelDataController::class);
Route::resource('kurikulumwalas', KurikulumWalasController::class);

// Route Siswa Halaman
    Route::get('/siswapage', [SiswaPageController::class, 'index'])->name('homepagesiswa.index');
    Route::resource('datadiri', DataDiriPageController::class);
    Route::resource('inputdatadiri', InputDataDiriSiswaController::class);
    Route::post('/biodatasiswa/store', [InputDataDiriSiswaController::class, 'store'])->name('biodatasiswa.store');
    Route::resource('datadiripage', DataDiriDataController::class);
    Route::get('/biodatasiswa/{id}/edit', [InputDataDiriSiswaController::class, 'edit'])->name('datadiri.edit');
    Route::put('/biodatasiswaupdate/{id}', [InputDataDiriSiswaController::class, 'update'])->name('biodatasiswa.update');
    Route::resource('catatankasussiswa', CatatanKasusController::class);


// CRUD DENAH TEMPAT KERJA KELOMPOK SISWA ADM WALAS
Route::resource('denahkerjakelompok', DenahKerjaKelompokSiswaController::class);
Route::get('/createkelompok', [DenahKerjaKelompokSiswaController::class, 'create'])->name('denahkerjakelompok.create');
Route::post('/kelompok/{id}/add-siswa', [DenahKerjaKelompokSiswaController::class, 'addSiswa'])->name('kelompok.addSiswa');
Route::post('/createkelompok/store', [DenahKerjaKelompokSiswaController::class, 'store'])->name('kelompoksiswa.store');
Route::post('/kelompok/simpan', [DenahKerjaKelompokSiswaController::class, 'simpan'])->name('kelompoksiswa.simpan');
Route::delete('/hapussiswadata/{id}', [DenahKerjaKelompokSiswaController::class, 'hapussiswadata'])->name('hapussiswadata');
Route::put('/kelompoksiswa/update/{id}', [DenahKerjaKelompokSiswaController::class, 'update'])->name('kelompoksiswa.update');

// CRUD JADWALPIKET
// Route::resource('jadwalpiket', JadwalPiketController::class);
Route::get('/createpiket', [JadwalPiketController::class, 'create'])->name('jadwalpiket.create');
// Route::post('/piket/{id}/add-siswa', [JadwalPiketController::class, 'addSiswa'])->name('piket.addSiswa');
Route::post('/createpiket/store', [JadwalPiketController::class, 'store'])->name('piket.store');
// Route::post('/piket/simpan', [JadwalPiketController::class, 'simpan'])->name('piket.simpan');
Route::delete('/hapussiswapiket/{id}', [JadwalPiketController::class, 'hapussiswapiket'])->name('hapussiswapiket');
// Route::put('/piket/update/{id}', [JadwalPiketController::class, 'update'])->name('piket.update');

Route::resource('jadwalpiket', JadwalPiketController::class)
    ->except(['edit', 'show']) // Kecualikan jika method tidak digunakan
    ->names([
        'index' => 'jadwalpiket.index',
        'create' => 'jadwalpiket.create',
        'store' => 'jadwalpiket.store',
        'update' => 'jadwalpiket.update',
        'destroy' => 'jadwalpiket.destroy',
    ]);

// Tambahkan siswa ke jadwal piket
Route::post('/jadwalpiket/{id}/siswa', [JadwalPiketController::class, 'addSiswa'])
    ->name('jadwalpiket.addSiswa');

// Simpan detail siswa pada jadwal piket
Route::post('/jadwalpiket/simpan', [JadwalPiketController::class, 'simpan'])
    ->name('jadwalpiket.simpan');

// Hapus data siswa dari jadwal piket
// Route::delete('/jadwalpiket/siswa/{id}', [JadwalPiketController::class, 'hapussiswapiket'])
//     ->name('jadwalpiket.hapusSiswa');

// Perbarui siswa di jadwal piket
Route::put('/jadwalpiket/siswa/{id}', [JadwalPiketController::class, 'update'])
    ->name('jadwalpiket.updateSiswa');

// CRUD SERAH TERIMA RAPOT
Route::resource('serahterimarapor', DaftarPenyerahanRapotController::class);
Route::get('/serahterimarapor-download-template', [DaftarPenyerahanRapotController::class, 'downloadTemplate'])->name('serahterimarapor.download-template');
Route::get('/penyerahanrapotcreate', [DaftarPenyerahanRapotController::class, 'create'])->name('penyerahanrapot.create');
Route::post('/penyerahanrapot/store', [DaftarPenyerahanRapotController::class, 'store'])->name('penyerahanrapot.store');
Route::get('/penyerahanrapot/{id}/edit', [DaftarPenyerahanRapotController::class, 'edit'])->name('penyerahanrapot.edit');
Route::get('/hapuspenyerahanrapot/{id}', [DaftarPenyerahanRapotController::class, 'hapuspenyerahanrapot'])->name('hapuspenyerahanrapot');
Route::put('/penyerahanrapot/{id}', [DaftarPenyerahanRapotController::class, 'update'])->name('penyerahanrapot.update');

// CRUD HOME VISIT
Route::resource('homevisit', HomeVisitController::class);
Route::post('/homevisit/generatepdf', [HomeVisitController::class, 'generatePDF'])->name('homevisit.generatepdf');
Route::get('/homevisitcreate', [HomeVisitController::class, 'create'])->name('homevisit.create');
Route::post('/homevisit/store', [HomeVisitController::class, 'store'])->name('homevisit.store');
Route::get('/homevisit/{id}/edit', [HomeVisitController::class, 'edit'])->name('homevisit.edit');
Route::get('/hapushomevisit/{id}', [HomeVisitController::class, 'hapushomevisit'])->name('hapushomevisit');
Route::put('/homevisit/{id}', [HomeVisitController::class, 'update'])->name('homevisit.update');

// CRUD BUKU TAMU ORTI
Route::resource('bukutamuortu', BukuTamuOrtuController::class);
Route::post('/bukutamuortu/generatepdf', [BukuTamuOrtuController::class, 'generatePDF'])->name('bukutamuortu.generatepdf');
Route::get('/bukutamuortucreate', [BukuTamuOrtuController::class, 'create'])->name('bukutamuortu.create');
Route::post('/bukutamuortu/store', [BukuTamuOrtuController::class, 'store'])->name('bukutamuortu.store');
Route::get('/bukutamuortu/{id}/edit', [BukuTamuOrtuController::class, 'edit'])->name('bukutamuortu.edit');
Route::get('/hapusbukutamuortu/{id}', [BukuTamuOrtuController::class, 'hapusbukutamuortu'])->name('hapusbukutamuortu');
Route::put('/bukutamuortu/{id}', [BukuTamuOrtuController::class, 'update'])->name('bukutamuortu.update');

// CRUD AGENDA WALAS
Route::resource('agendawalas', AgendaKegiatanWalasController::class);
Route::get('/agendawalascreate', [AgendaKegiatanWalasController::class, 'create'])->name('agendawalas.create');
Route::post('/agendawalas/store', [AgendaKegiatanWalasController::class, 'store'])->name('agendawalas.store');
Route::get('/agendawalas/{id}/edit', [AgendaKegiatanWalasController::class, 'edit'])->name('agendawalas.edit');
Route::get('/hapusagendawalas/{id}', [AgendaKegiatanWalasController::class, 'hapusagendawalas'])->name('hapusagendawalas');
Route::put('/agendawalas/{id}', [AgendaKegiatanWalasController::class, 'update'])->name('agendawalas.update');

// CRUD CATATAN KASUS
Route::resource('catatankasus', CatatanKasusSiswaController::class);
Route::get('/catatankasuscreate', [CatatanKasusSiswaController::class, 'create'])->name('catatankasus.create');
Route::post('/catatankasus/store', [CatatanKasusSiswaController::class, 'store'])->name('catatankasus.store');
Route::get('/catatankasus/{id}/edit', [CatatanKasusSiswaController::class, 'edit'])->name('catatankasus.edit');
Route::get('/hapuscatatankasus/{id}', [CatatanKasusSiswaController::class, 'hapuscatatankasus'])->name('hapuscatatankasus');
Route::put('/catatankasus/{id}', [CatatanKasusSiswaController::class, 'update'])->name('catatankasus.update');
Route::get('/catatankasus/export/pdf', [CatatanKasusSiswaController::class, 'index'])->name('catatankasus.export');


// CRUD DATA SISWA
Route::resource('daftarpesertadidik', DaftarPesertaDidikController::class);
Route::get('/daftarpesertadidikcreate', [DaftarPesertaDidikController::class, 'create'])->name('daftarpesertadidik.create');
Route::post('/daftarpesertadidik/store', [DaftarPesertaDidikController::class, 'store'])->name('daftarpesertadidik.store');
Route::get('/daftarpesertadidik/{id}/edit', [DaftarPesertaDidikController::class, 'edit'])->name('daftarpesertadidik.edit');
Route::get('/hapusdaftarpesertadidik/{id}', [DaftarPesertaDidikController::class, 'hapusdaftarpesertadidik'])->name('hapusdaftarpesertadidik');
Route::put('/daftarpesertadidik/{id}', [DaftarPesertaDidikController::class, 'update'])->name('daftarpesertadidik.update');
Route::get('/get-siswa/{id}', function ($id) {
    $biodata = \App\Models\BiodataSiswa::where('siswas_id', $id)->first();
    return response()->json($biodata);
});

// CRUD REKAPITULASI JUMLAH SISWA
Route::resource('rekapjumlahsiswa', RekapitulasiJumlahSiswaController::class);
Route::get('/rekapjumlahsiswacreate', [RekapitulasiJumlahSiswaController::class, 'create'])->name('rekapjumlahsiswa.create');
Route::post('/rekapjumlahsiswa/store', [RekapitulasiJumlahSiswaController::class, 'store'])->name('rekapjumlahsiswa.store');
Route::get('/rekapjumlahsiswa/{id}/edit', [RekapitulasiJumlahSiswaController::class, 'edit'])->name('rekapjumlahsiswa.edit');
Route::get('/hapusrekapjumlahsiswa/{id}', [RekapitulasiJumlahSiswaController::class, 'hapusrekapjumlahsiswa'])->name('hapusrekapjumlahsiswa');
Route::put('/rekapjumlahsiswa/{id}', [RekapitulasiJumlahSiswaController::class, 'update'])->name('rekapjumlahsiswa.update');

// CRUD REKAPITULASI JUMLAH SISWA
Route::resource('persentasesosialekonomi', PersentasePekerjaanOrtuController::class);
Route::get('/persentasesosialekonomicreate', [PersentasePekerjaanOrtuController::class, 'create'])->name('persentasesosialekonomi.create');
Route::post('/persentasesosialekonomi/store', [PersentasePekerjaanOrtuController::class, 'store'])->name('persentasesosialekonomi.store');
Route::get('/persentasesosialekonomi/{id}/edit', [PersentasePekerjaanOrtuController::class, 'edit'])->name('persentasesosialekonomi.edit');
Route::get('/hapuspersentasesosialekonomi/{id}', [PersentasePekerjaanOrtuController::class, 'hapuspersentasesosialekonomi'])->name('hapuspersentasesosialekonomi');
Route::put('/persentasesosialekonomi/{id}', [PersentasePekerjaanOrtuController::class, 'update'])->name('persentasesosialekonomi.update');

// CRUD REKAPITULASI PRESTASI SISWA
Route::resource('prestasisiswa', PrestasiSiswaController::class);
Route::post('/prestasisiswa/generatepdf', [PrestasiSiswaController::class, 'generatePDF'])->name('prestasisiswa.generatepdf');
Route::get('/prestasisiswacreate', [PrestasiSiswaController::class, 'create'])->name('prestasisiswa.create');
Route::post('/prestasisiswa/store', [PrestasiSiswaController::class, 'store'])->name('prestasisiswa.store');
Route::get('/prestasisiswa/{id}/edit', [PrestasiSiswaController::class, 'edit'])->name('prestasisiswa.edit');
Route::get('/hapusprestasisiswa/{id}', [PrestasiSiswaController::class, 'hapusprestasisiswa'])->name('hapusprestasisiswa');
Route::put('/prestasisiswa/{id}', [PrestasiSiswaController::class, 'update'])->name('prestasisiswa.update');

// CRUD PRESTASI SISWA (SISWA INPUT)
Route::resource('prestasisiswainput', PrestasiSiswaInputController::class);
Route::get('/prestasisiswainputcreate', [PrestasiSiswaInputController::class, 'create'])->name('prestasisiswainput.create');
Route::post('/prestasisiswainput/store', [PrestasiSiswaInputController::class, 'store'])->name('prestasisiswainput.store');
Route::get('/prestasisiswainput/{id}/edit', [PrestasiSiswaInputController::class, 'edit'])->name('prestasisiswainput.edit');
Route::get('/hapusprestasisiswainput/{id}', [PrestasiSiswaInputController::class, 'hapusprestasisiswainput'])->name('hapusprestasisiswainput');
Route::put('/prestasisiswainput/{id}', [PrestasiSiswaInputController::class, 'update'])->name('prestasisiswainput.update');


// Route Kaprog Walas Data
Route::resource('admwalasview', AdmWalasViewController::class);
Route::get('agendawalasview', [ViewAdmWalasKaprogController::class, 'agendawalas'])->name('admwalas.agendawalas');
Route::get('identiaskelasview', [ViewAdmWalasKaprogController::class, 'identitaskelas'])->name('admwalas.identitaskelas');
Route::get('lembarpengesahanview', [ViewAdmWalasKaprogController::class, 'lembarpengesahan'])->name('admwalas.lembarpengesahan');
Route::get('beritaacarakenaikanview', [ViewAdmWalasKaprogController::class, 'beritaacarakenaikan'])->name('admwalas.beritaacarakenaikan');
Route::get('beritaacarakelulusanview', [ViewAdmWalasKaprogController::class, 'beritaacarakelulusan'])->name('admwalas.beritaacarakelulusan');
Route::get('beritaacaraserahterimaview', [ViewAdmWalasKaprogController::class, 'beritaacaraserahterima'])->name('admwalas.beritaacaraserahterima');
Route::get('strukturorganisasikelasview', [ViewAdmWalasKaprogController::class, 'strukturorganisasikelas'])->name('admwalas.strukturorganisasikelas');
Route::get('jadwalkbmview', [ViewAdmWalasKaprogController::class, 'jadwalkbm'])->name('admwalas.jadwalkbm');
Route::get('presensisview', [ViewAdmWalasKaprogController::class, 'presensis'])->name('admwalas.presensis');
Route::get('piketkelasview', [ViewAdmWalasKaprogController::class, 'piketkelas'])->name('admwalas.piketkelas');
Route::get('serahterimaraporview', [ViewAdmWalasKaprogController::class, 'serahterimarapor'])->name('admwalas.serahterimarapor');
Route::get('catatankasusview', [ViewAdmWalasKaprogController::class, 'catatankasus'])->name('admwalas.catatankasus');
Route::get('daftarpesertadidikview', [ViewAdmWalasKaprogController::class, 'daftarpesertadidik'])->name('admwalas.daftarpesertadidik');
Route::get('rekapitulasijumlahsiswaview', [ViewAdmWalasKaprogController::class, 'rekapitulasipdidik'])->name('admwalas.rekapitulasipdidik');
Route::get('homevisitview', [ViewAdmWalasKaprogController::class, 'homevisit'])->name('admwalas.homevisit');
Route::post('/homevisitview/generatepdf', [ViewAdmWalasKaprogController::class, 'generatePDFhomevisit'])->name('homevisit.generatepdfhomevisit');
Route::get('bukutamuview', [ViewAdmWalasKaprogController::class, 'bukutamuortu'])->name('admwalas.bukutamuortu');
Route::post('/bukutamuview/generatepdf', [ViewAdmWalasKaprogController::class, 'generatePDFbukutamuortu'])->name('bukutamuortu.generatepdfbukutamuortu');
Route::get('persentasesosialekonomiview', [ViewAdmWalasKaprogController::class, 'persentasesosialekonomi'])->name('admwalas.persentasesosialekonomi');
Route::get('rentangpendapatanortuview', [ViewAdmWalasKaprogController::class, 'rentangpendapatanortu'])->name('admwalas.rentangpendapatanortu');
Route::post('/rentangpendapatanortuview/generatepdf', [ViewAdmWalasKaprogController::class, 'generatePDFpendapatanortu'])->name('pendapatanortu.generatepdfpendapatanortu');
Route::get('prestasisiswaview', [ViewAdmWalasKaprogController::class, 'prestasisiswa'])->name('admwalas.prestasisiswa');
Route::post('/prestasisiswaview/generatepdf', [ViewAdmWalasKaprogController::class, 'generatePDFprestasi'])->name('prestasisiswa.generatepdfprestasi');
Route::get('grafikjaraktempuhview', [ViewAdmWalasKaprogController::class, 'grafikjaraktempuh'])->name('admwalas.grafikjaraktempuh');
Route::post('/grafikjaraktempuhview/generatepdf', [ViewAdmWalasKaprogController::class, 'generatePDFgrafikjaraktempuh'])->name('grafikjaraktempuh.generatepdfgrafikjaraktempuh');

// Kakom View TA
Route::get('/detailkelasview/{walas_id}', [KakomRombelController::class, 'showDetail'])->name('rombel.showDetail');

// Route Kepsek Walas Data
Route::resource('admwalasviewkepsek', AdmWalasViewKepsekController::class);
Route::get('beritaacarakenaikanviewkepsek', [ViewAdmWalasKepsekController::class, 'beritaacarakenaikankepsek'])->name('admwalas.beritaacarakenaikankepsek');
Route::get('beritaacarakelulusanviewkepsek', [ViewAdmWalasKepsekController::class, 'beritaacarakelulusankepsek'])->name('admwalas.beritaacarakelulusankepsek');
Route::get('beritaacaraserahterimaviewkepsek', [ViewAdmWalasKepsekController::class, 'beritaacaraserahterimakepsek'])->name('admwalas.beritaacaraserahterimakepsek');
Route::get('agendawalasviewkepsek', [ViewAdmWalasKepsekController::class, 'agendawalaskepsek'])->name('admwalas.agendawalaskepsek');
Route::get('identiaskelasviewkepsek', [ViewAdmWalasKepsekController::class, 'identitaskelaskepsek'])->name('admwalas.identitaskelaskepsek');
Route::get('lembarpengesahanviewkepsek', [ViewAdmWalasKepsekController::class, 'lembarpengesahankepsek'])->name('admwalas.lembarpengesahankepsek');
Route::get('strukturorganisasikelasviewkepsek', [ViewAdmWalasKepsekController::class, 'strukturorganisasikelaskepsek'])->name('admwalas.strukturorganisasikelaskepsek');
Route::get('jadwalkbmviewkepsek', [ViewAdmWalasKepsekController::class, 'jadwalkbmkepsek'])->name('admwalas.jadwalkbmkepsek');
Route::get('presensisviewkepsek', [ViewAdmWalasKepsekController::class, 'presensiskepsek'])->name('admwalas.presensiskepsek');
Route::get('piketkelasviewkepsek', [ViewAdmWalasKepsekController::class, 'piketkelaskepsek'])->name('admwalas.piketkelaskepsek');
Route::get('serahterimaraporviewkepsek', [ViewAdmWalasKepsekController::class, 'serahterimaraporkepsek'])->name('admwalas.serahterimaraporkepsek');
Route::get('catatankasusviewkepsek', [ViewAdmWalasKepsekController::class, 'catatankasuskepsek'])->name('admwalas.catatankasuskepsek');
Route::get('daftarpesertadidikviewkepsek', [ViewAdmWalasKepsekController::class, 'daftarpesertadidikkepsek'])->name('admwalas.daftarpesertadidikkepsek');
Route::get('rekapitulasijumlahsiswaviewkepsek', [ViewAdmWalasKepsekController::class, 'rekapitulasipdidikkepsek'])->name('admwalas.rekapitulasipdidikkepsek');
Route::get('homevisitviewkepsek', [ViewAdmWalasKepsekController::class, 'homevisitkepsek'])->name('admwalas.homevisitkepsek');
Route::post('/homevisitviewkepsek/generatepdf', [ViewAdmWalasKepsekController::class, 'generatePDFkepsekhomevisit'])->name('homevisit.generatepdfkepsekhomevisit');
Route::get('bukutamuviewkepsek', [ViewAdmWalasKepsekController::class, 'bukutamuortukepsek'])->name('admwalas.bukutamuortukepsek');
Route::post('/bukutamuviewkepsek/generatepdf', [ViewAdmWalasKepsekController::class, 'generatePDFkepsekbukutamuortu'])->name('bukutamuortu.generatepdfkepsekbukutamuortu');
Route::get('persentasesosialekonomiviewkepsek', [ViewAdmWalasKepsekController::class, 'persentasesosialekonomikepsek'])->name('admwalas.persentasesosialekonomikepsek');
Route::get('rentangpendapatanortuviewkepsek', [ViewAdmWalasKepsekController::class, 'rentangpendapatanortukepsek'])->name('admwalas.rentangpendapatanortukepsek');
Route::post('/rentangpendapatanortuviewkepsek/generatepdf', [ViewAdmWalasKepsekController::class, 'generatePDFkepsekpendapatanortu'])->name('pendapatanortu.generatepdfkepsekpendapatanortu');
Route::get('prestasisiswaviewkepsek', [ViewAdmWalasKepsekController::class, 'prestasisiswakepsek'])->name('admwalas.prestasisiswakepsek');
Route::post('/prestasisiswaviewkepsek/generatepdf', [ViewAdmWalasKepsekController::class, 'generatePDFkepsekprestasi'])->name('prestasisiswa.generatepdfkepsekprestasi');
Route::get('grafikjaraktempuhviewkepsek', [ViewAdmWalasKepsekController::class, 'grafikjaraktempuhkepsek'])->name('admwalas.grafikjaraktempuhkepsek');
Route::post('/grafikjaraktempuhviewkepsek/generatepdf', [ViewAdmWalasKepsekController::class, 'generatePDFkepsekgrafikjaraktempuh'])->name('grafikjaraktempuh.generatepdfkepsekgrafikjaraktempuh');

// Kepsek View TA
Route::get('/detailkelasviewkepsek/{walas_id}', [KepsekRombelController::class, 'showDetailKepsek'])->name('rombel.showDetailKepsek');

// Route Kurikulum Walas Data
Route::resource('admwalasviewkurikulum', AdmWalasKurikulumViewController::class);
Route::get('beritaacarakenaikanviewkurikulum', [ViewAdmWalaskurikulumController::class, 'beritaacarakenaikankurikulum'])->name('admwalas.beritaacarakenaikankurikulum');
Route::get('beritaacarakelulusanviewkurikulum', [ViewAdmWalaskurikulumController::class, 'beritaacarakelulusankurikulum'])->name('admwalas.beritaacarakelulusankurikulum');
Route::get('beritaacaraserahterimaviewkurikulum', [ViewAdmWalaskurikulumController::class, 'beritaacaraserahterimakurikulum'])->name('admwalas.beritaacaraserahterimakurikulum');
Route::get('agendawalasviewkurikulum', [ViewAdmWalaskurikulumController::class, 'agendawalaskurikulum'])->name('admwalas.agendawalaskurikulum');
Route::get('identiaskelasviewkurikulum', [ViewAdmWalaskurikulumController::class, 'identitaskelaskurikulum'])->name('admwalas.identitaskelaskurikulum');
Route::get('lembarpengesahanviewkurikulum', [ViewAdmWalaskurikulumController::class, 'lembarpengesahankurikulum'])->name('admwalas.lembarpengesahankurikulum');
Route::get('strukturorganisasikelasviewkurikulum', [ViewAdmWalaskurikulumController::class, 'strukturorganisasikelaskurikulum'])->name('admwalas.strukturorganisasikelaskurikulum');
Route::get('jadwalkbmviewkurikulum', [ViewAdmWalaskurikulumController::class, 'jadwalkbmkurikulum'])->name('admwalas.jadwalkbmkurikulum');
Route::get('presensisviewkurikulum', [ViewAdmWalaskurikulumController::class, 'presensiskurikulum'])->name('admwalas.presensiskurikulum');
Route::get('piketkelasviewkurikulum', [ViewAdmWalaskurikulumController::class, 'piketkelaskurikulum'])->name('admwalas.piketkelaskurikulum');
Route::get('serahterimaraporviewkurikulum', [ViewAdmWalaskurikulumController::class, 'serahterimaraporkurikulum'])->name('admwalas.serahterimaraporkurikulum');
Route::get('catatankasusviewkurikulum', [ViewAdmWalaskurikulumController::class, 'catatankasuskurikulum'])->name('admwalas.catatankasuskurikulum');
Route::get('daftarpesertadidikviewkurikulum', [ViewAdmWalaskurikulumController::class, 'daftarpesertadidikkurikulum'])->name('admwalas.daftarpesertadidikkurikulum');
Route::get('rekapitulasijumlahsiswaviewkurikulum', [ViewAdmWalaskurikulumController::class, 'rekapitulasipdidikkurikulum'])->name('admwalas.rekapitulasipdidikkurikulum');
Route::get('homevisitviewkurikulum', [ViewAdmWalaskurikulumController::class, 'homevisitkurikulum'])->name('admwalas.homevisitkurikulum');
Route::post('/homevisitviewkurikulum/generatepdf', [ViewAdmWalaskurikulumController::class, 'generatePDFkurikulumhomevisit'])->name('homevisit.generatepdfkurikulumhomevisit');
Route::get('bukutamuviewkurikulum', [ViewAdmWalaskurikulumController::class, 'bukutamuortukurikulum'])->name('admwalas.bukutamuortukurikulum');
Route::post('/bukutamuviewkurikulum/generatepdf', [ViewAdmWalaskurikulumController::class, 'generatePDFkurikulumbukutamuortu'])->name('bukutamuortu.generatepdfkurikulumbukutamuortu');
Route::get('persentasesosialekonomiviewkurikulum', [ViewAdmWalaskurikulumController::class, 'persentasesosialekonomikurikulum'])->name('admwalas.persentasesosialekonomikurikulum');
Route::get('rentangpendapatanortuviewkurikulum', [ViewAdmWalaskurikulumController::class, 'rentangpendapatanortukurikulum'])->name('admwalas.rentangpendapatanortukurikulum');
Route::post('/rentangpendapatanortuviewkurikulum/generatepdf', [ViewAdmWalaskurikulumController::class, 'generatePDFkurikulumpendapatanortu'])->name('pendapatanortu.generatepdfkurikulumpendapatanortu');
Route::get('prestasisiswaviewkurikulum', [ViewAdmWalaskurikulumController::class, 'prestasisiswakurikulum'])->name('admwalas.prestasisiswakurikulum');
Route::post('/prestasisiswaviewkurikulum/generatepdf', [ViewAdmWalaskurikulumController::class, 'generatePDFkurikulumprestasi'])->name('prestasisiswa.generatepdfkurikulumprestasi');
Route::get('grafikjaraktempuhviewkurikulum', [ViewAdmWalaskurikulumController::class, 'grafikjaraktempuhkurikulum'])->name('admwalas.grafikjaraktempuhkurikulum');
Route::post('/grafikjaraktempuhviewkurikulum/generatepdf', [ViewAdmWalaskurikulumController::class, 'generatePDFkurikulumgrafikjaraktempuh'])->name('grafikjaraktempuh.generatepdfkurikulumgrafikjaraktempuh');

// Kurikulum View Kurikulum
Route::get('/detailkelasviewkurikulum/{walas_id}', [RombelDataController::class, 'showDetailKurikulum'])->name('rombel.showDetailKurikulum');

// Absen
Route::get('/absensi', [AbsenController::class, 'index'])->name('absensi.index');

// CRUD TA
Route::post('/keluar-rombel/save', [DataSiswaWalasController::class, 'saveKeterangan'])->name('keluar-rombel.save');
Route::post('/siswadata/simpan-keterangan/{id}', [KeluarRombelController::class, 'store'])->name('siswadata.simpanKeterangan');
Route::resource('keluarrombeldata', KeluarRombelViewController::class);

// Keluar Rombel View
Route::resource('keluarrombeldata', KeluarRombelViewController::class);
Route::get('/keluarrombeldetail/{rombels_id}', [KeluarRombelViewController::class, 'showSiswaKeluarRombel'])
    ->name('detail.keluarormbel');
Route::resource('keluarrombeldatakepsek', KeluarRombelViewKepsek::class);
Route::get('/keluarrombeldetailkepsek/{rombels_id}', [KeluarRombelViewKepsek::class, 'showSiswaKeluarRombelkepsek'])
        ->name('detail.keluarormbelkepsek');
Route::resource('keluarrombeldatakurikulum', KeluarRombelViewKurikulumController::class);
Route::get('/keluarrombeldetailkurikulum/{rombels_id}', [KeluarRombelViewKurikulumController::class, 'showSiswaKeluarRombelkurikulum'])
        ->name('detail.keluarormbelkurikulum');
Route::resource('keluarrombeldatakakom', KeluarRombelViewKakomController::class);
Route::get('/keluarrombeldetailkakom/{rombels_id}', [KeluarRombelViewKakomController::class, 'showSiswaKeluarRombelKakom'])
                ->name('detail.keluarormbelkakom');


// Route Alumni
Route::get('/alumni', [AlumniDataController::class, 'pengaturanalumni'])->name('alumni.index');
Route::get('/alumnidatakepsek', [AlumniDataController::class, 'pengaturanalumnikepsek'])->name('alumnikepsek.index');
Route::get('/alumnidatakakom', [AlumniDataController::class, 'pengaturanalumnikakom'])->name('alumnikakom.index');
Route::get('/alumnidatakurikulum', [AlumniDataController::class, 'pengaturanalumnikurikulum'])->name('alumnikurikulum.index');

// Logout admin
Route::post('/homepageadmin/logout', function () {
    Auth::guard('admins')->logout();
    session()->flash('status', 'Logout Berhasil');
    return redirect('/');
})->name('logoutadmin');


// Logout admin
Route::post('/homepageadmin/logout', function () {
    Auth::guard('admins')->logout();
    session()->flash('status', 'Logout Berhasil');
    return redirect('/');
})->name('logoutadmin');

// Logout walas
Route::post('/homepagegtk/logout', function () {
    Auth::guard('walas')->logout();
    session()->flash('status', 'Logout Berhasil');
    return redirect('/');
})->name('logoutwalas');

// Logout kakom
Route::post('/homepagekaprog/logout', function () {
    Auth::guard('kakoms')->logout();
    session()->flash('status', 'Logout Berhasil');
    return redirect('/');
})->name('logoutkakom');

// Logout kepsek
Route::post('/homepagekepsek/logout', function () {
    Auth::guard('kepseks')->logout();
    session()->flash('status', 'Logout Berhasil');
    return redirect('/');
})->name('logoutkepsek');

// Logout kurikullum
Route::post('/homepagekurikulum/logout', function () {
    Auth::guard('kurikulums')->logout();
    session()->flash('status', 'Logout Berhasil');
    return redirect('/');
})->name('logoutkurikulum');

// Logout kurikullum
Route::post('/homepagesiswa/logout', function () {
    Auth::guard('siswas')->logout();
    session()->flash('status', 'Logout Berhasil');
    return redirect('/');
})->name('logoutsiswa');
