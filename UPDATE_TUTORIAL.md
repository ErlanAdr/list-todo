# Tutorial Rutin: Cara Update Kode dari Komputer Lokal ke VPS

File ini adalah panduan cepat (Cheat Sheet) yang bisa Anda gunakan setiap kali Anda selesai mengubah kode di komputer lokal dan ingin mengupdatenya ke server VPS.

Proses ini melibatkan dua tahap: **Push (mendorong)** kode dari laptop Anda ke GitHub/GitLab, lalu **Pull (menarik)** kode dari GitHub/GitLab ke server VPS.

---

## TAHAP 1: Di Komputer Lokal Anda (Laptop/PC)

Lakukan ini setelah Anda selesai mengedit file dan memastikan aplikasinya berjalan dengan baik di lokal.

1. **Buka Git Bash**
   - Buka aplikasi **Git Bash** dan pastikan Anda sudah berada di dalam folder project:
     ```bash
     cd /d/project_todo
     ```

2. **Cek File Apa Saja yang Berubah (Opsional tapi disarankan)**
   - Untuk melihat daftar file yang baru saja Anda edit, tambahkan, atau hapus:
     ```bash
     git status
     ```
   - *File yang berwarna merah berarti ada perubahan namun belum disiapkan untuk di-upload.*

3. **Tambahkan Semua Perubahan**
   - Ketik perintah ini untuk menyiapkan semua file yang berubah agar siap dikirim:
     ```bash
     git add .
     ```
   - *(Tanda titik `.` berarti "semua file dan folder yang berubah di direktori ini")*

4. **Beri "Label" (Commit) pada Perubahan Tersebut**
   - Anda wajib memberikan catatan/pesan tentang apa yang baru saja Anda ubah. Ini sangat berguna sebagai riwayat.
     ```bash
     git commit -m "Memperbaiki tampilan tombol di halaman form"
     ```

5. **Kirim (Push) Kode ke Remote Repository (GitHub/GitLab)**
   - Langkah terakhir di komputer Anda, kirimkan kodenya:
     ```bash
     git push origin main
     ```
   - *(Tunggu sampai proses upload mencapai 100% dan berhasil)*

> **Selesai di lokal!** Kode Anda sekarang sudah tersimpan aman di GitHub/GitLab.

---

## TAHAP 2: Di Server VPS Anda

Setelah kode dikirim dari laptop, sekarang Anda harus menyuruh server untuk "mengambil" kode terbaru tersebut.

1. **Login ke Server VPS Anda**
   - Buka terminal / command prompt / Git Bash, lalu login SSH:
     ```bash
     ssh root@ip_vps_anda
     ```

2. **Masuk ke Folder Web Anda**
   - Pindah ke lokasi di mana aplikasi Anda di-host (sesuaikan dengan nama folder Anda, misal `/var/www/taskmanager`):
     ```bash
     cd /var/www/taskmanager
     ```

3. **Ambil (Pull) Kode Terbaru**
   - Tarik perubahan terbaru yang tadi Anda push dari laptop:
     ```bash
     sudo git pull origin main
     ```
   - *Anda akan melihat daftar file apa saja yang di-update di layar.*

4. **Amankan Hak Akses Folder (Sangat Penting)**
   - Karena Anda baru saja men-download file baru menggunakan akun `root` atau akun user lain, pastikan web server (Apache) tetap memiliki akses untuk membaca file-file tersebut. Selalu jalankan ini setelah melakukan `git pull`:
     ```bash
     sudo chown -R www-data:www-data /var/www/taskmanager
     ```

> **Selesai di VPS!** Silakan *refresh* website Anda di browser, dan perubahan terbaru sudah langsung aktif!

---

## Ringkasan Singkat (Copy-Paste Ready)

**Di Lokal (Git Bash):**
```bash
git add .
git commit -m "Update fitur X"
git push origin main
```

**Di VPS (SSH):**
```bash
cd /var/www/taskmanager
sudo git pull origin main
sudo chown -R www-data:www-data /var/www/taskmanager
```
