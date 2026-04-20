<?php
header('Content-Type: application/json; charset=utf-8');

$animals = [
    [
        'id' => 1, 'name' => 'Finn', 'species' => 'Other', 'color' => 'Sandy', 'age' => 12,
        'gender' => 'Male', 'health_status' => 'Healthy', 'status' => 'Available',
        'image_path' => 'uploads/fennec_fox.png', 'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'id' => 2, 'name' => 'Axel', 'species' => 'Fish', 'color' => 'Pink', 'age' => 8,
        'gender' => 'Male', 'health_status' => 'Healthy', 'status' => 'Available',
        'image_path' => 'uploads/axolotl.png', 'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'id' => 3, 'name' => 'Rusty', 'species' => 'Other', 'color' => 'Red', 'age' => 24,
        'gender' => 'Female', 'health_status' => 'Healthy', 'status' => 'Available',
        'image_path' => 'uploads/red_panda.png', 'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'id' => 4, 'name' => 'Max', 'species' => 'Dog', 'color' => 'Golden', 'age' => 36,
        'gender' => 'Male', 'health_status' => 'Healthy', 'status' => 'Adopted',
        'image_path' => null, 'created_at' => date('Y-m-d H:i:s')
    ]
];

$action = $_GET['action'] ?? '';

if ($action === 'export') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=pawhaven_animals.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Species', 'Color', 'Age (months)', 'Gender', 'Health', 'Status', 'Registered At']);
    foreach($animals as $a) {
        fputcsv($output, [$a['id'], $a['name'], $a['species'], $a['color'], $a['age'], $a['gender'], $a['health_status'], $a['status'], $a['created_at']]);
    }
    fclose($output);
    exit;
}

function json_response($success, $message = '', $data = null) {
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit;
}

switch ($action) {
    case 'check_auth':
        json_response(true, '', ['is_admin' => true]);
        break;

    case 'stats':
        $stats = [
            'total' => count($animals),
            'adopted' => count(array_filter($animals, fn($a) => $a['status'] === 'Adopted')),
            'treatment' => count(array_filter($animals, fn($a) => $a['health_status'] === 'Under Treatment')),
            'species' => []
        ];
        $species_count = [];
        foreach($animals as $a) {
            $species_count[$a['species']] = ($species_count[$a['species']] ?? 0) + 1;
        }
        foreach($species_count as $s => $c) {
            $stats['species'][] = ['species' => $s, 'count' => $c];
        }
        json_response(true, '', $stats);
        break;

    case 'list':
        $status = $_GET['status'] ?? 'Available';
        $filtered = array_filter($animals, function($a) use ($status) {
            if ($a['status'] !== $status) return false;
            if (!empty($_GET['species']) && $a['species'] !== $_GET['species']) return false;
            if (!empty($_GET['health']) && $a['health_status'] !== $_GET['health']) return false;
            if (!empty($_GET['search'])) {
                $search = strtolower($_GET['search']);
                if (strpos(strtolower($a['name']), $search) === false &&
                    strpos(strtolower($a['species']), $search) === false &&
                    strpos(strtolower($a['color']), $search) === false) return false;
            }
            return true;
        });
        json_response(true, '', array_values($filtered));
        break;

    case 'add':
    case 'update':
    case 'adopt':
    case 'delete':
        json_response(true, "Action simulated successfully!");
        break;

    default:
        json_response(false, 'Unknown action.');
}
