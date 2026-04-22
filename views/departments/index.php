<div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
    
    <!-- Add Department Form -->
    <div class="md:col-span-1">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
                <i class="ph ph-buildings text-indigo-500"></i>
                Add Department
            </h3>
            
            <form action="index.php?action=department_create" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Department Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors" placeholder="e.g. IT, HR, Marketing">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors mt-2">
                    Create Department
                </button>
            </form>
        </div>
    </div>

    <!-- Department List -->
    <div class="md:col-span-2">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Departments List</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 bg-slate-50 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Department Name</th>
                            <th class="py-3 px-6 bg-slate-50 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <?php foreach($departments as $d): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="py-3 px-6">
                                <span class="font-medium text-slate-800 dark:text-slate-200">
                                    <i class="ph ph-buildings text-slate-400 mr-2"></i>
                                    <?= htmlspecialchars($d['name']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-6 text-right">
                                <form action="index.php?action=department_delete" method="POST" onsubmit="return confirm('Delete this department?');" class="inline">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-50 dark:bg-red-900/30 rounded" title="Delete">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($departments)): ?>
                        <tr>
                            <td colspan="2" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                No departments found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
