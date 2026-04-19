<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/database.php';

$action = $_GET['action'] ?? '';

// Handle CSV export specially since it shouldn't return JSON
if ($action === 'export') {
    if (empty($_SESSION['is_admin'])) {
        http_response_code(403);
        exit('Unauthorized');
    }
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=pawhaven_animals.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Species', 'Color', 'Age (months)', 'Gender', 'Health', 'Status', 'Registered At']);
    $stmt = $pdo->query("SELECT id, name, species, color, age, gender, health_status, status, created_at FROM animals ORDER BY id DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Return JSON for all other actions
function json_response($success, $message = '', $data = null) {
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit;
}

switch ($action) {
    case 'login':
        $input = json_decode(file_get_contents('php://input'), true);
        $username = trim($input['username'] ?? '');
        $password = trim($input['password'] ?? '');
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['is_admin'] = true;
            json_response(true, 'Logged in successfully');
        }
        json_response(false, 'Invalid username or password');
        break;

    case 'logout':
        session_destroy();
        json_response(true, 'Logged out successfully');
        break;

    case 'check_auth':
        json_response(true, '', ['is_admin' => !empty($_SESSION['is_admin'])]);
        break;

    case 'stats':
        $stats = [
            'total' => $pdo->query("SELECT COUNT(*) FROM animals")->fetchColumn(),
            'adopted' => $pdo->query("SELECT COUNT(*) FROM animals WHERE status = 'Adopted'")->fetchColumn(),
            'treatment' => $pdo->query("SELECT COUNT(*) FROM animals WHERE health_status = 'Under Treatment'")->fetchColumn(),
            'species' => $pdo->query("SELECT species, COUNT(*) as count FROM animals GROUP BY species")->fetchAll()
        ];
        json_response(true, '', $stats);
        break;

    case 'list':
        $status = $_GET['status'] ?? 'Available';
        $sql = "SELECT * FROM animals WHERE status = :status";
        $params = [':status' => $status];

        if (!empty($_GET['species'])) {
            $sql .= " AND species = :species";
            $params[':species'] = $_GET['species'];
        }
        if (!empty($_GET['health'])) {
            $sql .= " AND health_status = :health";
            $params[':health'] = $_GET['health'];
        }
        if (!empty($_GET['search'])) {
            $sql .= " AND (name LIKE :search OR species LIKE :search OR color LIKE :search)";
            $params[':search'] = '%' . $_GET['search'] . '%';
        }
        
        $sql .= " ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        json_response(true, '', $stmt->fetchAll());
        break;

    case 'add':
        if (empty($_SESSION['is_admin'])) json_response(false, 'Unauthorized');
        
        $name    = trim($_POST['name'] ?? '');
        $species = trim($_POST['species'] ?? '');
        $color   = trim($_POST['color'] ?? '');
        $age     = intval($_POST['age'] ?? 0);
        $gender  = trim($_POST['gender'] ?? '');
        $health  = trim($_POST['health'] ?? 'Healthy');
        
        if (!$name || !$species || !$color || !$gender) {
            json_response(false, 'All fields are required.');
        }

        $image_path = null;
        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = 'uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = $destination;
            }
        }

        $stmt = $pdo->prepare("INSERT INTO animals (name, species, color, age, gender, health_status, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $species, $color, $age, $gender, $health, $image_path]);
        json_response(true, "$name registered successfully!");
        break;

    case 'update':
        if (empty($_SESSION['is_admin'])) json_response(false, 'Unauthorized');
        
        $id      = intval($_POST['id'] ?? 0);
        $name    = trim($_POST['name'] ?? '');
        $species = trim($_POST['species'] ?? '');
        $color   = trim($_POST['color'] ?? '');
        $age     = intval($_POST['age'] ?? 0);
        $gender  = trim($_POST['gender'] ?? '');
        $health  = trim($_POST['health'] ?? 'Healthy');

        if ($id <= 0 || !$name || !$species || !$color || !$gender) {
            json_response(false, 'All fields are required.');
        }

        $sql = "UPDATE animals SET name=?, species=?, color=?, age=?, gender=?, health_status=?";
        $params = [$name, $species, $color, $age, $gender, $health];

        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = 'uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $sql .= ", image_path=?";
                $params[] = $destination;
            }
        }

        $sql .= " WHERE id=?";
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        json_response(true, "$name's data updated successfully!");
        break;

    case 'adopt':
        if (empty($_SESSION['is_admin'])) json_response(false, 'Unauthorized');
        $input = json_decode(file_get_contents('php://input'), true);
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) json_response(false, 'Invalid ID');
        
        $stmt = $pdo->prepare("UPDATE animals SET status='Adopted', adopted_at=CURRENT_TIMESTAMP WHERE id=?");
        $stmt->execute([$id]);
        json_response(true, "Animal adopted successfully! 🎉");
        break;

    case 'delete':
        if (empty($_SESSION['is_admin'])) json_response(false, 'Unauthorized');
        $input = json_decode(file_get_contents('php://input'), true);
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) json_response(false, 'Invalid ID');
        
        $stmt = $pdo->prepare("DELETE FROM animals WHERE id=?");
        $stmt->execute([$id]);
        json_response(true, "Animal deleted completely.");
        break;

    default:
        json_response(false, 'Unknown action.');
}
