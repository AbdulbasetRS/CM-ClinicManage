@extends('admin.structure')

@section('title', __('admin.visit_statistics'))

@section('content')
    <div class="container">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __('admin.visit_statistics') }}</h1>
        </div>

        {{-- Filters --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.filter_data') }}</h6>
            </div>
            <div class="card-body">
                <form id="filterForm" class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label for="start_date" class="form-label">{{ __('admin.from_date') }}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="end_date" class="form-label">{{ __('admin.to_date') }}</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ now()->endOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">{{ __('admin.status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ __('admin.all') }}</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}">
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> {{ __('admin.view') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row mb-4">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    {{ __('admin.total_visits') }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalVisits">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.visits_chart') }}</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="position: relative; height: 400px; width: 100%;">
                    <canvas id="visitsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main.script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            let visitsChart = null;

            function loadChartData() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                const status = $('#status').val();

                $.ajax({
                    url: "{{ route('admin.visits.statistics') }}",
                    type: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        status: status
                    },
                    success: function(response) {
                        // Update Summary
                        $('#totalVisits').text(response.summary.total_visits);

                        // Update Chart
                        const ctx = document.getElementById('visitsChart').getContext('2d');

                        if (visitsChart) {
                            visitsChart.destroy();
                        }

                        visitsChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: response.labels,
                                datasets: response.datasets
                            },
                            options: {
                                maintainAspectRatio: false,
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 25,
                                        top: 25,
                                        bottom: 0
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            maxTicksLimit: 7
                                        }
                                    },
                                    y: {
                                        ticks: {
                                            maxTicksLimit: 5,
                                            padding: 10,
                                            callback: function(value, index, values) {
                                                return value.toLocaleString();
                                            }
                                        },
                                        grid: {
                                            color: "rgb(234, 236, 244)",
                                            zeroLineColor: "rgb(234, 236, 244)",
                                            drawBorder: false,
                                            borderDash: [2],
                                            zeroLineBorderDash: [2]
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    },
                                    tooltip: {
                                        backgroundColor: "rgb(255,255,255)",
                                        bodyColor: "#858796",
                                        titleMarginBottom: 10,
                                        titleColor: '#6e707e',
                                        titleFont: {
                                            size: 14
                                        },
                                        borderColor: '#dddfeb',
                                        borderWidth: 1,
                                        xPadding: 15,
                                        yPadding: 15,
                                        displayColors: false,
                                        intersect: false,
                                        mode: 'index',
                                        caretPadding: 10,
                                        callbacks: {
                                            label: function(context) {
                                                var label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                if (context.parsed.y !== null) {
                                                    label += context.parsed.y
                                                        .toLocaleString();
                                                }
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    },
                    error: function(xhr) {
                        console.error("Error fetching chart data:", xhr);
                        alert("حدث خطأ أثناء تحميل البيانات");
                    }
                });
            }

            // Initial Load
            loadChartData();

            // Filter Form Submit
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                loadChartData();
            });
        });
    </script>
@endsection
