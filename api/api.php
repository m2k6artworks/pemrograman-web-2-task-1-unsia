<?php
header('Content-Type: application/json');
require_once '../config/config.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

date_default_timezone_set('Asia/Jakarta');

switch ($action) {
    case 'read':
        fetchAllData();
        break;
    
    case 'get_single':
        getSingleData();
        break;
        
    case 'create':
        createData();
        break;
        
    case 'update':
        updateData();
        break;
        
    case 'update_field':
        updateField();
        break;
        
    case 'delete':
        deleteData();
        break;
        
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid action requested'
        ]);
}

function fetchAllData() {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM mahasiswa ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'data' => $result
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch data: ' . $e->getMessage()
        ]);
    }
}

// Function to get data by ID
function getSingleData() {
    global $conn;
    
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    
    if (!$id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID is required'
        ]);
        return;
    }
    
    try {
        $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch data: ' . $e->getMessage()
        ]);
    }
}

function createData() {
    global $conn;
    
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $nim = isset($_POST['nim']) ? trim($_POST['nim']) : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
    $kelas = isset($_POST['kelas']) ? trim($_POST['kelas']) : '';
    $program_studi = isset($_POST['program_studi']) ? trim($_POST['program_studi']) : '';
    $angkatan = isset($_POST['angkatan']) ? trim($_POST['angkatan']) : '';
    
    // Validate required fields
    if (empty($nama) || empty($nim) || empty($jenis_kelamin) || empty($kelas) || empty($program_studi) || empty($angkatan)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required'
        ]);
        return;
    }
    
    // Validate jenis_kelamin is one of the allowed values
    if (!in_array($jenis_kelamin, ['laki-laki', 'perempuan'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid gender value'
        ]);
        return;
    }
    
    try {
        // Check if NIM already exists
        $check = $conn->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim");
        $check->bindParam(':nim', $nim);
        $check->execute();
        
        if ($check->fetchColumn() > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'NIM already exists'
            ]);
            return;
        }
        
        $sql = "INSERT INTO mahasiswa (nama, nim, jenis_kelamin, kelas, program_studi, angkatan) 
                VALUES (:nama, :nim, :jenis_kelamin, :kelas, :program_studi, :angkatan)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':nim', $nim);
        $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
        $stmt->bindParam(':kelas', $kelas);
        $stmt->bindParam(':program_studi', $program_studi);
        $stmt->bindParam(':angkatan', $angkatan);
        
        $stmt->execute();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Data added successfully',
            'data' => [
                'id' => $conn->lastInsertId(),
                'nama' => $nama,
                'nim' => $nim,
                'jenis_kelamin' => $jenis_kelamin,
                'kelas' => $kelas,
                'program_studi' => $program_studi,
                'angkatan' => $angkatan
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add data: ' . $e->getMessage()
        ]);
    }
}

function updateData() {
    global $conn;
    
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $nim = isset($_POST['nim']) ? trim($_POST['nim']) : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
    $kelas = isset($_POST['kelas']) ? trim($_POST['kelas']) : '';
    $program_studi = isset($_POST['program_studi']) ? trim($_POST['program_studi']) : '';
    $angkatan = isset($_POST['angkatan']) ? trim($_POST['angkatan']) : '';
    
    // Validate required fields
    if (!$id || empty($nama) || empty($nim) || empty($jenis_kelamin) || empty($kelas) || empty($program_studi) || empty($angkatan)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required'
        ]);
        return;
    }
    
    // Validate jenis_kelamin is one of the allowed values
    if (!in_array($jenis_kelamin, ['laki-laki', 'perempuan'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid gender value'
        ]);
        return;
    }
    
    try {
        // Check if NIM already exists for other records
        $check = $conn->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim AND id != :id");
        $check->bindParam(':nim', $nim);
        $check->bindParam(':id', $id);
        $check->execute();
        
        if ($check->fetchColumn() > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'NIM already exists'
            ]);
            return;
        }
        
        $sql = "UPDATE mahasiswa SET 
                nama = :nama, 
                nim = :nim, 
                jenis_kelamin = :jenis_kelamin, 
                kelas = :kelas, 
                program_studi = :program_studi, 
                angkatan = :angkatan 
                WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':nim', $nim);
        $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
        $stmt->bindParam(':kelas', $kelas);
        $stmt->bindParam(':program_studi', $program_studi);
        $stmt->bindParam(':angkatan', $angkatan);
        
        $stmt->execute();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => [
                'id' => $id,
                'nama' => $nama,
                'nim' => $nim,
                'jenis_kelamin' => $jenis_kelamin,
                'kelas' => $kelas,
                'program_studi' => $program_studi,
                'angkatan' => $angkatan
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update data: ' . $e->getMessage()
        ]);
    }
}

function updateField() {
    global $conn;
    
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $field = isset($_POST['field']) ? $_POST['field'] : '';
    $value = isset($_POST['value']) ? trim($_POST['value']) : '';
    
    // Validate data
    if (!$id || empty($field)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID and field are required'
        ]);
        return;
    }
    
    // Validate empty values
    if (empty($value)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Value cannot be empty'
        ]);
        return;
    }
    
    // Validate field name to prevent SQL injection
    $allowedFields = ['nama', 'nim', 'jenis_kelamin', 'kelas', 'program_studi', 'angkatan'];
    if (!in_array($field, $allowedFields)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid field name'
        ]);
        return;
    }
    
    // Additional validation for jenis_kelamin
    if ($field === 'jenis_kelamin' && !in_array($value, ['laki-laki', 'perempuan'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid gender value'
        ]);
        return;
    }
    
    // Check if NIM already exists for other records when updating NIM
    if ($field === 'nim') {
        try {
            $check = $conn->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim AND id != :id");
            $check->bindParam(':nim', $value);
            $check->bindParam(':id', $id);
            $check->execute();
            
            if ($check->fetchColumn() > 0) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'NIM already exists'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error checking NIM: ' . $e->getMessage()
            ]);
            return;
        }
    }
    
    try {
        $sql = "UPDATE mahasiswa SET $field = :value WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':value', $value);
        
        $stmt->execute();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Field updated successfully',
            'field' => $field,
            'value' => $value
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update field: ' . $e->getMessage()
        ]);
    }
}

function deleteData() {
    global $conn;
    
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    
    if (!$id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID is required'
        ]);
        return;
    }
    
    try {
        // First check if record exists
        $check = $conn->prepare("SELECT COUNT(*) FROM mahasiswa WHERE id = :id");
        $check->bindParam(':id', $id);
        $check->execute();
        
        if ($check->fetchColumn() == 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Record not found'
            ]);
            return;
        }
        
        $stmt = $conn->prepare("DELETE FROM mahasiswa WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Data deleted successfully',
            'id' => $id
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete data: ' . $e->getMessage()
        ]);
    }
}
?> 