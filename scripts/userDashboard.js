// chartScript.js

function renderGameSetCharts(gameSets) {
    gameSets.forEach(set => {
        // Check if the chart element exists
        const ctx = document.getElementById('chart-' + set.SetCode);
        if (!ctx) return;

        // Initialize Chart
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Collected', 'Remaining'],
                datasets: [{
                    data: [set.userCollectedCards, set.totalCards - set.userCollectedCards],
                    backgroundColor: ['#4caf50', '#e0e0e0'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                cutout: '75%',
                events: []
            }
        });
    });
}

console.log('gamesSet '+gameSets);

// Assuming gameSets is already available globally
if (typeof gameSets !== 'undefined') {
    
    renderGameSetCharts(gameSets);
}
