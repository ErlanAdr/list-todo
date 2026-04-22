<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Add User Form -->
    <div class="md:col-span-1">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="ph ph-user-plus text-indigo-500"></i>
                Add New User
            </h3>
            
            <form action="index.php?action=user_create" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" placeholder="John Doe">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors">
                    Add User
                </button>
            </form>
        </div>
    </div>

    <!-- User List -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800">Team Members</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-200">User</th>
                            <th class="py-3 px-6 bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-200">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach($users as $u): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs shrink-0">
                                        <?= strtoupper(substr($u['name'], 0, 2)) ?>
                                    </div>
                                    <span class="font-medium text-slate-800"><?= htmlspecialchars($u['name']) ?></span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-sm text-slate-500">
                                <?= date('M d, Y', strtotime($u['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($users)): ?>
                        <tr>
                            <td colspan="2" class="py-8 text-center text-slate-400">
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
