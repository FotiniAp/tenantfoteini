<template>
    <div>
        <div class="mt-4">
            <div class="flex justify-center w-full items-center">
                <label for="tenant" class="block  text-end text-lg font-medium text-gray-900">Tenant:</label>
                <div class="w-2/6 ml-10">
                    <select @change="selectTenant" id="tenant" v-model="tenant"
                        class="border w-full border-gray-300 text-gray-900 text-base font-medium rounded-lg focus:ring-blue-500 block p-2.5 focus:border-blue-500">
                        <option value="" selected>Choose a Tenant</option>
                        <option v-for="(item, index) in tenantData" :key="index" :value="item">{{
                            item.name }}
                        </option>
                    </select>
                    <p class="text-red-500" v-if="vv?.studio?.$errors[0]?.$message">Please select Tenant first</p>
                </div>
            </div>
        </div>
        <div v-if="tenant && tenant != ''">
            <div class="flex mt-10 justify-center">
                <div class="btn w-11/12 pr-6">
                    <div class="flex mx-auto justify-end">
                        <a class="inline-block cursor-pointer py-1 text-xl text-white bg-gray-900 px-7 hover:bg-gray-700 rounded-xl"
                            @click="router.push({ name: 'addusers' })">
                            Create User
                        </a>
                    </div>
                </div>
            </div>

            <Modal :show="show" @someEvent="deteleData" />

            <div class="mt-10 flex items-center flex-col">
                <Table>
                    <thead>
                        <tr>
                            <th v-for="item in heading" :key="item" scope="col" class="px-6 py-3">
                                {{ item }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(item, index) in tableData" :key="index" class="bg-white border-b">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                {{ index + 1 }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                {{ item.username }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                {{ item.tenant.name }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                {{ item.role.name }}
                            </td>
                            <td scope="row" @click="redirectProfile(item)"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                <div class="cursor-pointer">View Profile</div>
                            </td>
                            <td>
                                <span @click="editItem(item.id)" class="cursor-pointer"><i
                                        class="fa-solid fa-pen-to-square hover:text-green-700 ml-5 text-xl"></i></span>
                                <span @click="deleteItem(item.id)" class="cursor-pointer"><i
                                        class="fa-solid fa-trash ml-3 hover:text-red-700 text-xl"></i></span>
                            </td>
                        </tr>
                    </tbody>
                </Table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue"
import { useVuelidate } from "@vuelidate/core";
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'
import Modal from '../components/Modal.vue'
import { required } from "@vuelidate/validators";
import { apiGetTenant, apiGetUsers, apiDeleteUser } from "../../API/utils";

const router = useRouter()
const store = useStore()

const state = reactive({
    tenant: '',
});
const tenant = ref('')
const tenantData = ref([])
const rules = {};
for (const key in state) {
    rules[key] = { required };
}

const tableData = ref([])
const vv = useVuelidate(rules, state);
var tenant_id;

const selectTenant = (val = null) => {
    tenant_id = val.target.value;

    userApiCall(tenant.value.id)
    store.dispatch('auth/setTenant', tenant.value)
};

const userApiCall = (val) => {
    var data = { 'tenant_id': val }
    apiGetUsers(data).then((resp) => {
        if (resp.status) {
            tableData.value = resp.data;
        }
    }).catch((err) => {
        console.log(err)
    })
}

const editItem = (val) => {
    router.push({ name: 'addusers', params: { id: val } });
}

let show = ref(false)
var deletedID;

const deleteItem = (id) => {
    deletedID = id
    show.value = true
}

const redirectProfile = (val) => {
    router.push({ name: 'userprofile', params: { id: val.id } })
}


const heading = ref([
    "No",
    "UserName",
    "TenantName",
    "Role",
    "View",
    "Action"
])

onMounted(() => {
    apiGetTenant().then((resp) => {
        if (resp.status) {
            tenantData.value = resp.data
        }
    }).catch((err) => {
        console.log(err)
    })
})

const deteleData = (val) => {
    if (val) {
        apiDeleteUser(deletedID).then((resp) => {
            if (resp.status) {
                show.value = false
                userApiCall(tenant.value.id)
            }
        }).catch((err) => {
            console.log(err)
        })
    } else {
        show.value = false
    }
}



</script>