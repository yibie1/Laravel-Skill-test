<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Product Form</h1>
        <form id="productForm">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity in Stock</label>
                <input type="number" class="form-control" id="quantity" name="quantity">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price per Item</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h2 class="mt-5">Product List</h2>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price per Item</th>
                    <th>Date Submitted</th>
                    <th>Total Value</th>
                </tr>
            </thead>
            <tbody id="productList"></tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Total Sum</th>
                    <th id="totalSum"></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            // Fetch all products when the page loads
            fetchProducts();

            // Handle form submission
            $('#productForm').on('submit', function (e) {
                e.preventDefault();

                let formData = {
                    name: $('#name').val(),
                    quantity: $('#quantity').val(),
                    price: $('#price').val()
                };

                $.ajax({
                    type: 'POST',
                    url: '/store',
                    data: formData,
                    success: function (response) {
                        alert(response.message);
                        fetchProducts();  // Refresh the product list
                        $('#productForm')[0].reset();
                    },
                    error: function (error) {
                        alert('Error occurred while submitting the form.');
                    }
                });
            });

            function fetchProducts() {
                $.ajax({
                    type: 'GET',
                    url: '/products',
                    success: function (response) {
                        let products = response.products;
                        let sumTotal = response.sumTotal;

                        let rows = '';
                        products.forEach(function (product) {
                            rows += `
                                <tr>
                                    <td>${product.name}</td>
                                    <td>${product.quantity}</td>
                                    <td>${product.price}</td>
                                    <td>${product.created_at}</td>
                                    <td>${(product.quantity * product.price).toFixed(2)}</td>
                                </tr>
                            `;
                        });

                        $('#productList').html(rows);
                        $('#totalSum').text(sumTotal.toFixed(2));
                    }
                });
            }
        });
    </script>
</body>
</html>
