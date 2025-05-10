<?php
require_once '../config/config.php';

// Check if data already exists to avoid duplicate inserts
$stmt = $conn->query("SELECT COUNT(*) FROM mahasiswa");
$count = $stmt->fetchColumn();

if ($count == 0) {
    $dummyData = [
        [
            'nama' => 'Ahmad Rizki',
            'nim' => '101234567',
            'jenis_kelamin' => 'laki-laki',
            'kelas' => '4A',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2020'
        ],
        [
            'nama' => 'Siti Nurhaliza',
            'nim' => '101234568',
            'jenis_kelamin' => 'perempuan',
            'kelas' => '4A',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2020'
        ],
        [
            'nama' => 'Budi Santoso',
            'nim' => '101234569',
            'jenis_kelamin' => 'laki-laki',
            'kelas' => '4B',
            'program_studi' => 'Sistem Informasi',
            'angkatan' => '2020'
        ],
        [
            'nama' => 'Dewi Anggraini',
            'nim' => '101234570',
            'jenis_kelamin' => 'perempuan',
            'kelas' => '4B',
            'program_studi' => 'Sistem Informasi',
            'angkatan' => '2020'
        ],
        [
            'nama' => 'Doni Pratama',
            'nim' => '101234571',
            'jenis_kelamin' => 'laki-laki',
            'kelas' => '4C',
            'program_studi' => 'Teknik Komputer',
            'angkatan' => '2021'
        ],
        [
            'nama' => 'Rina Wijaya',
            'nim' => '101234572',
            'jenis_kelamin' => 'perempuan',
            'kelas' => '4C',
            'program_studi' => 'Teknik Komputer',
            'angkatan' => '2021'
        ],
        [
            'nama' => 'Eko Nugroho',
            'nim' => '101234573',
            'jenis_kelamin' => 'laki-laki',
            'kelas' => '4D',
            'program_studi' => 'Manajemen Informatika',
            'angkatan' => '2021'
        ],
        [
            'nama' => 'Putri Rahayu',
            'nim' => '101234574',
            'jenis_kelamin' => 'perempuan',
            'kelas' => '4D',
            'program_studi' => 'Manajemen Informatika',
            'angkatan' => '2021'
        ],
        [
            'nama' => 'Irfan Hakim',
            'nim' => '101234575',
            'jenis_kelamin' => 'laki-laki',
            'kelas' => '4E',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2022'
        ],
        [
            'nama' => 'Anisa Fitriani',
            'nim' => '101234576',
            'jenis_kelamin' => 'perempuan',
            'kelas' => '4E',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2022'
        ]
    ];

    $sql = "INSERT INTO mahasiswa (nama, nim, jenis_kelamin, kelas, program_studi, angkatan) 
            VALUES (:nama, :nim, :jenis_kelamin, :kelas, :program_studi, :angkatan)";
    $stmt = $conn->prepare($sql);

    foreach ($dummyData as $data) {
        $stmt->execute($data);
    }

    echo "Dummy data inserted successfully!";
} else {
    echo "Data already exists in the table!";
}
?> 