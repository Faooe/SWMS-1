@props([
    'labels' => [],
    'data' => [],
])

<x-ui.card>

    <div class="mb-6 flex items-center justify-between">

        <div>

            <h2 class="text-xl font-bold">
                Attendance Trend
            </h2>

            <p class="text-sm text-slate-500">
                Last 7 Days
            </p>

        </div>

    </div>

    <div class="relative h-72 w-full">

        <canvas
            id="attendanceChart"
            data-labels="{{ json_encode($labels) }}"
            data-values="{{ json_encode($data) }}">
        </canvas>

    </div>

</x-ui.card>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('attendanceChart');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    const labels = JSON.parse(canvas.dataset.labels || '[]');
    const values = JSON.parse(canvas.dataset.values || '[]');

    new Chart(canvas, {

        type: 'line',

        data: {

            labels: labels,

            datasets: [{
                label: 'Attendance',
                data: values,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                tension: .4,
                fill: true,
            }],

        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                    },
                },
            },

            plugins: {
                legend: {
                    display: false,
                },
            },
        },

    });

});

</script>

@endpush
