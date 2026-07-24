<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
    } elseif (strlen($username) < 4 || strlen($password) < 6) {
        $error = 'Username must be at least 4 characters and password at least 6 characters';
    } else {
        if (function_exists('authenticateUser') && authenticateUser($username, $password)) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ERP System</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-p6vZ+r4Z0xgW1JY5wDr+K3PTrSsB7z7Yd7d9APf5b4j2XlqK52pV+ldFzEa4bskpX7+HtB9M7N1NNc1q14r3Mg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
            visibility: hidden;
            opacity: 0;
        }
        .auth-card {
            background: white;
            border-radius: 1.25rem;
            box-shadow: 0 24px 80px rgba(15, 23, 42, 0.12);
        }
    </style>
</head>
<body class="flex min-h-screen items-center justify-center px-4 py-8">
    <div class="w-full max-w-lg">
        <div class="mb-10 text-center">
            <p class="text-sm uppercase tracking-[0.35em] text-cyan-700">ERP SYSTEM</p>
            <h1 class="mt-4 text-4xl font-semibold text-slate-950">Welcome back</h1>
            <p class="mt-3 text-sm text-slate-500">Sign in to access your ERP dashboard.</p>
        </div>
        <div class="auth-card overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white p-8">
            <?php if (isset($error)): ?>
            <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <i class="fas fa-exclamation-circle mr-2"></i> <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/login" class="space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Username</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                        <input name="username" type="text" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm text-slate-900 outline-none transition focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200" placeholder="Your username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                        <input name="password" type="password" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm text-slate-900 outline-none transition focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200" placeholder="Enter your password" />
                    </div>
                </div>
                <button type="submit" class="w-full rounded-3xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white py-3.5 text-sm font-semibold shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-base">login</span>
                    <span>Sign In</span>
                </button>
            </form>
            <p class="mt-6 text-center text-sm text-slate-500">New here? <a href="<?= BASE_URL ?>/register" class="font-semibold text-blue-600 hover:text-blue-700">Create an account</a></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.body.style.visibility = 'visible';
            document.body.style.opacity = '1';
        });
    </script>
</body>
</html>
