@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="dashboard-wrapper p-4 rounded-3">

        @if (Auth::user()->role !== 'admin')
            {{-- FILTER BAR --}}
            <div class="row d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex gap-3 col-sm-12 col-lg-6 mb-3">
                    <select class="form-select shadow-sm bg-timber text-soft">
                        <option>Pilih Cabang</option>
                    </select>
                </div>

                <div class="col-sm-12 col-lg-6 mb-3">
                    <select class="form-select shadow-sm bg-timber text-soft">
                        <option>Pilih Data Tahun</option>
                    </select>
                </div>
            </div>
        @endif
        <div class="row g-4">

            {{-- LEFT INFO PANEL --}}
            <div class="col-lg-3">

                <div class="card card-forest shadow-lg mb-3 border-0">
                    <div class="card-body text-center">
                        <small>Penjualan Terbanyak</small>
                        <h6 class="fw-semibold mt-2">Per-2025</h6>

                        <h3 class="fw-bold mt-2">1200 pcs</h3>
                        <span class="badge badge-soft">
                            Meranti Terjual
                        </span>
                    </div>
                </div>


                <div class="card card-forest shadow-sm border-0">
                    <div class="card-body text-center">
                        <small>Rata-rata Penjualan</small>
                        <h6 class="fw-semibold mt-2">Per Bulan</h6>

                        <h3 class="fw-bold mt-2">123 pcs</h3>
                        <span class="badge badge-soft">
                            Meranti Terjual
                        </span>
                    </div>
                </div>


            </div>

            {{-- MAIN CHART --}}
            <div class="col-lg-9">
                <div class="card card-forest shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            Grafik Tren Penjualan - 2025
                        </h6>

                        <div class="chart-placeholder">
                            {{-- nanti Chart.js --}}
                            <span class="text-muted">Area Grafik Line Chart</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STOCK BAR CHART --}}
            <div class="col-12">
                <div class="card card-forest shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            Grafik Stok Kayu
                        </h6>

                        <div class="chart-placeholder">
                            {{-- nanti Chart.js --}}
                            <span class="text-muted">Area Grafik Bar Chart</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
