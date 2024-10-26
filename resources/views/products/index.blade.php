<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Inter, serif;
        }
    </style>
</head>
<body>
<main>
    <div class="container py-3">
        <header class="pb-3 mb-3 border-bottom">
            <a href="/" class="d-flex align-items-center text-body-emphasis text-decoration-none">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z"/>
                </svg>
                <span class="fs-4 fw-medium ms-2">Product List</span>
            </a>
        </header>

        <div class="card mb-5">
            <div class="card-body">
                <form action="{{ route('products.index') }}" method="get">
                    <div class="mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="search" class="form-control" id="search" name="search"
                               placeholder="Type a keyword against product name, description, category and keywords"
                               value="{{ request()->get('search') }}">
                    </div>
                    <div class="d-sm-flex justify-content-between">
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="search_types[]" id="search_type_exact" value="exact-match"
                                       {{ in_array('exact-match', request()->get('search_types', $searchMethods)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="search_type_exact">Exact Match</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="search_types[]" id="search_type_partial" value="partial-match"
                                       {{ in_array('partial-match', request()->get('search_types', $searchMethods)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="search_type_partial">Partial Match</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="search_types[]" id="search_type_phonetic" value="phonetic"
                                    {{ in_array('phonetic', request()->get('search_types', $searchMethods)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="search_type_phonetic">Phonetic</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="search_types[]" id="search_type_fulltext" value="fulltext"
                                    {{ in_array('fulltext', request()->get('search_types', $searchMethods)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="search_type_fulltext">Full Text</label>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="/" class="btn btn-light">Reset</a>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row align-items-md-stretch mb-3" style="min-height: calc(100vh - 450px)">
            @forelse($products as $index => $product)
                <div class="col-md-3 mb-3">
                    <div class="h-100 p-3 bg-body-tertiary border rounded-3">
                        <h1 class="h5 fw-medium">{{ $product->product_name }}</h1>
                        <p class="text-muted">{{ $product->category }}</p>
                        <p>{{ $product->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-success">
                                IDR {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="fw-bold border rounded px-2 py-1 text-center" style="min-width: 50px;">
                                {{ number_format($product->stock, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            @foreach(collect(explode(',', $product->keywords)) as $keyword)
                                <span class="bg-success-subtle border border-primary-subtle text-primary-emphasis rounded small mb-2 px-2 d-inline-flex">
                                    {{ ucwords($keyword) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="col mb-3">
                    <p class="text-muted">No product available</p>
                </div>
            @endforelse
        </div>
        {{ $products->links() }}

        <footer class="pt-3 mt-4 text-body-secondary border-top small">
            &copy; {{ date('Y') }} <strong>Angga Ari Wijaya</strong> all rights reserved
        </footer>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
