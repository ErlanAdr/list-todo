<?php
// Tentukan warna border card berdasarkan status utama
$cardBorderColor = 'border-slate-200 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-500'; // Default To Do
$accentLineColor = 'border-l-slate-400';

if ($t['status'] === 'In Progress') {
    $cardBorderColor = 'border-indigo-200 dark:border-indigo-800 hover:border-indigo-400 dark:hover:border-indigo-500';
    $accentLineColor = 'border-l-indigo-500';
} elseif ($t['status'] === 'Done') {
    $cardBorderColor = 'border-emerald-200 dark:border-emerald-800 hover:border-emerald-400 dark:hover:border-emerald-500';
    $accentLineColor = 'border-l-emerald-500';
}
?>

<div onclick="window.location.href='index.php?action=task_view&id=<?= $t['id'] ?>'" class="task-card block bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border <?= $cardBorderColor ?> hover:shadow-md transition-all cursor-pointer flex flex-col gap-3 group relative z-10 border-l-4 <?= $accentLineColor ?>">
    
    <!-- Header: Title & Department -->
    <div class="flex items-start justify-between gap-2">
        <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
            <?= htmlspecialchars($t['name']) ?>
        </h4>
        <?php if (!empty($t['department_name'])): ?>
            <span class="shrink-0 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider border border-slate-200 dark:border-slate-600">
                <?= htmlspecialchars($t['department_name']) ?>
            </span>
        <?php endif; ?>
    </div>
    
    <!-- Review Status Badge -->
    <?php if (isset($t['review_status']) && $t['review_status'] !== 'None'): ?>
        <div>
            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider
                <?php 
                    if ($t['review_status'] === 'Perlu Direview') echo 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-700/50 shadow-sm';
                    elseif ($t['review_status'] === 'Perlu Direvisi') echo 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-700/50 shadow-sm';
                    elseif ($t['review_status'] === 'Sudah Approve') echo 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700/50 shadow-sm';
                ?>">
                <i class="ph ph-tag-fill"></i> <?= htmlspecialchars($t['review_status']) ?>
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
                <div class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-700 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-slate-300 shadow-sm" title="<?= htmlspecialchars($t['assignee_name'] ?? 'Unassigned') ?>">
                    <?= !empty($t['assignee_name']) ? strtoupper(substr($t['assignee_name'], 0, 2)) : '?' ?>
                </div>
            </div>
            
            <?php if($t['comment_count'] > 0): ?>
            <div class="flex items-center gap-1 text-slate-500 dark:text-slate-400 text-xs font-medium">
                <i class="ph ph-chat-circle-dots"></i>
                <span><?= $t['comment_count'] ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if(!empty($t['assignment_date'])): ?>
        <div class="flex items-center gap-1 text-xs font-medium <?= strtotime($t['assignment_date']) < time() && $t['status'] != 'Done' ? 'text-rose-500' : 'text-slate-500 dark:text-slate-400' ?>">
            <i class="ph ph-calendar-check"></i>
            <?= date('M d', strtotime($t['assignment_date'])) ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="border-t border-slate-100 dark:border-slate-700/50 my-2"></div>

    <!-- Quick Status Updates (Hidden for guest) -->
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'guest'): ?>
    <div class="flex gap-2 items-center relative z-20">
        
        <!-- Board Status Dropdown -->
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-slate-400">
                <i class="ph ph-kanban"></i>
            </div>
            <form action="index.php?action=task_update_status" method="POST" onclick="event.preventDefault(); event.stopPropagation();" class="m-0">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <select name="status" onchange="this.form.submit()" class="w-full bg-slate-50 hover:bg-slate-100 dark:bg-slate-900/50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-600 dark:text-slate-300 cursor-pointer focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-1.5 pl-7 pr-6 text-[10px] uppercase font-bold tracking-wider appearance-none shadow-sm transition-colors" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.5rem top 50%; background-size: 0.5rem auto;">
                    <option value="To Do" <?= $t['status'] == 'To Do' ? 'selected' : '' ?>>To Do</option>
                    <option value="In Progress" <?= $t['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Done" <?= $t['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                </select>
            </form>
        </div>

        <!-- Review Status Dropdown -->
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-slate-400">
                <i class="ph ph-tag"></i>
            </div>
            <form action="index.php?action=task_update_review_status" method="POST" onclick="event.preventDefault(); event.stopPropagation();" class="m-0">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <select name="review_status" onchange="this.form.submit()" class="w-full bg-slate-50 hover:bg-slate-100 dark:bg-slate-900/50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-600 dark:text-slate-300 cursor-pointer focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-1.5 pl-7 pr-6 text-[10px] uppercase font-bold tracking-wider appearance-none shadow-sm transition-colors" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.5rem top 50%; background-size: 0.5rem auto;">
                    <option value="None" <?= ($t['review_status'] ?? 'None') == 'None' ? 'selected' : '' ?>>Label</option>
                    <option value="Perlu Direview" <?= ($t['review_status'] ?? 'None') == 'Perlu Direview' ? 'selected' : '' ?>>Direview</option>
                    <option value="Perlu Direvisi" <?= ($t['review_status'] ?? 'None') == 'Perlu Direvisi' ? 'selected' : '' ?>>Direvisi</option>
                    <option value="Sudah Approve" <?= ($t['review_status'] ?? 'None') == 'Sudah Approve' ? 'selected' : '' ?>>Approve</option>
                </select>
            </form>
        </div>

    </div>
    <?php else: ?>
    <div class="flex justify-between text-xs mt-1">
        <span class="text-slate-400 dark:text-slate-500 font-medium"><i class="ph ph-kanban"></i> <?= htmlspecialchars($t['status']) ?></span>
    </div>
    <?php endif; ?>
</div>
