# Pflegefachkraft App

Almanya'da Pflegefachkraft Ausbildung öğrencileri için PHP 8 tabanlı quiz ve ilerleme takip uygulamasının temel iskeleti.

## Gereksinimler
- PHP 8+
- MySQL/MariaDB
- Apache veya Nginx (public/ klasörü web root olarak tanımlanmalı)

## Kurulum
1. Projeyi `htdocs/pflegefachkraft_app` gibi bir dizine kopyalayın.
2. MySQL'de `pflegefachkraft` isimli veritabanını oluşturun ve `database/schema.sql` içindeki komutları çalıştırın.
3. `config/config.php` içinde veritabanı bilgilerinizi ve `base_url` değerini güncelleyin.
4. İlk admin'i oluşturmak için `users` tablosuna role=`admin` olacak bir kayıt ekleyebilir veya admin panelinden kullanıcı oluşturup rolünü `admin` yapabilirsiniz.

## Geliştirme Notları
- Yeni modül eklemek için `app/Controllers`, `app/Models` ve `app/Views` klasörlerinde ilgili dosyaları oluşturup `public/index.php` içindeki router'a rota ekleyin.
- View'lerde kullanıcı girdilerini yazdırırken `e()` fonksiyonunu kullanarak XSS'ten kaçının.
- Tüm POST formları CSRF token içerir; yeni form eklerken `Session::csrfToken()` çıktısını gizli input olarak göndermeyi unutmayın.
