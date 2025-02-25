<template>
    <div>
        <!-- Navbar -->
        <nav class="bg-green-500 text-white p-4 flex justify-between">
            <div class="text-lg font-bold">My App</div>
            <ul class="flex space-x-4">
                <li><router-link to="/" class="hover:underline">Home</router-link></li>
                <li><router-link to="/about" class="hover:underline">About</router-link></li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div class="p-6">
            <router-view></router-view>
        </div>
    </div>
</template>

<script setup>
</script>

<style scoped>
nav {
    display: flex;
    align-items: center;
}
</style>
