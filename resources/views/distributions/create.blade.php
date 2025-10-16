<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Distribusi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
  .container {
    min-height: 100vh;
  }
</style>
<body class="bg-light">

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Distribusi</h2>
    <a href="{{ url('/distributions') }}" class="btn btn-secondary">← Kembali</a>
  </div>

  <div class="card container-body">
    <div class="card-body ">
      <!-- Form Distribusi -->
      <form id="form-distribution">
        <div class="mb-3">
          <label for="barista" class="form-label">Pilih Barista</label>
          <select id="barista" name="barista_id" class="form-select">
            <option value="">-- Pilih Barista --</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="notes" class="form-label">Catatan (Opsional)</label>
          <textarea id="notes" name="notes" class="form-control"></textarea>
        </div>
      </form>

      <hr class="my-4">

      <!-- Produk Sementara -->
      <h5>Produk Sementara</h5>
      <div class="row g-3 mb-3">
        <div class="col-md-5">
          <select id="productSelect" class="form-select">
            <option value="">-- Pilih Produk --</option>
          </select>
        </div>
        <div class="col-md-3">
          <input type="number" value="1" id="productQty" class="form-control" placeholder="Qty">
        </div>
        <div class="col-md-2">
          <button type="button" id="addProductBtn" class="btn btn-primary w-100">Tambah</button>
        </div>
      </div>

      <table class="table table-bordered" id="temporaryTable">
        <thead>
          <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

      <button type="button" id="submitDistribution" class="btn btn-success mt-3">Submit Distribusi</button>

      <div class="text-end mt-3">
        <p><strong>Total Qty:</strong> <span id="totalQty">0</span></p>
        <p><strong>Estimasi Penjualan:</strong> Rp <span id="estimatedResult">0</span></p>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const getBaristaForOptions = async() => {
      try {
        const baristaSelect = document.getElementById('barista');
        const response = await fetch("/api/users/baristas", {
          method: "GET",
          headers: {
            "Content-Type": "application/json"
          }
        });

        const result = await response.json();
        if (result.status === "success") {
          result.data.forEach((b) => {
            const option = document.createElement("option")
            option.value = b.id
            option.textContent = b.name
            baristaSelect.appendChild(option)
          })
        }
      } catch(err) {
          alert("Gagal memuat daftar barista")
      }
    }

    getBaristaForOptions();

    // Ambil produk dari API
    const getProducts = async () => {
      try {
        const productSelect = document.getElementById('productSelect');
        const response = await fetch("/api/products", {
          method: "GET",
          headers: {
            "Content-Type": "application/json"
          }
        })

        const result = await response.json()
        if (result.status === "success") {
          result.data.forEach((product) => {
            const option = document.createElement("option");
            option.value = product.id
            option.textContent = `${product.name} - Rp${Number(product.price).toLocaleString()}`
            option.dataset.price = product.price
            productSelect.appendChild(option)
          })
        }
      } catch(err) {
        alert("gagal memuat harga produk")
        console.log(err)
      }
    }

    getProducts()
});

let temporaryProducts = [];

// Tambah produk sementara
document.getElementById('addProductBtn').addEventListener('click', function() {
    const productSelect = document.getElementById('productSelect');
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const productId = selectedOption.value;
    const productName = selectedOption.textContent;
    const price = Number(selectedOption.dataset.price);
    const qty = Number(document.getElementById('productQty').value);

    if(!productId || qty <= 0) { alert('Pilih produk dan masukkan qty yang valid!'); return; }

    const existing = temporaryProducts.find(p => p.id === productId);
    if(existing) {
        existing.qty += qty;
        existing.total = existing.qty * existing.price;
    } else {
        temporaryProducts.push({ id: productId, name: productName, price, qty, total: price * qty });
    }

    updateTemporaryTable();
    document.getElementById('productQty').value = 1;
    productSelect.selectedIndex = 0;
});

// Update tabel & total
function updateTemporaryTable() {
    const tbody = document.querySelector('#temporaryTable tbody');
    tbody.innerHTML = '';

    let totalQty = 0;
    let estimatedResult = 0;

    temporaryProducts.forEach((p, idx) => {
        totalQty += p.qty;
        estimatedResult += p.total;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${p.name}</td>
            <td>Rp ${Number(p.price).toLocaleString()}</td>
            <td>${p.qty}</td>
            <td>Rp ${Number(p.total).toLocaleString()}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeProduct(${idx})">Hapus</button></td>
        `;
        tbody.appendChild(tr);
    });

    document.getElementById('totalQty').textContent = totalQty;
    document.getElementById('estimatedResult').textContent = estimatedResult.toLocaleString();
}

function removeProduct(index) {
    temporaryProducts.splice(index, 1);
    updateTemporaryTable();
}

// Submit distribusi + produk
document.getElementById('submitDistribution').addEventListener('click', async function() {
    const baristaId = document.getElementById('barista').value;
    const notes = document.getElementById('notes').value;
    const totalQty = temporaryProducts.reduce((sum, p) => sum + p.qty, 0);
    const estimatedResult = temporaryProducts.reduce((sum, p) => sum + p.total, 0);

    if(!baristaId) { alert('Pilih barista terlebih dahulu!'); return; }
    if(temporaryProducts.length === 0) { alert('Tambahkan minimal satu produk!'); return; }

    this.disabled = true;


    const distributionDetails = temporaryProducts.map(p => ({

        product_id: p.id,
        qty: p.qty,
        price: p.price,
        total: p.total
    }));

    const ADMIN_NAME_FROM_SEEDER = 'admin user'

    const payload = {
        barista_id: baristaId,
        total_qty: totalQty,
        estimated_result: estimatedResult,
        notes: notes,
        created_by: ADMIN_NAME_FROM_SEEDER,
        details: distributionDetails 
    };

    try {
      const response = await fetch("/api/distributions", {
        method: "POST",
        headers: {
          "Content-Type" : "application/json"
        },
        body: JSON.stringify(payload)
      })

      const result = await response.json()
      if (result.status === "success") {
        alert("distribusi berhasil ditambahkan")
        window.location.href = "/distributions"
      } else {
        let errorMessage = result.message || "Gagal menambahkan distribusi"
        if (result.errors) {
          errorMessage += "\n\nDetail Error:\n" + Object.values(result.errors).flat().join("\n");
        }
        
        alert(errorMessage);
        document.getElementById('submitDistribution').disabled = false;
      }
    } catch(err) {
        console.error(err)
        alert("Distribusi gagal ditambahkan")
        document.getElementById('submitDistribution').disabled = false;
    }
});
</script>

</body>
</html>
