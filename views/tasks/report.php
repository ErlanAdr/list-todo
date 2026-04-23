<div class="mb-6 relative z-10">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2 mb-2">
        <i class="ph ph-chart-bar text-indigo-500"></i> Completed Tasks Report
    </h2>
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Tasks that have been marked as "Done" and are older than 7 days are archived here.
    </p>
</div>

<div class="relative z-10 max-w-5xl mx-auto space-y-8">
    <?php if (empty($grouped_reports)): ?>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-12 text-center border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400 dark:text-slate-500">
                <i class="ph ph-archive text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200 mb-2">No Reports Available</h3>
            <p class="text-slate-500 dark:text-slate-400">There are no completed tasks older than 7 days yet.</p>
        </div>
    <?php else: ?>
        <?php foreach ($grouped_reports as $date => $tasks): ?>
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <!-- Group Header -->
                <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i class="ph ph-calendar-check text-indigo-500"></i>
                        <?= date('d F Y', strtotime($date)) ?>
                    </h3>
                    <span class="text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 px-2.5 py-1 rounded-lg">
                        <?= count($tasks) ?> Task<?= count($tasks) > 1 ? 's' : '' ?>
                    </span>
                </div>
                
                <!-- Group Tasks List -->
                <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <?php foreach ($tasks as $t): ?>
                        <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors flex flex-col md:flex-row md:items-center gap-4">
                            
                            <div class="flex-1">
                                <a href="index.php?action=task_view&id=<?= $t['id'] ?>" class="font-bold text-slate-800 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors text-lg inline-block mb-1">
                                    <?= htmlspecialchars($t['name']) ?>
                                </a>
                                
                                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mt-2">
                                    <div class="flex items-center gap-1.5 font-medium">
                                        <i class="ph ph-user-circle text-slate-400"></i>
                                        <?= !empty($t['assignee_name']) ? htmlspecialchars($t['assignee_name']) : 'Unassigned' ?>
                                    </div>
                                    
                                    <?php if (!empty($t['department_name'])): ?>
                                    <div class="flex items-center gap-1.5 border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800 px-2 py-0.5 rounded">
                                        <i class="ph ph-buildings text-slate-400"></i>
                                        <?= htmlspecialchars($t['department_name']) ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($t['comment_count'] > 0): ?>
                                    <div class="flex items-center gap-1 font-medium">
                                        <i class="ph ph-chat-circle-dots"></i>
                                        <?= $t['comment_count'] ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 md:w-auto shrink-0">
                                <?php if (isset($t['review_status']) && $t['review_status'] !== 'None'): ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider
                                        <?php 
                                            if ($t['review_status'] === 'Perlu Direview') echo 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-700/50 shadow-sm';
                                            elseif ($t['review_status'] === 'Perlu Direvisi') echo 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-700/50 shadow-sm';
                                            elseif ($t['review_status'] === 'Sudah Approve') echo 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700/50 shadow-sm';
                                        ?>">
                                        <i class="ph ph-tag-fill"></i> <?= htmlspecialchars($t['review_status']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                    <i class="ph ph-check-circle"></i> Done
                                </span>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
