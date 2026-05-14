#!/usr/bin/env bash
# Hentikan proses jika ada perintah yang gagal
set -e

echo "1. Menginstal dependensi PHP..."
composer install --no-dev --optimize-autoloader

echo "2. Menyiapkan cache untuk performa maksimal..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "3. Membuat jembatan folder gambar (storage link)..."
php artisan storage:link

echo "4. Menjalankan migrasi database ke Supabase..."
php artisan migrate --force

echo "Selesai! Aplikasi Rempah siap dihidangkan."