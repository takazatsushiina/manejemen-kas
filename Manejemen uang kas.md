# **📄 SISTEM MANAJEMEN UANG KAS**

## **Dokumentasi Analisis Kebutuhan Perangkat Lunak (Software Requirements Engineering)**

## **1\. Pendahuluan**

### **1.1 Latar Belakang**

Sistem Manajemen Uang Kas diperlukan untuk mengelola seluruh transaksi masuk dan keluar secara lebih akurat, cepat, dan terdokumentasi. Sistem ini bertujuan membantu organisasi dalam memantau saldo kas secara *real-time*, menghasilkan laporan keuangan yang andal, memfasilitasi rekonsiliasi, dan meminimalisir kesalahan pencatatan yang sering terjadi pada proses manual.

### **1.2 Tujuan Dokumen**

Dokumen ini dibuat untuk mendefinisikan kebutuhan perangkat lunak secara lengkap dan terstruktur, yang mencakup:

1. Kebutuhan Fungsional (Functional Requirements)  
2. Kebutuhan Non-Fungsional (Non-Functional Requirements)  
3. Identifikasi Aktor dan Use Case Utama  
4. Desain Dasar Basis Data (Skema Tabel)

### **1.3 Lingkup Sistem**

Sistem Manajemen Uang Kas mencakup modul-modul utama berikut:

* Pencatatan kas masuk  
* Pencatatan kas keluar  
* Pengelolaan kas kecil (*petty cash*)  
* Validasi dan persetujuan bukti transaksi  
* Rekonsiliasi kas–bank  
* Penyediaan laporan arus kas dan saldo

## **2\. Definisi, Akronim, dan Singkatan**

| Istilah | Definisi |
| :---- | :---- |
| **Kas Masuk** | Uang atau dana yang diterima oleh organisasi. |
| **Kas Keluar** | Uang atau dana yang dikeluarkan (pembayaran) oleh organisasi. |
| **Rekonsiliasi** | Proses pencocokan catatan saldo kas dalam sistem dengan saldo di rekening bank. |
| **Bukti Transaksi** | Dokumen (fisik atau digital) yang memverifikasi keabsahan transaksi kas. |
| **User** | Pengguna sistem dengan peran spesifik (Kasir, Akuntan, Manajer). |

## **3\. Aktor Sistem**

| Aktor | Peran Utama dalam Sistem |
| :---- | :---- |
| **Kasir** | Menginput semua transaksi kas harian dan mengelola kas kecil. |
| **Akuntan** | Melakukan validasi, mencatat, mengedit, dan merekonsiliasi transaksi. |
| **Manajer Keuangan** | Menyetujui transaksi kas besar, rekonsiliasi, dan memantau seluruh laporan keuangan. |
| **Sistem** | Melakukan pemrosesan data, perhitungan saldo, penyimpanan, dan penyediaan laporan. |

## **4\. Kebutuhan Fungsional**

### **4.1 Modul Kasir**

| ID | Kebutuhan Fungsional |
| :---- | :---- |
| F1.1 | Dapat membuat dan menyimpan data transaksi kas masuk. |
| F1.2 | Dapat membuat dan menyimpan data transaksi kas keluar. |
| F1.3 | Dapat mengelola dan mencatat pengeluaran kas kecil (petty cash). |
| F1.4 | Dapat mengunggah bukti transaksi (format: JPG, PNG, PDF). |
| F1.5 | Dapat mengirim laporan ringkasan kas harian kepada Akuntan. |

### **4.2 Modul Akuntan**

| ID | Kebutuhan Fungsional |
| :---- | :---- |
| F2.1 | Dapat memvalidasi transaksi yang diinput Kasir (**Approve/Reject**). |
| F2.2 | Dapat melakukan proses rekonsiliasi antara saldo kas sistem dengan saldo bank. |
| F2.3 | Dapat mengedit dan menghapus transaksi yang berstatus **Pending** atau **Rejected**. |
| F2.4 | Dapat membuat dan mengekspor laporan arus kas untuk periode tertentu (PDF/Excel). |

### **4.3 Modul Manajer Keuangan**

| ID | Kebutuhan Fungsional |
| :---- | :---- |
| F3.1 | Dapat menyetujui transaksi kas keluar dengan nominal besar (telah diatur ambangnya). |
| F3.2 | Dapat melihat semua laporan kas, laporan transaksi detail, dan saldo kas *real-time*. |
| F3.3 | Dapat menyetujui atau menolak hasil rekonsiliasi bank yang diajukan Akuntan. |

### **4.4 Modul Sistem**

| ID | Kebutuhan Fungsional |
| :---- | :---- |
| F4.1 | Sistem harus menghitung dan memperbarui saldo kas secara otomatis setelah setiap transaksi tervalidasi. |
| F4.2 | Sistem harus menyimpan seluruh data transaksi, bukti, dan laporan secara terpusat. |
| F4.3 | Sistem harus mencatat log aktivitas setiap *user* (misal: login, buat transaksi, validasi). |
| F4.4 | Sistem harus mampu menghasilkan dan mengekspor laporan dalam format PDF atau Excel. |

## **5\. Kebutuhan Non-Fungsional**

| Kebutuhan | Penjelasan |
| :---- | :---- |
| **Keamanan (Security)** | Sistem harus menerapkan autentikasi multi-level (berdasarkan peran) dan mengenkripsi data sensitif (misal: password dan data transaksi). |
| **Performa (Performance)** | Akses dan pengambilan data transaksi (laporan) harus kurang dari 2 detik. |
| **Reliability (Keandalan)** | Sistem harus dapat beroperasi tanpa gangguan (uptime) 24/7, kecuali saat *maintenance* terencana. |
| **Usability (Kegunaan)** | Antarmuka pengguna (UI) harus intuitif, sederhana, dan mudah digunakan oleh seluruh peran tanpa pelatihan ekstensif. |
| **Scalability (Skalabilitas)** | Basis data harus mampu menampung pertumbuhan data hingga lebih dari 1 juta transaksi per tahun tanpa penurunan performa. |
| **Backup & Recovery** | Sistem harus melakukan pencadangan data otomatis harian dan menyediakan mekanisme pemulihan data yang cepat. |

## **6\. Use Case Diagram**

**Use Case Utama:**

1. Mengelola Kas Masuk  
2. Mengelola Kas Keluar  
3. Validasi Transaksi  
4. Rekonsiliasi Kas-Bank  
5. Pengelolaan Saldo Kas Kecil  
6. Pembuatan Laporan (Harian/Periode)

## **7\. Desain Dasar Basis Data**

### **7.1 Struktur Tabel**

#### **1\. users**

| Field | Tipe | Keterangan |
| :---- | :---- | :---- |
| user\_id | INT (PK) | ID pengguna (Primary Key) |
| username | VARCHAR | Nama pengguna |
| email | VARCHAR | Email (digunakan untuk login) |
| password | VARCHAR | Hash password untuk autentikasi |
| role | ENUM('kasir', 'akuntan', 'manajer') | Level hak akses |

#### **2\. cash\_transactions**

| Field | Tipe | Keterangan |
| :---- | :---- | :---- |
| transaction\_id | INT (PK) | ID transaksi |
| user\_id | INT (FK) | User yang membuat transaksi |
| type | ENUM('masuk', 'keluar') | Jenis transaksi (Debit/Kredit) |
| amount | DECIMAL(15,2) | Nominal uang |
| description | TEXT | Catatan transaksi |
| date | DATE | Tanggal transaksistatus |
| status | ENUM('pending', 'approved', 'rejected') | Status validasi |
| approval\_by | INT (FK) | Akuntan/Manajer yang menyetujui |

#### **3\. petty\_cash**

| Field | Tipe | Keterangan |
| :---- | :---- | :---- |
| petty\_id | INT (PK) | ID Kas Kecil |
| current\_balance | DECIMAL(15,2) | Saldo kas kecil saat ini |
| last\_updated | DATETIME | Waktu update terakhir |

#### **4\. receipts**

| Field | Tipe | Keterangan |
| :---- | :---- | :---- |
| receipt\_id | INT (PK) | ID bukti |
| transaction\_id | INT (FK) | Transaksi terkait |
| file\_path | VARCHAR | Lokasi penyimpanan file bukti (di cloud/server) |
| uploaded\_at | DATETIME | Waktu upload |

#### **5\. reconciliation**

| Field | Tipe | Keterangan |
| :---- | :---- | :---- |
| recon\_id | INT (PK) | ID rekonsiliasi |
| user\_id | INT (FK) | Akuntan yang membuat |
| bank\_balance | DECIMAL(15,2) | Saldo bank |
| cash\_book\_balance | DECIMAL(15,2) | Saldo buku kas sistem |
| difference | DECIMAL(15,2) | Selisih (bank\_balance \- cash\_book\_balance) |
| recon\_date | DATE | Tanggal rekonsiliasi |
| approval\_by | INT (FK) | Manajer yang menyetujui |

#### **6\. cash\_reports**

| Field | Tipe | Keterangan |
| :---- | :---- | :---- |
| report\_id | INT (PK) | ID laporan |
| user\_id | INT (FK) | Pembuat laporan |
| type | ENUM('harian', 'mingguan', 'bulanan') | Jenis laporan |
| file\_path | VARCHAR | Lokasi file PDF/Excel |
| created\_at | DATETIME | Waktu dibuat |

## **8\. Penjelasan Fungsi Setiap Tabel**

1. **users**: Digunakan untuk menyimpan data pengguna sistem (Kasir, Akuntan, Manajer), esensial untuk autentikasi dan otorisasi hak akses.  
2. **cash\_transactions**: Merupakan tabel inti yang mencatat setiap detail transaksi kas masuk dan keluar, serta status validasinya.  
3. **petty\_cash**: Berfungsi untuk melacak saldo kas kecil yang dikelola oleh Kasir.  
4. **receipts**: Menyimpan metadata file bukti transaksi, yang terhubung langsung ke catatan cash\_transactions.  
5. **reconciliation**: Menyimpan hasil pencocokan (rekonsiliasi) antara saldo kas sistem dan bank, serta persetujuan dari Manajer.  
6. **cash\_reports**: Menyimpan histori pembuatan laporan kas yang telah diekspor dalam format dokumen.

## **9\. Kebutuhan Antarmuka (UI/UX)**

Sistem harus menyediakan antarmuka yang bersih dan fungsional, meliputi:

* **Dashboard**: Menampilkan ringkasan saldo kas, total transaksi, dan grafik visualisasi arus kas.  
* **Menu Input**: Formulir terpisah untuk input Kas Masuk dan Kas Keluar dengan kolom wajib (nominal, deskripsi, tanggal, upload bukti).  
* **Menu Validasi**: Tampilan daftar transaksi yang menunggu persetujuan (untuk Akuntan/Manajer) dengan tombol **Approve/Reject**.  
* **Menu Rekonsiliasi**: Tampilan *interface* untuk memasukkan saldo bank dan membandingkannya dengan saldo buku kas.  
* **Laporan Kas**: Fitur filter tanggal dan ekspor ke format PDF/Excel.  
* **Manajemen Pengguna**: Tampilan untuk Akuntan/Manajer untuk menambah, mengedit, atau menonaktifkan akun *user*.

## **10\. Kebutuhan Hardware & Software**

| Perangkat | Spesifikasi |
| :---- | :---- |
| **Server** | RAM 4GB, CPU 2-core, OS Linux, Web Server NGINX, PHP 8, MySQL |
| **Client** | Perangkat dengan *browser* modern yang mendukung HTML5/CSS3/JavaScript (Chrome, Edge, Firefox). |
| **Software Stack** | Framework Backend: Laravel; Database: MySQL; Frontend: Bootstrap (atau Vue.js/React untuk interaksi yang lebih dinamis). |

## **11\. Kesimpulan**

Dokumen ini telah merangkum keseluruhan kebutuhan sistem secara mendetail untuk digunakan sebagai panduan utama dalam proses analisis, desain, dan pengembangan Sistem Manajemen Uang Kas. Pemenuhan kebutuhan fungsional dan non-fungsional yang didefinisikan akan memastikan sistem yang dihasilkan berjalan efektif, aman, dan sesuai dengan tujuan organisasi.