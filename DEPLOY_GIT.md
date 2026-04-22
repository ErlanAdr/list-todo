# Panduan Deploy Menggunakan Git

Panduan ini akan menjelaskan langkah-langkah detail untuk melakukan deployment aplikasi Task Manager ini ke VPS Ubuntu menggunakan Git. Pendekatan ini jauh lebih rapi dan mudah untuk proses update kedepannya dibandingkan mengupload file secara manual (FTP/SCP).

---

## Tahap 0: Persiapan Git di Windows (Bagi Pemula)

Jika Anda belum pernah menggunakan Git di Windows, ikuti langkah ini terlebih dahulu:

1. **Download dan Install Git:**
   - Kunjungi situs resmi: [https://git-scm.com/download/win](https://git-scm.com/download/win)
   - Download versi "64-bit Git for Windows Setup".
   - Install aplikasinya (tekan Next terus menggunakan pengaturan default).
2. **Buka Git Bash:**
   - Setelah terinstall, sangat disarankan tidak menggunakan Command Prompt (CMD) biasa. Buka menu Start Windows Anda, cari dan buka aplikasi bernama **Git Bash**.
   - Git Bash adalah terminal khusus yang membuat perintah Git berjalan lebih lancar di Windows.
3. **Konfigurasi Identitas (Hanya perlu dilakukan 1 kali selamanya):**
   - Di layar hitam Git Bash, ketik perintah berikut (tekan Enter setiap baris). Ganti dengan nama dan akun GitHub Anda:
     ```bash
     git config --global user.name "Nama Anda"
     git config --global user.email "email@anda.com"
     ```
4. **Masuk ke Folder Project Anda:**
   - Di Git Bash, Anda harus masuk ke lokasi folder `project_todo`. Karena letaknya di Drive D, ketikkan perintah ini:
     ```bash
     cd /d/project_todo
     ```
   - *(Catatan: Di Git Bash, penulisan drive `d:\` menggunakan format `/d/`)*

Setelah menyelesaikan tahap ini, tetap gunakan jendela **Git Bash** tersebut untuk melanjutkan ke **Tahap 1** di bawah.

---

## Tahap 1: Persiapan di Komputer Lokal

Pertama, pastikan project Anda sudah menggunakan Git dan di-push ke remote repository (seperti GitHub, GitLab, atau Bitbucket).

1. **Inisialisasi Git di folder project:**
   ```bash
   cd d:/project_todo
   git init
   ```

2. **Buat file `.gitignore`:**
   Sangat penting agar file konfigurasi database lokal tidak ikut ter-upload. Buat file bernama `.gitignore` di root folder dan isikan:
   ```text
   config/database.php
   .vscode/
   ```
   *(Catatan: Karena `database.php` di-ignore, pastikan Anda membuat `config/database.example.php` berisi template kosong untuk panduan di server, lalu commit file example tersebut).*

3. **Commit dan Push ke Remote Repository:**
   ```bash
   git add .
   git commit -m "Initial commit Task Manager"
   git branch -M main
   git remote add origin https://github.com/username/repo-anda.git
   git push -u origin main
   ```

---

## Tahap 2: Persiapan di VPS Ubuntu

Pastikan VPS Anda sudah terinstall Apache, PHP, dan MySQL (lihat panduan dasar di `README.md`).

1. **Login ke VPS Anda via SSH:**
   ```bash
   ssh root@ip_vps_anda
   ```

2. **Install Git (Jika belum ada):**
   ```bash
   sudo apt update
   sudo apt install git -y
   ```

3. **Buat SSH Key di VPS untuk Akses GitHub/GitLab (Opsional tapi disarankan):**
   Agar Anda bisa melakukan `git pull` tanpa harus memasukkan username/password setiap saat (terutama jika repository Anda private).
   ```bash
   ssh-keygen -t ed25519 -C "vps-deploy-key"
   ```
   - Tekan `Enter` terus sampai selesai.
   - Tampilkan public key Anda:
     ```bash
     cat ~/.ssh/id_ed25519.pub
     ```
   - Copy teks yang muncul dan paste ke pengaturan SSH Keys di GitHub/GitLab Anda (Settings -> SSH and GPG keys -> New SSH key).

---

## Tahap 3: Cloning dan Setup di VPS

1. **Masuk ke folder web root Apache:**
   ```bash
   cd /var/www/
   ```

2. **Clone repository Anda:**
   Jika menggunakan SSH:
   ```bash
   sudo git clone git@github.com:username/repo-anda.git taskmanager
   ```
   Jika menggunakan HTTPS (dan repository public):
   ```bash
   sudo git clone https://github.com/username/repo-anda.git taskmanager
   ```

3. **Setup Database:**
   Sama seperti di `README.md`, masuk ke MySQL, buat database, user, dan import `database.sql`:
   ```bash
   mysql -u root -p < /var/www/taskmanager/database.sql
   ```

4. **Buat file konfigurasi Database Production:**
   Karena file `config/database.php` di-ignore di Git, Anda harus membuatnya secara manual di server.
   ```bash
   cd /var/www/taskmanager/config
   sudo nano database.php
   ```
   Lalu masukkan script koneksi PDO seperti di lokal, tapi dengan kredensial database server Anda (username, password, dan db_name production). Simpan (Ctrl+X, Y, Enter).

5. **Atur Permission Folder:**
   Pastikan Apache memiliki hak akses untuk membaca dan mengeksekusi file Anda.
   ```bash
   sudo chown -R www-data:www-data /var/www/taskmanager
   sudo chmod -R 755 /var/www/taskmanager
   ```

6. **Konfigurasi Virtual Host Apache:**
   Arahkan DocumentRoot Virtual Host Apache Anda ke `/var/www/taskmanager/public` (Detail langkah ini ada di `README.md` bagian 7).

---

## Tahap 4: Cara Update / Deploy Perubahan Baru

Inilah kelebihan menggunakan Git. Saat Anda melakukan perubahan kode di komputer lokal, cara mengupdatenya ke server sangatlah mudah.

### Di Komputer Lokal:
1. Lakukan perubahan kode.
2. Commit dan push ke repository:
   ```bash
   git add .
   git commit -m "Menambahkan fitur baru"
   git push origin main
   ```

### Di VPS Server:
1. Login SSH ke server.
2. Masuk ke folder project:
   ```bash
   cd /var/www/taskmanager
   ```
3. Tarik (pull) perubahan terbaru:
   ```bash
   sudo git pull origin main
   ```
4. Jika ada file baru yang terbuat (seperti gambar yang diupload user atau log) pastikan permissionnya tetap aman dengan menjalankan kembali:
   ```bash
   sudo chown -R www-data:www-data /var/www/taskmanager
   ```

*(Kode Anda sekarang sudah terupdate di server dalam hitungan detik!)*

---

## Tahap 5 (Opsional & Lanjutan): Otomatisasi (CI/CD)

Agar Anda tidak perlu login SSH dan melakukan `git pull` secara manual setiap kali ada perubahan, Anda bisa menggunakan **Git Hooks (Post-Receive Hook)** atau **GitHub Actions / GitLab CI**.

### Konsep Dasar Menggunakan GitHub Webhook:
1. Anda membuat sebuah script PHP (misal `deploy.php`) di server Anda.
2. Script tersebut berisi command `shell_exec('git pull origin main');`
3. Anda mendaftarkan URL `https://domain-anda.com/deploy.php` ke menu **Webhooks** di repository GitHub Anda.
4. Setiap kali Anda melakukan `git push` dari lokal, GitHub akan secara otomatis menembak URL tersebut, dan server Anda akan langsung mengeksekusi `git pull`.

*Perlu diperhatikan: Jika menggunakan webhook, pastikan file `deploy.php` diamankan dengan secret key agar tidak sembarang orang bisa men-trigger proses deploy.*
