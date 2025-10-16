<?php

namespace Database\Seeders;

use App\Models\Word;
use Illuminate\Database\Seeder;

class WordSeeder extends Seeder
{
    public function run(): void
    {
        $words = [
            ['lemma' => 'Haus', 'gender' => 'n', 'plural' => 'Häuser', 'level' => 'A1', 'example_sentence' => 'Das Haus ist groß.', 'example_translation' => 'Ev büyük.', 'tags' => ['ev', 'yer']],
            ['lemma' => 'Hund', 'gender' => 'm', 'plural' => 'Hunde', 'level' => 'A1', 'example_sentence' => 'Der Hund bellt.', 'example_translation' => 'Köpek havlıyor.', 'tags' => ['hayvan']],
            ['lemma' => 'Zeit', 'gender' => 'f', 'plural' => 'Zeiten', 'level' => 'A2', 'example_sentence' => 'Die Zeit vergeht.', 'example_translation' => 'Zaman geçiyor.', 'tags' => ['soyut']],
            ['lemma' => 'Auto', 'gender' => 'n', 'plural' => 'Autos', 'level' => 'A1', 'example_sentence' => 'Das Auto fährt schnell.', 'example_translation' => 'Araba hızlı gidiyor.', 'tags' => ['taşıt']],
            ['lemma' => 'Blume', 'gender' => 'f', 'plural' => 'Blumen', 'level' => 'A1', 'example_sentence' => 'Die Blume duftet.', 'example_translation' => 'Çiçek kokuyor.', 'tags' => ['doğa']],
            ['lemma' => 'Tisch', 'gender' => 'm', 'plural' => 'Tische', 'level' => 'A1', 'example_sentence' => 'Der Tisch ist rund.', 'example_translation' => 'Masa yuvarlak.', 'tags' => ['ev']],
            ['lemma' => 'Fenster', 'gender' => 'n', 'plural' => 'Fenster', 'level' => 'A1', 'example_sentence' => 'Das Fenster ist offen.', 'example_translation' => 'Pencere açık.', 'tags' => ['ev']],
            ['lemma' => 'Stadt', 'gender' => 'f', 'plural' => 'Städte', 'level' => 'A2', 'example_sentence' => 'Die Stadt ist laut.', 'example_translation' => 'Şehir gürültülü.', 'tags' => ['yer']],
            ['lemma' => 'Baum', 'gender' => 'm', 'plural' => 'Bäume', 'level' => 'A1', 'example_sentence' => 'Der Baum ist alt.', 'example_translation' => 'Ağaç yaşlı.', 'tags' => ['doğa']],
            ['lemma' => 'Kind', 'gender' => 'n', 'plural' => 'Kinder', 'level' => 'A1', 'example_sentence' => 'Das Kind spielt.', 'example_translation' => 'Çocuk oynuyor.', 'tags' => ['insan']],
            ['lemma' => 'Tür', 'gender' => 'f', 'plural' => 'Türen', 'level' => 'A1', 'example_sentence' => 'Die Tür ist geschlossen.', 'example_translation' => 'Kapı kapalı.', 'tags' => ['ev']],
            ['lemma' => 'Lehrer', 'gender' => 'm', 'plural' => 'Lehrer', 'level' => 'A1', 'example_sentence' => 'Der Lehrer erklärt alles.', 'example_translation' => 'Öğretmen her şeyi açıklıyor.', 'tags' => ['meslek']],
            ['lemma' => 'Lehrerin', 'gender' => 'f', 'plural' => 'Lehrerinnen', 'level' => 'A1', 'example_sentence' => 'Die Lehrerin hilft.', 'example_translation' => 'Öğretmen yardım ediyor.', 'tags' => ['meslek']],
            ['lemma' => 'Buch', 'gender' => 'n', 'plural' => 'Bücher', 'level' => 'A1', 'example_sentence' => 'Das Buch ist spannend.', 'example_translation' => 'Kitap heyecanlı.', 'tags' => ['nesne']],
            ['lemma' => 'Freund', 'gender' => 'm', 'plural' => 'Freunde', 'level' => 'A2', 'example_sentence' => 'Der Freund besucht mich.', 'example_translation' => 'Arkadaşım beni ziyaret ediyor.', 'tags' => ['insan']],
            ['lemma' => 'Freundin', 'gender' => 'f', 'plural' => 'Freundinnen', 'level' => 'A2', 'example_sentence' => 'Die Freundin ruft an.', 'example_translation' => 'Arkadaşım arıyor.', 'tags' => ['insan']],
            ['lemma' => 'Computer', 'gender' => 'm', 'plural' => 'Computer', 'level' => 'A2', 'example_sentence' => 'Der Computer startet.', 'example_translation' => 'Bilgisayar açılıyor.', 'tags' => ['teknoloji']],
            ['lemma' => 'Bildschirm', 'gender' => 'm', 'plural' => 'Bildschirme', 'level' => 'B1', 'example_sentence' => 'Der Bildschirm ist hell.', 'example_translation' => 'Ekran parlak.', 'tags' => ['teknoloji']],
            ['lemma' => 'Lampe', 'gender' => 'f', 'plural' => 'Lampen', 'level' => 'A1', 'example_sentence' => 'Die Lampe leuchtet.', 'example_translation' => 'Lamba yanıyor.', 'tags' => ['ev']],
            ['lemma' => 'Schule', 'gender' => 'f', 'plural' => 'Schulen', 'level' => 'A1', 'example_sentence' => 'Die Schule beginnt um acht.', 'example_translation' => 'Okul sekizde başlıyor.', 'tags' => ['yer']],
            ['lemma' => 'Universität', 'gender' => 'f', 'plural' => 'Universitäten', 'level' => 'B1', 'example_sentence' => 'Die Universität ist bekannt.', 'example_translation' => 'Üniversite tanınmış.', 'tags' => ['yer']],
            ['lemma' => 'Lehrbuch', 'gender' => 'n', 'plural' => 'Lehrbücher', 'level' => 'B1', 'example_sentence' => 'Das Lehrbuch ist neu.', 'example_translation' => 'Ders kitabı yeni.', 'tags' => ['eğitim']],
            ['lemma' => 'Arzt', 'gender' => 'm', 'plural' => 'Ärzte', 'level' => 'A2', 'example_sentence' => 'Der Arzt untersucht.', 'example_translation' => 'Doktor muayene ediyor.', 'tags' => ['meslek']],
            ['lemma' => 'Ärztin', 'gender' => 'f', 'plural' => 'Ärztinnen', 'level' => 'A2', 'example_sentence' => 'Die Ärztin erklärt.', 'example_translation' => 'Doktor açıklıyor.', 'tags' => ['meslek']],
            ['lemma' => 'Bäckerei', 'gender' => 'f', 'plural' => 'Bäckereien', 'level' => 'A1', 'example_sentence' => 'Die Bäckerei öffnet früh.', 'example_translation' => 'Fırın erken açılıyor.', 'tags' => ['yer']],
            ['lemma' => 'Kaffee', 'gender' => 'm', 'plural' => 'Kaffees', 'level' => 'A1', 'example_sentence' => 'Der Kaffee ist heiß.', 'example_translation' => 'Kahve sıcak.', 'tags' => ['yiyecek']],
            ['lemma' => 'Tee', 'gender' => 'm', 'plural' => 'Tees', 'level' => 'A1', 'example_sentence' => 'Der Tee schmeckt gut.', 'example_translation' => 'Çay lezzetli.', 'tags' => ['yiyecek']],
            ['lemma' => 'Brötchen', 'gender' => 'n', 'plural' => 'Brötchen', 'level' => 'A1', 'example_sentence' => 'Das Brötchen ist frisch.', 'example_translation' => 'Simit taze.', 'tags' => ['yiyecek']],
            ['lemma' => 'Stuhl', 'gender' => 'm', 'plural' => 'Stühle', 'level' => 'A1', 'example_sentence' => 'Der Stuhl ist bequem.', 'example_translation' => 'Sandalye rahat.', 'tags' => ['ev']],
            ['lemma' => 'Lampe', 'gender' => 'f', 'plural' => 'Lampen', 'level' => 'A1', 'example_sentence' => 'Die Lampe ist alt.', 'example_translation' => 'Lamba eski.', 'tags' => ['ev']],
            ['lemma' => 'Telefon', 'gender' => 'n', 'plural' => 'Telefone', 'level' => 'A1', 'example_sentence' => 'Das Telefon klingelt.', 'example_translation' => 'Telefon çalıyor.', 'tags' => ['teknoloji']],
            ['lemma' => 'Zug', 'gender' => 'm', 'plural' => 'Züge', 'level' => 'A1', 'example_sentence' => 'Der Zug kommt pünktlich.', 'example_translation' => 'Tren zamanında geliyor.', 'tags' => ['taşıt']],
            ['lemma' => 'Flugzeug', 'gender' => 'n', 'plural' => 'Flugzeuge', 'level' => 'A1', 'example_sentence' => 'Das Flugzeug landet.', 'example_translation' => 'Uçak iniyor.', 'tags' => ['taşıt']],
            ['lemma' => 'Straße', 'gender' => 'f', 'plural' => 'Straßen', 'level' => 'A1', 'example_sentence' => 'Die Straße ist lang.', 'example_translation' => 'Sokak uzun.', 'tags' => ['yer']],
            ['lemma' => 'Fahrrad', 'gender' => 'n', 'plural' => 'Fahrräder', 'level' => 'A1', 'example_sentence' => 'Das Fahrrad ist neu.', 'example_translation' => 'Bisiklet yeni.', 'tags' => ['taşıt']],
            ['lemma' => 'Restaurant', 'gender' => 'n', 'plural' => 'Restaurants', 'level' => 'A1', 'example_sentence' => 'Das Restaurant ist voll.', 'example_translation' => 'Restoran dolu.', 'tags' => ['yer']],
            ['lemma' => 'Hotel', 'gender' => 'n', 'plural' => 'Hotels', 'level' => 'A1', 'example_sentence' => 'Das Hotel ist modern.', 'example_translation' => 'Otel modern.', 'tags' => ['yer']],
            ['lemma' => 'Küche', 'gender' => 'f', 'plural' => 'Küchen', 'level' => 'A1', 'example_sentence' => 'Die Küche ist sauber.', 'example_translation' => 'Mutfak temiz.', 'tags' => ['ev']],
            ['lemma' => 'Bad', 'gender' => 'n', 'plural' => 'Bäder', 'level' => 'A1', 'example_sentence' => 'Das Bad ist klein.', 'example_translation' => 'Banyo küçük.', 'tags' => ['ev']],
            ['lemma' => 'Zimmer', 'gender' => 'n', 'plural' => 'Zimmer', 'level' => 'A1', 'example_sentence' => 'Das Zimmer ist hell.', 'example_translation' => 'Oda aydınlık.', 'tags' => ['ev']],
            ['lemma' => 'Kühlschrank', 'gender' => 'm', 'plural' => 'Kühlschränke', 'level' => 'A1', 'example_sentence' => 'Der Kühlschrank ist leer.', 'example_translation' => 'Buzdolabı boş.', 'tags' => ['ev']],
            ['lemma' => 'Bett', 'gender' => 'n', 'plural' => 'Betten', 'level' => 'A1', 'example_sentence' => 'Das Bett ist weich.', 'example_translation' => 'Yatak yumuşak.', 'tags' => ['ev']],
            ['lemma' => 'Sofa', 'gender' => 'n', 'plural' => 'Sofas', 'level' => 'A1', 'example_sentence' => 'Das Sofa ist gemütlich.', 'example_translation' => 'Kanepe rahat.', 'tags' => ['ev']],
            ['lemma' => 'Regal', 'gender' => 'n', 'plural' => 'Regale', 'level' => 'A1', 'example_sentence' => 'Das Regal ist voll.', 'example_translation' => 'Raf dolu.', 'tags' => ['ev']],
            ['lemma' => 'Stift', 'gender' => 'm', 'plural' => 'Stifte', 'level' => 'A1', 'example_sentence' => 'Der Stift schreibt gut.', 'example_translation' => 'Kalem iyi yazıyor.', 'tags' => ['okul']],
            ['lemma' => 'Papier', 'gender' => 'n', 'plural' => 'Papiere', 'level' => 'A1', 'example_sentence' => 'Das Papier ist weiß.', 'example_translation' => 'Kâğıt beyaz.', 'tags' => ['okul']],
            ['lemma' => 'Heft', 'gender' => 'n', 'plural' => 'Hefte', 'level' => 'A1', 'example_sentence' => 'Das Heft liegt auf dem Tisch.', 'example_translation' => 'Defter masada.', 'tags' => ['okul']],
            ['lemma' => 'Leinwand', 'gender' => 'f', 'plural' => 'Leinwände', 'level' => 'B1', 'example_sentence' => 'Die Leinwand ist groß.', 'example_translation' => 'Tuval büyük.', 'tags' => ['sanat']],
            ['lemma' => 'Museum', 'gender' => 'n', 'plural' => 'Museen', 'level' => 'B1', 'example_sentence' => 'Das Museum ist alt.', 'example_translation' => 'Müze eski.', 'tags' => ['yer']],
            ['lemma' => 'Bibliothek', 'gender' => 'f', 'plural' => 'Bibliotheken', 'level' => 'A2', 'example_sentence' => 'Die Bibliothek ist ruhig.', 'example_translation' => 'Kütüphane sessiz.', 'tags' => ['yer']],
            ['lemma' => 'Park', 'gender' => 'm', 'plural' => 'Parks', 'level' => 'A1', 'example_sentence' => 'Der Park ist grün.', 'example_translation' => 'Park yeşil.', 'tags' => ['doğa']],
            ['lemma' => 'Fluss', 'gender' => 'm', 'plural' => 'Flüsse', 'level' => 'A2', 'example_sentence' => 'Der Fluss fließt schnell.', 'example_translation' => 'Nehir hızlı akıyor.', 'tags' => ['doğa']],
            ['lemma' => 'Berg', 'gender' => 'm', 'plural' => 'Berge', 'level' => 'A2', 'example_sentence' => 'Der Berg ist hoch.', 'example_translation' => 'Dağ yüksek.', 'tags' => ['doğa']],
            ['lemma' => 'See', 'gender' => 'm', 'plural' => 'Seen', 'level' => 'A2', 'example_sentence' => 'Der See ist tief.', 'example_translation' => 'Göl derin.', 'tags' => ['doğa']],
            ['lemma' => 'Meer', 'gender' => 'n', 'plural' => 'Meere', 'level' => 'A2', 'example_sentence' => 'Das Meer ist ruhig.', 'example_translation' => 'Deniz sakin.', 'tags' => ['doğa']],
            ['lemma' => 'Wald', 'gender' => 'm', 'plural' => 'Wälder', 'level' => 'A2', 'example_sentence' => 'Der Wald ist dunkel.', 'example_translation' => 'Orman karanlık.', 'tags' => ['doğa']],
            ['lemma' => 'Insel', 'gender' => 'f', 'plural' => 'Inseln', 'level' => 'B1', 'example_sentence' => 'Die Insel ist klein.', 'example_translation' => 'Ada küçük.', 'tags' => ['doğa']],
            ['lemma' => 'Wetter', 'gender' => 'n', 'plural' => 'Wetter', 'level' => 'A1', 'example_sentence' => 'Das Wetter ist schön.', 'example_translation' => 'Hava güzel.', 'tags' => ['hava']],
            ['lemma' => 'Regen', 'gender' => 'm', 'plural' => 'Regen', 'level' => 'A1', 'example_sentence' => 'Der Regen fällt stark.', 'example_translation' => 'Yağmur şiddetli yağıyor.', 'tags' => ['hava']],
            ['lemma' => 'Schnee', 'gender' => 'm', 'plural' => 'Schnee', 'level' => 'A2', 'example_sentence' => 'Der Schnee ist frisch.', 'example_translation' => 'Kar taze.', 'tags' => ['hava']],
        ];

        foreach ($words as $word) {
            Word::updateOrCreate(['lemma' => $word['lemma']], $word);
        }
    }
}
