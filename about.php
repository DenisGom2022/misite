<?php
require_once 'config.php';
session_start();

$current_user = $_SESSION['username'] ?? '';
$user_role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .about-header {
            text-align: center;
            padding: 4rem 0;
            background: var(--card-bg);
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        .about-header h1 {
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        .about-header p {
            color: var(--text-muted);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        .about-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .about-card {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }
        .about-card:hover {
            transform: translateY(-5px);
        }
        .about-card h2 {
            color: var(--accent-color);
            font-size: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .about-card svg {
            width: 24px;
            height: 24px;
        }
        .about-card p {
            color: var(--text-dark);
            line-height: 1.6;
        }
        .team-section {
            text-align: center;
            margin-top: 3rem;
        }
        .team-section h2 {
            color: var(--text-dark);
            font-size: 2rem;
            margin-bottom: 2rem;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        .team-member {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow);
        }
        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 1rem;
            object-fit: cover;
        }
        .team-member h3 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        .team-member p {
            color: var(--text-muted);
            font-size: 0.9rem;
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
                        <a href="about.php" class="active">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Sobre Nosotros
                        </a>
                    </li>
                    <li>
                        <a href="contact.php">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contacto
                        </a>
                    </li>
                    <?php if ($current_user): ?>
                    <li>
                        <a href="admin.php">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Admin
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="about-header">
            <h1>Sobre Este Proyecto</h1>
            <p>Este sistema de blog fue desarrollado por Miguel García utilizando PHP como un proyecto para practicar y mejorar sus conocimientos en desarrollo web.</p>
        </div>

        <div class="about-content">
            <div class="about-card">
                <h2>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Objetivo del Proyecto
                </h2>
                <p>Este blog fue creado como un proyecto de práctica para aplicar conocimientos de desarrollo web con PHP, HTML, CSS y JavaScript, implementando funcionalidades completas de un sistema de gestión de contenido.</p>
            </div>

            <div class="about-card">
                <h2>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    Tecnologías Utilizadas
                </h2>
                <p>Desarrollado con PHP para el backend, MySQL para la base de datos, HTML5 y CSS3 para el frontend, con JavaScript para las interacciones dinámicas. El diseño es completamente responsivo y moderno.</p>
            </div>

            <div class="about-card">
                <h2>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Aprendizaje Continuo
                </h2>
                <p>Este proyecto representa el esfuerzo continuo de Miguel García por mejorar sus habilidades en programación web, implementando buenas prácticas de desarrollo y patrones de diseño modernos.</p>
            </div>
        </div>

        <div class="team-section">
            <h2>Desarrollador</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="https://via.placeholder.com/120" alt="Miguel García">
                    <h3>Miguel García</h3>
                    <p>Desarrollador Full Stack<br>Creador del proyecto</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>