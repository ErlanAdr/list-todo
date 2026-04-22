<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Add User Form (Super Admin Only) -->
    <div class="md:col-span-1">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
                <i class="ph ph-user-plus text-indigo-500"></i>
                Add New User
            </h3>
            
            <form action="index.php?action=user_create" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors" placeholder="johndoe">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors" placeholder="John Doe">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Role</label>
                    <select name="role" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors">
                        <option value="user">User</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors mt-2">
                    Create User
                </button>
            </form>
        </div>
    </div>

    <!-- User List -->
    <div class="md:col-span-2">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Team Members</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 bg-slate-50 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">User</th>
                            <th class="py-3 px-6 bg-slate-50 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Role</th>
                            <th class="py-3 px-6 bg-slate-50 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <?php foreach($users as $u): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-bold text-xs shrink-0">
                                        <?= strtoupper(substr($u['name'], 0, 2)) ?>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-800 dark:text-slate-200"><?= htmlspecialchars($u['name']) ?></span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">@<?= htmlspecialchars($u['username']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <?php if($u['role'] === 'super_admin'): ?>
                                    <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs rounded-full font-medium">Super Admin</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs rounded-full font-medium">User</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-6 text-sm text-slate-500 dark:text-slate-400">
                                <?= date('M d, Y', strtotime($u['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($users)): ?>
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                No users found. Add some team members!
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
