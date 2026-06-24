<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CONTOH CV ATS SIAP KERJA</title>
    <style>
        @page {
            margin: 1.5cm;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.5;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        h1, h2, h3, h4 {
            color: #111111;
            margin: 0 0 5px 0;
        }
        h1 {
            font-size: 20pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            font-size: 9.5pt;
            color: #555555;
            margin-bottom: 20px;
        }
        .subtitle a {
            color: #333333;
            text-decoration: none;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #111111;
            padding-bottom: 3px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        .entry {
            margin-bottom: 15px;
        }
        .entry-header {
            margin-bottom: 4px;
        }
        .entry-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .entry-table td {
            padding: 0;
            vertical-align: top;
        }
        .entry-title {
            font-weight: bold;
            font-size: 11pt;
            color: #111111;
        }
        .entry-right {
            text-align: right;
            font-size: 10pt;
            color: #555555;
        }
        .entry-subtitle {
            font-style: italic;
            color: #444444;
            font-size: 10pt;
            margin-bottom: 5px;
        }
        ul {
            margin: 0 0 5px 0;
            padding-left: 20px;
        }
        li {
            margin-bottom: 4px;
            font-size: 10pt;
        }
        .skills-list {
            font-size: 10pt;
        }
        .skills-category {
            margin-bottom: 6px;
        }
        .skills-category strong {
            color: #111111;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div>
        <h1>test Santoso</h1>
        <div class="subtitle">
            Jakarta, Indonesia | +62 812-3456-7890 | test.santoso@email.com | linkedin.com/in/testsantoso | github.com/testsantoso
        </div>
    </div>

    <!-- Summary Section -->
    <div class="section">
        <div class="section-title">Ringkasan Profesional</div>
        <p style="margin: 0; font-size: 10pt;">
            Software Engineer yang berdedikasi dengan pengalaman lebih dari 3 tahun dalam mengembangkan aplikasi web berkinerja tinggi menggunakan PHP (Laravel), JavaScript (Vue.js/React), dan basis data SQL. Terbukti sukses memimpin tim dalam migrasi arsitektur monolitik ke microservices yang meningkatkan kecepatan pemrosesan data sebesar 40%. Memiliki pemahaman kuat mengenai siklus hidup pengembangan perangkat lunak (SDLC) serta metodologi Agile/Scrum.
        </p>
    </div>

    <!-- Experience Section -->
    <div class="section">
        <div class="section-title">Pengalaman Kerja</div>
        
        <div class="entry">
            <table class="entry-table">
                <tr>
                    <td class="entry-title">PT Teknologi Utama Indonesia</td>
                    <td class="entry-right">Jakarta, Indonesia</td>
                </tr>
                <tr>
                    <td class="entry-subtitle">Software Engineer</td>
                    <td class="entry-right">Januari 2024 – Sekarang</td>
                </tr>
            </table>
            <ul>
                <li>Memimpin tim pengembangan fitur baru menggunakan framework Laravel dan Vue.js, berhasil meningkatkan retensi pengguna aktif harian (DAU) sebesar 15%.</li>
                <li>Mengoptimalkan query database PostgreSQL dan implementasi Redis caching, yang mengurangi waktu respon API sebesar 30%.</li>
                <li>Berkolaborasi dengan Product Manager untuk merancang sistem pembayaran otomatis terintegrasi dengan Midtrans Payment Gateway.</li>
                <li>Menulis unit test dan integration test menggunakan PHPUnit untuk memastikan coverage kode berada di atas 85%.</li>
            </ul>
        </div>

        <div class="entry">
            <table class="entry-table">
                <tr>
                    <td class="entry-title">Cipta Solusi Nusantara</td>
                    <td class="entry-right">Bandung, Indonesia</td>
                </tr>
                <tr>
                    <td class="entry-subtitle">Junior Web Developer</td>
                    <td class="entry-right">Juli 2022 – Desember 2023</td>
                </tr>
            </table>
            <ul>
                <li>Mengembangkan modul pelaporan internal perusahaan menggunakan React dan Node.js, mempercepat pembuatan laporan bulanan departemen keuangan.</li>
                <li>Membantu proses pemeliharaan dan debugging bug pada website e-commerce yang sudah berjalan, mengurangi persentase bug kritis sebesar 25%.</li>
                <li>Menerapkan prinsip responsive design pada interface website utama menggunakan CSS murni dan Bootstrap untuk meningkatkan kompatibilitas mobile.</li>
            </ul>
        </div>
    </div>

    <!-- Education Section -->
    <div class="section">
        <div class="section-title">Pendidikan</div>
        <div class="entry">
            <table class="entry-table">
                <tr>
                    <td class="entry-title">Universitas Indonesia</td>
                    <td class="entry-right">Depok, Indonesia</td>
                </tr>
                <tr>
                    <td class="entry-subtitle">Sarjana Komputer (S.Kom.) dalam Ilmu Komputer (IPK: 3.75 / 4.00)</td>
                    <td class="entry-right">Agustus 2018 – Juni 2022</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="section">
        <div class="section-title">Proyek Mandiri</div>
        
        <div class="entry">
            <table class="entry-table">
                <tr>
                    <td class="entry-title">Aplikasi Pemantau Kinerja Karyawan (HR Portal)</td>
                    <td class="entry-right">Laravel, Vue.js, Tailwind CSS</td>
                </tr>
            </table>
            <ul>
                <li>Membangun platform internal untuk HRD memantau KPI karyawan dengan otentikasi role-based access control (RBAC).</li>
                <li>Mengintegrasikan fitur visualisasi data statistik kinerja menggunakan Chart.js untuk memberikan insight cepat kepada jajaran manajerial.</li>
            </ul>
        </div>
    </div>

    <!-- Skills Section -->
    <div class="section">
        <div class="section-title">Keahlian & Sertifikasi</div>
        <div class="skills-list">
            <div class="skills-category">
                <strong>Bahasa Pemrograman & Framework:</strong> PHP (Laravel), JavaScript (Vue.js, React, Node.js), HTML5, CSS3, SQL.
            </div>
            <div class="skills-category">
                <strong>Basis Data & Alat:</strong> MySQL, PostgreSQL, Redis, Git, GitHub, Docker, Postman.
            </div>
            <div class="skills-category">
                <strong>Metodologi & Bahasa:</strong> Agile/Scrum, SDLC, RESTful API, Bahasa Indonesia (Native), Bahasa Inggris (Professional).
            </div>
            <div class="skills-category">
                <strong>Sertifikasi:</strong> Dicoding Back-End Developer Expert (2023), AWS Certified Cloud Practitioner (2024).
            </div>
        </div>
    </div>

</body>
</html>
