<div class="mb-6 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors relative z-10">
    <form action="index.php" method="GET" class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <input type="hidden" name="action" value="index">
        
        <!-- Search & Filter Controls -->
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <!-- Client-side text search -->
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="ph ph-magnifying-glass text-gray-400"></i>
                </div>
                <input type="text" id="searchInput" onkeyup="filterTasks()" placeholder="Search tasks..." class="pl-10 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 border transition-colors">
            </div>

            <!-- Server-side filters -->
            <div class="flex items-center gap-2 w-full md:w-auto">
                <select name="assigned_to" onchange="this.form.submit()" class="w-full md:w-auto border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 pl-3 pr-8 border transition-colors text-sm">
                    <option value="">All Users</option>
                    <?php foreach($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= isset($_GET['assigned_to']) && $_GET['assigned_to'] == $u['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="date" name="date" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '' ?>" onchange="this.form.submit()" class="w-full md:w-auto border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors text-sm">
                
                <?php if(!empty($_GET['assigned_to']) || !empty($_GET['date'])): ?>
                    <a href="index.php" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-slate-100 dark:bg-slate-700 rounded-lg transition-colors" title="Clear Filters">
                        <i class="ph ph-x"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full items-start relative z-10">
    
    <!-- To Do Column -->
    <div class="bg-slate-100 dark:bg-slate-800/50 rounded-xl p-4 min-h-[500px] border border-slate-200 dark:border-slate-700 transition-colors">
        <h3 class="font-semibold text-slate-700 dark:text-slate-300 mb-4 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                To Do
            </span>
            <span class="bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold px-2 py-1 rounded-full"><?= count($board['To Do']) ?></span>
        </h3>
        
        <div class="space-y-3 task-list">
            <?php foreach($board['To Do'] as $t): ?>
                <?php include 'task_card.php'; ?>
            <?php endforeach; ?>
            <?php if(empty($board['To Do'])): ?>
                <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center text-slate-400 dark:text-slate-500 text-sm">
                    No tasks here
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- In Progress Column -->
    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-4 min-h-[500px] border border-indigo-100 dark:border-indigo-800/30 transition-colors">
        <h3 class="font-semibold text-indigo-900 dark:text-indigo-300 mb-4 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                In Progress
            </span>
            <span class="bg-indigo-200 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 text-xs font-bold px-2 py-1 rounded-full"><?= count($board['In Progress']) ?></span>
        </h3>
        
        <div class="space-y-3 task-list">
            <?php foreach($board['In Progress'] as $t): ?>
                <?php include 'task_card.php'; ?>
            <?php endforeach; ?>
            <?php if(empty($board['In Progress'])): ?>
                <div class="border-2 border-dashed border-indigo-200 dark:border-indigo-800/50 rounded-lg p-6 text-center text-indigo-400 dark:text-indigo-500 text-sm">
                    No tasks here
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Done Column -->
    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 min-h-[500px] border border-green-100 dark:border-green-800/30 transition-colors">
        <h3 class="font-semibold text-green-900 dark:text-green-300 mb-4 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                Done
            </span>
            <span class="bg-green-200 dark:bg-green-900/50 text-green-700 dark:text-green-400 text-xs font-bold px-2 py-1 rounded-full"><?= count($board['Done']) ?></span>
        </h3>
        
        <div class="space-y-3 task-list">
            <?php foreach($board['Done'] as $t): ?>
                <?php include 'task_card.php'; ?>
            <?php endforeach; ?>
            <?php if(empty($board['Done'])): ?>
                <div class="border-2 border-dashed border-green-200 dark:border-green-800/50 rounded-lg p-6 text-center text-green-400 dark:text-green-500 text-sm">
                    No tasks here
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function filterTasks() {
        let input = document.getElementById('searchInput');
        let filter = input.value.toLowerCase();
        let cards = document.querySelectorAll('.task-card');

        cards.forEach(function(card) {
            let title = card.querySelector('.task-title').innerText.toLowerCase();
            let assignee = card.querySelector('.task-assignee') ? card.querySelector('.task-assignee').innerText.toLowerCase() : '';
            if (title.indexOf(filter) > -1 || assignee.indexOf(filter) > -1) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    }
</script>
