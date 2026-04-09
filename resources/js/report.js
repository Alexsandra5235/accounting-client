window.searchReports = function() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const reports = document.querySelectorAll('.report-item');

    reports.forEach(report => {
        const filename = report.querySelector('h4')?.textContent?.toLowerCase() || '';
        const date = report.querySelector('.fa-calendar-alt + span')?.textContent?.toLowerCase() || '';

        if (filename.includes(searchTerm) || date.includes(searchTerm)) {
            report.style.display = 'block';
        } else {
            report.style.display = 'none';
        }
    });
}

window.filterReports = function() {
    const period = document.getElementById('periodFilter').value;
    const reports = document.querySelectorAll('.report-item');
    const now = new Date();

    reports.forEach(report => {
        const dateText = report.querySelector('.fa-calendar-alt + span')?.textContent || '';
        let show = true;

        if (period !== 'all') {
            // Парсим дату
            const months = {
                'января': 0, 'февраля': 1, 'марта': 2, 'апреля': 3, 'мая': 4, 'июня': 5,
                'июля': 6, 'августа': 7, 'сентября': 8, 'октября': 9, 'ноября': 10, 'декабря': 11
            };

            const parts = dateText.trim().split(' ');
            if (parts.length >= 3) {
                const day = parseInt(parts[0]);
                const month = months[parts[1]];
                const year = parseInt(parts[2]);
                const reportDate = new Date(year, month, day);

                const diffTime = now - reportDate;
                const diffDays = diffTime / (1000 * 60 * 60 * 24);

                if (period === 'today' && diffDays > 1) show = false;
                if (period === 'week' && diffDays > 7) show = false;
                if (period === 'month' && diffDays > 30) show = false;
            }
        }

        report.style.display = show ? 'block' : 'none';
    });
}
