<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>

    <!-- KPI Summary Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-muted">Total de Usuarios</h5>
                        <h2 class="display-6 fw-bold" id="kpi-users">Cargando...</h2>
                    </div>
                    <i class="fas fa-users card-icon text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-muted">Ventas Mes</h5>
                        <h2 class="display-6 fw-bold" id="kpi-sales-month">$--</h2>
                    </div>
                    <i class="fas fa-dollar-sign card-icon text-warning"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-muted">Ganancia Mes</h5>
                        <h2 class="display-6 fw-bold text-success" id="kpi-profit-month">$--</h2>
                    </div>
                    <i class="fas fa-chart-line card-icon text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-muted">Ganancia Total</h5>
                        <h2 class="display-6 fw-bold text-success" id="kpi-profit-total">$--</h2>
                    </div>
                    <i class="fas fa-wallet card-icon text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Top 5 Zonas de Compra</h5>
                </div>
                <div class="card-body">
                    <canvas id="zonesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Top 5 Productos Más Vendidos</h5>
                </div>
                <div class="card-body">
                    <canvas id="productsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Discount Codes Row -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Uso de Códigos de Descuento</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre del Código</th>
                                    <th class="text-center">Veces Usado</th>
                                    <th class="text-end">Monto Total Descontado</th>
                                </tr>
                            </thead>
                            <tbody id="discounts-table-body">
                                <tr>
                                    <td colspan="3" class="text-center p-3">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Tables Row -->
    <div class="row g-4">
        <!-- Recurrencia de Clientes -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recurrencia de Clientes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th class="text-center">Pedidos</th>
                                </tr>
                            </thead>
                            <tbody id="recurrence-table-body">
                                <tr>
                                    <td colspan="2" class="text-center p-3">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Más Vendidos (Detalle) -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Desglose de Productos Más Vendidos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th class="text-center">Volumen</th>
                                    <th class="text-end">Ingresos Generados</th>
                                </tr>
                            </thead>
                            <tbody id="products-table-body">
                                <tr>
                                    <td colspan="4" class="text-center p-3">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zonas De Compra (Detalle) -->
    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Desglose por Zonas de Compra</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Colonia / Zona</th>
                                    <th class="text-center">Total Pedidos</th>
                                    <th class="text-center">% del Total</th>
                                </tr>
                            </thead>
                            <tbody id="zones-table-body">
                                <tr>
                                    <td colspan="3" class="text-center p-3">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchDashboardSummary();
        fetchRecurrence();
        fetchTopProducts();
        fetchZones();
        fetchDiscountStats();
    });

    function fetchDashboardSummary() {
        fetch('../../back/admin_analytics.php?action=dashboard_summary')
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('kpi-users').innerText = data.data.users;

                    const formatCurrency = (val) => '$' + parseFloat(val).toLocaleString('es-MX', { minimumFractionDigits: 2 });

                    document.getElementById('kpi-sales-month').innerText = formatCurrency(data.data.monthly_sales);
                    document.getElementById('kpi-profit-month').innerText = formatCurrency(data.data.monthly_profit);
                    document.getElementById('kpi-profit-total').innerText = formatCurrency(data.data.total_profit);
                }
            });
    }

    function fetchRecurrence() {
        fetch('../../back/admin_analytics.php?action=recurrence')
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('recurrence-table-body');
                tbody.innerHTML = '';
                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(user => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${user.nombre} ${user.apellido_paterno}</td>
                            <td class="text-center fw-bold">${user.total_pedidos}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                    // Quick update KPI users count (approximation based on return or separate query)
                    // document.getElementById('kpi-users').innerText = data.data.length + '+';
                } else {
                    tbody.innerHTML = '<tr><td colspan="2" class="text-center">No hay datos</td></tr>';
                }
            });
    }

    function fetchTopProducts() {
        fetch('../../back/admin_analytics.php?action=top_products')
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('products-table-body');
                tbody.innerHTML = '';

                const labels = [];
                const values = [];
                const colors = ['#599332', '#4EAE3E', '#8BC34A', '#CDDC39', '#FFEB3B'];

                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach((p, index) => {
                        // Table Layout
                        const tr = document.createElement('tr');
                        const revenue = parseFloat(p.total_ingresos);
                        tr.innerHTML = `
                            <td>${p.nombre}</td>
                            <td><span class="badge bg-secondary">${p.categoria || 'Sin Categoría'}</span></td>
                            <td class="text-center fw-bold">${p.total_vendido}</td>
                            <td class="text-end">$${revenue.toLocaleString('es-MX', { minimumFractionDigits: 2 })}</td>
                        `;
                        tbody.appendChild(tr);

                        // Chart Data (Top 5)
                        if (index < 5) {
                            labels.push(p.nombre);
                            values.push(p.total_vendido);
                        }
                    });

                    // Render Chart
                    const ctx = document.getElementById('productsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Unidades Vendidas',
                                data: values,
                                backgroundColor: '#599332',
                                borderColor: '#2d4c48',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true } }
                        }
                    });

                    // Update Top KPI
                    document.getElementById('kpi-products').innerText = data.data.length; // Just showing count of top products returned
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay datos</td></tr>';
                }
            });
    }

    function fetchZones() {
        fetch('../../back/admin_analytics.php?action=zones')
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('zones-table-body');
                tbody.innerHTML = '';

                const labels = [];
                const values = [];
                const colors = ['#E67E22', '#F39C12', '#F1C40F', '#D35400', '#E74C3C'];

                let totalOrders = 0;
                if (data.status === 'success' && data.data.length > 0) {
                    // Calculate Total for Percentage
                    data.data.forEach(z => totalOrders += parseInt(z.total));

                    data.data.forEach((z, index) => {
                        // Table Layout
                        const tr = document.createElement('tr');
                        const percentage = ((z.total / totalOrders) * 100).toFixed(1);

                        tr.innerHTML = `
                            <td>${z.colonia}</td>
                            <td class="text-center fw-bold">${z.total}</td>
                            <td class="text-center">${percentage}%</td>
                        `;
                        tbody.appendChild(tr);

                        // Chart Data (Top 5)
                        if (index < 5) {
                            labels.push(z.colonia);
                            values.push(z.total);
                        }
                    });

                    // Render Chart
                    const ctx = document.getElementById('zonesChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: values,
                                backgroundColor: colors,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'right',
                                }
                            }
                        }
                    });

                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center">No hay datos</td></tr>';
                }
            });
    }

    function fetchDiscountStats() {
        fetch('../../back/admin_analytics.php?action=discount_stats')
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('discounts-table-body');
                tbody.innerHTML = '';
                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(d => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="fw-bold text-primary">${d.descuento_codigo}</td>
                            <td class="text-center fw-bold">${d.usos}</td>
                            <td class="text-end text-success">$${parseFloat(d.total_monto).toLocaleString('es-MX', { minimumFractionDigits: 2 })}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center">No hay códigos usados aún</td></tr>';
                }
            })
            .catch(err => console.error('Error loading discounts:', err));
    }
</script>