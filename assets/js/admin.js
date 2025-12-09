(function () {
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length) {
        setTimeout(() => alerts.forEach(el => el.remove()), 4000);
    }
})();
