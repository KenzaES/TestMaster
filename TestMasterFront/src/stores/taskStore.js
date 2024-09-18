import { defineStore } from 'pinia';
import axios from 'axios';

// Define the Pinia store
export const useTaskStore = defineStore('taskStore', {
  state: () => ({
    tasks: [],
    error: null,
  }),
  actions: {
    // Fetch tasks from the API
    async fetchTasks() {
      console.log('Fetching tasks...');
      try {
        const response = await axios.get('/tasks', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },
        });
        this.tasks = response.data;
        console.log('Fetched tasks:', response.data);
      } catch (error) {
        this.error = error.response ? error.response.data.message : 'An error occurred';
        console.error('Error fetching tasks:', error);
      }
    },

    // Add a new task to the API
    async addTask(taskData) {
      console.log('Adding task with data:', taskData);
      try {
        const response = await axios.post('/add/tasks', taskData, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },
        });
        console.log('Task added successfully:', response.data);
        await this.fetchTasks(); // Refresh the task list
      } catch (error) {
        this.error = error.response ? error.response.data.message : 'An error occurred';
        console.error('Error adding task:', error);
      }
    },

    // Edit an existing task
    async editTask(taskId, updatedData) {
      console.log('Editing task ID:', taskId, 'with data:', updatedData);
      try {
        const response = await axios.put(`/edit/tasks/${taskId}`, updatedData, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },
        });
        console.log('Task updated successfully:', response.data);
        await this.fetchTasks(); // Refresh the task list
      } catch (error) {
        this.error = error.response ? error.response.data.message : 'An error occurred';
        console.error('Error updating task:', error);
      }
    },

    // Delete a task
    async deleteTask(taskId) {
      console.log('Deleting task ID:', taskId);
      try {
        await axios.delete(`/tasks/${taskId}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },
        });
        console.log('Task deleted successfully');
        await this.fetchTasks(); // Refresh the task list
      } catch (error) {
        this.error = error.response ? error.response.data.message : 'An error occurred';
        console.error('Error deleting task:', error);
      }
    },
  },
});
