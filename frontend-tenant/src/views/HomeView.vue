<template>
  <div>
    <div class="text-2xl">
      Welcome {{ userName }}
    </div>
  </div>
</template>

<script setup>

import { ref, computed } from 'vue'
import { useStore } from "vuex";
const store = useStore();

const userName = computed(() => {
  return store.state.auth.userInfo.username;
})

</script>
