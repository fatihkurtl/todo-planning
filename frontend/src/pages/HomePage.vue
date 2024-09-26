<script setup lang="ts">
import { ref, computed } from 'vue';
import type { IAssignedTasks } from '@/interfaces/assigned-tasks';
import type { IDevelopers } from '@/interfaces/developers';
import type { ITasks } from '@/interfaces/tasks';
import ApiServices from '../services/api-services';

const api = new ApiServices();

const assignedTasks = ref<IAssignedTasks[]>([]);
const totalWeeks = ref<number>(0);
const tasks = ref<ITasks[]>([]);
const developers = ref<IDevelopers[]>([]);
const loading = ref<boolean>(false);
const error = ref<null | any>(null);

const getAll = async () => {
    try {
        loading.value = true;
        const [tasksProvider, fetchedDevelopers] = await Promise.all([
            api.getAll('/tasks'),
            api.getAll('/developers')
        ]);
        tasks.value = [...tasksProvider];
        developers.value = fetchedDevelopers;
        await assignTasks(tasks.value, developers.value);
    } catch (e) {
        console.error(e);
        error.value = e;
    } finally {
        loading.value = false;
    }
};

getAll();

const developerCapacities = {
    1: 1,
    2: 2,
    3: 3,
    4: 4,
    5: 5
};

async function assignTasks(tasks: ITasks[], developers: IDevelopers[]) {
    const weeklyCapacity = 45;
    const developerWorkload: Record<number, number[]> = {};
    developers.forEach(dev => {
        developerWorkload[dev.id] = [0];
    });

    const sortedTasks = [...tasks].sort((a, b) => b.level - a.level);

    for (const task of sortedTasks) {
        let assigned = false;
        const sortedDevelopers = [...developers].sort((a, b) =>
            (developerWorkload[a.id][developerWorkload[a.id].length - 1] / developerCapacities[a.id]) -
            (developerWorkload[b.id][developerWorkload[b.id].length - 1] / developerCapacities[b.id])
        );

        for (const developer of sortedDevelopers) {
            const capacity = developerCapacities[developer.id];
            const taskDuration = task.time / capacity;
            const currentWeekWorkload = developerWorkload[developer.id][developerWorkload[developer.id].length - 1];

            if (currentWeekWorkload + taskDuration <= weeklyCapacity) {
                developerWorkload[developer.id][developerWorkload[developer.id].length - 1] += taskDuration;
                assignedTasks.value.push({
                    taskId: task.id,
                    developerId: developer.id,
                    developerName: developer.name,
                    taskTitle: task.title,
                    taskLevel: task.level,
                    taskTime: task.time,
                    developerCapacity: capacity,
                    week: developerWorkload[developer.id].length
                });
                assigned = true;
                break;
            }
        }

        if (!assigned) {
            developers.forEach(dev => {
                developerWorkload[dev.id].push(0);
            });
            const developer = sortedDevelopers[0];
            const capacity = developerCapacities[developer.id];
            const taskDuration = task.time / capacity;
            developerWorkload[developer.id][developerWorkload[developer.id].length - 1] = taskDuration;
            assignedTasks.value.push({
                taskId: task.id,
                developerId: developer.id,
                developerName: developer.name,
                taskTitle: task.title,
                taskLevel: task.level,
                taskTime: task.time,
                developerCapacity: capacity,
                week: developerWorkload[developer.id].length
            });
        }
    }

    totalWeeks.value = Math.max(...Object.values(developerWorkload).map(weeks => weeks.length));
}

const weeklySchedule = computed(() => {
    const schedule: IAssignedTasks[][] = [];
    for (let i = 0; i < totalWeeks.value; i++) {
        schedule.push(assignedTasks.value.filter(task => task.week === i + 1));
    }
    return schedule;
});

</script>

<template>
    <h2 class="text-lg font-semibold">Total Weeks: {{ totalWeeks }}</h2>
    <div v-for="(week, weekIndex) in weeklySchedule" :key="weekIndex" class="mb-8">
        <div class="mb-4 justify-between flex items-center">
            <h3 class="text-lg font-semibold">Week {{ weekIndex + 1 }}</h3>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-100 uppercase bg-gray-800 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Task</th>
                        <th scope="col" class="px-6 py-3">Developer</th>
                        <th scope="col" class="px-6 py-3">Duration</th>
                        <th scope="col" class="px-6 py-3">Level</th>
                    </tr>
                </thead>
                <tbody>
                    <div v-if="loading" class="text-center py-4">
                        Loading...
                    </div>
                    <div v-else-if="error" class="text-center py-4 text-red-500">
                        {{ error }}
                    </div>
                    <tr v-else v-for="(assignment, index) in week" :key="assignment.taskId"
                        class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ index + 1 }}</td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ assignment.taskTitle }}
                        </th>
                        <td class="px-6 py-4">{{ assignment.developerName }}</td>
                        <td class="px-6 py-4">
                            {{ (assignment.taskTime / assignment.developerCapacity).toFixed(2) }} hours
                        </td>
                        <td class="px-6 py-4">{{ assignment.taskLevel }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped></style>