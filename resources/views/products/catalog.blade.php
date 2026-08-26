<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <!-- Incluimos Bootstrap de forma rápida para dar estilo al listado y a la paginación -->
    <link href="https://jsdelivr.net" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <h1 class="mb-4">Nuestro Catálogo</h1>

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Rejilla de productos -->
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2">{{ $product->category->name ?? 'Sin categoría' }}</span>
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($product->description, 80) }}</p>
                        <h6 class="text-primary font-bold">${{ number_format($product->price, 2) }}</h6>
                    </div>
                    <div class="card-footer bg-white border-top-0 pb-3">
                        <p class="card-text small text-secondary">Disponibles: {{ $product->stock }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center">No hay productos disponibles en este momento.</p>
            </div>
            @endforelse
        </div>

        <!-- Enlaces de navegación entre páginas (Paginador automático de Laravel) -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    </div>
</body>

</html>