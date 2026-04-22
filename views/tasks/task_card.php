<div class="task-card bg-white p-4 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow cursor-pointer flex flex-col gap-3 group relative">
    
    <!-- Actions Menu (hidden by default, shown on hover) -->
    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white p-1 rounded-lg shadow-sm border border-slate-100">
        <a href="index.php?action=task_edit&id=<?= $t['id'] ?>" class="text-slate-400 hover:text-indigo-600 p-1" title="Edit">
            <i class="ph ph-pencil-simple"></i>
        </a>
        <form action="index.php?action=task_delete" method="POST" onsubmit="return confirm('Delete this task?');" class="inline">
            <input type="hidden" name="id" value="<?= $t['id'] ?>">
            <button type="submit" class="text-slate-400 hover:text-red-600 p-1" title="Delete">
                <i class="ph ph-trash"></i>
            </button>
        </form>
    </div>

    <!-- Title -->
    <h4 class="font-medium text-slate-800 task-title pr-12"><?= htmlspecialchars($t['name']) ?></h4>
    
    <!-- Detail Snippet -->
    <?php if(!empty($t['detail'])): ?>
        <p class="text-sm text-slate-500 line-clamp-2"><?= htmlspecialchars($t['detail']) ?></p>
    <?php endif; ?>

    <!-- Meta Info -->
    <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
        
        <!-- Assignee -->
        <div class="flex items-center gap-1.5 task-assignee" title="Assigned to">
            <?php if($t['assignee_name']): ?>
                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-[10px]">
                    <?= strtoupper(substr($t['assignee_name'], 0, 2)) ?>
                </div>
                <span class="truncate max-w-[80px]"><?= htmlspecialchars($t['assignee_name']) ?></span>
            <?php else: ?>
                <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center">
                    <i class="ph ph-user"></i>
                </div>
                <span>Unassigned</span>
            <?php endif; ?>
        </div>

        <!-- Date or URL Icons -->
        <div class="flex items-center gap-2">
            <?php if(!empty($t['url'])): ?>
                <a href="<?= htmlspecialchars($t['url']) ?>" target="_blank" class="text-blue-500 hover:text-blue-700" title="Link">
                    <i class="ph ph-link text-sm"></i>
                </a>
            <?php endif; ?>
            
            <?php if(!empty($t['assignment_date'])): ?>
                <span class="flex items-center gap-1" title="Date Assigned">
                    <i class="ph ph-calendar-blank"></i>
                    <?= date('M d', strtotime($t['assignment_date'])) ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Quick Status Update -->
    <div class="mt-2 text-xs flex justify-end">
        <form action="index.php?action=task_update_status" method="POST" class="inline">
            <input type="hidden" name="id" value="<?= $t['id'] ?>">
            <select name="status" onchange="this.form.submit()" class="bg-transparent border-none text-slate-400 hover:text-indigo-600 cursor-pointer focus:ring-0 py-0 pl-0 pr-6 text-xs" style="background-position: right 0.2rem center;">
                <option value="To Do" <?= $t['status'] == 'To Do' ? 'selected' : '' ?>>Move to To Do</option>
                <option value="In Progress" <?= $t['status'] == 'In Progress' ? 'selected' : '' ?>>Move to In Progress</option>
                <option value="Done" <?= $t['status'] == 'Done' ? 'selected' : '' ?>>Move to Done</option>
            </select>
        </form>
    </div>
</div>
