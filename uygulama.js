document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const dugme = document.getElementById('tema-dugme');
    const kayitliTema = localStorage.getItem('tema');

    if (kayitliTema === 'koyu') {
        body.classList.add('tema-koyu');
    }

    dugme?.addEventListener('click', () => {
        body.classList.toggle('tema-koyu');
        const yeniTema = body.classList.contains('tema-koyu') ? 'koyu' : 'acik';
        localStorage.setItem('tema', yeniTema);
    });

    document.querySelectorAll('[data-aksiyon="cevabi-goster"]').forEach((buton) => {
        buton.addEventListener('click', () => {
            const hedef = document.getElementById(buton.dataset.hedef || '');
            if (hedef) {
                hedef.toggleAttribute('data-gorunur');
            }
        });
    });
});
