<script setup>
import { getCurrentInstance } from 'vue'
const props = defineProps(['show'])
const instance = getCurrentInstance()
const handleButtonClick = (val) => {
    instance.emit('someEvent', val)
}
</script>

<template>
    <div v-if="props.show" class="fixed inset-0 z-10 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">Are you sure you want to
                        delete this item?</h3>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button @click="handleButtonClick(true)" type="button"
                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Yes</button>
                    <button @click="handleButtonClick(false)" type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">No</button>
                </div>
            </div>
        </div>
    </div>
</template>
