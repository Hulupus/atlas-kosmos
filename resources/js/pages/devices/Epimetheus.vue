<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Geräte',
        href: route('devices.index'),
    },
    {
        title: 'Epimetheus',
        href: '',
    },
];

</script>

<template>
    <Head title="Epimetheus"/>

    <AppLayout :breadcrumbs="breadcrumbs">

    </AppLayout>
</template>

<style scoped>

</style>
