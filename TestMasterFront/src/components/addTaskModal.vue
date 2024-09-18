<template>
    <div class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center overflow-auto">
      <div class="bg-white dark:bg-orange-400 rounded-lg p-4 mt-44 shadow-lg w-full max-w-lg">
        <div class="inline-flex">
          <h3 class="text-2xl text-orange-800 font-bold mb-2 text-center mt-20">
            {{ editTaskId ? 'Edit Task' : 'Add New Task' }}
          </h3>
          <img src="@/assets/Add tasks.gif" alt="" class="h-40 max-w-xs ml-36 rounded-full float-right">
        </div>
        <!-- Task form fields -->
        <div class="mb-4">
          <label class="block text-sm font-semibold mb-2">Title</label>
          <input v-model="taskTitle" type="text" class="w-full p-2 border rounded" placeholder="Enter task title" />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-semibold mb-2">Description</label>
          <textarea v-model="taskDescription" class="w-full p-2 border rounded" placeholder="Enter task description"></textarea>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-semibold mb-2">Status</label>
          <select v-model="taskStatus" class="w-full p-2 border rounded">
            <option value="normal">Normal</option>
            <option value="important">Important</option>
            <option value="urgent">Urgent</option>
          </select>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-semibold mb-2">Progression</label>
          <select v-model="taskProgression" class="w-full p-2 border rounded">
            <option value="waiting">In waiting</option>
            <option value="in progress">In progress</option>
            <option value="done">Done</option>
          </select>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-semibold mb-2">Start Date</label>
          <input v-model="taskStartDate" type="date" class="w-full p-2 border rounded" />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-semibold mb-2">Deadline</label>
          <input v-model="taskDeadline" type="date" class="w-full p-2 border rounded" />
        </div>
        <p v-if="error" class="text-red-500 bg-orange-200 mt-4 mb-4 p-2 rounded-sm animate-pulse">{{ error }}</p>
        <!-- Actions -->
        <div class="flex justify-end space-x-4">
          <button @click="$emit('close')" class="py-2 px-4 bg-gray-200 rounded-lg">Cancel</button>
          <button @click="submitTask" class="py-2 px-4 bg-blue-500 text-white rounded-lg" :disabled="loading">Submit</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  import { useTaskStore } from '@/stores/taskStore';
  
  const props = defineProps({
    editTaskId: Number, // optional
  });
  
  const showModal = ref(false);
  const taskTitle = ref('');
  const taskDescription = ref('');
  const taskStatus = ref('normal');
  const taskProgression = ref('waiting');
  const taskStartDate = ref('');
  const taskDeadline = ref('');
  const taskStore = useTaskStore();
  const error = ref(null);
  const loading = ref(false);
  
  watch(() => props.editTaskId, async (newId) => {
    if (newId) {
      const task = taskStore.tasks.find(task => task.id === newId);
      if (task) {
        taskTitle.value = task.title;
        taskDescription.value = task.description;
        taskStatus.value = task.status;
        taskProgression.value = task.progression;
        taskStartDate.value = task.start_date;
        taskDeadline.value = task.end_date;
      }
    }
  });
  
  const submitTask = async () => {
    loading.value = true;
  
    const taskData = {
      title: taskTitle.value,
      description: taskDescription.value,
      status: taskStatus.value,
      progression: taskProgression.value,
      start_date: taskStartDate.value,
      end_date: taskDeadline.value,
    };
  
    try {
      if (props.editTaskId) {
        await taskStore.editTask(props.editTaskId, taskData);
      } else {
        await taskStore.addTask(taskData);
      }
      if (!taskStore.error) {
        emit('close');
      } else {
        error.value = taskStore.error;
      }
    } catch (err) {
      error.value = 'Failed to add or update task';
    } finally {
      loading.value = false;
    }
  };
  </script>
  