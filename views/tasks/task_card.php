<div onclick="window.location.href='index.php?action=task_view&id=<?= $t['id'] ?>'" class="task-card block bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all cursor-pointer flex flex-col gap-3 group relative z-10 border-l-4 border-l-transparent hover:border-l-indigo-500">
    
    <!-- Header: Title & Department -->
    <div class="flex items-start justify-between gap-2">
        <h4 class="font-semibold text-slate-800 dark:text-slate-100 text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
            <?= htmlspecialchars($t['name']) ?>
        </h4>
        <?php if (!empty($t['department_name'])): ?>
            <span class="shrink-0 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                <?= htmlspecialchars($t['department_name']) ?>
            </span>
        <?php endif; ?>
    </div>
    
    <!-- Review Status Badge -->
    <?php if (isset($t['review_status']) && $t['review_status'] !== 'None'): ?>
        <div>
            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-1 rounded-md uppercase tracking-wider
                <?php 
                    if ($t['review_status'] === 'Perlu Direview') echo 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300 border border-purple-200 dark:border-purple-700/50';
                    elseif ($t['review_status'] === 'Perlu Direvisi') echo 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200 dark:border-amber-700/50';
                    elseif ($t['review_status'] === 'Sudah Approve') echo 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700/50';
                ?>">
                <i class="ph ph-tag"></i> <?= htmlspecialchars($t['review_status']) ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- Description Snippet -->
    <?php if(!empty($t['detail'])): ?>
        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
            <?= htmlspecialchars($t['detail']) ?>
        </p>
    <?php endif; ?>
    
    <!-- Meta Data -->
    <div class="flex items-center justify-between mt-auto pt-2">
        <div class="flex items-center gap-3">
            <div class="flex -space-x-2">
                <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[10px] font-bold text-indigo-700 dark:text-indigo-300" title="<?= htmlspecialchars($t['assignee_name'] ?? 'Unassigned') ?>">
                    <?= !empty($t['assignee_name']) ? strtoupper(substr($t['assignee_name'], 0, 2)) : '?' ?>
                </div>
            </div>
            
            <?php if($t['comment_count'] > 0): ?>
            <div class="flex items-center gap-1 text-slate-400 dark:text-slate-500 text-xs">
                <i class="ph ph-chat-teardrop"></i>
                <span><?= $t['comment_count'] ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if(!empty($t['assignment_date'])): ?>
        <div class="flex items-center gap-1 text-xs font-medium <?= strtotime($t['assignment_date']) < time() && $t['status'] != 'Done' ? 'text-red-500' : 'text-slate-400 dark:text-slate-500' ?>">
            <i class="ph ph-clock"></i>
            <?= date('M d', strtotime($t['assignment_date'])) ?>
        </div>
        <?php endif; ?>
    </div>
    
    <hr class="border-slate-100 dark:border-slate-700/50 my-1">

    <!-- Quick Status Updates (Hidden for guest) -->
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'guest'): ?>
    <div class="mt-1 flex justify-between items-center relative z-20">
        
        <!-- Board Status Dropdown -->
        <div class="flex-1">
            <form action="index.php?action=task_update_status" method="POST" onclick="event.preventDefault(); event.stopPropagation();">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <select name="status" onchange="this.form.submit()" class="w-full bg-transparent border border-slate-200 dark:border-slate-700 rounded text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer focus:ring-0 py-1 pl-2 pr-6 text-[10px] uppercase font-bold tracking-wider appearance-none" style="background-position: right 0.2rem center;">
                    <option value="To Do" <?= $t['status'] == 'To Do' ? 'selected' : '' ?>>To Do</option>
                    <option value="In Progress" <?= $t['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Done" <?= $t['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                </select>
            </form>
        </div>

        <!-- Review Status Dropdown -->
        <div class="flex-1 ml-2">
            <form action="index.php?action=task_update_review_status" method="POST" onclick="event.preventDefault(); event.stopPropagation();">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <select name="review_status" onchange="this.form.submit()" class="w-full bg-transparent border border-slate-200 dark:border-slate-700 rounded text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer focus:ring-0 py-1 pl-2 pr-6 text-[10px] uppercase font-bold tracking-wider appearance-none" style="background-position: right 0.2rem center;">
                    <option value="None" <?= ($t['review_status'] ?? 'None') == 'None' ? 'selected' : '' ?>>- Label -</option>
                    <option value="Perlu Direview" <?= ($t['review_status'] ?? 'None') == 'Perlu Direview' ? 'selected' : '' ?>>Direview</option>
                    <option value="Perlu Direvisi" <?= ($t['review_status'] ?? 'None') == 'Perlu Direvisi' ? 'selected' : '' ?>>Direvisi</option>
                    <option value="Sudah Approve" <?= ($t['review_status'] ?? 'None') == 'Sudah Approve' ? 'selected' : '' ?>>Approve</option>
                </select>
            </form>
        </div>

    </div>
    <?php else: ?>
    <div class="mt-1 flex justify-between text-xs">
        <span class="text-slate-400 dark:text-slate-500 font-medium"><?= htmlspecialchars($t['status']) ?></span>
    </div>
    <?php endif; ?>
</div>
