<template>
    <div class="w-full relative">
        <div class="w-full flex mt-8 justify-center">
            <div class="flex w-11/12 justify-between items-center">
                <div class="text-2xl capitalize"> {{ editstatus ? 'Edit User' : 'Add User' }}</div>
            </div>
        </div>
        <div class="w-full flex justify-center">
            <div class="border w-11/12 left-o right-0 top-0 bottom-0 my-14"><b>Selected Tenant :- {{ selectedTenant.name
            }}</b></div>
        </div>

        <div>
            <div class="w-full text-center m-5">
                <p v-for="(error, index) in errorMessage" class="text-red-900">{{ error }}</p>
            </div>
            <div class="w-full flex justify-center">

                <div class="w-11/12">
                    <div class="flex justify-left items-center">
                        <label for="username" class="block w-1/6 text-lg font-medium text-gray-900">UserName:
                        </label>
                        <div class="w-full ml-10">
                            <input type="text" id="username" v-model="vv.username.$model"
                                class="border border-gray-300 text-gray-900 w-3/5 text-base font-medium rounded-lg focus:ring-blue-500 block p-2.5 focus:border-blue-500"
                                placeholder="UserName" required>
                            <p class="text-red-500" v-if="vv?.username?.$errors[0]?.$message">Username is
                                required!</p>
                        </div>
                    </div>
                    <div class="flex mt-10 justify-left items-center" v-if="!editstatus">
                        <label for="password" class="block w-1/6 text-lg font-medium text-gray-900">Password :
                        </label>
                        <div class="w-full ml-10">
                            <input type="password" id="password" v-model="vv.password.$model"
                                class="border border-gray-300 text-gray-900 w-3/5 text-base font-medium rounded-lg focus:ring-blue-500 block p-2.5 focus:border-blue-500"
                                placeholder="Password" required>
                            <p class="text-red-500" v-if="vv?.password?.$errors[0]?.$message">Password is
                                required!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full flex justify-center">
                <div class="w-8/12 pl-12 flex items-center justify-left mt-16">
                    <div @click="editstatus ? editDataSave(editID) : saveData()"
                        class="me-7 text-xl cursor-pointer text-white bg-green-600 pt-1 pb-2 px-7 rounded-xl">
                        <span>Save</span>
                    </div>
                    <div @click="router.push({ name: 'users' })"
                        class="flex items-center cursor-pointer justify-center text-xl text-white bg-gray-900 pt-1 pb-2 px-7 rounded-xl">
                        <span>Cancel</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { useRouter } from 'vue-router';
import { ref, reactive, onMounted, toRef, computed } from 'vue'
import { apiGetTenant, apiGetUsers, apiAddUsers, apiEditUsers } from "../../API/utils";
import { useStore } from 'vuex'
const store = useStore()
const router = useRouter()
const editstatus = ref(false)
const editID = ref(router.currentRoute.value.params.id ? router.currentRoute.value.params.id : 0)
const tenantData = ref([])
const errorMessage = ref([])

onMounted(() => {
    apiGetTenant().then((resp) => {
        if (resp.status) {
            tenantData.value = resp.data
        }
    }).catch((err) => {
        console.log(err)
    })
    if (editID.value != 0) {
        editstatus.value = true
        var user_id = { 'user_id': editID.value }
        apiGetUsers(user_id).then((resp) => {
            if (resp.status) {
                state.username = resp.data.username
            }
        }).catch((err) => {
            console.log(err)
        })
    }
})
const selectedTenant = computed(() => {
    return store.getters['auth/tenantVal']
})
const state = reactive({
    username: null,
    password: null,
})

const rules = {
    username: { required },
    password: { required },
};

const vv = useVuelidate(rules, {
    username: toRef(state, "username"),
    password: toRef(state, "password"),
});

const editDataSave = (id) => {

    var fromdata = {
        username: state.username,
        tenant_id: selectedTenant.value.id,
        role_id: 3
    }
    apiEditUsers(id, fromdata).then((resp) => {
        if (resp.status) {
            router.push({ name: 'users' })
        }
    }).catch((err) => {
        console.log(err)
    })
}

const validation = () => {
    vv.value.$touch();
    if (vv.value.$error) return;
}

const saveData = () => {
    validation()
    if (vv.value.$error) {
        return;
    } else {
        try {

            var fromdata = {
                username: state.username,
                password: state.password,
                tenant_id: selectedTenant.value.id,
                role_id: 3
            }
            apiAddUsers(fromdata).then((resp) => {
                if (resp.status) {
                    router.push({ name: 'users' })
                }
            }).catch((err) => {
                if (err.response.data.message) {
                    errorMessage.value = err.response.data.message
                }
            })
        } catch (err) {
            console.log(err);
        }
    }


}

</script>