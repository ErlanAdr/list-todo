<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                    }
                }
            }
        }
        
        // Initial dark mode check for login page
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 font-sans text-slate-800 dark:text-slate-200 h-screen flex items-center justify-center relative overflow-hidden transition-colors duration-300">
    
    <!-- Interactive Background Fidget (Simple bouncing circles) -->
    <div id="fidget-canvas" class="absolute inset-0 pointer-events-none opacity-50 dark:opacity-20 z-0"></div>

    <div class="max-w-md w-full bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 z-10">
        <div class="text-center mb-8">
            <i class="ph ph-kanban text-indigo-500 text-5xl mb-2"></i>
            <h2 class="text-2xl font-bold">Welcome Back</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Please sign in to your account</p>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="mb-4 px-4 py-3 rounded-lg text-sm <?= $_SESSION['msg_type'] === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800 dark:bg-red-900/30 dark:text-red-400' ?>">
                <?= $_SESSION['message']; ?>
            </div>
            <?php 
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
            endif; 
        ?>

        <form action="index.php?action=login" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text" name="username" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors">
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors mt-2">
                Sign In
            </button>
        </form>
    </div>

    <!-- Minimal physics script for the background fidget -->
    <script>
        // Simple ambient floating particles that react to mouse
        const canvas = document.getElementById('fidget-canvas');
        let particles = [];
        let mouse = { x: window.innerWidth / 2, y: window.innerHeight / 2 };

        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        for (let i = 0; i < 15; i++) {
            let el = document.createElement('div');
            let size = Math.random() * 40 + 10;
            el.className = 'absolute rounded-full bg-indigo-500 blur-xl transition-transform duration-75';
            el.style.width = size + 'px';
            el.style.height = size + 'px';
            canvas.appendChild(el);
            
            particles.push({
                el: el,
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                vx: (Math.random() - 0.5) * 2,
                vy: (Math.random() - 0.5) * 2
            });
        }

        function animate() {
            particles.forEach(p => {
                // Move
                p.x += p.vx;
                p.y += p.vy;
                
                // Bounce
                if (p.x <= 0 || p.x >= window.innerWidth) p.vx *= -1;
                if (p.y <= 0 || p.y >= window.innerHeight) p.vy *= -1;

                // Attract slightly to mouse
                let dx = mouse.x - p.x;
                let dy = mouse.y - p.y;
                p.vx += dx * 0.0001;
                p.vy += dy * 0.0001;
                
                // Friction limit
                p.vx *= 0.99;
                p.vy *= 0.99;

                p.el.style.transform = `translate(${p.x}px, ${p.y}px)`;
            });
            requestAnimationFrame(animate);
        }
        animate();
    </script>
</body>
</html>
