<a href="index.php?action=task_edit&id=<?= $t['id'] ?>" class="task-card block bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all cursor-pointer flex flex-col gap-3 group relative z-10 border-l-4 border-l-transparent hover:border-l-indigo-500">
    
    <!-- Header: Title & Department -->
    <div class="flex items-start justify-between gap-2">
        <h4 class="font-medium text-slate-800 dark:text-slate-100 task-title"><?= htmlspecialchars($t['name']) ?></h4>
        <?php if(!empty($t['department_name'])): ?>
            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] uppercase font-bold rounded-md shrink-0">
                <?= htmlspecialchars($t['department_name']) ?>
            </span>
        <?php endif; ?>
    </div>
    
    <!-- Detail Snippet -->
    <?php if(!empty($t['detail'])): ?>
        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2"><?= htmlspecialchars($t['detail']) ?></p>
    <?php endif; ?>

    <!-- Image Thumbnails -->
    <?php if(!empty($t['images'])): ?>
        <div class="flex flex-wrap gap-1 mt-1">
            <?php foreach(array_slice($t['images'], 0, 3) as $img): ?>
                <a href="<?= htmlspecialchars($img['file_path']) ?>" target="_blank" class="w-10 h-10 rounded-md overflow-hidden border border-slate-200 dark:border-slate-600 block">
                    <img src="<?= htmlspecialchars($img['file_path']) ?>" class="w-full h-full object-cover hover:scale-110 transition-transform">
                </a>
            <?php endforeach; ?>
            <?php if(count($t['images']) > 3): ?>
                <div class="w-10 h-10 rounded-md bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-500 dark:text-slate-300">
                    +<?= count($t['images']) - 3 ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Meta Info -->
    <div class="mt-auto pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
        
        <!-- Assignee -->
        <div class="flex items-center gap-1.5 task-assignee" title="Assigned to">
            <?php if($t['assignee_name']): ?>
                <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-bold text-[10px] shrink-0">
                    <?= strtoupper(substr($t['assignee_name'], 0, 2)) ?>
                </div>
                <span class="truncate max-w-[80px]"><?= htmlspecialchars($t['assignee_name']) ?></span>
            <?php else: ?>
                <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 flex items-center justify-center shrink-0">
                    <i class="ph ph-user"></i>
                </div>
                <span>Unassigned</span>
            <?php endif; ?>
        </div>

        <!-- Date or URL Icons -->
        <div class="flex items-center gap-2">
            <?php if(!empty($t['urls'])): ?>
                <div class="group/urls relative flex items-center">
                    <i class="ph ph-link text-sm text-blue-500"></i>
                    <span class="ml-0.5"><?= count($t['urls']) ?></span>
                    
                    <!-- Tooltip with all URLs -->
                    <div class="absolute bottom-full right-0 mb-2 w-48 bg-slate-800 text-white text-xs rounded-lg p-2 opacity-0 invisible group-hover/urls:opacity-100 group-hover/urls:visible transition-all z-50 shadow-lg">
                        <?php foreach($t['urls'] as $url): ?>
                            <a href="<?= htmlspecialchars($url['url']) ?>" target="_blank" class="block truncate hover:text-indigo-300 mb-1 last:mb-0">
                                <?= htmlspecialchars($url['url']) ?>
                            </a>
                        <?php endforeach; ?>
                        <div class="absolute top-full right-3 border-4 border-transparent border-t-slate-800"></div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($t['comment_count']) && $t['comment_count'] > 0): ?>
                <span class="flex items-center gap-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300" title="<?= $t['comment_count'] ?> Feedback">
                    <i class="ph ph-chat-teardrop text-sm"></i>
                    <span><?= $t['comment_count'] ?></span>
                </span>
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
    <div class="mt-2 text-xs flex justify-end relative z-20">
        <form action="index.php?action=task_update_status" method="POST" class="inline w-full text-right" onclick="event.preventDefault(); event.stopPropagation();">
            <input type="hidden" name="id" value="<?= $t['id'] ?>">
            <select name="status" onchange="this.form.submit()" class="bg-transparent border-none text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer focus:ring-0 py-0 pl-0 pr-6 text-xs text-right appearance-none" style="background-position: right 0.2rem center;">
                <option value="To Do" <?= $t['status'] == 'To Do' ? 'selected' : '' ?>>To Do</option>
                <option value="In Progress" <?= $t['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Done" <?= $t['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
            </select>
        </form>
    </div>
</a>
