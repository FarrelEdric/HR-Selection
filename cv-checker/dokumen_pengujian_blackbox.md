# DOKUMEN PENGUJIAN BLACKBOX
## SISTEM APLIKASI CV CHECKER & REKRUTMEN (BERBASIS LARAVEL & AI n8n)

Dokumen ini disusun untuk merekam hasil pengujian fungsionalitas sistem aplikasi **CV Checker & Rekrutmen** menggunakan metode pengujian **Blackbox Testing** (khususnya *Equivalence Partitioning* dan *Boundary Value Analysis*). Dokumen ini ditujukan sebagai lampiran atau bab pengujian pada naskah skripsi.

---

## 1. PENDAHULUAN

### 1.1 Deskripsi Umum Sistem
Sistem Aplikasi CV Checker & Rekrutmen adalah sistem berbasis web (Laravel) yang mengintegrasikan portal lowongan kerja publik untuk pelamar dengan sistem manajemen rekrutmen internal admin serta analisis CV otomatis berbasis kecerdasan buatan (AI/LLM) menggunakan integrasi alur kerja *n8n*.

### 1.2 Metode Pengujian
Pengujian dilakukan dengan menggunakan metode **Blackbox Testing**, yaitu menguji sistem dari segi spesifikasi fungsionalitas tanpa harus mengetahui struktur internal kode program (*source code*). Teknik yang digunakan meliputi:
* **Equivalence Partitioning (EP):** Membagi domain masukan ke dalam beberapa kelas data untuk merancang kasus uji yang valid dan tidak valid.
* **Boundary Value Analysis (BVA):** Memfokuskan pengujian pada nilai batas masukan (seperti batas ukuran file unggahan dan panjang karakter).

### 1.3 Lingkungan Pengujian (Test Environment)
* **Sistem Operasi:** Windows 10/11
* **Web Server:** Apache (via Laragon/XAMPP) / PHP Built-in Server
* **Bahasa Pemrograman:** PHP >= 8.2 (Laravel Framework) & Node.js (untuk konverter Excel)
* **Database Server:** MySQL / MariaDB
* **Web Browser:** Google Chrome / Mozilla Firefox
* **Layanan Eksternal:** n8n Workflow Automation Engine, Google Drive API

---

## 2. MATRIKS KASUS UJI (TEST CASE MATRIX)

Berikut adalah daftar use case yang diuji beserta jumlah kasus uji yang dirancang:

| ID Use Case | Nama Use Case | Jumlah Kasus Uji | Skenario Pengujian |
| :--- | :--- | :---: | :--- |
| **UC-01** | Melihat Daftar Lowongan | 1 | Menampilkan seluruh daftar lowongan pekerjaan aktif di halaman utama. |
| **UC-02** | Melihat Detail Lowongan | 1 | Menampilkan informasi lengkap persyaratan kerja dari lowongan tertentu. |
| **UC-03** | Melamar Pekerjaan | 3 | Mengirim berkas lamaran dengan data valid, data tidak lengkap, serta file yang tidak didukung atau melebihi batas ukuran maksimal. |
| **UC-04** | Login Admin | 2 | Login dengan akun terdaftar (sukses) dan login dengan akun salah / format salah (gagal). |
| **UC-05** | Logout Admin | 1 | Mengakhiri sesi login admin dan kembali ke portal utama. |
| **UC-06** | Memantau Statistik Dashboard | 2 | Membuka dashboard statistik utama dan memfilter grafik tren pelamar berdasarkan tanggal. |
| **UC-07** | Mengelola Lowongan Kerja (CRUD) | 4 | Menambah lowongan baru (sukses/gagal validasi), mengubah data lowongan, dan menghapus lowongan. |
| **UC-08** | Memantau & Mengelola Pelamar | 3 | Melihat daftar pelamar masuk, mencari/menyaring data pelamar, serta menghapus data pelamar. |
| **UC-09** | Mengelola Data Kandidat (CRUD) | 3 | Menambah data kandidat manual, menyunting profil kandidat, dan menghapus profil kandidat. |
| **UC-10** | Import Data Kandidat | 2 | Import massal menggunakan berkas Excel/CSV valid (sukses) dan berkas tanpa kolom nama (gagal). |
| **UC-11** | Export Data Kandidat | 2 | Mengunduh berkas laporan format CSV dan PDF (A4 Landscape) dengan kriteria filter pencarian. |
| **UC-12** | Bulk Delete Data Kandidat | 1 | Menghapus banyak data kandidat sekaligus menggunakan fitur checkbox checklist. |
| **UC-13** | Memicu Analisis CV Google Drive | 2 | Mengirim link Google Drive ke n8n dengan form lengkap (sukses) dan form kosong (gagal). |
| **UC-14** | Menerima Hasil Analisis CV | 2 | Menerima callback data hasil analisis dari n8n dengan token valid (sukses) dan token tidak valid (ditolak). |
| **UC-15** | Memantau & Memfilter Hasil Analisis CV AI | 3 | Menampilkan hasil analisis terurut skor, menyaring berdasarkan kota/posisi/rekomendasi, dan membuka modal detail evaluasi AI. |

---

### 3. DETAIL HASIL PENGUJIAN BLACKBOX

### Kelompok 1: Fitur Pelamar (Public Portal)

#### UC-01: Melihat Daftar Lowongan
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC01-01** | Menampilkan seluruh daftar lowongan pekerjaan aktif di halaman utama. | 1. Pengguna membuka peramban (browser).<br>2. Pengguna mengakses URL utama aplikasi (`/`). | Akses URL halaman utama (`/`). | Sistem memuat halaman utama secara cepat, menampilkan navigasi portal karir, dan menampilkan daftar kartu lowongan kerja yang aktif (Judul Lowongan, Lokasi) yang diambil dari database. | Sistem menampilkan halaman utama portal karir lengkap dengan daftar lowongan kerja terbaru. | Berhasil (Sesuai) |

#### UC-02: Melihat Detail Lowongan
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC02-01** | Menampilkan detail lowongan kerja terpilih. | 1. Pengguna berada di halaman utama (`/`).<br>2. Pengguna menekan tombol "Detail" atau "Lihat Lowongan" pada salah satu kartu lowongan pekerjaan. | Klik tombol dengan tautan `/job/{id}` (misal: `/job/1`). | Sistem mengarahkan pengguna ke halaman `/job/{id}`, menampilkan informasi detail secara lengkap meliputi: Judul lowongan, lokasi, deskripsi tugas, kualifikasi, benefit, serta menampilkan formulir melamar pekerjaan. | Sistem berhasil mengarahkan ke halaman detail lowongan pekerjaan dan menampilkan deskripsi detail beserta formulir lamaran. | Berhasil (Sesuai) |

#### UC-03: Melamar Pekerjaan
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC03-01** | Kasus Positif - Sukses Melamar | 1. Pengguna membuka formulir lamaran di halaman detail lowongan.<br>2. Pengguna mengisi semua kolom wajib dengan data valid.<br>3. Pengguna mengunggah berkas CV format PDF (ukuran < 5MB).<br>4. Pengguna menekan tombol "Kirim Lamaran". | Nama: `Ahmad Fauzi`<br>Email: `ahmad.fauzi@gmail.com`<br>No. Telepon: `081234567890`<br>Alamat: `Jl. Merdeka No. 45, Bandung`<br>File CV: `cv_ahmad.pdf` (1.2 MB, PDF)<br>LinkedIn: `https://linkedin.com/in/ahmadfauzi` (opsional) | Sistem menyimpan berkas unggahan ke storage lokal, menyimpan data pendaftaran ke database dengan status default "Applied", menampilkan notifikasi sukses "Lamaran berhasil dikirim", dan mengosongkan form. | Respons JSON diterima dengan pesan `'Lamaran berhasil dikirim'` dan data berhasil tersimpan di tabel `candidates` dengan status "Applied". | Berhasil (Sesuai) |
| **BB-UC03-02** | Kasus Negatif - Kolom Wajib Kosong | 1. Pengguna mengosongkan kolom Nama, Email, Telepon, Alamat, dan berkas CV.<br>2. Pengguna menekan tombol "Kirim Lamaran". | Form kosong tanpa input. | Sistem memvalidasi input, menolak pengiriman, dan menampilkan pesan kesalahan validasi di bawah setiap kolom wajib (misal: "The name field is required.", "The email field is required.", dst.). | Sistem menolak pengiriman dan memunculkan respons validasi error untuk masing-masing kolom yang kosong. | Berhasil (Sesuai) |
| **BB-UC03-03** | Kasus Negatif - Ukuran & Format File Tidak Valid | 1. Pengguna mengisi formulir dengan data diri valid.<br>2. Pengguna mengunggah file CV berformat `.exe` atau `.txt` atau file PDF berukuran 8 MB (melebihi batas 5MB).<br>3. Pengguna menekan tombol "Kirim Lamaran". | File CV: `game_installer.exe` (Ukuran: 8.5 MB). | Sistem memvalidasi unggahan berkas, menggagalkan pendaftaran, dan menampilkan pesan error: "The cv file must be a file of type: pdf, doc, docx." atau "The cv file must not be greater than 5120 kilobytes." | Sistem memblokir pengiriman berkas dan memunculkan pesan validasi format dan ukuran file yang salah. | Berhasil (Sesuai) |

---

### Kelompok 2: Autentikasi Admin

#### UC-04: Login Admin
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC04-01** | Kasus Positif - Login Sukses | 1. Admin membuka halaman login (`/login`).<br>2. Admin memasukkan email dan password akun terdaftar yang benar.<br>3. Admin menekan tombol "Login". | Email: `admin@recruitment.com`<br>Password: `passwordadmin123` | Sistem berhasil melakukan autentikasi, membuat sesi baru, dan mengarahkan admin ke dasbor utama (`/admin`). | Sistem mengarahkan admin ke dashboard `/admin` dan mengubah status navigasi menjadi "Logged In". | Berhasil (Sesuai) |
| **BB-UC04-02** | Kasus Negatif - Login Gagal | 1. Admin membuka halaman login (`/login`).<br>2. Admin memasukkan email terdaftar tetapi password salah, atau memasukkan email tidak terdaftar.<br>3. Admin menekan tombol "Login". | Email: `admin@recruitment.com`<br>Password: `salahpassword` | Sistem menolak login, tidak membuat sesi baru, mengembalikan admin ke halaman login, dan menampilkan pesan error: "The provided credentials do not match our records." | Sistem menampilkan error validasi credential dan admin tetap berada di halaman login. | Berhasil (Sesuai) |

#### UC-05: Logout Admin
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC05-01** | Keluar dari Sesi Admin | 1. Admin berada di panel admin terautentikasi.<br>2. Admin menekan tombol "Logout" pada pojok kanan atas / menu panel. | Klik tombol "Logout" (mengirim request `POST` ke `/logout`). | Sistem mengakhiri sesi login admin, menghapus session token, dan mengarahkan kembali ke halaman utama publik (`/`). Akses ke URL `/admin` setelah logout harus diblokir oleh middleware auth dan dialihkan ke `/login`. | Sesi admin berhasil ditutup, dialihkan ke halaman depan, dan upaya mengakses `/admin` langsung dibuang ke halaman login. | Berhasil (Sesuai) |

---

### Kelompok 3: Dashboard & Analisis

#### UC-06: Memantau Statistik Dashboard
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC06-01** | Memantau Angka & Tren Statistik | 1. Admin melakukan login sukses dan berada di halaman dashboard `/admin`. | Akses URL `/admin`. | Dashboard menampilkan kartu informasi ringkasan: Total Pelamar (Candidate Count), Total Lowongan Kerja Aktif, Jumlah Pelamar Diterima (Accepted), Jumlah Pelamar Pending, tabel pelamar terbaru (Recent Applicants), serta menampilkan visualisasi grafik tren lamaran harian (Chart.js). | Semua widget angka statistik dan grafik Chart.js tampil dengan data dinamis yang sesuai dengan database. | Berhasil (Sesuai) |
| **BB-UC06-02** | Filter Tanggal Grafik | 1. Admin berada di halaman dashboard `/admin`.<br>2. Admin memilih "Start Date" dan "End Date" pada form filter tanggal grafik, lalu menekan tombol "Filter". | Start Date: `2026-06-15`<br>End Date: `2026-06-22` | Grafik Chart.js secara dinamis memuat ulang sumbu X (label tanggal dari 15 Jun s/d 22 Jun) dan sumbu Y (jumlah pelamar pada tanggal tersebut) sesuai rentang waktu yang dipilih. | URL berubah menjadi `/admin?start_date=2026-06-15&end_date=2026-06-22` dan grafik menampilkan data sesuai rentang tanggal tersebut. | Berhasil (Sesuai) |

---

### Kelompok 4: Manajemen Lowongan Kerja

#### UC-07: Mengelola Lowongan Kerja (CRUD)
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC07-01** | Create - Tambah Lowongan | 1. Admin membuka menu "Manajemen Lowongan" -> klik "Tambah Lowongan".<br>2. Admin mengisi Form Lowongan dengan lengkap dan menekan "Simpan". | Title: `Laravel Web Developer`<br>Location: `Bandung (Hybrid)`<br>Description: `Mengembangkan aplikasi web berbasis Laravel...`<br>Qualification: `Minimal 2 tahun pengalaman, menguasai REST API...`<br>Benefit: `Asuransi, bonus performa, lingkungan kerja fleksibel` | Sistem memvalidasi masukan, menyimpan data ke tabel `jobs`, mengalihkan kembali ke daftar lowongan (`/admin/jobs`), serta memunculkan notifikasi sukses "Lowongan berhasil ditambahkan". | Lowongan baru tersimpan di database dan tampil di daftar teratas tabel manajemen lowongan dengan notifikasi sukses. | Berhasil (Sesuai) |
| **BB-UC07-02** | Create - Gagal Validasi Form Kosong | 1. Admin membuka form Tambah Lowongan.<br>2. Admin mengosongkan kolom Title, Location, Description, dan Qualification, lalu menekan "Simpan". | Form kosong. | Sistem membatalkan penyimpanan dan memunculkan pesan validasi error: "The title field is required.", "The location field is required.", "The description field is required.", "The qualification field is required." | Penyimpanan digagalkan dan pesan error validasi muncul di form. | Berhasil (Sesuai) |
| **BB-UC07-03** | Update - Mengubah Detail Lowongan | 1. Admin menekan tombol "Edit" pada salah satu lowongan di tabel.<br>2. Admin mengubah data Title dan Location pada form, lalu menekan "Update". | Title: `Senior Laravel Web Developer`<br>Location: `Jakarta (Full Remote)` | Sistem memvalidasi perubahan, memperbarui baris data di tabel `jobs`, mengalihkan kembali ke `/admin/jobs`, dan menampilkan pesan "Lowongan berhasil diupdate". | Data lowongan ter-update di database dan daftar halaman menampilkan perubahan title serta lokasi yang baru. | Berhasil (Sesuai) |
| **BB-UC07-04** | Delete - Menghapus Lowongan | 1. Admin menekan tombol "Hapus" pada salah satu lowongan kerja di daftar tabel.<br>2. Admin menyetujui konfirmasi dialog penghapusan. | Klik tombol hapus (mengirim request `DELETE` ke `/admin/jobs/{id}`). | Sistem menghapus baris data lowongan tersebut dari database, memuat ulang halaman `/admin/jobs`, dan menampilkan pesan "Lowongan berhasil dihapus". | Data terhapus secara permanen dari database, baris data hilang dari tabel, dan notifikasi sukses muncul. | Berhasil (Sesuai) |

---

### Kelompok 5: Manajemen Pelamar Masuk

#### UC-08: Memantau & Mengelola Pelamar
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC08-01** | Melihat Daftar Pelamar & Detail Berkas | 1. Admin masuk ke menu "Data Pelamar" (`/admin/candidates`).<br>2. Admin menekan tombol "Detail" atau nama salah satu pelamar untuk melihat profil lengkap dan berkas unggahan.<br>3. Admin menekan tautan berkas CV, KTP, atau KK. | Akses halaman `/admin/candidates` dan klik berkas lampiran. | Sistem memuat tabel berisi seluruh nama pelamar yang melamar via portal karir publik. Halaman detail memuat profil lengkap serta tombol file lampiran. Tautan berkas membuka file asli yang disimpan di storage lokal (misal: PDF CV terbuka langsung di tab baru browser atau diunduh). | Semua berkas pelamar dapat dibuka di tab browser baru secara langsung menggunakan stream response dari file local storage Laravel. | Berhasil (Sesuai) |
| **BB-UC08-02** | Mencari dan Memfilter Pelamar | 1. Admin berada di halaman daftar pelamar (`/admin/candidates`).<br>2. Admin memasukkan kata kunci nama pada kotak pencarian dan memilih lowongan pekerjaan pada dropdown filter, lalu menekan "Filter". | Search: `Ahmad`<br>Job: `Laravel Web Developer` | Sistem menyaring baris tabel dan hanya menampilkan pelamar bernama `Ahmad` yang mendaftar pada lowongan `Laravel Web Developer`. | Pencarian dan filter bekerja secara responsif, membatasi data tabel hanya pada pelamar yang sesuai filter. | Berhasil (Sesuai) |
| **BB-UC08-03** | Menghapus Pelamar | 1. Admin menekan tombol "Hapus" pada baris data pelamar tertentu.<br>2. Admin menyetujui konfirmasi penghapusan. | Klik tombol hapus (request `DELETE` ke `/admin/candidates/{id}`). | Data pelamar terhapus dari tabel `candidates` di database, halaman dimuat ulang dengan pesan sukses "Applicant deleted successfully." | Baris data pelamar hilang dan pesan sukses penghapusan muncul. | Berhasil (Sesuai) |

---

### Kelompok 6: Manajemen Data Kandidat (Parsed & Imported)

#### UC-09: Mengelola Data Kandidat (CRUD)
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC09-01** | Create - Tambah Kandidat Manual | 1. Admin membuka menu "Data Candidate" -> klik "Add Candidate".<br>2. Admin mengisi Form Kandidat dengan lengkap dan menekan "Save". | Name: `Budi Santoso`<br>Email: `budi.santoso@yahoo.com`<br>Phone: `087712345678`<br>City: `Yogyakarta`<br>Job: `Laravel Web Developer` (ID: 1)<br>Skills: `PHP, Laravel, MySQL, Bootstrap` | Sistem menyimpan profil kandidat ke tabel `candidate_data`, mengalihkan ke `/admin/candidate-data`, dan memunculkan notifikasi sukses "Candidate data created successfully." | Kandidat baru berhasil didaftarkan secara manual dan masuk ke tabel manajemen kandidat. | Berhasil (Sesuai) |
| **BB-UC09-02** | Update - Mengedit Profil Kandidat | 1. Admin menekan tombol "Edit" pada salah satu kandidat di halaman `/admin/candidate-data`.<br>2. Admin mengubah isi kolom City, Skills, dan Vote (Skor/Evaluasi), lalu menekan "Save". | City: `Sleman, Yogyakarta`<br>Skills: `PHP, Laravel, React.js, MySQL`<br>Vote: `8.5` | Sistem memperbarui baris data pada tabel `candidate_data`, mengalihkan kembali ke daftar kandidat, dan memunculkan pesan "Candidate data updated successfully." | Perubahan data berhasil tersimpan di database dan langsung tercermin pada halaman index maupun show kandidat. | Berhasil (Sesuai) |
| **BB-UC09-03** | Delete - Menghapus Kandidat | 1. Admin menekan tombol "Hapus" pada salah satu baris kandidat.<br>2. Admin melakukan konfirmasi penghapusan. | Klik tombol hapus (request `DELETE` ke `/admin/candidate-data/{id}`). | Baris data kandidat terhapus dari tabel `candidate_data`, halaman reload dengan notifikasi "Candidate data deleted successfully." | Data kandidat terhapus secara permanen dari database. | Berhasil (Sesuai) |

#### UC-10: Import Data Kandidat (Excel/CSV)
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC10-01** | Kasus Positif - Sukses Import dengan Konverter | 1. Admin membuka halaman `/admin/candidate-data`.<br>2. Admin menekan tombol "Import", memilih berkas Excel `.xlsx` yang berisi daftar nama kandidat dengan header kolom bahasa Indonesia / Inggris.<br>3. Admin menekan tombol "Submit Import". | Berkas: `kandidat_baru.xlsx` (Berisi kolom: *Nama Lengkap, Telepon, Surel, Kota, Keahlian*). | Sistem memicu script `convert_excel.js` untuk mengonversi Excel menjadi CSV sementara di latar belakang. Sistem membaca header, melakukan *Smart Header Mapping* secara bilingual, menstandardisasi tanggal lahir, menyimpan data ke `candidate_data`, dan menampilkan notifikasi sukses. | Sistem berhasil mengonversi berkas xlsx ke CSV via Node.js script, memetakan header secara bilingual, mengimport data kandidat secara massal, dan memberikan respons sukses. | Berhasil (Sesuai) |
| **BB-UC10-02** | Kasus Negatif - Kolom Nama Tidak Ditemukan | 1. Admin mengunggah berkas CSV yang tidak memiliki kolom "NAME" atau "Nama".<br>2. Admin menekan tombol "Submit Import". | Berkas: `kandidat_tanpa_nama.csv` (Kolom: *Telepon, Surel, Kota*). | Sistem membatalkan proses pembacaan baris data, menghapus berkas sementara, dan mengalihkan kembali halaman dengan pesan error: "Kolom 'NAME' tidak ditemukan. Sistem mendeteksi kolom: [telepon, surel, kota]. Mohon pastikan judul kolom benar." | Sistem memblokir proses import dan menampilkan pesan error detail mengenai kolom "NAME" yang tidak terdeteksi. | Berhasil (Sesuai) |

#### UC-11: Export Data Kandidat (CSV/PDF)
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC11-01** | Export CSV | 1. Admin berada di halaman `/admin/candidate-data`.<br>2. Admin menekan tombol "Export CSV". | Klik tombol "Export CSV" (mengakses `/admin/candidate-data/export`). | Sistem melakukan kueri ke seluruh data kandidat, menyusun berkas berformat CSV dengan baris pembatas koma/titik koma, dan browser langsung mengunduh file dengan nama `candidates_YYYY-MM-DD_HH-II-SS.csv`. | File CSV terunduh secara otomatis dengan struktur kolom yang lengkap dan data kandidat yang akurat. | Berhasil (Sesuai) |
| **BB-UC11-02** | Export PDF dengan Filter | 1. Admin berada di halaman `/admin/candidate-data`.<br>2. Admin memasukkan kata kunci pencarian kota "Bandung" pada filter pencarian.<br>3. Admin menekan tombol "Export PDF". | Filter kota = `Bandung` + Klik tombol "Export PDF" (mengakses `/admin/candidate-data/export-pdf?search=Bandung`). | Sistem memfilter data kandidat asal Bandung, memuat view template PDF, merender dokumen PDF berukuran A4 dengan orientasi Landscape menggunakan DomPDF, dan browser mengunduh berkas laporan `candidate-report-YYYY-MM-DD.pdf`. | Laporan PDF Landscape berhasil diunduh dan di dalamnya hanya memuat data kandidat yang tinggal di kota Bandung. | Berhasil (Sesuai) |

#### UC-12: Bulk Delete Data Kandidat
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC12-01** | Menghapus Massal Kandidat | 1. Admin berada di halaman daftar kandidat (`/admin/candidate-data`).<br>2. Admin memberi checklist centang pada 3 baris kandidat di tabel.<br>3. Admin menekan tombol "Bulk Delete" di atas tabel.<br>4. Admin menyetujui kotak dialog konfirmasi penghapusan. | Checkbox ID Kandidat: `[4, 7, 9]` + Klik "Bulk Delete". | Sistem melakukan kueri penghapusan massal untuk ID 4, 7, dan 9 dari database, memuat ulang halaman index, dan menampilkan notifikasi sukses "3 candidates deleted successfully." | Baris data kandidat terpilih terhapus sekaligus dan sistem memunculkan jumlah kandidat yang sukses dihapus. | Berhasil (Sesuai) |

---

### Kelompok 7: AI CV Checker & Analisis

#### UC-13: Memicu Analisis CV Google Drive
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC13-01** | Kasus Positif - Sukses Mengirim Trigger ke n8n | 1. Admin membuka menu "CV Checker" (`/admin/cv-checker`).<br>2. Admin mengisi Form Analisis CV: Link Google Drive Folder, Nama Folder, dan Kebutuhan Kriteria Profil secara lengkap.<br>3. Admin menekan tombol "Analyze". | Google Drive Link: `https://drive.google.com/drive/folders/1aBcDeFgHiJkLmNoP`<br>Folder Name: `CV_Kandidat_IT_2026`<br>Profile/Kriteria: `Mencari Backend Engineer dengan keahlian PHP, Laravel, PostgreSQL, Docker` | Sistem memvalidasi input form, memicu panggilan webhook API asinkron ke n8n service menggunakan class `N8nService`, lalu mengalihkan admin ke `/admin/cv-results` dengan pesan sukses. | Sistem berhasil melakukan pemicuan webhook n8n di background, lalu melakukan redirect halaman dengan pesan sukses yang sesuai. | Berhasil (Sesuai) |
| **BB-UC13-02** | Kasus Negatif - Input Tidak Lengkap / URL Salah | 1. Admin membuka menu "CV Checker".<br>2. Admin mengosongkan kolom "Kebutuhan Kriteria Profil" atau mengisi format "Google Drive Link" dengan teks biasa.<br>3. Admin menekan tombol "Analyze". | Google Drive Link: `ini-bukan-url-drive`<br>Folder Name: `CV_Folder`<br>Profile/Kriteria: (Kosong) | Sistem membatalkan pengiriman pemicu ke n8n dan memunculkan pesan validasi error: "The driveLink must be a valid URL." dan "The profile wanted field is required." | Pengiriman diblokir oleh Laravel Request Validation dan error ditampilkan di bawah masing-masing kolom input. | Berhasil (Sesuai) |

#### UC-14: Menerima Hasil Analisis CV (Callback API)
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC14-01** | Kasus Positif - Sukses Menerima Data Callback | 1. Layanan luar (n8n workflow) menyelesaikan ekstraksi dan penilaian CV kandidat.<br>2. n8n mengirimkan data JSON hasil analisis ke endpoint `/api/cv-result` dengan token valid. | Header: `Authorization: Bearer <token_valid>` + JSON payload lengkap hasil evaluasi (name, email, score, skills, dll.) | Middleware `ValidateN8nToken` menyetujui request, sistem memvalidasi input, mengonversi format tanggal lahir & processed_at, menyimpan ke `cv_results` dan `candidate_data`, serta merespon HTTP `200` dengan JSON `{"status": 200, "message": "Saved"}`. | Data tersimpan dengan sukses pada kedua tabel (`cv_results` dan `candidate_data`), dan n8n menerima respons HTTP 200 OK. | Berhasil (Sesuai) |
| **BB-UC14-02** | Kasus Negatif - Token Webhook Salah/Tidak Ada | 1. Penyerang luar memanggil URL `/api/cv-result` menggunakan payload JSON dengan token salah atau tanpa token. | Header: `Authorization: Bearer token_salah_atau_kosong` + JSON payload | Middleware `ValidateN8nToken` menolak request sebelum masuk ke Controller, menggagalkan proses simpan database, dan mengembalikan respons HTTP `401 Unauthorized` atau `403 Forbidden`. | Request diblokir oleh middleware dengan respons HTTP 401 Unauthorized, sehingga database aman dari manipulasi data eksternal ilegal. | Berhasil (Sesuai) |

#### UC-15: Memantau & Memfilter Hasil Analisis CV AI
| ID Pengujian | Skenario Pengujian | Langkah-Langkah Pengujian | Data Masukan (Test Input) | Hasil yang Diharapkan | Hasil Aktual | Kesimpulan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BB-UC15-01** | Menampilkan Hasil Terurut & Pencarian | 1. Admin masuk ke menu "Hasil Analisis CV" (`/admin/cv-results`).<br>2. Admin memantau pengurutan default.<br>3. Admin memasukkan kata kunci pencarian nama atau keahlian di kolom pencarian. | Akses URL `/admin/cv-results` & Input Search = `Rian`. | Halaman menampilkan tabel hasil analisis CV yang secara default terurut dari skor tertinggi (10) ke terendah (1). Pencarian memperbarui daftar baris tabel untuk menampilkan kandidat bernama/keahlian `Rian`. | Secara default, sistem menyortir data berdasarkan skor tertinggi, dan pencarian kata kunci berfungsi dengan tepat memunculkan data Rian Hidayat. | Berhasil (Sesuai) |
| **BB-UC15-02** | Penyaringan Berdasarkan Status Rekomendasi AI, Kota, & Posisi | 1. Admin berada di halaman `/admin/cv-results`.<br>2. Admin memilih filter dropdown "Status Rekomendasi" = `Recommended` dan memilih kota `Jakarta Selatan`.<br>3. Admin menekan tombol "Filter". | Dropdown Status = `recommended`, Kota = `Jakarta Selatan`. | Sistem melakukan kueri filter: `where score >= 8` dan `where city = 'Jakarta Selatan'`. Tabel dimuat ulang hanya menampilkan kandidat yang direkomendasikan AI yang tinggal di Jakarta Selatan. | Sistem menyaring data dengan benar sesuai parameter kueri URL `/admin/cv-results?status=recommended&city=Jakarta+Selatan`. | Berhasil (Sesuai) |
| **BB-UC15-03** | Membuka Detail Modal Evaluasi AI | 1. Admin berada di halaman daftar hasil analisis CV.<br>2. Admin mengklik baris data kandidat atau menekan tombol "Detail". | Klik tombol Detail untuk kandidat dengan ID 1. | Sistem memunculkan halaman/modal detail yang memuat informasi terperinci hasil olahan AI, meliputi: Rangkuman CV, Daftar Keahlian, Riwayat Kerja, Alasan Evaluasi AI, serta tombol tautan "Lihat CV Asli di Google Drive". | Seluruh informasi hasil ekstraksi teks dan evaluasi AI ditampilkan dengan detail, dan tautan file asli dapat diklik dengan mengarahkan langsung ke URL Google Drive yang sesuai. | Berhasil (Sesuai) |

---

## 4. KESIMPULAN HASIL PENGUJIAN

Berdasarkan pengujian fungsionalitas (Blackbox Testing) yang telah dilakukan terhadap **15 Use Case** pada aplikasi **CV Checker & Rekrutmen**, ditarik kesimpulan sebagai berikut:

1. **Seluruh fungsionalitas utama sistem** (mulai dari portal pelamar publik, manajemen data lowongan, autentikasi admin, pengolahan data kandidat manual/import/export, hingga integrasi analisis CV berbasis AI lewat webhook n8n) **berjalan dengan baik dan 100% Sesuai/Berhasil**.
2. **Validasi data masukan** (baik pada form input teks biasa, format berkas lampiran lamaran, batasan ukuran file, maupun validasi format berkas Excel/CSV untuk import kandidat) telah berfungsi dengan baik untuk mencegah masuknya data rusak atau tidak valid ke dalam sistem.
3. **Keamanan Webhook callback API** dari n8n ke aplikasi Laravel telah teruji aman dengan adanya validasi token menggunakan middleware otentikasi khusus (`ValidateN8nToken`).
4. **Hasil pengujian ini menunjukkan** bahwa aplikasi telah memenuhi spesifikasi fungsional yang dirancang dalam Use Case dan siap digunakan sebagai dasar penulisan bab analisis hasil pengujian pada skripsi.
