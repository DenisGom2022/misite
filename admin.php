<?php
session_start();
require_once 'config.php';

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';
$current_user = $_SESSION['username'];
$user_role = $_SESSION['role'];

// Procesar formulario de nuevo post
if ($_POST['action'] ?? '' === 'create_post') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if ($title && $content) {
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$title, $content]);
            $message = "Post creado exitosamente!";
        } catch (PDOException $e) {
            $error = "Error al crear el post: " . $e->getMessage();
        }
    } else {
        $error = "Título y contenido son obligatorios.";
    }
}

// Procesar eliminación de post
if ($_POST['action'] ?? '' === 'delete_post') {
    $post_id = $_POST['post_id'] ?? 0;
    try {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $message = "Post eliminado exitosamente!";
    } catch (PDOException $e) {
        $error = "Error al eliminar el post: " . $e->getMessage();
    }
}

// Procesar búsqueda (VULNERABLE)
$search = $_GET['search'] ?? '';
if ($search) {
    // VULNERABLE: Concatenación directa sin escapar - NUNCA hacer esto
    $query = "SELECT * FROM posts WHERE title LIKE '%" . $search . "%' ORDER BY created_at DESC";
    $stmt = $pdo->query($query);
} else {
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
}
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-header {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
        }
        .admin-header h2 {
            color: var(--text-dark);
            font-size: 2rem;
            margin: 0;
        }
        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .admin-card {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        .admin-card h2 {
            color: var(--text-dark);
            font-size: 1.5rem;
            margin: 0 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .admin-card h2 svg {
            width: 24px;
            height: 24px;
            color: var(--accent-color);
        }
        .search-box {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .search-box input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            background: var(--input-bg);
            transition: all 0.3s ease;
        }
        .search-box input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
        }
        .search-box button {
            padding: 0.75rem 1.5rem;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        .search-box button:hover {
            background: var(--accent-dark);
        }
        .search-box svg {
            width: 20px;
            height: 20px;
        }
        .post-form {
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 500;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            background: var(--input-bg);
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
        }
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        .posts-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .post-card {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .post-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }
        .post-card h3 {
            margin: 0 0 1rem;
            color: var(--text-dark);
        }
        .post-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .post-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: var(--accent-color);
            color: white;
        }
        .btn-primary:hover {
            background: var(--accent-dark);
        }
        .btn-secondary {
            background: var(--bg-alt);
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }
        .btn-secondary:hover {
            background: var(--bg-hover);
            border-color: var(--accent-color);
        }
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .btn-danger {
            background: var(--error-bg);
            color: var(--error-text);
        }
        .btn-danger:hover {
            background: var(--error-border);
        }
        .btn svg {
            width: 20px;
            height: 20px;
        }
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .alert.success {
            background: var(--success-bg);
            color: var(--success-text);
            border: 1px solid var(--success-border);
        }
        .alert.error {
            background: var(--error-bg);
            color: var(--error-text);
            border: 1px solid var(--error-border);
        }
        .alert svg {
            width: 24px;
            height: 24px;
        }
        .admin-section {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        .admin-section h2 {
            color: var(--text-dark);
            font-size: 1.5rem;
            margin: 0 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .table-container {
            overflow-x: auto;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card-bg);
        }
        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        .table th {
            background: var(--bg-alt);
            font-weight: 600;
            color: var(--text-dark);
        }
        .table tr:hover {
            background: var(--bg-hover);
        }
        .table td:last-child {
            width: 1%;
            white-space: nowrap;
        }
        .status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status.active {
            background: var(--success-bg);
            color: var(--success-text);
        }
        .status.pending {
            background: var(--warning-bg);
            color: var(--warning-text);
        }
        .no-results {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
            background: var(--bg-alt);
            border-radius: 0.5rem;
            margin: 1rem 0;
        }
        .flex {
            display: flex;
        }
        .justify-between {
            justify-content: space-between;
        }
        .items-center {
            align-items: center;
        }
        .gap-4 {
            gap: 1rem;
        }
        .mb-4 {
            margin-bottom: 1rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: var(--accent-color);
            color: white;
            border-radius: 2rem;
        }
        .user-info svg {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="brand">
                <div class="logo">M</div>
                <h1><a href="index.php">BLOG MIGUEL</a></h1>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="index.php">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="admin.php" class="active">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Admin
                        </a>
                    </li>
                    <li>
                        <div class="user-info">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <?php echo htmlspecialchars($current_user); ?> 
                            (<?php echo htmlspecialchars($user_role); ?>)
                        </div>
                    </li>
                    <li>
                        <a href="login.php?action=logout">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php if ($message): ?>
            <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <section class="admin-section">
            <div class="flex justify-between items-center mb-4">
                <h2>
                    <svg style="width: 1.5rem; height: 1.5rem; display: inline; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Administración de Posts - Crear Nueva Entrada
                </h2>
            </div>

            <form method="POST" class="post-form">
                <input type="hidden" name="action" value="create_post">
                <div class="form-group">
                    <label for="title">Título del artículo</label>
                    <input type="text" id="title" name="title" required
                           placeholder="Ingresa un título descriptivo">
                </div>
                <div class="form-group">
                    <label for="content">Contenido</label>
                    <textarea id="content" name="content" rows="12" required
                              placeholder="Escribe el contenido de tu artículo aquí..."
                              style="resize: vertical;"></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="btn btn-primary">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Publicar artículo
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Limpiar
                    </button>
                </div>
            </form>
        </section>

        <section class="admin-section">
            <h2>Gestionar Entradas</h2>
            
            <!-- Formulario de búsqueda vulnerable -->
            <form method="GET" class="search-form" style="margin-bottom: 20px;">
                <div class="form-group">
                    <label for="search">Buscar entradas:</label>
                    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Introduce título a buscar...">
                    <button type="submit" class="btn btn-small">Buscar</button>
                    <?php if (isset($_GET['search'])): ?>
                        <a href="admin.php" class="btn btn-small">Limpiar</a>
                    <?php endif; ?>
                </div>
            </form>
            
            <?php if (empty($posts)): ?>
                <p class="no-results">No hay entradas disponibles.</p>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo $post['title']; ?></strong>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></td>
                                    <td>
                                        <span class="status active">Publicado</span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-small">
                                                Ver
                                            </a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este post?')">
                                                <input type="hidden" name="action" value="delete_post">
                                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                                <button type="submit" class="btn btn-small btn-danger">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>