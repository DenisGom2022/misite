<?php
require_once 'config.php';

$post_id = $_GET['id'] ?? 0;

// VULNERABLE: Consulta SQL sin preparar - NUNCA hacer esto en producción
$query = "SELECT * FROM posts WHERE id = " . $post_id;



try {
    $stmt = $pdo->query($query);
    $post = $stmt->fetch();
} catch (PDOException $e) {
    echo "<div style='background: red; color: white; padding: 10px;'>";
    echo "<strong>ERROR SQL:</strong> " . $e->getMessage();
    echo "</div>";
    $post = false;
}

if (!$post) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .post-header {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .post-title {
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        .post-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }
        .post-meta .date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .post-meta svg {
            width: 20px;
            height: 20px;
        }
        .post-content {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            line-height: 1.7;
            color: var(--text-dark);
        }
        .post-content p {
            margin-bottom: 1.5rem;
        }
        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }
        .post-footer {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .back-to-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .back-to-home:hover {
            transform: translateX(-4px);
        }
        .back-to-home svg {
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
                        <a href="admin.php">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Admin
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <article>
            <div class="post-header">
                <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="post-meta">
                    <span class="date">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>
                    </span>
                </div>
            </div>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>
            <div class="post-footer">
                <a href="index.php" class="back-to-home">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al inicio
                </a>
            </div>
        </article>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> BLOG MIGUEL. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>