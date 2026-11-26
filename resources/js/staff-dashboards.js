(() => {
    const toast = document.getElementById('staffToast');
    let toastTimer;
    const showToast = (message) => {
        if (!toast) return;
        toast.textContent = message;
        toast.classList.add('visible');
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(() => toast.classList.remove('visible'), 2800);
    };

    document.addEventListener('click', (event) => {
        const toastButton = event.target.closest('[data-demo-toast]');
        if (toastButton) showToast(toastButton.dataset.demoToast || 'This action is available in the integrated version.');

        if (event.target.closest('[data-sidebar-open]')) {
            document.getElementById('staffSidebar')?.classList.add('open');
            document.querySelector('.staff-sidebar-scrim')?.classList.add('visible');
        }
        if (event.target.closest('[data-sidebar-close]')) {
            document.getElementById('staffSidebar')?.classList.remove('open');
            document.querySelector('.staff-sidebar-scrim')?.classList.remove('visible');
        }
    });

    document.querySelectorAll('[data-table-search]').forEach((input) => {
        input.addEventListener('input', () => {
            const query = input.value.toLocaleLowerCase().trim();
            const table = input.closest('.staff-table-panel');
            table?.querySelectorAll('[data-table-row]').forEach((row) => {
                row.hidden = !row.textContent.toLocaleLowerCase().includes(query);
            });
        });
    });
})();
