@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-4 gap-6">
    <a href="{{ route('admin.users') }}"
        class="block bg-white p-4 rounded shadow hover:bg-gray-50 transition">

        <p class="text-sm text-gray-500">Total User</p>
        <h2 class="text-2xl font-bold">{{ $totalUsers }}</h2>
    </a>
</div>

    <div class="bg-white p-6 rounded-xl shadow h-[350px] mt-6">
    <h3 class="text-lg font-semibold mb-4">Pengunjung</h3>
    <canvas id="visitorChart"></canvas></div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('visitorChart');
    let chart;

    async function loadChart() {
        const res = await fetch('/admin/visitor-chart');
        const data = await res.json();

        const labels = data.map(d => d.date);
        const totals = data.map(d => d.total);

        if (!chart) {
           chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Pengunjung',
                    data: totals,
                    borderWidth: 2,
                    tension: 0.35,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} pengunjung`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    }
                }
            }
        });

        } else {
            chart.data.labels = labels;
            chart.data.datasets[0].data = totals;
            chart.update();
        }
    }

    // load awal
    loadChart();

    // auto refresh tiap 5 detik
    setInterval(loadChart, 5000);
    </script>

@endsection
