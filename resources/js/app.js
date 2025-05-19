import './bootstrap';
import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    const tasksContainer = document.getElementById('tasks-container');
    
    if (tasksContainer) {
        new Sortable(tasksContainer, {
            animation: 150,
            handle: '.task-item',
            ghostClass: 'bg-gray-100',
            onEnd: function(evt) {
                const tasks = Array.from(tasksContainer.children).map((item, index) => ({
                    id: item.dataset.taskId,
                    order: index
                }));

                fetch('/tasks/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ tasks })
                }).then(response => {
                    if (!response.ok) {
                        console.error('Failed to update task order');
                    }
                });
            }
        });
    }
}); 