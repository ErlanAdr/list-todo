<div class="mb-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
    <div class="relative w-full sm:w-64">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="ph ph-magnifying-glass text-gray-400"></i>
        </div>
        <input type="text" id="searchInput" onkeyup="filterTasks()" placeholder="Search tasks..." class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 border">
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full items-start">
    
    <!-- To Do Column -->
    <div class="bg-slate-100 rounded-xl p-4 min-h-[500px] border border-slate-200">
        <h3 class="font-semibold text-slate-700 mb-4 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                To Do
            </span>
            <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-1 rounded-full"><?= count($board['To Do']) ?></span>
        </h3>
        
        <div class="space-y-3 task-list">
            <?php foreach($board['To Do'] as $t): ?>
                <?php include 'task_card.php'; ?>
            <?php endforeach; ?>
            <?php if(empty($board['To Do'])): ?>
                <div class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center text-slate-400 text-sm">
                    No tasks here
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- In Progress Column -->
    <div class="bg-indigo-50 rounded-xl p-4 min-h-[500px] border border-indigo-100">
        <h3 class="font-semibold text-indigo-900 mb-4 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                In Progress
            </span>
            <span class="bg-indigo-200 text-indigo-700 text-xs font-bold px-2 py-1 rounded-full"><?= count($board['In Progress']) ?></span>
        </h3>
        
        <div class="space-y-3 task-list">
            <?php foreach($board['In Progress'] as $t): ?>
                <?php include 'task_card.php'; ?>
            <?php endforeach; ?>
            <?php if(empty($board['In Progress'])): ?>
                <div class="border-2 border-dashed border-indigo-200 rounded-lg p-6 text-center text-indigo-400 text-sm">
                    No tasks here
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Done Column -->
    <div class="bg-green-50 rounded-xl p-4 min-h-[500px] border border-green-100">
        <h3 class="font-semibold text-green-900 mb-4 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                Done
            </span>
            <span class="bg-green-200 text-green-700 text-xs font-bold px-2 py-1 rounded-full"><?= count($board['Done']) ?></span>
        </h3>
        
        <div class="space-y-3 task-list">
            <?php foreach($board['Done'] as $t): ?>
                <?php include 'task_card.php'; ?>
            <?php endforeach; ?>
            <?php if(empty($board['Done'])): ?>
                <div class="border-2 border-dashed border-green-200 rounded-lg p-6 text-center text-green-400 text-sm">
                    No tasks here
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Auto-hide notification script -->
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
