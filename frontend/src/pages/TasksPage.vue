<script setup lang="ts">
import { ref } from 'vue';
import ApiServices from '../services/api-services'
import type { ITasks } from '../interfaces/tasks'

const api = new ApiServices()

const tasks = ref<ITasks[]>([]);
const loading = ref<boolean>(false);
const error = ref<any>();

async function getAllTasks() {
    try {
        loading.value = true
        const result = await api.getAll('/tasks')
        console.log(result);
        setTimeout(() => {
            tasks.value = result
            loading.value = false
        }, 1000)
    } catch (e) {
        console.log(e);
        error.value = e
    }
}

getAllTasks()

</script>

<template>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-100 uppercase bg-gray-800 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Task
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Level
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Created at
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Updated at
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="loading"
                    class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        Loading...
                    </td>
                </tr>
                <tr v-else-if="error">
                    <td colspan="6" class="px-6 py-4 text-center">
                        {{ error }}
                    </td>
                </tr>
                <tr v-else v-for="task in tasks" :key="task.id"
                    class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">
                        {{ task.id }}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ task.title }}
                    </th>
                    <td class="px-6 py-4">
                        {{ task.time }}
                    </td>
                    <td class="px-6 py-4">
                        {{ task.level }}
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(task.created_at).toLocaleDateString() }}
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(task.updated_at).toLocaleDateString() }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<style scoped></style>