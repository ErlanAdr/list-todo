<div class="max-w-3xl mx-auto mb-4 relative z-10 flex items-center justify-between">
    <a href="index.php" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-colors">
        <i class="ph ph-arrow-left"></i> Back to Board
    </a>
    
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'guest'): ?>
    <div class="flex items-center gap-3">
        <a href="index.php?action=task_edit&id=<?= $task['id'] ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-2">
            <i class="ph ph-pencil-simple"></i> Edit Task
        </a>
        <form action="index.php?action=task_delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.');" class="inline m-0">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <i class="ph ph-trash"></i> Delete
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>

<div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors relative z-10 overflow-hidden">
    <!-- Header Area -->
    <div class="p-8 border-b border-slate-100 dark:border-slate-700/50">
        <div class="flex items-start justify-between gap-4">
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 leading-tight">
                <?= htmlspecialchars($task['name']) ?>
            </h1>
            
            <div class="flex flex-col items-end gap-2 shrink-0">
                <!-- Board Status -->
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    <?php 
                        if ($task['status'] === 'To Do') echo 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300';
                        elseif ($task['status'] === 'In Progress') echo 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300';
                        else echo 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300';
                    ?>">
                    <i class="ph ph-kanban"></i> <?= htmlspecialchars($task['status']) ?>
                </span>
                
                <!-- Review Status -->
                <?php if (isset($task['review_status']) && $task['review_status'] !== 'None'): ?>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border
                    <?php 
                        if ($task['review_status'] === 'Perlu Direview') echo 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300 border-purple-200 dark:border-purple-700/50';
                        elseif ($task['review_status'] === 'Perlu Direvisi') echo 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 border-amber-200 dark:border-amber-700/50';
                        elseif ($task['review_status'] === 'Sudah Approve') echo 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 border-emerald-200 dark:border-emerald-700/50';
                    ?>">
                    <i class="ph ph-tag"></i> <?= htmlspecialchars($task['review_status']) ?>
                </span>
                <?php endif; ?>
                
                <?php if (!empty($task['department_name'])): ?>
                    <span class="text-xs font-medium text-slate-500 flex items-center gap-1 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 px-2 py-0.5 rounded">
                        <i class="ph ph-buildings"></i> <?= htmlspecialchars($task['department_name']) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="flex items-center gap-6 mt-6 text-sm text-slate-500 dark:text-slate-400">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-600 dark:text-slate-300">
                    <?= !empty($task['assignee_name']) ? strtoupper(substr($task['assignee_name'], 0, 2)) : '?' ?>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wider text-slate-400">Assignee</div>
                    <div class="font-medium text-slate-700 dark:text-slate-200"><?= !empty($task['assignee_name']) ? htmlspecialchars($task['assignee_name']) : 'Unassigned' ?></div>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="ph ph-calendar-blank text-lg"></i>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wider text-slate-400">Assign Date</div>
                    <div class="font-medium text-slate-700 dark:text-slate-200"><?= !empty($task['assignment_date']) ? date('d M Y', strtotime($task['assignment_date'])) : 'No Date' ?></div>
                </div>
            </div>
            
            <div class="ml-auto text-right">
                <div class="text-xs text-slate-400">Created by</div>
                <div class="font-medium text-slate-600 dark:text-slate-300"><?= htmlspecialchars($task['creator_name']) ?></div>
            </div>
        </div>
    </div>
    
    <!-- Detail Content Area -->
    <div class="p-8 space-y-8">
        <?php if (!empty($task['detail'])): ?>
        <div class="bg-slate-50 dark:bg-slate-900/40 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50">
            <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-4 flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-text-align-left text-lg"></i> Description
            </h4>
            <div class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 whitespace-pre-wrap text-[15px] leading-relaxed">
                <?= htmlspecialchars($task['detail']) ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($task['urls'])): ?>
        <div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-3 flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-link text-slate-400"></i> Related Links
            </h4>
            <ul class="space-y-2">
                <?php foreach($task['urls'] as $url): ?>
                <li>
                    <a href="<?= htmlspecialchars($url['url']) ?>" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium hover:underline bg-indigo-50 dark:bg-indigo-900/20 px-3 py-1.5 rounded-lg transition-colors">
                        <i class="ph ph-arrow-square-out"></i> <?= htmlspecialchars($url['url']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($task['images'])): ?>
        <div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-3 flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-image text-slate-400"></i> Attachments
            </h4>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                <?php foreach($task['images'] as $img): ?>
                <a href="<?= htmlspecialchars($img['file_path']) ?>" target="_blank" class="block aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 group relative">
                    <img src="<?= htmlspecialchars($img['file_path']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                        <i class="ph ph-magnifying-glass-plus text-white opacity-0 group-hover:opacity-100 text-2xl transition-opacity"></i>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Feedback / Comments Section -->
<div id="comments" class="max-w-3xl mx-auto mt-6 bg-white dark:bg-slate-800 p-8 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors relative z-10">
    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-2">
        <i class="ph ph-chat-teardrop-text text-indigo-500"></i> Feedback & Discussion
    </h3>
    
    <!-- Comment List -->
    <div class="space-y-4 mb-6">
        <?php if(!empty($task['comments'])): ?>
            <?php foreach($task['comments'] as $c): ?>
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-sm shrink-0">
                        <?= strtoupper(substr($c['user_name'], 0, 2)) ?>
                    </div>
                    <div class="flex-1 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl rounded-tl-none border border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-semibold text-slate-800 dark:text-slate-200 text-sm"><?= htmlspecialchars($c['user_name']) ?></span>
                            <span class="text-xs text-slate-400"><?= date('M d, H:i', strtotime($c['created_at'])) ?></span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 text-sm whitespace-pre-wrap"><?= htmlspecialchars($c['comment']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-sm">
                No feedback yet.
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Add Comment Form (Hidden for Guests) -->
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'guest'): ?>
    <form action="index.php?action=task_comment" method="POST" class="flex gap-3">
        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-bold text-sm shrink-0">
            <?= isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 2)) : 'ME' ?>
        </div>
        <div class="flex-1">
            <textarea name="comment" required rows="2" class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 border transition-colors text-sm" placeholder="Write your feedback here..."></textarea>
            <div class="flex justify-end mt-2">
                <button type="submit" class="bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 text-white text-sm font-medium py-2 px-6 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="ph ph-paper-plane-right"></i> Send
                </button>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>
