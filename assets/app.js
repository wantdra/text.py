(function () {
    const list = document.getElementById('redemittelList');
    if (!list) {
        return;
    }
    const categorySelect = document.getElementById('categoryFilter');
    const difficultySelect = document.getElementById('difficultyFilter');
    const searchInput = document.getElementById('searchInput');
    const favoritesOnly = document.getElementById('favoritesOnly');
    const FAVORITES_KEY = 'redemittelFavorites';

    const favorites = new Set(JSON.parse(localStorage.getItem(FAVORITES_KEY) || '[]'));

    function renderFavoriteState(card, isFavorite) {
        const btn = card.querySelector('.favorite-toggle');
        btn.classList.toggle('is-favorite', isFavorite);
        btn.textContent = isFavorite ? 'Favorilerde' : 'Favorilere Ekle';
        btn.setAttribute('aria-pressed', isFavorite ? 'true' : 'false');
    }

    function applyFavoriteState() {
        const cards = list.querySelectorAll('.card');
        cards.forEach(card => {
            const id = card.dataset.id;
            renderFavoriteState(card, favorites.has(id));
        });
    }

    function saveFavorites() {
        localStorage.setItem(FAVORITES_KEY, JSON.stringify(Array.from(favorites)));
    }

    function matchesFilters(card) {
        const category = card.dataset.category;
        const difficulty = card.dataset.difficulty;
        const id = card.dataset.id;
        const text = `${card.querySelector('.german').textContent} ${card.querySelector('.turkish').textContent}`.toLowerCase();
        const search = (searchInput.value || '').toLowerCase();

        const categoryMatch = !categorySelect.value || category === categorySelect.value;
        const difficultyMatch = !difficultySelect.value || difficulty === difficultySelect.value;
        const searchMatch = !search || text.includes(search);
        const favoritesMatch = !favoritesOnly.checked || favorites.has(id);

        return categoryMatch && difficultyMatch && searchMatch && favoritesMatch;
    }

    function filterCards() {
        const cards = list.querySelectorAll('.card');
        let visibleCount = 0;
        cards.forEach(card => {
            if (matchesFilters(card)) {
                card.removeAttribute('hidden');
                visibleCount += 1;
            } else {
                card.setAttribute('hidden', 'hidden');
            }
        });

        if (visibleCount === 0) {
            showEmptyState();
        } else {
            removeEmptyState();
        }
    }

    function showEmptyState() {
        if (!document.getElementById('emptyState')) {
            const empty = document.createElement('div');
            empty.id = 'emptyState';
            empty.className = 'empty-state';
            empty.innerHTML = '<h3>Sonuç bulunamadı</h3><p>Filtreleri değiştirerek yeni ifadeler keşfedin.</p>';
            list.appendChild(empty);
        }
    }

    function removeEmptyState() {
        const empty = document.getElementById('emptyState');
        if (empty) {
            empty.remove();
        }
    }

    function pronounce(text) {
        const url = `https://translate.googleapis.com/translate_tts?ie=UTF-8&client=tw-ob&tl=de&q=${encodeURIComponent(text)}`;
        const audio = new Audio(url);
        audio.play().catch(() => {
            alert('Ses çalınamadı. Lütfen internet bağlantınızı kontrol edin.');
        });
    }

    list.addEventListener('click', (event) => {
        const target = event.target;
        const card = target.closest('.card');
        if (!card) return;

        if (target.classList.contains('favorite-toggle')) {
            const id = card.dataset.id;
            if (favorites.has(id)) {
                favorites.delete(id);
            } else {
                favorites.add(id);
            }
            renderFavoriteState(card, favorites.has(id));
            saveFavorites();
            if (favoritesOnly.checked) {
                filterCards();
            }
        }

        if (target.classList.contains('pronounce')) {
            pronounce(target.dataset.text);
        }
    });

    [categorySelect, difficultySelect, searchInput, favoritesOnly].forEach(el => {
        el && el.addEventListener('input', filterCards);
    });

    applyFavoriteState();
    filterCards();
})();
