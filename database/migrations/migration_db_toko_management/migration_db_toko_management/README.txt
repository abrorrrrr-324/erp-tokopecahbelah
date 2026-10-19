MIGRATION DB TOKO MANAGEMENT

Isi: 12 file migration Laravel untuk PostgreSQL.

Pemasangan:
1. Cadangkan database terlebih dahulu.
2. Salin semua file .php ke folder database/migrations proyek Laravel.
3. PENTING: Laravel biasanya sudah memiliki migration create_users_table.php.
   Jangan biarkan dua migration membuat tabel users. Jika proyek sudah punya
   migration users, gabungkan kolom nama, peran, dan status ke migration yang
   sudah ada atau ganti migration users lama jika belum pernah dipakai.
4. Pastikan .env menggunakan koneksi PostgreSQL dan nama database yang benar.
5. Jalankan: php artisan migrate
6. Periksa hasilnya di pgAdmin.

Catatan:
- Nama tabel dan kolom mengikuti skema ERD yang sebelumnya dibuat.
- Foreign key disusun sesuai urutan timestamp.
- Migration ini membuat struktur tabel, bukan data awal/seeder.
- Jika tabel lama sudah ada, jangan langsung menjalankan migrate; periksa status
  migration dan struktur database terlebih dahulu agar tidak bentrok.
