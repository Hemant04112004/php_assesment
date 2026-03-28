<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1a2743;
            --muted: #68708a;
            --line: #d6dff0;
            --primary: #1f6feb;
            --teal: #00a8a8;
            --focus: rgba(31, 111, 235, 0.16);
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Outfit", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(36rem 20rem at 0% 0%, rgba(31, 111, 235, 0.18), transparent),
                linear-gradient(160deg, #eff4ff 0%, #f4f9ff 42%, #edf7f8 100%);
        }

        .title {
            font-family: "Fraunces", serif;
            font-size: clamp(1.85rem, 3vw, 2.4rem);
            letter-spacing: -0.02em;
        }

        .subtitle {
            color: var(--muted);
        }

        .panel {
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 1.1rem;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 1rem 2.4rem rgba(20, 33, 58, 0.1);
            backdrop-filter: blur(2px);
        }

        .form-label {
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border-radius: 0.75rem;
            border-color: var(--line);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem var(--focus);
        }

        .btn-main {
            border: none;
            border-radius: 0.75rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--teal));
            box-shadow: 0 0.65rem 1.2rem rgba(31, 111, 235, 0.24);
        }
    </style>
</head>
<body>
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="title mb-1">Add Product</h1>
                    <p class="subtitle mb-0">Enter product details, choose category, and select payment method.</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>

            <div class="panel p-4 p-md-5">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('products.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label for="product_name" class="form-label">Product name</label>
                        <input id="product_name" name="product_name" type="text" class="form-control" value="{{ old('product_name') }}" maxlength="255" required>
                    </div>

                    <div class="col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="" selected disabled>Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="quantity_in_stock" class="form-label">Quantity in stock</label>
                        <input id="quantity_in_stock" name="quantity_in_stock" type="number" class="form-control" value="{{ old('quantity_in_stock') }}" min="0" step="1" required>
                    </div>

                    <div class="col-md-6">
                        <label for="price_per_item" class="form-label">Price per item</label>
                        <input id="price_per_item" name="price_per_item" type="number" class="form-control" value="{{ old('price_per_item') }}" min="0" step="0.01" required>
                    </div>

                    <div class="col-md-6">
                        <label for="payment_method" class="form-label">Payment method</label>
                        <select id="payment_method" name="payment_method" class="form-select" required>
                            <option value="" selected disabled>Select payment method</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-main px-4">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
