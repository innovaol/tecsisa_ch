const DB_NAME = 'tecsisa_offline_db';
const DB_VERSION = 1;
const STORE_NAME = 'pending_tasks';

const offlineDB = {
    db: null,

    async init() {
        if (this.db) return this.db;

        return new Promise((resolve, reject) => {
            const request = indexedDB.open(DB_NAME, DB_VERSION);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains(STORE_NAME)) {
                    db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
                }
            };

            request.onsuccess = (event) => {
                this.db = event.target.result;
                resolve(this.db);
            };

            request.onerror = (event) => {
                console.error('IndexedDB error:', event.target.error);
                reject(event.target.error);
            };
        });
    },

    async saveTask(taskData) {
        const db = await this.init();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([STORE_NAME], 'readwrite');
            const store = transaction.objectStore(STORE_NAME);
            
            // Add metadata
            taskData.offline_timestamp = new Date().toISOString();
            
            // Use .put() instead of .add() to allow overwriting if the technician 
            // edits the same task multiple times while offline.
            const request = store.put(taskData);

            request.onsuccess = () => {
                console.log('[OfflineDB] Task saved successfully:', taskData.fields?.id || 'new');
                resolve(request.result);
            };

            request.onerror = (event) => {
                console.error('[OfflineDB] Error saving task:', event.target.error);
                reject(event.target.error);
            };
        });
    },

    async getAllTasks() {
        const db = await this.init();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([STORE_NAME], 'readonly');
            const store = transaction.objectStore(STORE_NAME);
            const request = store.getAll();

            request.onsuccess = () => resolve(request.result);
            request.onerror = (event) => reject(event.target.error);
        });
    },

    async deleteTask(id) {
        const db = await this.init();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([STORE_NAME], 'readwrite');
            const store = transaction.objectStore(STORE_NAME);
            const request = store.delete(id);

            request.onsuccess = () => resolve();
            request.onerror = (event) => reject(event.target.error);
        });
    }
};

window.offlineDB = offlineDB;
