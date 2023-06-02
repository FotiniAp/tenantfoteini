<template>
    <div>
        <div class="text-2xl">
            User Profile
        </div>
        <div class="mt-12">
            <div class="flex items-center">
                <span class="w-2/12">UserName :</span>
                <div class="w-8/12">{{ userName }}</div>
            </div>
            <div class="flex mt-10 items-center">
                <span class="w-2/12">Tenant Name :</span>
                <div class="w-8/12">{{ tenantName }}</div>
            </div>
            <div class="flex mt-10 items-center">
                <span class="w-2/12">Role :</span>
                <div class="w-8/12">{{ role }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { apiGetUsers } from '../../API/utils'

const router = useRouter();

const userName = ref('');
const tenantName = ref('');
const role = ref('');

console.log(router.currentRoute.value.params.id)

const editID = ref(router.currentRoute.value.params.id ? router.currentRoute.value.params.id : 0)


onMounted(() => {
    if (editID.value != 0) {
        var user_id = { 'user_id': editID.value }
        apiGetUsers(user_id).then((resp) => {
            if (resp.status) {
                userName.value = resp.data.username
                tenantName.value = resp.data.tenant.name
                role.value = resp.data.role.name
            }
        }).catch((err) => {
            console.log(err)
        })
    }
})

</script>