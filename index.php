<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 20px;
            max-width: 1200px;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .editable {
            cursor: pointer;
            position: relative;
        }
        .editable:hover {
            background-color: #f0f0f0;
        }
        .editable:hover::after {
            content: "Double-click to edit";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 5px 10px;
            background-color: #333;
            color: white;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10;
        }
        .btn-add {
            margin-bottom: 15px;
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-add:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 10px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,0.125);
            padding: 15px 20px;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.3s linear;
        }
        .loading-overlay.show {
            visibility: visible;
            opacity: 1;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="text-center">Data Mahasiswa</h1>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-header row justify-content-between align-items-center">
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                    <h5 class="mb-0">Daftar Mahasiswa</h5>
                </div>
                <div class="col-12 col-md-4 mb-2 mb-md-0 text-center">
                    <button id="refreshBtn" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sync-alt"></i> Refresh Data
                    </button>
                </div>
                <div class="col-12 col-md-4 text-center text-md-end">
                    <button type="button" class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Tambah Mahasiswa
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="mahasiswaTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Jenis Kelamin</th>
                                <th>Kelas</th>
                                <th>Program Studi</th>
                                <th>Angkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Mahasiswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" required>
                        </div>
                        <div class="mb-3">
                            <label for="program_studi" class="form-label">Program Studi</label>
                            <input type="text" class="form-control" id="program_studi" name="program_studi" required>
                        </div>
                        <div class="mb-3">
                            <label for="angkatan" class="form-label">Angkatan</label>
                            <input type="text" class="form-control" id="angkatan" name="angkatan" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveAdd">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="edit_nim" name="nim" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="edit_kelas" name="kelas" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_program_studi" class="form-label">Program Studi</label>
                            <input type="text" class="form-control" id="edit_program_studi" name="program_studi" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_angkatan" class="form-label">Angkatan</label>
                            <input type="text" class="form-control" id="edit_angkatan" name="angkatan" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveEdit">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data mahasiswa ini?</p>
                    <p><strong>Data yang sudah dihapus tidak dapat dikembalikan!</strong></p>
                    <input type="hidden" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            function showLoading() {
                $('#loadingOverlay').addClass('show');
            }
            
            function hideLoading() {
                $('#loadingOverlay').removeClass('show');
            }
            
            var table = $('#mahasiswaTable').DataTable({
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "zeroRecords": "Tidak ada data yang cocok",
                    "emptyTable": "Tidak ada data tersedia",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": "api/api.php?action=read",
                    "type": "GET",
                    "dataSrc": "data",
                    "beforeSend": function() {
                        showLoading();
                    },
                    "complete": function() {
                        hideLoading();
                    },
                    "error": function(xhr, status, error) {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load data: ' + error
                        });
                    }
                },
                "columns": [
                    { "data": "id" },
                    { 
                        "data": "nama", 
                        "className": "editable",
                        "render": function(data, type, row) {
                            return '<span class="editable-data">' + data + '</span>';
                        }
                    },
                    { 
                        "data": "nim", 
                        "className": "editable",
                        "render": function(data, type, row) {
                            return '<span class="editable-data">' + data + '</span>';
                        }
                    },
                    { 
                        "data": "jenis_kelamin",
                        "className": "editable",
                        "render": function(data, type, row) {
                            return '<span class="editable-data">' + data + '</span>';
                        }
                    },
                    { 
                        "data": "kelas",
                        "className": "editable",
                        "render": function(data, type, row) {
                            return '<span class="editable-data">' + data + '</span>';
                        }
                    },
                    { 
                        "data": "program_studi",
                        "className": "editable",
                        "render": function(data, type, row) {
                            return '<span class="editable-data">' + data + '</span>';
                        }
                    },
                    { 
                        "data": "angkatan",
                        "className": "editable",
                        "render": function(data, type, row) {
                            return '<span class="editable-data">' + data + '</span>';
                        }
                    },
                    {
                        "data": null,
                        "className": "action-buttons text-center",
                        "orderable": false,
                        "render": function(data, type, row) {
                            return '<button type="button" class="btn btn-primary btn-sm edit-btn me-1" data-id="' + row.id + '"><i class="fas fa-edit"></i></button>' +
                                   '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' + row.id + '"><i class="fas fa-trash"></i></button>';
                        }
                    }
                ],
                "order": [[0, "desc"]]
            });

            // edit action handler
            $('#mahasiswaTable').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                showLoading();
                
                $.ajax({
                    url: 'api/api.php?action=get_single',
                    type: 'GET',
                    data: {id: id},
                    success: function(response) {
                        hideLoading();

                        var data = response;
                        if (data.status === 'success') {
                            $('#edit_id').val(data.data.id);
                            $('#edit_nama').val(data.data.nama);
                            $('#edit_nim').val(data.data.nim);
                            $('#edit_jenis_kelamin').val(data.data.jenis_kelamin);
                            $('#edit_kelas').val(data.data.kelas);
                            $('#edit_program_studi').val(data.data.program_studi);
                            $('#edit_angkatan').val(data.data.angkatan);
                            $('#editModal').modal('show');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch data: ' + error
                        });
                    }
                });
            });

            // delete action handler
            $('#mahasiswaTable').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                $('#delete_id').val(id);
                $('#deleteModal').modal('show');
            });

            // Save new data
            $('#saveAdd').click(function() {
                var formData = $('#addForm').serialize();
                
                // Form validation
                if (!validateForm('#addForm')) {
                    return;
                }
                
                showLoading();
                
                $.ajax({
                    url: 'api/api.php?action=create',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        hideLoading();
                        var data = response;
                        if (data.status === 'success') {
                            $('#addModal').modal('hide');
                            $('#addForm')[0].reset();
                            
                            // Refresh the table
                            table.ajax.reload();
                            
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add data: ' + error
                        });
                    }
                });
            });

            // Save edit
            $('#saveEdit').click(function() {
                var formData = $('#editForm').serialize();
                
                // Form validation
                if (!validateForm('#editForm')) {
                    return;
                }
                
                showLoading();
                
                $.ajax({
                    url: 'api/api.php?action=update',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        hideLoading();
                        var data = response;
                        if (data.status === 'success') {
                            $('#editModal').modal('hide');
                            
                            // Refresh the table
                            table.ajax.reload();
                            
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update data: ' + error
                        });
                    }
                });
            });

            // Confirm delete action handler
            $('#confirmDelete').click(function() {
                var id = $('#delete_id').val();
                showLoading();
                
                $.ajax({
                    url: 'api/api.php?action=delete',
                    type: 'POST',
                    data: {id: id},
                    success: function(response) {
                        hideLoading();
                        var data = response;
                        if (data.status === 'success') {
                            $('#deleteModal').modal('hide');
                            
                            // Refresh the table
                            table.ajax.reload();
                            
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete data: ' + error
                        });
                    }
                });
            });

            // Inline editing
            $('#mahasiswaTable').on('dblclick', 'td.editable', function() {
                var cell = table.cell(this);
                var row = table.row($(this).closest('tr'));
                var rowData = row.data();
                var columnIndex = cell.index().column;
                var columnName = table.column(columnIndex).header().textContent.toLowerCase();
                
                // Map the header text to the actual data field
                var field;
                switch(columnName) {
                    case 'nama': field = 'nama'; break;
                    case 'nim': field = 'nim'; break;
                    case 'jenis kelamin': field = 'jenis_kelamin'; break;
                    case 'kelas': field = 'kelas'; break;
                    case 'program studi': field = 'program_studi'; break;
                    case 'angkatan': field = 'angkatan'; break;
                    default: return; // If not editable column
                }
                
                var currentValue = cell.data();
                
                // Create editable input
                var element;
                
                if (field === 'jenis_kelamin') {
                    element = $('<select class="form-control form-control-sm">')
                        .append($('<option value="laki-laki">').text('laki-laki'))
                        .append($('<option value="perempuan">').text('perempuan'))
                        .val(currentValue);
                } else {
                    element = $('<input type="text" class="form-control form-control-sm">').val(currentValue);
                }
                
                $(this).html(element);
                
                element.focus();
                
                // Handle blur event (when focus leaves the input)
                element.on('blur', function() {
                    var newValue = $(this).val();
                    
                    // If value is empty, revert to original
                    if (newValue === '') {
                        cell.data(currentValue).draw();
                        return;
                    }
                    
                    if (newValue !== currentValue) {
                        // Show loading
                        showLoading();
                        
                        // Update via AJAX
                        $.ajax({
                            url: 'api/api.php?action=update_field',
                            type: 'POST',
                            data: {
                                id: rowData.id,
                                field: field,
                                value: newValue
                            },
                            success: function(response) {
                                hideLoading();
                                var data = response;
                                if (data.status === 'success') {
                                    // Refresh the table
                                    table.ajax.reload();
                                    
                                    // Show success message with Toast
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    });
                                    
                                    Toast.fire({
                                        icon: 'success',
                                        title: data.message
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message
                                    });
                                    cell.data(currentValue).draw();
                                }
                            },
                            error: function(xhr, status, error) {
                                hideLoading();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to update data: ' + error
                                });
                                cell.data(currentValue).draw();
                            }
                        });
                    } else {
                        cell.data(currentValue).draw();
                    }
                });
                
                // Handle Enter key
                element.on('keypress', function(e) {
                    if (e.which === 13) {
                        $(this).blur();
                    }
                });
                
                // Handle Escape key
                element.on('keydown', function(e) {
                    if (e.which === 27) {
                        cell.data(currentValue).draw();
                    }
                });
            });
            
            // Form validation function
            function validateForm(formSelector) {
                var isValid = true;
                
                $(formSelector + ' [required]').each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        
                        // Add invalid feedback if not exists
                        if ($(this).next('.invalid-feedback').length === 0) {
                            $(this).after('<div class="invalid-feedback">This field is required</div>');
                        }
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please fill in all required fields'
                    });
                }
                
                return isValid;
            }
            
            // Remove invalid class on input
            $(document).on('input', '.form-control, .form-select', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid');
                }
            });
            
            // Refresh data button
            /* $('.card-header').append(
                '<button id="refreshBtn" class="btn btn-outline-primary btn-sm ms-2">' +
                '<i class="fas fa-sync-alt"></i> Refresh Data</button>'
            ); */
            
            $('#refreshBtn').on('click', function() {
                table.ajax.reload();
                
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                
                Toast.fire({
                    icon: 'success',
                    title: 'Data refreshed successfully'
                });
            });
        });
    </script>
</body>
</html> 