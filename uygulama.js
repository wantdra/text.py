document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const temaDugme = document.getElementById('tema-dugme');
    const kayitliTema = localStorage.getItem('tema');

    if (kayitliTema === 'acik') {
        body.classList.remove('tema-koyu');
        body.classList.add('tema-acik');
    } else {
        body.classList.add('tema-koyu');
    }

    temaDugme?.addEventListener('click', () => {
        const koyu = body.classList.toggle('tema-koyu');
        if (koyu) {
            body.classList.remove('tema-acik');
            localStorage.setItem('tema', 'koyu');
        } else {
            body.classList.add('tema-acik');
            localStorage.setItem('tema', 'acik');
        }
    });

    document.querySelectorAll('.buton').forEach((buton) => {
        buton.addEventListener('click', (event) => {
            const mevcut = buton.querySelector('.ripple-dalga');
            mevcut?.remove();
            const ripple = document.createElement('span');
            const boyut = Math.max(buton.offsetWidth, buton.offsetHeight);
            const rect = buton.getBoundingClientRect();
            ripple.className = 'ripple-dalga';
            ripple.style.width = ripple.style.height = `${boyut}px`;
            ripple.style.left = `${(event.clientX - rect.left) - boyut / 2}px`;
            ripple.style.top = `${(event.clientY - rect.top) - boyut / 2}px`;
            buton.appendChild(ripple);
            ripple.addEventListener('animationend', () => ripple.remove());
        });
    });

    document.querySelectorAll('[data-aksiyon="cevabi-goster"]').forEach((buton) => {
        buton.addEventListener('click', () => {
            const hedef = document.getElementById(buton.dataset.hedef || '');
            if (hedef) {
                hedef.toggleAttribute('data-gorunur');
            }
        });
    });

    const hareketAzalt = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!hareketAzalt) {
        const gozlemci = new IntersectionObserver((kesitler) => {
            kesitler.forEach((kesit) => {
                if (kesit.isIntersecting) {
                    kesit.target.classList.add('gorunur');
                    gozlemci.unobserve(kesit.target);
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('[data-scroll]').forEach((eleman) => gozlemci.observe(eleman));
    } else {
        document.querySelectorAll('[data-scroll]').forEach((eleman) => eleman.classList.add('gorunur'));
    }

    const adminVeriEl = document.getElementById('admin-veri');
    if (!adminVeriEl) {
        return;
    }

    const adminDurum = {
        ...JSON.parse(adminVeriEl.textContent || '{}'),
    };

    const tabloKonteyner = document.getElementById('admin-tablosu');
    const bilgiKutusu = document.getElementById('admin-bilgi');
    const textarea = document.getElementById('ham-json-metin');
    const csrf = document.getElementById('admin-csrf');
    const yeniSatirBtn = document.getElementById('yeni-satir');
    const jsonKaydetBtn = document.getElementById('ham-json-kaydet');
    const jsonIndirBtn = document.getElementById('json-indir');
    const sekmeButonlari = document.querySelectorAll('.buton-sekme');

    const guncelKaynak = () => adminDurum.aktif;

    const kayitlariAl = () => adminDurum.kaynaklar[guncelKaynak()].veri;

    const alanlariAl = () => adminDurum.kaynaklar[guncelKaynak()].alanlar;

    const mesajYaz = (mesaj, tur = '') => {
        if (!bilgiKutusu) return;
        bilgiKutusu.textContent = mesaj;
        bilgiKutusu.classList.remove('basarili', 'hata');
        if (tur) {
            bilgiKutusu.classList.add(tur);
        }
        if (mesaj) {
            setTimeout(() => {
                bilgiKutusu.classList.remove('basarili', 'hata');
                bilgiKutusu.textContent = '';
            }, 4000);
        }
    };

    const tabloOlustur = () => {
        if (!tabloKonteyner) return;
        tabloKonteyner.innerHTML = '';
        const tablo = document.createElement('table');
        tablo.className = 'tablo';
        const thead = document.createElement('thead');
        const baslikSatir = document.createElement('tr');
        baslikSatir.appendChild(document.createElement('th')).textContent = '#';
        alanlariAl().forEach((alan) => {
            const th = document.createElement('th');
            th.textContent = alan.baslik;
            baslikSatir.appendChild(th);
        });
        thead.appendChild(baslikSatir);
        tablo.appendChild(thead);

        const tbody = document.createElement('tbody');
        kayitlariAl().forEach((kayit, index) => {
            const tr = document.createElement('tr');
            const sira = document.createElement('td');
            sira.textContent = String(index + 1);
            tr.appendChild(sira);
            alanlariAl().forEach((alan) => {
                const td = document.createElement('td');
                td.dataset.duzenlenebilir = 'true';
                td.dataset.id = kayit.id;
                td.dataset.alan = alan.anahtar;
                let deger = kayit[alan.anahtar];
                if (Array.isArray(deger)) {
                    deger = deger.join('\n');
                }
                td.textContent = deger ?? '';
                td.title = `Çift tıkla: ${alan.placeholder}`;
                td.addEventListener('dblclick', () => duzenlemeyiAc(td));
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
        tablo.appendChild(tbody);
        const sarmal = document.createElement('div');
        sarmal.className = 'tablo-wrapper';
        sarmal.appendChild(tablo);
        tabloKonteyner.appendChild(sarmal);
        tabloKonteyner.dataset.kayitSayisi = String(kayitlariAl().length);
    };

    const textareaGuncelle = () => {
        if (!textarea) return;
        const veri = kayitlariAl();
        textarea.value = JSON.stringify(veri, null, 2);
    };

    const kaynagiDegistir = (yeniKaynak) => {
        if (!adminDurum.kaynaklar[yeniKaynak]) return;
        adminDurum.aktif = yeniKaynak;
        sekmeButonlari.forEach((buton) => {
            const secili = buton.dataset.kaynak === yeniKaynak;
            buton.setAttribute('aria-selected', secili ? 'true' : 'false');
        });
        const url = new URL(window.location.href);
        url.searchParams.set('kaynak', yeniKaynak);
        window.history.replaceState({}, '', url);
        tabloOlustur();
        textareaGuncelle();
    };

    sekmeButonlari.forEach((buton) => {
        buton.addEventListener('click', () => {
            const hedef = buton.dataset.kaynak;
            if (hedef) {
                kaynagiDegistir(hedef);
            }
        });
    });

    const adminPost = async (parametreler) => {
        const formData = new FormData();
        Object.entries(parametreler).forEach(([anahtar, deger]) => {
            formData.append(anahtar, deger);
        });
        if (csrf) {
            formData.append('token', csrf.value);
        }
        formData.append('kaynak', guncelKaynak());
        try {
            const yanit = await fetch('admin.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            return await yanit.json();
        } catch (hata) {
            return { basarili: false, mesaj: 'Sunucuya bağlanılamadı.' };
        }
    };

    const duzenlemeyiAc = (hucre) => {
        if (hucre.isContentEditable) return;
        const orijinal = hucre.textContent;
        hucre.contentEditable = 'true';
        hucre.focus();
        const sec = window.getSelection();
        const range = document.createRange();
        range.selectNodeContents(hucre);
        sec.removeAllRanges();
        sec.addRange(range);

        const blurIsleyici = async () => {
            hucre.removeEventListener('blur', blurIsleyici);
            hucre.removeEventListener('keydown', tusIsleyici);
            hucre.contentEditable = 'false';
            const yeniDeger = hucre.textContent || '';
            if (yeniDeger === orijinal) {
                return;
            }
            hucre.dataset.kaydediliyor = 'true';
            const sonuc = await adminPost({
                islem: 'guncelle',
                id: hucre.dataset.id || '',
                alan: hucre.dataset.alan || '',
                deger: yeniDeger,
            });
            hucre.removeAttribute('data-kaydediliyor');
            if (!sonuc.basarili) {
                hucre.textContent = orijinal;
                mesajYaz(sonuc.mesaj || 'Kaydedilemedi.', 'hata');
                return;
            }
            mesajYaz(sonuc.mesaj || 'Kaydedildi.', 'basarili');
            const kayit = kayitlariAl().find((k) => k.id === hucre.dataset.id);
            if (kayit) {
                const alan = hucre.dataset.alan;
                if (alan === 'secenekler') {
                    kayit[alan] = yeniDeger.split(/\r?\n|,/).map((parca) => parca.trim()).filter(Boolean);
                    hucre.textContent = kayit[alan].join('\n');
                } else {
                    kayit[alan] = yeniDeger.trim();
                }
            }
            textareaGuncelle();
        };

        const tusIsleyici = (event) => {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                hucre.blur();
            }
        };

        hucre.addEventListener('blur', blurIsleyici);
        hucre.addEventListener('keydown', tusIsleyici);
    };

    yeniSatirBtn?.addEventListener('click', async () => {
        const sonuc = await adminPost({ islem: 'ekle' });
        if (!sonuc.basarili) {
            mesajYaz(sonuc.mesaj || 'Yeni kayıt eklenemedi.', 'hata');
            return;
        }
        kayitlariAl().push(sonuc.kayit);
        tabloOlustur();
        textareaGuncelle();
        let mesaj = sonuc.mesaj || 'Yeni kayıt eklendi.';
        if (sonuc.geciciSifre) {
            mesaj += ` Geçici şifre: ${sonuc.geciciSifre}`;
        }
        mesajYaz(mesaj, 'basarili');
    });

    jsonKaydetBtn?.addEventListener('click', async () => {
        const ham = textarea ? textarea.value : '';
        const sonuc = await adminPost({ islem: 'json_kaydet', ham });
        if (!sonuc.basarili) {
            mesajYaz(sonuc.mesaj || 'JSON kaydedilemedi.', 'hata');
            return;
        }
        try {
            const guncel = JSON.parse(ham);
            adminDurum.kaynaklar[guncelKaynak()].veri = guncel;
        } catch (e) {
            // yok say
        }
        tabloOlustur();
        mesajYaz(sonuc.mesaj || 'JSON kaydedildi.', 'basarili');
    });

    jsonIndirBtn?.addEventListener('click', () => {
        const metin = textarea ? textarea.value : JSON.stringify(kayitlariAl(), null, 2);
        const blob = new Blob([metin], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${guncelKaynak()}-${new Date().toISOString().replace(/[:.]/g, '-')}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        mesajYaz('JSON dışa aktarıldı.', 'basarili');
    });

    kaynagiDegistir(adminDurum.aktif);
});
