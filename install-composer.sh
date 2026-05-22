#!/bin/bash

# Script instalasi Composer untuk Sistem Presensi Kelas
# Jalankan script ini setelah memastikan PHP dan Composer terinstall di server Anda

echo "=================================================="
echo "Composer Installer - Sistem Presensi Kelas"
echo "=================================================="
echo ""

# Cek apakah composer sudah terinstall
if ! command -v composer &> /dev/null; then
    echo "⚠️  Composer belum terinstall!"
    echo ""
    echo "Silakan install Composer terlebih dahulu:"
    echo ""
    echo "Cara 1 (Linux/Ubuntu):"
    echo "  curl -sS https://getcomposer.org/installer | php"
    echo "  sudo mv composer.phar /usr/local/bin/composer"
    echo ""
    echo "Cara 2 (Download langsung):"
    echo "  wget https://getcomposer.org/composer.phar"
    echo "  chmod +x composer.phar"
    echo "  sudo mv composer.phar /usr/local/bin/composer"
    echo ""
    echo "Setelah Composer terinstall, jalankan kembali script ini."
    exit 1
fi

echo "✅ Composer ditemukan: $(composer --version)"
echo ""

# Cek apakah PHP sudah terinstall
if ! command -v php &> /dev/null; then
    echo "⚠️  PHP belum terinstall atau tidak ada di PATH!"
    echo "Pastikan PHP 7.4 atau lebih baru sudah terinstall."
    exit 1
fi

echo "✅ PHP ditemukan: $(php -v | head -n 1)"
echo ""

# Backup composer.json lama jika ada
if [ -f "composer.json.bak" ]; then
    echo "📁 File backup composer.json.bak sudah ada."
else
    echo "ℹ️  File composer.json siap untuk instalasi."
fi

echo ""
echo "=================================================="
echo "Menginstall dependencies..."
echo "=================================================="
echo ""

# Jalankan composer install
composer install --no-interaction --optimize-autoloader

if [ $? -eq 0 ]; then
    echo ""
    echo "=================================================="
    echo "✅ Instalasi berhasil!"
    echo "=================================================="
    echo ""
    echo "Library yang terinstall:"
    echo "  - phpoffice/phpspreadsheet (untuk export Excel)"
    echo "  - dompdf/dompdf (untuk export PDF)"
    echo "  - tecnickcom/tcpdf (alternatif PDF)"
    echo ""
    echo "Selanjutnya:"
    echo "  1. Pastikan folder vendor dapat diakses oleh web server"
    echo "  2. Konfigurasi aplikasi jika diperlukan"
    echo "  3. Akses aplikasi melalui browser"
    echo ""
else
    echo ""
    echo "=================================================="
    echo "❌ Instalasi gagal!"
    echo "=================================================="
    echo ""
    echo "Periksa:"
    echo "  - Koneksi internet"
    echo "  - Versi PHP (minimal 7.4)"
    echo "  - Extension PHP yang diperlukan (zip, xml, mbstring, gd)"
    echo ""
    exit 1
fi
