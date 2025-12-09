(function () {
    const sentences = Array.from(document.querySelectorAll('#sentenceList li')).map(li => li.textContent);
    const sentenceContainer = document.querySelector('#sentenceList');
    const motivationText = document.querySelector('#motivationText');
    const reviewButton = document.querySelector('#startReview');

    const messages = [
        'Kısa tekrarlar motivasyonunu taze tutar.',
        'Yeni kelimeleri yüksek sesle oku, telaffuzun gelişsin.',
        'Hedefini arkadaşınla paylaş, birlikte ilerleyin.',
    ];

    function rotate(list, element, interval = 5000) {
        if (!element || list.length === 0) return;
        let index = 0;
        setInterval(() => {
            index = (index + 1) % list.length;
            element.textContent = list[index];
        }, interval);
    }

    rotate(sentences, sentenceContainer?.firstElementChild, 4000);
    rotate(messages, motivationText, 6000);

    if (reviewButton) {
        reviewButton.addEventListener('click', () => {
            alert('Bugünün tekrar listesi hazır!');
        });
    }

    const reminderToggle = document.getElementById('reminderToggle');
    if (reminderToggle) {
        reminderToggle.addEventListener('click', () => {
            reminderToggle.classList.toggle('active');
        });
    }
})();
