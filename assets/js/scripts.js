// Tab switching logic
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.nav-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();

            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('nav-tab-active'));

            // Hide all contents
            contents.forEach(content => content.style.display = 'none');

            // Add active class to the clicked tab
            this.classList.add('nav-tab-active');

            // Show the corresponding content
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.style.display = 'block';
            }
        });
    });

    // export stuff
    const exportButtons = document.querySelectorAll('.export-csv');
    exportButtons.forEach(button => {
        button.addEventListener('click', function () {
            const exportType = this.dataset.export;
            const formData = new FormData();
            formData.append('action', 'wp_tech_metrics_export_csv');
            formData.append('export_type', exportType);

            fetch(ajaxurl, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `wp-tech-metrics-${exportType}-${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(error => console.error('Export failed', error));
        });
    });
});
