@extends('layouts.app')

@section('title', 'Products List')

@section('content')

<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); 
    }

    #productDetailModal img {
        height: 250px;
        width: 250px;
        object-fit: cover;
    }

    .search-bar {
        max-width: 600px;
        margin: 0 auto 10px; 
    }

    .search-bar input {
        border-radius: 25px;
        padding: 10px 20px;
        font-size: 1rem;
        border: 1px solid #ccc;
        width: 100%;
    }

    .search-bar input:focus {
        border-color: #007bff;
        outline: none;
    }

    .filter-section {
        text-align: center;
        margin-bottom: 20px; 
    }

    .genre-buttons {
        display: flex;
        justify-content: center;
        gap: 10px; 
    }

    .genre-buttons a {
        border-radius: 25px;
    }

    
.modal-content {
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.modal-header {
    border-bottom: none;
    border-radius: 15px 15px 0 0;
}

.modal-body {
    font-family: 'Arial', sans-serif;
    text-align: center;
    padding: 20px;
}

.modal-footer {
    border-top: none;
    justify-content: center;
}

.img-fluid {
    max-height: 300px;
    object-fit: contain;
}

</style>

<div class="container py-5">
    <h1 class="text-center mb-4" style="font-family: 'Georgia', serif; font-size: 2.5rem; color: #2c3e50;">
        вσσк ℓιѕт 📓
    </h1>

    @if(session('success'))
            <div class="modal fade" id="notificationSuccessModal" tabindex="-1" role="dialog" aria-labelledby="notificationSuccessModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="notificationSuccessModalLabel">Success</h5>
                        </div>
                        <div class="modal-body">
                            {{ session('success') }}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="modal fade" id="notificationErrorModal" tabindex="-1" role="dialog" aria-labelledby="notificationErrorModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="notificationErrorModalLabel">Error</h5>
                        </div>
                        <div class="modal-body">
                            {{ session('error') }}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    
    <div class="filter-section">
       
        <div class="search-bar">
            <form action="{{ route('products.index') }}" method="GET">
                <input type="text" name="search" class="form-control" placeholder="Search for a product..." value="{{ request()->search }}">
            </form>
        </div>

        <div class="genre-buttons">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">All</a>
            <a href="{{ route('products.index', ['genre' => 'Sci-Fi']) }}" class="btn btn-outline-success">Sci-Fi</a>
            <a href="{{ route('products.index', ['genre' => 'Romance']) }}" class="btn btn-outline-danger">Romance</a>
            <a href="{{ route('products.index', ['genre' => 'Horror']) }}" class="btn btn-outline-dark">Horror</a>
            <a href="{{ route('products.index', ['genre' => 'Comedy']) }}" class="btn btn-outline-warning">Comedy</a>
            <a href="{{ route('products.index', ['genre' => 'Fantasy']) }}" class="btn btn-outline-info">Fantasy</a>
            <a href="{{ route('products.index', ['genre' => 'Action']) }}" class="btn btn-outline-secondary">Action</a>
        </div>
    </div>

    <!-- Notification Modals -->
    @if(session('success'))
        <div class="modal fade" id="notificationSuccessModal" tabindex="-1" role="dialog" aria-labelledby="notificationSuccessModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationSuccessModalLabel">Success</h5>
                    </div>
                    <div class="modal-body">
                        {{ session('success') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="modal fade" id="notificationErrorModal" tabindex="-1" role="dialog" aria-labelledby="notificationErrorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationErrorModalLabel">Error</h5>
                    </div>
                    <div class="modal-body">
                        {{ session('error') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->check() && auth()->user()->role->name === 'Admin')
        <div class="mb-4 text-end">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Product
            </a>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm product-card" style="border-radius: 15px;">
                <img src="{{ $product->image ? asset('images/' . $product->image) : 'https://via.placeholder.com/150x150' }}" 
                 class="card-img-top" alt="{{ $product->product_name }}" 
                 style="height: 250px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;" 
                 data-bs-toggle="modal" data-bs-target="#productDetailModal" 
                 onclick="showProductDetails('{{ $product->product_name }}', '{{ $product->description }}', '{{ $product->image ? asset('images/' . $product->image) : 'https://via.placeholder.com/150x150' }}', {{ $product->price }}, {{ $product->discount ?? 0 }}, {{ $product->stock }})">
                <div class="card-body">
                <h5 class="card-title text-truncate" style="font-size: 1.2rem; color: #333; font-weight: bold;">
                {{ $product->product_name }}
                </h5>
                        <p class="card-text" style="color: #555; font-size: 1rem;">{{ Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 1rem;">
                                @if ($product->discount)
                                    <span style="text-decoration: line-through;">${{ number_format($product->price, 2) }}</span>
                                    <span class="text-success">Now ${{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                                @else
                                    ${{ number_format($product->price, 2) }}
                                @endif
                            </span>
                            <span class="badge" 
                            style="
                             @if ($product->genre === 'Sci-Fi') background-color: #28a745; 
                             @elseif ($product->genre === 'Romance') background-color: #dc3545;
                             @elseif ($product->genre === 'Horror') background-color: #343a40;
                             @elseif ($product->genre === 'Comedy') background-color: #ffc107;
                             @elseif ($product->genre === 'Fantasy') background-color: #17a2b8;
                             @elseif ($product->genre === 'Action') background-color: #6c757d;
                             @else background-color: #007bff; 
                             @endif
                             color: white;">
                             {{ $product->genre }}
                             </span>
                         
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <div class="btn-group" role="group" aria-label="Product Actions">
                        @if($product->stock > 0)
                        <a href="{{ route('product.buy', $product->id) }}" class="btn btn-success btn-sm">Buy</a>
                        @else
                       <button type="button" class="btn btn-secondary btn-sm" onclick="showOutOfStockModal()">
                       <i class="bi bi-cart"></i> Out of Stock
                       </button>
                       @endif

                      @if(auth()->user()->role->name === 'Admin')
                     <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm mx-2">
                     <i class="bi bi-brush"></i> Edit
                     </a>

                    <button type="button" class="btn btn-danger btn-sm mx-2" onclick="showDeleteModal('{{ route('products.destroy', $product) }}')">
                    <i class="bi bi-trash"></i> Delete
                    </button>
                    @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $products->links() }}
</div>

<div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productDetailModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="productImage" src="" alt="" class="img-fluid mb-3">
                <h5 id="productName"></h5>
                <p id="productDescription"></p>
                <p id="productPrice"></p>
                <p>Stock: <span id="productStock"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="outOfStockModal" tabindex="-1" role="dialog" aria-labelledby="outOfStockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="outOfStockModalLabel">Out of Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                This product is currently out of stock.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showProductDetails(name, description, image, price, discount, stock) {
        document.getElementById('productName').textContent = name;
        document.getElementById('productDescription').textContent = description;
        document.getElementById('productImage').src = image;
        document.getElementById('productPrice').textContent = discount > 0 ? '$' + (price * (1 - discount / 100)).toFixed(2) : '$' + price.toFixed(2);
        document.getElementById('productStock').textContent = stock;
    }

    function showOutOfStockModal() {
        var modal = new bootstrap.Modal(document.getElementById('outOfStockModal'));
        modal.show();
    }

    function hideOutOfStockModal() {
        var modal = bootstrap.Modal.getInstance(document.getElementById('outOfStockModal'));
        modal.hide();
    }

    function showDeleteModal(url) {
        document.getElementById('deleteForm').action = url;
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
           
            if (document.getElementById('notificationSuccessModal')) {
                var successModal = new bootstrap.Modal(document.getElementById('notificationSuccessModal'));
                successModal.show();
            }

            if (document.getElementById('notificationErrorModal')) {
                var errorModal = new bootstrap.Modal(document.getElementById('notificationErrorModal'));
                errorModal.show();
            }
        });
    </script>

@endsection