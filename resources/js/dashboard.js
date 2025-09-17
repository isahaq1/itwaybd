import Chart from 'chart.js/auto';

function initDashboardChart() {
    const el = document.getElementById('sales-chart');
    if (!el) return;
    const labels = JSON.parse(el.dataset.labels || '[]');
    const values = JSON.parse(el.dataset.values || '[]');
    new Chart(el, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Sales (BDT)',
                data: values,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

document.addEventListener('DOMContentLoaded', initDashboardChart);


