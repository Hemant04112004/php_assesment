<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #19253f;
            --muted: #687089;
            --line: #d9e2f2;
            --surface: #ffffff;
            --primary: #1f6feb;
            --primary-soft: rgba(31, 111, 235, 0.16);
            --teal: #00a8a8;
            --amber: #e7a239;
            --rose: #ef5c7d;
            --mint: #1db774;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Outfit", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(42rem 25rem at 4% 0%, rgba(31, 111, 235, 0.18), transparent),
                radial-gradient(25rem 20rem at 98% 22%, rgba(29, 183, 116, 0.16), transparent),
                linear-gradient(165deg, #eff4ff 0%, #f3f9ff 42%, #eef8f8 100%);
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            animation: enter 0.7s ease-out both;
        }

        .title {
            font-family: "Fraunces", serif;
            font-size: clamp(2rem, 3vw, 2.8rem);
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .subtitle {
            color: var(--muted);
            max-width: 52rem;
        }

        .panel {
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 1.1rem;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 1rem 2.4rem rgba(20, 33, 58, 0.1);
            backdrop-filter: blur(3px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .stat-card {
            border-radius: 0.95rem;
            border: 1px solid var(--line);
            background: #fff;
            padding: 0.85rem 0.95rem;
            box-shadow: 0 0.4rem 0.9rem rgba(20, 33, 58, 0.06);
        }

        .stat-label {
            font-size: 0.78rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .stat-value {
            margin-top: 0.15rem;
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-total .stat-value { color: var(--primary); }
        .stat-low .stat-value { color: var(--rose); }
        .stat-medium .stat-value { color: var(--amber); }
        .stat-high .stat-value { color: var(--mint); }

        .toolbar {
            display: grid;
            grid-template-columns: 180px 1fr auto auto;
            gap: 0.75rem;
        }

        .toolbar .form-select,
        .toolbar .form-control {
            border-radius: 0.75rem;
            border-color: var(--line);
        }

        .toolbar .form-select:focus,
        .toolbar .form-control:focus,
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem var(--primary-soft);
        }

        .btn-soft {
            border-radius: 0.75rem;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            font-weight: 600;
        }

        .btn-soft:hover {
            border-color: #b8c7e4;
            background: #f7faff;
        }

        .btn-main {
            border: none;
            border-radius: 0.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--teal));
            box-shadow: 0 0.65rem 1.2rem rgba(31, 111, 235, 0.24);
        }

        .form-panel {
            border: 1px solid var(--line);
            border-radius: 0.95rem;
            background: linear-gradient(180deg, #ffffff, #f8fbff);
        }

        .table-wrap {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid var(--line);
            background: #fff;
        }

        .table {
            margin: 0;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table thead th {
            background: #f4f8ff;
            border-bottom: 1px solid var(--line);
            color: #22314f;
            font-weight: 700;
            white-space: nowrap;
        }

        .table tfoot tr {
            background: #f1f6ff;
        }

        .sort-btn {
            all: unset;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .sort-btn i {
            font-size: 0.72rem;
            color: #7182a6;
        }

        .id-link {
            border: none;
            background: transparent;
            color: var(--primary);
            text-decoration: underline;
            text-underline-offset: 2px;
            font-weight: 600;
            padding: 0;
        }

        .money {
            text-align: right;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .row-actions {
            min-width: 88px;
        }

        .edit-actions {
            display: flex;
            gap: 0.35rem;
        }

        .input-sm {
            min-width: 84px;
        }

        .table-empty {
            color: var(--muted);
            text-align: center;
            padding: 1.6rem;
        }

        @keyframes enter {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .toolbar {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container py-4 py-md-5 page-wrap">
    <div class="row justify-content-center">
        <div class="col-xl-11">
            <div class="mb-3 mb-md-4">
                <h1 class="title">Inventory Dashboard</h1>
                <p class="subtitle mb-0">Overview your products, filter by duration, search quickly, export current results, and manage each row from the action menu.</p>
            </div>

            <div class="stats-grid mb-3 mb-md-4">
                <div class="stat-card stat-total">
                    <div class="stat-label">Total Products</div>
                    <div class="stat-value" id="stat-total-products">0</div>
                </div>
                <div class="stat-card stat-low">
                    <div class="stat-label">Low Stock (0-10)</div>
                    <div class="stat-value" id="stat-low-products">0</div>
                </div>
                <div class="stat-card stat-medium">
                    <div class="stat-label">Medium Stock (11-50)</div>
                    <div class="stat-value" id="stat-medium-products">0</div>
                </div>
                <div class="stat-card stat-high">
                    <div class="stat-label">High Stock (51+)</div>
                    <div class="stat-value" id="stat-high-products">0</div>
                </div>
            </div>

            <div class="panel p-3 p-md-4">
                <div id="alert-area" class="mb-3"></div>

                <div class="toolbar mb-3">
                    <select id="duration-filter" class="form-select" aria-label="Duration filter">
                        <option value="all">All Time</option>
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="90">Last 90 Days</option>
                    </select>

                    <input id="search-input" type="search" class="form-control" placeholder="Search by id, product, quantity, price or datetime">

                    <button id="export-btn" type="button" class="btn btn-soft">
                        <i class="bi bi-download me-1"></i> Export
                    </button>

                    <button id="toggle-form-btn" type="button" class="btn btn-main text-white" data-bs-toggle="collapse" data-bs-target="#add-product-panel" aria-expanded="false" aria-controls="add-product-panel">
                        <i class="bi bi-plus-lg me-1"></i> Add Product
                    </button>
                </div>

                <div id="add-product-panel" class="collapse mb-3">
                    <div class="form-panel p-3">
                        <form id="product-form" class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="product_name" class="form-label fw-semibold">Product name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" required maxlength="255" placeholder="Example: Wireless Keyboard">
                            </div>
                            <div class="col-md-3">
                                <label for="quantity_in_stock" class="form-label fw-semibold">Quantity in stock</label>
                                <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" min="0" step="1" required placeholder="0">
                            </div>
                            <div class="col-md-3">
                                <label for="price_per_item" class="form-label fw-semibold">Price per item</label>
                                <input type="number" class="form-control" id="price_per_item" name="price_per_item" min="0" step="0.01" required placeholder="0.00">
                            </div>
                            <div class="col-md-1 d-grid">
                                <button type="submit" class="btn btn-main text-white">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="products-table">
                            <thead>
                            <tr>
                                <th>
                                    <button type="button" class="sort-btn" data-sort="id">ID <i class="bi bi-arrow-down-up"></i></button>
                                </th>
                                <th>
                                    <button type="button" class="sort-btn" data-sort="product_name">Product name <i class="bi bi-arrow-down-up"></i></button>
                                </th>
                                <th>
                                    <button type="button" class="sort-btn" data-sort="quantity_in_stock">Quantity in stock <i class="bi bi-arrow-down-up"></i></button>
                                </th>
                                <th>
                                    <button type="button" class="sort-btn" data-sort="price_per_item">Price per item <i class="bi bi-arrow-down-up"></i></button>
                                </th>
                                <th>
                                    <button type="button" class="sort-btn" data-sort="datetime_submitted">Datetime submitted <i class="bi bi-arrow-down-up"></i></button>
                                </th>
                                <th class="text-end">
                                    <button type="button" class="sort-btn" data-sort="total_value_number">Total value number <i class="bi bi-arrow-down-up"></i></button>
                                </th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="products-body"></tbody>
                            <tfoot>
                            <tr class="fw-semibold">
                                <td colspan="5">Sum total</td>
                                <td id="grand-total" class="money">0.00</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-5">ID</dt>
                    <dd class="col-7" id="detail-id"></dd>

                    <dt class="col-5">Product name</dt>
                    <dd class="col-7" id="detail-name"></dd>

                    <dt class="col-5">Quantity in stock</dt>
                    <dd class="col-7" id="detail-qty"></dd>

                    <dt class="col-5">Price per item</dt>
                    <dd class="col-7" id="detail-price"></dd>

                    <dt class="col-5">Datetime submitted</dt>
                    <dd class="col-7" id="detail-datetime"></dd>

                    <dt class="col-5">Total value number</dt>
                    <dd class="col-7" id="detail-total"></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const form = document.getElementById('product-form');
    const bodyEl = document.getElementById('products-body');
    const totalEl = document.getElementById('grand-total');
    const alertArea = document.getElementById('alert-area');
    const durationFilterEl = document.getElementById('duration-filter');
    const searchInputEl = document.getElementById('search-input');
    const exportBtn = document.getElementById('export-btn');
    const addPanelEl = document.getElementById('add-product-panel');
    const productNameInput = document.getElementById('product_name');

    const totalProductsEl = document.getElementById('stat-total-products');
    const lowProductsEl = document.getElementById('stat-low-products');
    const mediumProductsEl = document.getElementById('stat-medium-products');
    const highProductsEl = document.getElementById('stat-high-products');

    const detailsModalEl = document.getElementById('productDetailsModal');
    const detailIdEl = document.getElementById('detail-id');
    const detailNameEl = document.getElementById('detail-name');
    const detailQtyEl = document.getElementById('detail-qty');
    const detailPriceEl = document.getElementById('detail-price');
    const detailDatetimeEl = document.getElementById('detail-datetime');
    const detailTotalEl = document.getElementById('detail-total');

    const detailsModal = new bootstrap.Modal(detailsModalEl);
    const state = {
        products: [],
        filteredProducts: [],
        duration: 'all',
        search: '',
        sortKey: 'datetime_submitted',
        sortDirection: 'desc'
    };

    function money(value) {
        return Number(value).toFixed(2);
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function parseDate(value) {
        if (!value) {
            return null;
        }

        const parsed = new Date(String(value).replace(' ', 'T'));
        return Number.isNaN(parsed.getTime()) ? null : parsed;
    }

    function shortId(value) {
        const input = String(value || '');
        return input.length > 8 ? input.slice(0, 8) : input;
    }

    function showAlert(message, type = 'success') {
        alertArea.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${escapeHtml(message)}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    }

    function updateSummaryCards(products) {
        const total = products.length;
        const low = products.filter((item) => Number(item.quantity_in_stock) <= 10).length;
        const medium = products.filter((item) => Number(item.quantity_in_stock) >= 11 && Number(item.quantity_in_stock) <= 50).length;
        const high = products.filter((item) => Number(item.quantity_in_stock) >= 51).length;

        totalProductsEl.textContent = String(total);
        lowProductsEl.textContent = String(low);
        mediumProductsEl.textContent = String(medium);
        highProductsEl.textContent = String(high);
    }

    function compareProducts(a, b) {
        const key = state.sortKey;
        const direction = state.sortDirection === 'asc' ? 1 : -1;

        let left = a[key];
        let right = b[key];

        if (key === 'quantity_in_stock' || key === 'price_per_item' || key === 'total_value_number') {
            left = Number(left);
            right = Number(right);
        } else if (key === 'datetime_submitted') {
            left = parseDate(left)?.getTime() || 0;
            right = parseDate(right)?.getTime() || 0;
        } else {
            left = String(left || '').toLowerCase();
            right = String(right || '').toLowerCase();
        }

        if (left < right) {
            return -1 * direction;
        }

        if (left > right) {
            return 1 * direction;
        }

        return 0;
    }

    function filterProducts() {
        const searchTerm = state.search.trim().toLowerCase();
        const days = Number(state.duration);
        const now = new Date();

        let rows = [...state.products];

        if (!Number.isNaN(days) && days > 0) {
            const threshold = new Date(now);
            threshold.setDate(now.getDate() - days);
            rows = rows.filter((item) => {
                const date = parseDate(item.datetime_submitted);
                return date && date >= threshold;
            });
        }

        if (searchTerm) {
            rows = rows.filter((item) => {
                const haystack = [
                    item.id,
                    item.product_name,
                    item.quantity_in_stock,
                    item.price_per_item,
                    item.datetime_submitted,
                    item.total_value_number
                ]
                    .map((value) => String(value || '').toLowerCase())
                    .join(' ');

                return haystack.includes(searchTerm);
            });
        }

        rows.sort(compareProducts);
        return rows;
    }

    function renderTable(products) {
        if (!products.length) {
            bodyEl.innerHTML = '<tr><td colspan="7" class="table-empty">No matching records found.</td></tr>';
            totalEl.textContent = money(0);
            return;
        }

        bodyEl.innerHTML = products.map((item) => {
            const safeId = escapeHtml(item.id);
            const safeName = escapeHtml(item.product_name);
            const safeDate = escapeHtml(item.datetime_submitted);
            const qty = Number(item.quantity_in_stock);
            const price = Number(item.price_per_item);
            const total = Number(item.total_value_number);

            return `
                <tr data-id="${safeId}">
                    <td>
                        <button type="button" class="id-link detail-link">${escapeHtml(shortId(item.id))}</button>
                    </td>
                    <td>
                        <span class="view-field" data-field="product_name">${safeName}</span>
                        <input class="form-control form-control-sm edit-field d-none" name="product_name" value="${safeName}">
                    </td>
                    <td>
                        <span class="view-field" data-field="quantity_in_stock">${qty}</span>
                        <input class="form-control form-control-sm edit-field d-none input-sm" type="number" name="quantity_in_stock" min="0" step="1" value="${qty}">
                    </td>
                    <td>
                        <span class="view-field" data-field="price_per_item">${money(price)}</span>
                        <input class="form-control form-control-sm edit-field d-none input-sm" type="number" name="price_per_item" min="0" step="0.01" value="${money(price)}">
                    </td>
                    <td>${safeDate}</td>
                    <td class="money">${money(total)}</td>
                    <td>
                        <div class="row-actions view-actions">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-soft" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item view-action" type="button">View</button></li>
                                    <li><button class="dropdown-item edit-action" type="button">Edit</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="edit-actions d-none">
                            <button class="btn btn-sm btn-success save-btn" type="button">Save</button>
                            <button class="btn btn-sm btn-outline-secondary cancel-btn" type="button">Cancel</button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        const grandTotal = products.reduce((carry, item) => carry + Number(item.total_value_number || 0), 0);
        totalEl.textContent = money(grandTotal);
    }

    function applyFiltersAndRender() {
        state.filteredProducts = filterProducts();
        updateSummaryCards(state.filteredProducts);
        renderTable(state.filteredProducts);
    }

    function openDetailsModal(id) {
        const item = state.products.find((product) => product.id === id);
        if (!item) {
            return;
        }

        detailIdEl.textContent = item.id;
        detailNameEl.textContent = item.product_name;
        detailQtyEl.textContent = String(item.quantity_in_stock);
        detailPriceEl.textContent = money(item.price_per_item);
        detailDatetimeEl.textContent = item.datetime_submitted;
        detailTotalEl.textContent = money(item.total_value_number);

        detailsModal.show();
    }

    function toggleEditMode(row, editing) {
        row.querySelectorAll('.view-field').forEach((el) => el.classList.toggle('d-none', editing));
        row.querySelectorAll('.edit-field').forEach((el) => el.classList.toggle('d-none', !editing));
        row.querySelector('.view-actions').classList.toggle('d-none', editing);
        row.querySelector('.edit-actions').classList.toggle('d-none', !editing);
    }

    async function fetchProducts() {
        const response = await fetch('/products', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Could not load products.');
        }

        const payload = await response.json();
        state.products = payload.products || [];
        applyFiltersAndRender();
    }

    async function submitForm(event) {
        event.preventDefault();

        const data = new FormData(form);
        const payload = Object.fromEntries(data.entries());

        const response = await fetch('/products', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            const failed = await response.json();
            throw new Error(failed.message || 'Validation failed.');
        }

        form.reset();
        bootstrap.Collapse.getOrCreateInstance(addPanelEl).hide();
        showAlert('Product saved successfully.', 'success');
        await fetchProducts();
    }

    async function saveRow(row) {
        const id = row.dataset.id;
        const payload = {};

        row.querySelectorAll('.edit-field').forEach((input) => {
            payload[input.name] = input.value;
        });

        const response = await fetch(`/products/${id}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            const failed = await response.json();
            throw new Error(failed.message || 'Update failed.');
        }

        showAlert('Product updated successfully.', 'success');
        await fetchProducts();
    }

    function exportCsv() {
        const rows = state.filteredProducts;
        if (!rows.length) {
            showAlert('No records available to export.', 'warning');
            return;
        }

        const headers = ['id', 'product_name', 'quantity_in_stock', 'price_per_item', 'datetime_submitted', 'total_value_number'];
        const lines = [headers.join(',')];

        rows.forEach((item) => {
            const values = headers.map((key) => {
                const raw = String(item[key] ?? '');
                return `"${raw.replaceAll('"', '""')}"`;
            });

            lines.push(values.join(','));
        });

        const csv = lines.join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `products_export_${Date.now()}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }

    form.addEventListener('submit', async (event) => {
        try {
            await submitForm(event);
        } catch (error) {
            showAlert(error.message, 'danger');
        }
    });

    durationFilterEl.addEventListener('change', () => {
        state.duration = durationFilterEl.value;
        applyFiltersAndRender();
    });

    searchInputEl.addEventListener('input', () => {
        state.search = searchInputEl.value;
        applyFiltersAndRender();
    });

    exportBtn.addEventListener('click', exportCsv);

    addPanelEl.addEventListener('shown.bs.collapse', () => {
        productNameInput.focus();
    });

    document.querySelectorAll('.sort-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const key = btn.dataset.sort;
            if (state.sortKey === key) {
                state.sortDirection = state.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                state.sortKey = key;
                state.sortDirection = 'asc';
            }

            applyFiltersAndRender();
        });
    });

    bodyEl.addEventListener('click', async (event) => {
        const row = event.target.closest('tr[data-id]');
        if (!row) {
            return;
        }

        const id = row.dataset.id;

        if (event.target.closest('.detail-link') || event.target.closest('.view-action')) {
            openDetailsModal(id);
            return;
        }

        if (event.target.closest('.edit-action')) {
            toggleEditMode(row, true);
            return;
        }

        if (event.target.closest('.cancel-btn')) {
            await fetchProducts();
            return;
        }

        if (event.target.closest('.save-btn')) {
            try {
                await saveRow(row);
            } catch (error) {
                showAlert(error.message, 'danger');
            }
        }
    });

    fetchProducts().catch((error) => showAlert(error.message, 'danger'));
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
