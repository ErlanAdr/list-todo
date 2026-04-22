<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Phosphor Icons for modern icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="css/style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#f3f4f6',
                        dark: '#1e293b'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-dark text-white flex flex-col hidden md:flex transition-all duration-300" id="sidebar">
        <div class="h-16 flex items-center justify-center border-b border-slate-700">
            <h1 class="text-xl font-bold flex items-center gap-2">
                <i class="ph ph-kanban text-indigo-400 text-2xl"></i>
                TaskManager
            </h1>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1">
                <li>
                    <a href="index.php" class="flex items-center px-6 py-3 hover:bg-slate-800 transition-colors <?= (!isset($_GET['action']) || $_GET['action'] == 'index') ? 'bg-indigo-600 hover:bg-indigo-700 border-l-4 border-indigo-400' : '' ?>">
                        <i class="ph ph-squares-four mr-3 text-lg"></i>
                        Board
                    </a>
                </li>
                <li>
                    <a href="index.php?action=users" class="flex items-center px-6 py-3 hover:bg-slate-800 transition-colors <?= (isset($_GET['action']) && $_GET['action'] == 'users') ? 'bg-indigo-600 hover:bg-indigo-700 border-l-4 border-indigo-400' : '' ?>">
                        <i class="ph ph-users mr-3 text-lg"></i>
                        Users
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Mobile Header & Sidebar Overlay -->
    <div class="md:hidden fixed inset-0 z-40 bg-gray-900 bg-opacity-50 hidden" id="mobile-overlay" onclick="toggleSidebar()"></div>
    
    <aside class="md:hidden fixed inset-y-0 left-0 z-50 w-64 bg-dark text-white transform -translate-x-full transition-transform duration-300" id="mobile-sidebar">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700">
            <h1 class="text-xl font-bold flex items-center gap-2">
                <i class="ph ph-kanban text-indigo-400 text-2xl"></i>
                TaskManager
            </h1>
            <button onclick="toggleSidebar()" class="text-gray-400 hover:text-white">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1">
                <li>
                    <a href="index.php" class="flex items-center px-6 py-3 hover:bg-slate-800 transition-colors <?= (!isset($_GET['action']) || $_GET['action'] == 'index') ? 'bg-indigo-600' : '' ?>">
                        <i class="ph ph-squares-four mr-3 text-lg"></i>
                        Board
                    </a>
                </li>
                <li>
                    <a href="index.php?action=users" class="flex items-center px-6 py-3 hover:bg-slate-800 transition-colors <?= (isset($_GET['action']) && $_GET['action'] == 'users') ? 'bg-indigo-600' : '' ?>">
                        <i class="ph ph-users mr-3 text-lg"></i>
                        Users
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 shrink-0 z-10">
            <div class="flex items-center">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="ph ph-list text-2xl"></i>
                </button>
                <h2 class="ml-4 md:ml-0 text-xl font-semibold text-gray-800 capitalize">
                    <?= isset($_GET['action']) ? str_replace('_', ' ', $_GET['action']) : 'Task Board' ?>
                </h2>
            </div>
            <div class="flex items-center gap-4">
                <a href="index.php?action=task_create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                    <i class="ph ph-plus-circle text-lg"></i>
                    New Task
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-6">
            
            <!-- Notifications -->
            <?php if (isset($_SESSION['message'])): ?>
                <div id="notification" class="mb-6 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between <?= $_SESSION['msg_type'] === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' ?>">
                    <div class="flex items-center gap-2">
                        <?php if ($_SESSION['msg_type'] === 'success'): ?>
                            <i class="ph ph-check-circle text-xl text-green-600"></i>
                        <?php else: ?>
                            <i class="ph ph-warning-circle text-xl text-red-600"></i>
                        <?php endif; ?>
                        <span><?= $_SESSION['message']; ?></span>
                    </div>
                    <button onclick="document.getElementById('notification').style.display='none'" class="text-gray-500 hover:text-gray-700">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
                <?php 
                unset($_SESSION['message']);
                unset($_SESSION['msg_type']);
                endif; 
            ?>

            <!-- Inject specific view content here -->
            <?php require_once $content; ?>
            
        </div>
    </main>

    <script src="js/app.js"></script>
</body>
</html>
