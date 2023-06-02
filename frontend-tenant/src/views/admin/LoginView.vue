<template>
    <div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div
                class="absolute inset-0 bg-gradient-to-r from-blue-300 to-blue-600 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
            </div>
            <div class="relative py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
                <div class="max-w-md mx-auto">
                    <div>
                        <h1 class="text-2xl font-semibold">Login Form</h1>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="py-8 text-base leading-6 w-full space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                            <div class="relative w-full">
                                <input v-model="vv.username.$model" autocomplete="off" id="username" name="username"
                                    type="text"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="Username" />
                                <label for="username"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">UserName</label>
                                <p class="text-red-500">{{ vv?.username?.$errors[0]?.$message }}</p>
                            </div>
                            <div class="relative w-full">
                                <input v-model="vv.password.$model" autocomplete="off" id="password" name="password"
                                    type="password"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                    placeholder="Password" />
                                <label for="password"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">Password</label>
                                <p class="text-red-500">{{ vv?.password?.$errors[0]?.$message }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-red-500">{{ errorMessage }}</p>
                            </div>
                            <div class="relative">
                                <button @click.prevent="handalSubmit"
                                    class="bg-blue-500 text-white rounded-md px-2 py-1">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { ref, toRef, reactive } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import { apiLogin } from "../../../API/utils";

const store = useStore();
const router = useRouter();

const state = reactive({
    username: '',
    password: '',
})

const rules = {
    username: { required },
    password: { required },
};

const vv = useVuelidate(rules, {
    username: toRef(state, "username"),
    password: toRef(state, "password"),
});

let submitted = ref(false)
const errorMessage = ref('')
function handalSubmit() {
    submitted.value = true;
    vv.value.$touch();
    if (vv.value.$invalid) return;
    apiLogin(state).then((res) => {
        if (res.status) {
            localStorage.setItem("token", res.access_token);
            localStorage.setItem("role", res.data.role_name)
            store.dispatch("auth/login", res)
            router.push({ name: 'mainView' })
        }
    }).catch((error) => {
        errorMessage.value = error.response.data.message
    })
}
</script>

<style scoped></style>
