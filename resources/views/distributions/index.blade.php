<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Distribusi Produk</title>

  <!-- Bootstrap & DataTables -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="app.css">
  
  <style>
    body { 
      background: #f8f9fa; 
    }
    .page-header { 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      margin-bottom: 20px; 
    }
    .table-container { 
      background: white; 
      padding: 20px; 
      border-radius: 10px; 
      box-shadow: 0 2px 6px rgba(0,0,0,0.1); 
    }
    .dataTables_filter {
      margin-bottom: 20px;
    }

  </style>
</head>

<body class="p-4">

  <div class="">
    <div class="page-header">
      <h2>Daftar Distribusi Produk</h2>
      <a href="/distributions/create" class="btn btn-primary">+ Tambah Distribusi</a>
    </div>
    <div class="table-container">
      <table id="distributionsTable" class="table table-rounded table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Tanggal Distribusi</th>
            <th>Barista</th>
            <th>Total Quantity</th>
            <th>Estimasi Penjualan</th>
            <th>Catatan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Modal Detail -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailModalLabel">Detail Distribusi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered" id="detailTable">
            <thead class="table-light">
              <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
      $(document).ready(function() {

      const table = $('#distributionsTable').DataTable({
        // Tambahkan properti serverSide dan processing
        processing: true,
        serverSide: true,
        ajax: {
          url: '/api/distributions',
        },
        columns: [
          { data: 'created_at', name: 'created_at' }, 
          { data: 'barista', name: 'barista.name', orderable: false, searchable: false }, 
          { data: 'total_qty', name: 'total_qty' },
          { data: 'estimated_result', name: 'estimated_result' }, 
          { data: 'notes', name: 'notes' },
          { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
      });

      $('#distributionsTable tbody').on('click', '.btn-detail', function() {
      const distributionId = $(this).data('id');

      fetch(`/api/distribution-products/${distributionId}`)
        .then(res => res.json())
        .then(json => {
      const tbody = $('#detailTable tbody');
      tbody.empty();

      if(json.status === 'success' && json.data.length > 0) {
        json.data.forEach(p => {
          tbody.append(`
            <tr>
              <td>${p.product_name}</td>
              <td>${p.qty}</td>
              <td>Rp ${Number(p.price).toLocaleString()}</td>
              <td>Rp ${Number(p.total).toLocaleString()}</td>
            </tr>
          `);
        });
      } else {
        tbody.append('<tr><td colspan="4" class="text-center">Tidak ada produk</td></tr>');
      }
    });
    });

     $('#distributionsTable tbody').on('click', '.btn-delete', function() {
    const distributionId = $(this).data('id');
    
    if(confirm('Apakah yakin ingin menghapus distribusi ini?')) {
        fetch(`/api/distributions/${distributionId}`, { 
            method: 'DELETE' 
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`Gagal menghapus. Status: ${res.status}`);
            }
      
            return res.text();
        })
        .then(responseText => {
            let json;
            if(json.status === 'success') {
                alert('Distribusi berhasil dihapus: ' + json.message);
                table.ajax.reload(); 
            } else {
                alert('Gagal menghapus distribusi: ' + (json.message || 'Error tidak diketahui'));
            }
        })
        .catch(error => {
            console.error('Delete Error:', error);
            alert('Terjadi kesalahan saat menghapus distribusi: ' + error.message);
        });
    }
});
 });
  </script>
</body>
</html>
