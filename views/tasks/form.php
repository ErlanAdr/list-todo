<div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 p-8 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors relative z-10">
    <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">
        <?= $is_edit ? 'Edit Task' : 'Create New Task' ?>
    </h3>

    <form action="index.php?action=task_store" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php if($is_edit): ?>
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
        <?php endif; ?>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Task Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="<?= htmlspecialchars($task['name']) ?>" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors" placeholder="What needs to be done?">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Details</label>
            <textarea name="detail" rows="4" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors" placeholder="Add more details..."><?= htmlspecialchars($task['detail']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Assign To</label>
                <select name="assigned_to" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors">
                    <option value="">Unassigned</option>
                    <?php foreach($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= $task['assigned_to'] == $u['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors">
                    <option value="To Do" <?= $task['status'] == 'To Do' ? 'selected' : '' ?>>To Do</option>
                    <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Done" <?= $task['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                </select>
            </div>
        </div>

        <!-- Multiple URLs Section -->
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Related URLs</label>
            <div id="urls-container" class="space-y-2">
                <?php if(!empty($task['urls'])): ?>
                    <?php foreach($task['urls'] as $url): ?>
                        <div class="flex gap-2 url-row">
                            <input type="url" name="urls[]" value="<?= htmlspecialchars($url['url']) ?>" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors" placeholder="https://...">
                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg border border-transparent"><i class="ph ph-trash"></i></button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex gap-2 url-row">
                        <input type="url" name="urls[]" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors" placeholder="https://...">
                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg border border-transparent"><i class="ph ph-trash"></i></button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" onclick="addUrlField()" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 font-medium flex items-center gap-1 hover:text-indigo-700">
                <i class="ph ph-plus"></i> Add another URL
            </button>
        </div>

        <!-- Images Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Upload Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full text-sm text-slate-500 dark:text-slate-400
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-medium
                    file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/50 dark:file:text-indigo-300
                    hover:file:bg-indigo-100 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Assignment Date</label>
                <input type="date" name="assignment_date" value="<?= htmlspecialchars($task['assignment_date']) ?>" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors">
            </div>
        </div>
        
        <?php if($is_edit && !empty($task['images'])): ?>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Existing Images</label>
                <div class="flex flex-wrap gap-2">
                    <?php foreach($task['images'] as $img): ?>
                        <div class="w-20 h-20 rounded border border-slate-200 dark:border-slate-700 overflow-hidden relative group">
                            <img src="<?= htmlspecialchars($img['file_path']) ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100 dark:border-slate-700">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors">
                <?= $is_edit ? 'Update Task' : 'Save Task' ?>
            </button>
            <a href="index.php" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 font-medium py-2 px-4">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function addUrlField() {
    const container = document.getElementById('urls-container');
    const newRow = document.createElement('div');
    newRow.className = 'flex gap-2 url-row';
    newRow.innerHTML = `
        <input type="url" name="urls[]" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors" placeholder="https://...">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg border border-transparent"><i class="ph ph-trash"></i></button>
    `;
    container.appendChild(newRow);
}
</script>
