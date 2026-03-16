const syncHelper = {
    isSyncing: false,

    async syncAll() {
        if (this.isSyncing || !navigator.onLine) return;
        
        const tasks = await window.offlineDB.getAllTasks();
        if (tasks.length === 0) return;

        console.log(`[Sync] Found ${tasks.length} pending tasks to sync.`);
        this.isSyncing = true;

        for (const task of tasks) {
            try {
                await this.uploadTask(task);
                await window.offlineDB.deleteTask(task.id);
                console.log(`[Sync] Task ${task.original_task_id || 'unknown'} synced successfully.`);
            } catch (error) {
                console.error(`[Sync] Failed to sync task:`, error);
                // We keep it in DB to try again later
            }
        }

        this.isSyncing = false;
        
        // Notify UI if needed
        window.dispatchEvent(new CustomEvent('offline-sync-completed'));
    },

    async uploadTask(taskData) {
        const formData = new FormData();
        
        // Reconstruct form data
        for (const key in taskData.fields) {
            const value = taskData.fields[key];
            
            if (Array.isArray(value)) {
                value.forEach(item => formData.append(key, item));
            } else if (typeof value === 'object' && value !== null) {
                // Key-value pairs like form_data[field]
                for (const subKey in value) {
                    formData.append(`${key}[${subKey}]`, value[subKey]);
                }
            } else {
                formData.append(key, value);
            }
        }

        // Handle Photos stored as Blobs/DataURLs
        if (taskData.blobs) {
            for (const key in taskData.blobs) {
                const blobInfo = taskData.blobs[key];
                const res = await fetch(blobInfo.data);
                const blob = await res.blob();
                formData.append(blobInfo.fieldName, blob, blobInfo.fileName);
            }
        }

        const response = await fetch(taskData.url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Server responded with ${response.status}`);
        }

        return await response.json();
    }
};

// Listen for connection status changes
window.addEventListener('online', () => {
    console.log('[Sync] Device is back online. Starting sync...');
    syncHelper.syncAll();
});

// Periodic check
setInterval(() => syncHelper.syncAll(), 60000); // Every minute

window.syncHelper = syncHelper;
