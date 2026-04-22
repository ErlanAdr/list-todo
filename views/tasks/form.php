<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-200">
    <h3 class="text-xl font-bold text-slate-800 mb-6">
        <?= $is_edit ? 'Edit Task' : 'Create New Task' ?>
    </h3>

    <form action="index.php?action=task_store" method="POST" class="space-y-6">
        <?php if($is_edit): ?>
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
        <?php endif; ?>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Task Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="<?= htmlspecialchars($task['name']) ?>" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" placeholder="What needs to be done?">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Details</label>
            <textarea name="detail" rows="4" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" placeholder="Add more details..."><?= htmlspecialchars($task['detail']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Assign To</label>
                <select name="assigned_to" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                    <option value="">Unassigned</option>
                    <?php foreach($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= $task['assigned_to'] == $u['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                    <option value="To Do" <?= $task['status'] == 'To Do' ? 'selected' : '' ?>>To Do</option>
                    <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Done" <?= $task['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Related URL</label>
                <input type="url" name="url" value="<?= htmlspecialchars($task['url']) ?>" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" placeholder="https://...">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Assignment Date</label>
                <input type="date" name="assignment_date" value="<?= htmlspecialchars($task['assignment_date']) ?>" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors">
                <?= $is_edit ? 'Update Task' : 'Save Task' ?>
            </button>
            <a href="index.php" class="text-slate-500 hover:text-slate-700 font-medium py-2 px-4">
                Cancel
            </a>
        </div>
    </form>
</div>
