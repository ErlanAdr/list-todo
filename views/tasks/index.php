<div class="mb-6 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
        <i class="ph ph-kanban text-indigo-500"></i> Board
    </h2>
    
    <!-- Filter Bar -->
    <form action="index.php" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto bg-white dark:bg-slate-800 p-2 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center bg-slate-50 dark:bg-slate-900/50 rounded-lg px-3 py-1.5 border border-slate-200 dark:border-slate-700 w-full sm:w-auto">
            <i class="ph ph-users text-slate-400 mr-2"></i>
            <select name="assigned_to" class="bg-transparent border-none text-sm text-slate-600 dark:text-slate-300 focus:ring-0 cursor-pointer w-full" onchange="this.form.submit()">
                <option value="">All Assignees</option>
                <?php foreach($users as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= (isset($_GET['assigned_to']) && $_GET['assigned_to'] == $u['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="flex items-center bg-slate-50 dark:bg-slate-900/50 rounded-lg px-3 py-1.5 border border-slate-200 dark:border-slate-700 w-full sm:w-auto">
            <i class="ph ph-calendar-blank text-slate-400 mr-2"></i>
            <input type="date" name="date" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '' ?>" class="bg-transparent border-none text-sm text-slate-600 dark:text-slate-300 focus:ring-0 cursor-pointer w-full" onchange="this.form.submit()">
        </div>
        
        <?php if((isset($_GET['assigned_to']) && $_GET['assigned_to'] !== '') || (isset($_GET['date']) && $_GET['date'] !== '')): ?>
            <a href="index.php" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Clear Filters">
                <i class="ph ph-x-circle text-lg"></i>
            </a>
        <?php endif; ?>
        
        <div class="flex items-center gap-3 ml-auto">
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'guest'): ?>
            <a href="index.php?action=task_create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-2 whitespace-nowrap">
                <i class="ph ph-plus-circle text-lg"></i>
                <span class="hidden sm:inline">New Task</span>
            </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Board Columns (5 Columns with Horizontal Scroll) -->
<div class="flex flex-nowrap overflow-x-auto gap-6 pb-6 pt-2 snap-x hide-scrollbar relative z-10 min-h-[60vh]">
    
    <!-- TO DO Column -->
    <div class="min-w-[320px] w-[320px] shrink-0 snap-start bg-slate-50/80 dark:bg-slate-900/40 rounded-2xl p-4 border-t-4 border-slate-400">
        <div class="flex items-center justify-between mb-4 px-2">
            <h3 class="font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> To Do
            </h3>
            <span class="bg-white dark:bg-slate-800 text-slate-500 text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                <?= count($board['To Do'] ?? []) ?>
            </span>
        </div>
        <div class="space-y-4">
            <?php 
            if(empty($board['To Do'])) echo '<div class="text-center py-8 text-sm text-slate-400 dark:text-slate-600 border-2 border-dashed border-slate-200 dark:border-slate-700/50 rounded-xl">No tasks yet</div>';
            foreach(($board['To Do'] ?? []) as $t) {
                require 'task_card.php';
            }
            ?>
        </div>
    </div>

    <!-- IN PROGRESS Column -->
    <div class="min-w-[320px] w-[320px] shrink-0 snap-start bg-slate-50/80 dark:bg-slate-900/40 rounded-2xl p-4 border-t-4 border-indigo-500">
        <div class="flex items-center justify-between mb-4 px-2">
            <h3 class="font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> In Progress
            </h3>
            <span class="bg-white dark:bg-slate-800 text-slate-500 text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                <?= count($board['In Progress'] ?? []) ?>
            </span>
        </div>
        <div class="space-y-4">
            <?php 
            if(empty($board['In Progress'])) echo '<div class="text-center py-8 text-sm text-slate-400 dark:text-slate-600 border-2 border-dashed border-slate-200 dark:border-slate-700/50 rounded-xl">No tasks yet</div>';
            foreach(($board['In Progress'] ?? []) as $t) {
                require 'task_card.php';
            }
            ?>
        </div>
    </div>
    
    <!-- PERLU DIREVIEW Column -->
    <div class="min-w-[320px] w-[320px] shrink-0 snap-start bg-slate-50/80 dark:bg-slate-900/40 rounded-2xl p-4 border-t-4 border-purple-500">
        <div class="flex items-center justify-between mb-4 px-2">
            <h3 class="font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Perlu Direview
            </h3>
            <span class="bg-white dark:bg-slate-800 text-slate-500 text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                <?= count($board['Perlu Direview'] ?? []) ?>
            </span>
        </div>
        <div class="space-y-4">
            <?php 
            if(empty($board['Perlu Direview'])) echo '<div class="text-center py-8 text-sm text-slate-400 dark:text-slate-600 border-2 border-dashed border-slate-200 dark:border-slate-700/50 rounded-xl">No tasks yet</div>';
            foreach(($board['Perlu Direview'] ?? []) as $t) {
                require 'task_card.php';
            }
            ?>
        </div>
    </div>
    
    <!-- PERLU DIREVISI Column -->
    <div class="min-w-[320px] w-[320px] shrink-0 snap-start bg-slate-50/80 dark:bg-slate-900/40 rounded-2xl p-4 border-t-4 border-amber-500">
        <div class="flex items-center justify-between mb-4 px-2">
            <h3 class="font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Perlu Direvisi
            </h3>
            <span class="bg-white dark:bg-slate-800 text-slate-500 text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                <?= count($board['Perlu Direvisi'] ?? []) ?>
            </span>
        </div>
        <div class="space-y-4">
            <?php 
            if(empty($board['Perlu Direvisi'])) echo '<div class="text-center py-8 text-sm text-slate-400 dark:text-slate-600 border-2 border-dashed border-slate-200 dark:border-slate-700/50 rounded-xl">No tasks yet</div>';
            foreach(($board['Perlu Direvisi'] ?? []) as $t) {
                require 'task_card.php';
            }
            ?>
        </div>
    </div>

    <!-- SUDAH APPROVE Column -->
    <div class="min-w-[320px] w-[320px] shrink-0 snap-start bg-slate-50/80 dark:bg-slate-900/40 rounded-2xl p-4 border-t-4 border-emerald-500">
        <div class="flex items-center justify-between mb-4 px-2">
            <h3 class="font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Sudah Approve
            </h3>
            <span class="bg-white dark:bg-slate-800 text-slate-500 text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                <?= count($board['Sudah Approve'] ?? []) ?>
            </span>
        </div>
        <div class="space-y-4">
            <?php 
            if(empty($board['Sudah Approve'])) echo '<div class="text-center py-8 text-sm text-slate-400 dark:text-slate-600 border-2 border-dashed border-slate-200 dark:border-slate-700/50 rounded-xl">No tasks yet</div>';
            foreach(($board['Sudah Approve'] ?? []) as $t) {
                require 'task_card.php';
            }
            ?>
        </div>
    </div>

</div>

<style>
/* Custom scrollbar for horizontal board */
.hide-scrollbar::-webkit-scrollbar {
    height: 8px;
}
.hide-scrollbar::-webkit-scrollbar-track {
    background: transparent; 
}
.hide-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.3); 
    border-radius: 10px;
}
.hide-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(156, 163, 175, 0.5); 
}
</style>
