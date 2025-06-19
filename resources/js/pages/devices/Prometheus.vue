<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Geräte',
        href: route('devices.index'),
    },
    {
        title: 'Prometheus',
        href: '',
    },
];
</script>

<template>
    <Head title="Prometheus" />

    <AppLayout :breadcrumbs="breadcrumbs"> </AppLayout>
</template>

<style scoped></style>
