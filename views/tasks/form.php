<div class="max-w-2xl mx-auto mb-4 relative z-10">
    <a href="index.php" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-colors">
        <i class="ph ph-arrow-left"></i> Back to Board
    </a>
</div>

<div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 p-8 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors relative z-10">
    <div class="flex items-start justify-between mb-6">
        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">
            <?= $is_edit ? 'Task Details' : 'Create New Task' ?>
        </h3>
        
        <?php if($is_edit && !empty($task['creator_name'])): ?>
            <div class="text-right text-xs text-slate-400 dark:text-slate-500">
                Created by:<br>
                <span class="font-medium text-slate-600 dark:text-slate-300"><?= htmlspecialchars($task['creator_name']) ?></span>
            </div>
        <?php endif; ?>
    </div>

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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Department</label>
                <select name="department_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border transition-colors">
                    <option value="">No Department</option>
                    <?php foreach($departments as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= $task['department_id'] == $d['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['name']) ?>
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

        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors">
                    <?= $is_edit ? 'Update Task' : 'Save Task' ?>
                </button>
                <a href="index.php" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 font-medium py-2 px-4">
                    Cancel
                </a>
            </div>
            
            <?php if($is_edit): ?>
                <!-- Delete is now inside the form as a secondary action -->
                <button type="button" onclick="if(confirm('Are you sure you want to delete this task?')) document.getElementById('delete-form').submit();" class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ph ph-trash"></i> Delete Task
                </button>
            <?php endif; ?>
        </div>
    </form>
    
    <?php if($is_edit): ?>
        <form id="delete-form" action="index.php?action=task_delete" method="POST" class="hidden">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
        </form>
    <?php endif; ?>
</div>

<?php if($is_edit): ?>
<!-- Feedback / Comments Section -->
<div id="comments" class="max-w-2xl mx-auto mt-6 bg-white dark:bg-slate-800 p-8 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors relative z-10">
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
                No feedback yet. Be the first to start the discussion!
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Add Comment Form -->
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
</div>
<?php endif; ?>

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
