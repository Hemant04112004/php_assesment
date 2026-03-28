<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PHP Skills Test - Inventory Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(145deg, #f9fbfd, #eef4ff);
            min-height: 100vh;
        }

        .page-card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.75rem 2.5rem rgba(9, 30, 66, 0.08);
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .money {
            text-align: right;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="page-card card">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 mb-3">Inventory Submission</h1>
                    <p class="text-muted mb-4">Submit a product and track the running total value.</p>

                    <div id="alert-area" class="mb-3"></div>

                    <form id="product-form" class="row g-3">
                        <div class="col-md-5">
                            <label for="product_name" class="form-label">Product name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required maxlength="255">
                        </div>
                        <div class="col-md-3">
                            <label for="quantity_in_stock" class="form-label">Quantity in stock</label>
                            <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" min="0" step="1" required>
                        </div>
                        <div class="col-md-3">
                            <label for="price_per_item" class="form-label">Price per item</label>
                            <input type="number" class="form-control" id="price_per_item" name="price_per_item" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-1 d-grid align-items-end">
                            <button type="submit" class="btn btn-primary mt-md-4">Add</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle" id="products-table">
                            <thead class="table-light">
                            <tr>
                                <th>Product name</th>
                                <th>Quantity in stock</th>
                                <th>Price per item</th>
                                <th>Datetime submitted</th>
                                <th class="text-end">Total value number</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="products-body"></tbody>
                            <tfoot>
                            <tr class="table-secondary fw-semibold">
                                <td colspan="4">Sum total</td>
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

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const form = document.getElementById('product-form');
    const bodyEl = document.getElementById('products-body');
    const totalEl = document.getElementById('grand-total');
    const alertArea = document.getElementById('alert-area');

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

    function showAlert(message, type = 'success') {
        alertArea.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    }

    function renderRows(products, grandTotal) {
        if (!products.length) {
            bodyEl.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No submissions yet.</td></tr>';
            totalEl.textContent = money(0);
            return;
        }

        bodyEl.innerHTML = products.map((item) => {
            const safeName = escapeHtml(item.product_name);

            return `
                <tr data-id="${item.id}">
                    <td>
                        <span class="view-field">${safeName}</span>
                        <input class="form-control form-control-sm edit-field d-none" name="product_name" value="${safeName}">
                    </td>
                    <td>
                        <span class="view-field">${item.quantity_in_stock}</span>
                        <input class="form-control form-control-sm edit-field d-none" type="number" name="quantity_in_stock" min="0" step="1" value="${item.quantity_in_stock}">
                    </td>
                    <td>
                        <span class="view-field">${money(item.price_per_item)}</span>
                        <input class="form-control form-control-sm edit-field d-none" type="number" name="price_per_item" min="0" step="0.01" value="${money(item.price_per_item)}">
                    </td>
                    <td>${item.datetime_submitted}</td>
                    <td class="money">${money(item.total_value_number)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary edit-btn">Edit</button>
                        <button class="btn btn-sm btn-success save-btn d-none">Save</button>
                        <button class="btn btn-sm btn-outline-secondary cancel-btn d-none">Cancel</button>
                    </td>
                </tr>
            `;
        }).join('');

        totalEl.textContent = money(grandTotal);
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
        renderRows(payload.products, payload.grand_total);
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
            const message = failed.message || 'Validation failed.';
            throw new Error(message);
        }

        form.reset();
        showAlert('Product saved.', 'success');
        await fetchProducts();
    }

    function toggleEditMode(row, editing) {
        row.querySelectorAll('.view-field').forEach((el) => el.classList.toggle('d-none', editing));
        row.querySelectorAll('.edit-field').forEach((el) => el.classList.toggle('d-none', !editing));
        row.querySelector('.edit-btn').classList.toggle('d-none', editing);
        row.querySelector('.save-btn').classList.toggle('d-none', !editing);
        row.querySelector('.cancel-btn').classList.toggle('d-none', !editing);
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
            const message = failed.message || 'Update failed.';
            throw new Error(message);
        }

        showAlert('Product updated.', 'success');
        await fetchProducts();
    }

    form.addEventListener('submit', async (event) => {
        try {
            await submitForm(event);
        } catch (error) {
            showAlert(error.message, 'danger');
        }
    });

    bodyEl.addEventListener('click', async (event) => {
        const row = event.target.closest('tr[data-id]');
        if (!row) {
            return;
        }

        if (event.target.classList.contains('edit-btn')) {
            toggleEditMode(row, true);
            return;
        }

        if (event.target.classList.contains('cancel-btn')) {
            await fetchProducts();
            return;
        }

        if (event.target.classList.contains('save-btn')) {
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
