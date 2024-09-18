<template>
    <div class="top-0 left-0 w-full z-50 bg-white border-b backdrop-blur-lg bg-opacity-80">
      <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        <div class="relative flex h-16 justify-between">
          <div class="flex flex-1 items-stretch justify-start">
            <a class="flex flex-shrink-0 items-center" href="#">
              <img class="block h-12 w-auto" src="@/assets/logo.png" alt="Logo">
            </a>
          </div>
          <div class="flex-shrink-0 flex px-2 py-3 items-center space-x-8">
            <RouterLink class="text-amber-800 hover:bg-amber-500 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm" to="/welcomeToYourAccount">Home</RouterLink>
            <RouterLink class="text-amber-800 hover:bg-amber-500 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm" to="/Tasks">Tasks</RouterLink>
            <RouterLink class="text-amber-800 hover:bg-amber-500 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm" to="/mealCalendar">Dashboard</RouterLink>
            <RouterLink class="text-amber-800 hover:bg-amber-500 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm" to="/sign-out">Sign Out</RouterLink>
          </div>
        </div>
      </div>
    </div>
  
    <div class="mt-20">
      <h1 class="text-center text-orange-700 font-bold text-3xl mb-16">Task List</h1>
      <div class="float-right">
        <button @click="showModal.addTask = true"
          class="mb-20 mr-4 bg-yellow-400 text-gray-900 hover:bg-yellow-300 py-2 px-6 rounded-full text-lg font-semibold transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
          Add Task
        </button>
      </div>
  
      <!-- Modal Component -->
      <AddTaskModal v-if="showModal.addTask" @close="closeModal" @add-task="fetchTasks" />
  
      <table class="min-w-full divide-y divide-gray-200 overflow-x-auto">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progression</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="task in tasks" :key="task.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ task.title }}</div>
                  <div class="text-sm text-gray-500">{{ task.description }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ task.status }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ task.progression }}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ new Date(task.start_date).toLocaleDateString() }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ new Date(task.end_date).toLocaleDateString() }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button @click="editTask(task.id)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
              <button @click="deleteTask(task.id)" class="ml-2 text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import AddTaskModal from '@/components/addTaskModal.vue';
  
  const showModal = ref({
    addTask: false,
  });
  
  const tasks = ref([]);
  
  const closeModal = () => {
    showModal.value.addTask = false;
  };
  
  const fetchTasks = async () => {
    try {
      const response = await axios.get('/tasks', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
      });
      tasks.value = response.data; // Adjust based on your API response
    } catch (error) {
      console.error('Error fetching tasks:', error);
    }
  };
  
  const addTask = async (task) => {
    try {
      await axios.post('/tasks', task, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
      });
      fetchTasks(); // Refresh the task list
      closeModal();
    } catch (error) {
      console.error('Error adding task:', error);
    }
  };
  
  const editTask = async (id) => {
    // Implement task editing logic or navigation to edit page
    console.log('Edit task with ID:', id);
  };
  
  const deleteTask = async (id) => {
    try {
      await axios.delete(`/tasks/${id}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
      });
      fetchTasks(); // Refresh the task list
    } catch (error) {
      console.error('Error deleting task:', error);
    }
  };
  
  // Fetch tasks when component is mounted
  onMounted(fetchTasks);
  </script>
  