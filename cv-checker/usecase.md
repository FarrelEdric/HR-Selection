# Dokumen Use Case - Aplikasi CV Checker & Rekrutmen

Dokumen ini berisi spesifikasi *Use Case* lengkap untuk sistem aplikasi **CV Checker & Rekrutmen** berbasis Laravel. Aplikasi ini mengintegrasikan portal lowongan kerja publik dengan sistem manajemen rekrutmen internal admin serta analisis CV otomatis berbasis AI menggunakan *n8n workflow*.

---

## 1. Aktor Sistem (Actors)

| Aktor | Deskripsi |
| :--- | :--- |
| **Pelamar (Candidate)** | Pengguna publik (non-login) yang mencari lowongan pekerjaan dan mengirimkan lamaran beserta berkas pendukung (CV, Portfolio, KTP, KK). |
| **Admin (HRD / Recruiter)** | Pengguna internal terautentikasi yang bertugas mengelola lowongan kerja, memantau pelamar, mengelola data kandidat (CRUD, import, export), serta memicu analisis CV otomatis. |
| **Sistem n8n** | Layanan automasi eksternal (diakses via webhook) yang melakukan ekstraksi teks CV dari Google Drive, mengevaluasinya menggunakan AI/LLM, dan mengirimkan kembali data hasil skor serta analisis ke aplikasi. |

---

## 2. Diagram Use Case (Mermaid)

```mermaid
usecaseDiagram
    actor "Pelamar (Candidate)" as Candidate
    actor "Admin (HRD/Recruiter)" as Admin
    actor "Sistem n8n (External)" as N8n

    left to right direction

    rectangle "Sistem CV Checker & Rekrutmen" {
        %% Public Use Cases
        usecase "UC-01: Melihat Daftar Lowongan" as UC01
        usecase "UC-02: Melihat Detail Lowongan" as UC02
        usecase "UC-03: Melamar Pekerjaan" as UC03
        
        %% Auth Use Cases
        usecase "UC-04: Login Admin" as UC04
        usecase "UC-05: Logout Admin" as UC05

        %% Dashboard & Analytics
        usecase "UC-06: Memantau Statistik Dashboard" as UC06

        %% Job Management
        usecase "UC-07: Mengelola Lowongan Kerja (CRUD)" as UC07

        %% Candidate Applicants Management
        usecase "UC-08: Memantau & Mengelola Pelamar" as UC08

        %% Candidate Data Management
        usecase "UC-09: Mengelola Data Kandidat (CRUD)" as UC09
        usecase "UC-10: Import Data Kandidat (Excel/CSV)" as UC10
        usecase "UC-11: Export Data Kandidat (CSV/PDF)" as UC11
        usecase "UC-12: Bulk Delete Data Kandidat" as UC12

        %% AI CV Checker
        usecase "UC-13: Memicu Analisis CV Google Drive" as UC13
        usecase "UC-14: Menerima Hasil Analisis CV" as UC14
        usecase "UC-15: Memantau Hasil Analisis CV AI" as UC15
    }

    %% Actor Relationships
    Candidate --> UC01
    Candidate --> UC02
    Candidate --> UC03

    Admin --> UC04
    Admin --> UC05
    Admin --> UC06
    Admin --> UC07
    Admin --> UC08
    Admin --> UC09
    Admin --> UC10
    Admin --> UC11
    Admin --> UC12
    Admin --> UC13
    Admin --> UC15

    N8n --> UC14
```

---

## 3. Spesifikasi Detail Use Case

### Kelompok 1: Fitur Pelamar (Public Portal)

#### UC-01: Melihat Daftar Lowongan
* **Deskripsi:** Pelamar membuka halaman utama portal karir untuk melihat semua lowongan pekerjaan yang sedang aktif.
* **Aktor:** Pelamar
* **Prekondisi:** Aplikasi terhubung ke database dan dapat diakses secara publik.
* **Alur Utama:**
  1. Pelamar mengunjungi URL utama aplikasi (`/`).
  2. Sistem mengambil data seluruh lowongan kerja terbaru dari database.
  3. Sistem menampilkan daftar lowongan kerja beserta informasi ringkas (Judul, Lokasi).
* **Postkondisi:** Pelamar melihat daftar lowongan kerja aktif.

#### UC-02: Melihat Detail Lowongan
* **Deskripsi:** Pelamar melihat informasi detail mengenai syarat, kualifikasi, deskripsi tugas, dan benefit dari suatu lowongan kerja tertentu.
* **Aktor:** Pelamar
* **Prekondisi:** Pelamar sedang membuka halaman daftar lowongan kerja.
* **Alur Utama:**
  1. Pelamar memilih salah satu lowongan kerja yang diinginkan.
  2. Sistem mengarahkan pelamar ke halaman detail lowongan (`/job/{id}`).
  3. Sistem memuat deskripsi, kualifikasi, benefit, dan lokasi secara lengkap.
* **Postkondisi:** Pelamar memahami detail persyaratan lowongan pekerjaan tersebut.

#### UC-03: Melamar Pekerjaan
* **Deskripsi:** Pelamar mengirimkan data diri beserta dokumen pendukung (CV, Portfolio, KTP, KK) untuk melamar pada lowongan kerja tertentu.
* **Aktor:** Pelamar
* **Prekondisi:** Pelamar berada di halaman detail lowongan kerja.
* **Alur Utama:**
  1. Pelamar menekan tombol "Lamar" atau mengisi formulir lamaran yang tersedia di halaman detail lowongan.
  2. Pelamar mengisi formulir: Nama Lengkap, Email, Nomor Telepon, Alamat, LinkedIn (opsional), serta mengunggah file CV (PDF/Docx), Portfolio (PDF/Zip), KTP (Jpg/Pdf), dan KK (Jpg/Pdf).
  3. Pelamar mengirimkan formulir lamaran.
  4. Sistem melakukan validasi tipe berkas dan ukuran file.
  5. Sistem menyimpan file unggahan ke storage lokal/public dan mencatat data pelamar di database dengan status default "Applied".
  6. Sistem memberikan respon sukses kepada pelamar.
* **Postkondisi:** Data lamaran berhasil tersimpan ke sistem rekrutmen.

---

### Kelompok 2: Autentikasi Admin

#### UC-04: Login Admin
* **Deskripsi:** Admin melakukan autentikasi untuk masuk ke dalam dasbor administrasi sistem rekrutmen.
* **Aktor:** Admin
* **Prekondisi:** Admin berada di halaman login (`/login`).
* **Alur Utama:**
  1. Admin memasukkan Email dan Password.
  2. Admin menekan tombol login.
  3. Sistem memverifikasi kredensial di database.
  4. Jika valid, sistem membuat sesi login dan mengarahkan admin ke dasbor utama (`/admin`).
* **Postkondisi:** Admin berhasil masuk ke dalam sesi terautentikasi untuk mengelola sistem.

#### UC-05: Logout Admin
* **Deskripsi:** Admin mengakhiri sesi masuk mereka dan keluar dari panel admin.
* **Aktor:** Admin
* **Prekondisi:** Admin sedang masuk ke sistem (terautentikasi).
* **Alur Utama:**
  1. Admin menekan tombol "Logout" pada menu panel.
  2. Sistem menghapus sesi autentikasi saat ini.
  3. Sistem mengarahkan admin kembali ke halaman login.
* **Postkondisi:** Sesi admin berakhir dan hak akses admin ditutup.

---

### Kelompok 3: Dashboard & Analisis

#### UC-06: Memantau Statistik Dashboard
* **Deskripsi:** Admin memantau kinerja rekrutmen, grafik lamaran masuk, dan rangkuman posisi terfavorit.
* **Aktor:** Admin
* **Prekondisi:** Admin sudah berhasil login.
* **Alur Utama:**
  1. Admin mengakses halaman dasbor utama (`/admin`).
  2. Sistem menghitung statistik: Total Pelamar, Total Lowongan Aktif, Jumlah Pelamar Berstatus Diterima/Pending.
  3. Sistem memuat data tren lamaran harian (grafik Chart.js) yang dapat difilter berdasarkan rentang tanggal.
  4. Sistem menampilkan daftar posisi lowongan beserta jumlah pelamar terbanyak (Job Interest).
* **Postkondisi:** Admin mendapatkan laporan statistik rekrutmen terbaru secara visual.

---

### Kelompok 4: Manajemen Lowongan Kerja

#### UC-07: Mengelola Lowongan Kerja (CRUD)
* **Deskripsi:** Admin dapat menambah lowongan baru, mengubah detail lowongan, menampilkan list, atau menghapus lowongan yang sudah tidak aktif.
* **Aktor:** Admin
* **Prekondisi:** Admin login dan masuk ke menu "Manajemen Lowongan".
* **Alur Utama:**
  * **Melihat & Mencari:** Admin membuka daftar lowongan, mencari berdasarkan kata kunci judul/lokasi, dan sistem menampilkan hasil yang dipaginasi.
  * **Tambah (Create):** Admin mengisi form lowongan (Judul, Deskripsi, Kualifikasi, Benefit, Lokasi) -> Sistem memvalidasi dan menyimpan ke database.
  * **Ubah (Update):** Admin memilih lowongan -> mengubah data pada form -> menyimpan kembali perubahan.
  * **Hapus (Delete):** Admin menghapus lowongan -> sistem menghapus data terkait dari database.
* **Postkondisi:** Database lowongan pekerjaan diperbarui sesuai aksi admin.

---

### Kelompok 5: Manajemen Pelamar Masuk

#### UC-08: Memantau & Mengelola Pelamar
* **Deskripsi:** Admin meninjau data pelamar publik yang masuk secara langsung melalui portal karir.
* **Aktor:** Admin
* **Prekondisi:** Admin login dan masuk ke menu "Data Pelamar".
* **Alur Utama:**
  1. Admin membuka daftar pelamar masuk.
  2. Admin dapat mencari nama/email pelamar atau memfilter berdasarkan lowongan yang dilamar.
  3. Admin memilih salah satu pelamar untuk melihat detail lamaran, alamat, berkas KTP, KK, serta berkas CV/Portfolio yang diunggah.
  4. Admin dapat menghapus data pelamar jika berkas tidak valid atau bermasalah.
* **Postkondisi:** Admin selesai meninjau berkas asli pelamar masuk.

---

### Kelompok 6: Manajemen Data Kandidat (Parsed & Imported)

#### UC-09: Mengelola Data Kandidat (CRUD)
* **Deskripsi:** Admin dapat mengelola data profil kandidat yang telah diekstrak atau diimport (meliputi data personal, riwayat pendidikan, riwayat kerja, keahlian, dan catatan evaluasi).
* **Aktor:** Admin
* **Prekondisi:** Admin login dan masuk ke menu "Data Candidate".
* **Alur Utama:**
  * Admin dapat menambah kandidat baru secara manual, atau mengedit profil kandidat yang sudah ada (mengubah nama, email, HP, domisili, pendidikan, keahlian, hingga hasil vote/pertimbangan).
  * Admin dapat menghapus data kandidat tertentu.
* **Postkondisi:** Data kandidat terupdate di database.

#### UC-10: Import Data Kandidat (Excel/CSV)
* **Deskripsi:** Admin mengunggah berkas eksternal (Excel/CSV) yang berisi data kandidat dalam jumlah banyak sekaligus.
* **Aktor:** Admin
* **Prekondisi:** Admin memiliki file berekstensi `.csv`, `.xlsx`, atau `.xls`.
* **Alur Utama:**
  1. Admin mengunggah berkas Excel/CSV kandidat di halaman manajemen data kandidat.
  2. Jika format berkas adalah Excel (`.xlsx`/`.xls`), sistem di latar belakang memicu script Node.js (`convert_excel.js`) untuk menerjemahkannya ke format CSV.
  3. Sistem mendeteksi pemisah kolom (koma `,` atau titik koma `;`) dan membaca baris header berkas secara dinamis.
  4. Sistem melakukan *Smart Header Mapping* secara bilingual (Inggris/Indonesia). Contoh: Header "Nama Lengkap" atau "Name" otomatis dipetakan ke field database `name`.
  5. Sistem memproses setiap baris, melakukan standardisasi format tanggal lahir, dan menyimpan data kandidat baru ke dalam database.
  6. Sistem menampilkan notifikasi jumlah data yang berhasil diimport dan data yang dilewati (skipped).
* **Postkondisi:** Data kandidat bertambah secara massal di database.

#### UC-11: Export Data Kandidat (CSV/PDF)
* **Deskripsi:** Admin mengunduh data seluruh kandidat yang tersimpan di sistem dalam bentuk dokumen CSV atau laporan cetak PDF.
* **Aktor:** Admin
* **Prekondisi:** Admin berada di halaman data kandidat.
* **Alur Utama:**
  * **Export CSV:** Admin menekan tombol "Export CSV" -> Sistem menghasilkan file CSV dengan struktur kolom lengkap kandidat dan mengirimkannya sebagai unduhan browser.
  * **Export PDF:** Admin menerapkan pencarian atau filter rentang tanggal (jika ada) -> menekan tombol "Export PDF" -> Sistem menyusun data kandidat ke dalam format A4 landscape dan mengunduh berkas laporan PDF.
* **Postkondisi:** Dokumen CSV atau PDF laporan kandidat berhasil tersimpan di komputer admin.

#### UC-12: Bulk Delete Data Kandidat
* **Deskripsi:** Admin menghapus banyak baris data kandidat sekaligus menggunakan fitur checklist.
* **Aktor:** Admin
* **Prekondisi:** Admin berada di halaman daftar data kandidat.
* **Alur Utama:**
  1. Admin mencentang beberapa kandidat yang ingin dihapus pada tabel daftar kandidat.
  2. Admin menekan tombol "Bulk Delete".
  3. Sistem meminta konfirmasi penghapusan.
  4. Setelah dikonfirmasi, sistem menghapus semua kandidat dengan ID yang dipilih secara massal.
* **Postkondisi:** Kandidat-kandidat terpilih berhasil dihapus sekaligus dari database.

---

### Kelompok 7: AI CV Checker & Analisis

#### UC-13: Memicu Analisis CV Google Drive
* **Deskripsi:** Admin menginstruksikan sistem untuk menganalisis tumpukan berkas CV yang tersimpan di Google Drive secara otomatis melalui *n8n integration*.
* **Aktor:** Admin
* **Prekondisi:** Admin login dan masuk ke menu "CV Checker". Berkas-berkas CV calon kandidat telah dikumpulkan di dalam suatu folder Google Drive publik/shareable.
* **Alur Utama:**
  1. Admin memasukkan **Google Drive Folder Link**, **Nama Folder**, dan **Kebutuhan Profil (Profile/Job Description yang Dicari)**.
  2. Admin menekan tombol "Analyze".
  3. Sistem memicu *n8n workflow service* dengan mengirimkan payload JSON berisi link drive, nama folder, dan kriteria lowongan.
  4. Sistem Laravel langsung mengalihkan admin ke halaman hasil analisis CV dengan memberikan pesan sukses bahwa pemicu (*trigger*) telah berjalan di latar belakang.
* **Postkondisi:** Pekerjaan analisis dikirimkan dan diproses oleh n8n secara asinkron.

#### UC-14: Menerima Hasil Analisis CV (Callback API)
* **Deskripsi:** Sistem menerima respon hasil ekstraksi dan evaluasi CV satu-persatu dari n8n melalui API endpoint yang aman.
* **Aktor:** Sistem n8n
* **Prekondisi:** Pekerjaan ekstraksi & analisis CV selesai dilakukan oleh AI di sisi n8n.
* **Alur Utama:**
  1. Sistem n8n mengirimkan request `POST` ke endpoint `/api/cv-result` aplikasi Laravel.
  2. Middleware `ValidateN8nToken` memvalidasi token otorisasi yang disertakan n8n.
  3. Sistem Laravel memvalidasi data masukan dari n8n: Nama, email, HP, domisili, pendidikan, riwayat kerja, keahlian, rangkuman CV, alasan pertimbangan AI, link file CV asli, dan skor kelayakan (1-10).
  4. Sistem menyimpan data hasil analisis ke tabel `cv_results`.
  5. Sistem juga menduplikasi informasi tersebut ke tabel `candidate_data` untuk mempermudah pencarian rekrutmen.
  6. Sistem Laravel merespon dengan status `200 Success` ke n8n.
* **Postkondisi:** Data hasil analisis AI tersimpan di database dan siap digunakan oleh HRD.

#### UC-15: Memantau & Memfilter Hasil Analisis CV AI
* **Deskripsi:** Admin meninjau skor kelayakan AI, kesimpulan keahlian, dan resume kandidat hasil pemindaian otomatis.
* **Aktor:** Admin
* **Prekondisi:** Admin login dan membuka halaman "Hasil Analisis CV" (`/admin/cv-results`).
* **Alur Utama:**
  1. Admin melihat tabel hasil analisis yang diurutkan berdasarkan skor tertinggi secara default.
  2. Admin memfilter hasil berdasarkan:
     - **Pekerjaan/Posisi** (Dropdown daftar jabatan hasil deteksi).
     - **Kota Tinggal / Domisili** (Dropdown daftar kota hasil deteksi).
     - **Status Rekomendasi AI** (Recommended: $\ge 8$, Consider: $5 - 7.9$, Not Recommended: $< 5$).
  3. Admin melakukan pencarian kata kunci yang dicocokkan dengan nama, email, keahlian, pendidikan, dan posisi kandidat.
  4. Admin mengurutkan data berdasarkan skor (tertinggi/terendah) atau berdasarkan hasil analisis yang diproses hari ini (*processed today*).
  5. Admin mengklik baris hasil untuk membaca detail evaluasi AI: Rangkuman CV, Keahlian yang Cocok, Riwayat Kerja, Alasan/Pertimbangan AI (*Consideration*), dan mengunduh/membuka file CV asli melalui tombol tautan yang disediakan.
* **Postkondisi:** Admin mendapatkan kandidat terbaik hasil kurasi AI dengan cepat.
