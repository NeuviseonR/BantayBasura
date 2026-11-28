(() => {
    'use strict';

    const menuToggle = document.getElementById('menuToggle');
    const navList = document.getElementById('navList');
    if (menuToggle && navList) {
        menuToggle.addEventListener('click', () => navList.classList.toggle('active'));
    }

    const hasBannedWords = (text, words) => {
        const value = String(text || '').toLocaleLowerCase();
        return words.some((word) => {
            const term = String(word || '').trim().toLocaleLowerCase();
            if (!term) return false;
            const escaped = term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            try {
                return new RegExp(`(?<![\\p{L}\\p{N}])${escaped}(?![\\p{L}\\p{N}])`, 'iu').test(value);
            } catch (_) {
                return new RegExp(`\\b${escaped}\\b`, 'i').test(value);
            }
        });
    };

    const createForm = document.getElementById('ticketCreateForm');
    if (createForm) {
        let bannedWords = [];
        try { bannedWords = JSON.parse(createForm.dataset.bannedWords || '[]'); } catch (_) { bannedWords = []; }

        const title = document.getElementById('title');
        const description = document.getElementById('description');
        const titleCount = document.getElementById('titleCount');
        const descriptionCount = document.getElementById('descriptionCount');
        const alertBox = document.getElementById('ticketProfanityAlert');
        const fileInput = document.getElementById('attachment');
        const reportSelect = document.getElementById('waste_report_id');
        const preview = document.getElementById('ticketFilePreview');
        const fileName = document.getElementById('ticketFileName');
        const removeFile = document.getElementById('removeTicketFile');

        const updateCounts = () => {
            if (titleCount && title) titleCount.textContent = String(title.value.length);
            if (descriptionCount && description) descriptionCount.textContent = String(description.value.length);
        };
        title?.addEventListener('input', updateCounts);
        description?.addEventListener('input', updateCounts);
        updateCounts();

        fileInput?.addEventListener('change', () => {
            const file = fileInput.files && fileInput.files[0];
            if (file) {
                if (reportSelect) reportSelect.value = '';
                if (file.size > 5 * 1024 * 1024) {
                    window.alert('The screenshot must be 5 MB or smaller.');
                    fileInput.value = '';
                    preview.hidden = true;
                    return;
                }
                fileName.textContent = file.name;
                preview.hidden = false;
            } else if (preview) {
                preview.hidden = true;
            }
        });

        reportSelect?.addEventListener('change', () => {
            if (reportSelect.value && fileInput) {
                fileInput.value = '';
                if (preview) preview.hidden = true;
            }
        });

        removeFile?.addEventListener('click', () => {
            if (fileInput) fileInput.value = '';
            if (preview) preview.hidden = true;
        });

        createForm.addEventListener('submit', (event) => {
            const content = `${title?.value || ''}\n${description?.value || ''}`;
            if (hasBannedWords(content, bannedWords)) {
                event.preventDefault();
                if (alertBox) {
                    alertBox.hidden = false;
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            if (alertBox) alertBox.hidden = true;
            if (!window.confirm('Once submitted, you cannot edit or delete the original ticket. Do you want to send it now?')) {
                event.preventDefault();
            }
        });
    }

    const replyForm = document.getElementById('ticketReplyForm');
    if (replyForm) {
        let bannedWords = [];
        try { bannedWords = JSON.parse(replyForm.dataset.bannedWords || '[]'); } catch (_) { bannedWords = []; }
        const body = document.getElementById('body');
        const alertBox = document.getElementById('ticketReplyProfanityAlert');
        replyForm.addEventListener('submit', (event) => {
            if (hasBannedWords(body?.value || '', bannedWords)) {
                event.preventDefault();
                if (alertBox) {
                    alertBox.hidden = false;
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            if (alertBox) alertBox.hidden = true;
        });
    }
})();
