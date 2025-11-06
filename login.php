<?php
session_start();
require_once 'config.php';

$error = '';
$debug_info = '';

// Procesar login (VULNERABLE)
if ($_POST['action'] ?? '' === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username && $password) {
        // VULNERABLE: Consulta SQL sin preparar - NUNCA hacer esto en producción
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        
        // DEBUG: Mostrar la consulta para ver inyecciones
        $debug_info = "CONSULTA SQL EJECUTADA: " . $query;
        
        try {
            $stmt = $pdo->query($query);
            $user = $stmt->fetch();
            
            if ($user) {
                // Login exitoso
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Actualizar último login (también vulnerable)
                $update_query = "UPDATE users SET last_login = NOW() WHERE id = " . $user['id'];
                $pdo->query($update_query);
                
                header('Location: admin.php');
                exit;
            } else {
                $error = "Credenciales incorrectas.";
            }
        } catch (PDOException $e) {
            $error = "Error en el login: " . $e->getMessage();
        }
    } else {
        $error = "Usuario y contraseña son obligatorios.";
    }
}

// Procesar logout
if ($_GET['action'] ?? '' === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-container {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--bg-gradient);
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            padding: 2.5rem;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .login-logo {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-logo svg {
            width: 32px;
            height: 32px;
            color: white;
        }
        .login-subtitle {
            color: var(--text-muted);
            margin-top: 0.5rem;
        }
        .input-with-icon {
            position: relative;
            display: block;
            width: 100%;
        }
        .input-with-icon svg {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--text-muted);
            pointer-events: none;
            z-index: 2;
        }
        .login-form {
            margin-top: 2rem;
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
        .form-group input {
            width: 100%;
            height: 48px;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            background: var(--input-bg);
            transition: all 0.3s ease;
        }
        .input-with-icon input {
            padding-left: 3rem !important;
        }
        .form-group input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
        }
        .alert {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .alert.error {
            background: var(--error-bg);
            color: var(--error-text);
            border: 1px solid var(--error-border);
        }
        .alert svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        .login-button {
            width: 100%;
            padding: 0.875rem;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .login-button:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
        }
        .login-button svg {
            width: 20px;
            height: 20px;
        }
        .login-footer {
            margin-top: 2rem;
            text-align: center;
            color: var(--text-muted);
        }
        .text-accent {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
        }
        .text-accent:hover {
            text-decoration: underline;
        }
        .secure-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
        .secure-login svg {
            width: 16px;
            height: 16px;
            color: var(--success-color);
        }
        .debug-info {
            background: var(--warning-bg);
            border: 1px solid var(--warning-border);
            padding: 1rem;
            margin-top: 1rem;
            border-radius: 0.5rem;
            font-family: monospace;
            font-size: 0.875rem;
            color: var(--warning-text);
        }
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            color: #155724;
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
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="login.php" class="active">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Login
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h2>¡Bienvenido de nuevo!</h2>
                <p class="login-subtitle">Por favor, inicia sesión en tu cuenta</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert error">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            

            
            <form method="POST" class="login-form">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <div class="input-with-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input type="text" id="username" name="username" required
                               placeholder="Introduce tu nombre de usuario">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-with-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" id="password" name="password" required
                               placeholder="Introduce tu contraseña">
                    </div>
                </div>
                <button type="submit" class="login-button">
                    <span>Iniciar Sesión</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <div class="login-footer">
                <p>¿No tienes una cuenta? <a href="#" class="text-accent">Contacta al Administrador</a></p>
                <div class="secure-login">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Sistema de Acceso Seguro</span>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>