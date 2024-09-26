<script setup lang="ts">
import { ref } from 'vue';
import ApiServices from '../services/api-services'
import type { IDevelopers } from '../interfaces/developers'

const api = new ApiServices()

const developers = ref<IDevelopers[]>([]);
const loading = ref<boolean>(false);
const error = ref<any>();

const getAllDevelopers = async () => {
    try {
        loading.value = true
        const result = await api.getAll('/developers')
        console.log(result)
        setTimeout(() => {
            developers.value = result
            loading.value = false
        }, 1000)
    } catch (e) {
        console.log(e)
        error.value = e
    }
}

getAllDevelopers()

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
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Level
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Result
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
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        Loading...
                    </td>
                </tr>
                <tr v-else-if="error">
                    <td colspan="7" class="px-6 py-4 text-center bg-gray-50">
                        {{ error }}
                    </td>
                </tr>
                <tr v-else v-for="developer in developers" :key="developer.id"
                    class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">
                        {{ developer.id }}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ developer.name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ developer.time }}
                    </td>
                    <td class="px-6 py-4">
                        {{ developer.level }}
                    </td>
                    <td class="px-6 py-4">
                        {{ developer.result }}
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(developer.created_at).toLocaleDateString() }}
                    </td>
                    <td class="px-6 py-4">
                        {{ new Date(developer.updated_at).toLocaleDateString() }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped></style>