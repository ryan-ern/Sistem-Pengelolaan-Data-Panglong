@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="dashboard-wrapper p-4 rounded-3">

        @if (Auth::user()->role !== 'admin')
            <form method="GET" class="row mb-4">
                <div class="col-lg-6 mb-3">
                    <select name="cabang_id" class="form-select bg-timber text-soft text-center" onchange="this.form.submit()">
                        <option value="">---- Pilih Cabang ----</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->id }}" {{ request('cabang_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-3">
                    <select name="tahun" class="form-select bg-timber text-soft text-center"
                        onchange="this.form.submit()">
                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
            </form>
        @endif

        <div class="row g-4">

            {{-- LEFT INFO PANEL --}}
            <div class="col-lg-3">

                <div class="card card-forest shadow-lg mb-3 border-0">
                    <div class="card-body text-center">
                        <small>Penjualan Terbanyak</small>
                        <h6 class="fw-semibold mt-2">Per-{{ request('tahun', now()->year) }}</h6>

                        <h3 class="fw-bold mt-2"> {{ $penjualanTerbanyak->qty ?? 0 }} pcs</h3>
                        <span class="badge badge-soft">
                            {{ $penjualanTerbanyak->jenis_kayu ?? '-' }} Terjual
                        </span>
                    </div>
                </div>


                <div class="card card-forest shadow-sm border-0">
                    <div class="card-body text-center">
                        <small>Rata-rata Penjualan</small>
                        <h6 class="fw-semibold mt-2">Per Bulan ({{ $tahun }})</h6>

                        <h2 class="fw-bold mt-2">
                            Rp {{ number_format($rataBulanan, 0, ',', '.') }}
                        </h2>
                    </div>
                </div>

            </div>

            {{-- MAIN CHART --}}
            <div class="col-lg-9">
                <div class="card card-forest shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            Grafik Tren Penjualan - {{ request('tahun', now()->year) }}
                        </h6>

                        <div class="chart-placeholder p-2">
                            <canvas id="trenChart" height="110" width="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STOCK BAR CHART --}}
            <div class="col-12 d-none d-md-block">
                <div class="card card-forest shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            Grafik Stok Kayu - Terkini
                        </h6>

                        <div class="chart-placeholder p-2">
                            <canvas id="stokBarChart" height="100" width="600"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PIE CHART --}}
            <div class="col-12 d-sm-block d-md-none">
                <div class="card card-forest shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            Grafik Stok Kayu - Terkini
                        </h6>

                        <div class="container">
                            <div class="row">
                                <div class="col-7">
                                    <canvas id="stokPieChart" height="100"></canvas>
                                </div>
                                <div class="col-5">
                                    <ul id="pieLegend" class="list-unstyled small"></ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>


    <script>
        const trenPenjualan = @json($trenPenjualan);
        const stokKayu = @json($stokKayu);

        console.log(trenPenjualan)
        console.log(stokKayu)
        /* ===============================
               LINE CHART - TREN PENJUALAN
            ================================ */
        const bulanLabels = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
        ];

        const trenData = bulanLabels.map((_, i) =>
            trenPenjualan[i + 1] ?? 0
        );

        new Chart(document.getElementById('trenChart'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: trenData,
                    tension: 0.4,
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => 'Rp ' + v.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });


        /* ===============================
           BAR & PIE DATA
        ================================ */
        const stokLabels = stokKayu.map(s => s.jenis_kayu);
        const stokValues = stokKayu.map(s => s.jumlah);


        /* ===============================
           BAR CHART - STOK KAYU (DESKTOP)
        ================================ */
        const barEl = document.getElementById('stokBarChart');
        if (barEl) {
            new Chart(barEl, {
                type: 'bar',
                data: {
                    labels: stokLabels,
                    datasets: [{
                        label: 'Stok',
                        data: stokValues
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }


        /* ===============================
           PIE CHART - STOK KAYU (MOBILE)
        ================================ */
        const colors = [
            '#4CAF50', // hijau
            '#FF9800', // oranye
            '#2196F3', // biru
            '#9C27B0', // ungu
            '#795548', // coklat kayu
            '#F44336', // merah
            '#00BCD4', // cyan
        ];


        const pieChart = new Chart(document.getElementById('stokPieChart'), {
            type: 'pie',
            data: {
                labels: stokLabels,
                datasets: [{
                    data: stokValues,
                    backgroundColor: colors,
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        color: '#000',
                        formatter: (value) => value,
                        anchor: 'end',
                        align: 'end',
                        offset: 18,
                        clamp: false,
                        textStrokeColor: '#ffffff', // ⬅️ OUTLINE PUTIH
                        textStrokeWidth: 3,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            }
        });

        /* ===============================
           LEGEND CUSTOM (KANAN)
        ================================ */
        const legendEl = document.getElementById('pieLegend');
        legendEl.innerHTML = '';

        stokLabels.forEach((label, i) => {
            const li = document.createElement('li');
            li.classList.add('mb-1');
            li.innerHTML = `
            <span style="
                display:inline-block;
                width:10px;
                height:10px;
                background:${colors[i % colors.length]};
                margin-right:6px;
                border-radius:3px
            "></span>
            <strong>${stokValues[i]}</strong> ${label}
        `;
            legendEl.appendChild(li);
        });
    </script>
@endsection
