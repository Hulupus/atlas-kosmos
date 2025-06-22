<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Measurement } from '@/types/Measurement';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/de';
import localizedFormat from 'dayjs/plugin/localizedFormat';
import type { Device } from '@/types/Device';
import Heading from '@/components/Heading.vue';

const props = defineProps<{
    measurements: Measurement[];
    selectedDeviceDetails: Device;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Messwerte',
        href: '',
    },
    {
        title: props.selectedDeviceDetails.name,
        href: '',
    },
];



dayjs.extend(localizedFormat);
dayjs.locale('de');

const formatDateTime = (timestamp: Date | string | null) => {
    if (!timestamp) return 'N/A';
    return dayjs(timestamp).format('DD.MM.YYYY HH:mm:ss');
};
</script>

<template>
    <Head title="Messwerte" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="w-full px-4 py-6">
            <Heading :title="'Messungen vom Gerät ' + selectedDeviceDetails.name" />

            <Table>
                <TableCaption>A list of your recent invoices.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead> Parameter </TableHead>
                        <TableHead class="text-right"> Wert </TableHead>
                        <TableHead>Einheit</TableHead>
                        <TableHead> Notizen</TableHead>
                        <TableHead> Gemessen am </TableHead>
                        <TableCell> Gespeichert am </TableCell>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="(measurement, index) in measurements" :key="index">
                        <TableCell class="font-medium"> {{ measurement.metric_name }} </TableCell>
                        <TableCell class="text-right"> {{ measurement.value }} </TableCell>
                        <TableCell> {{ measurement.unit }} </TableCell>
                        <TableCell> {{ measurement.notes }} </TableCell>
                        <TableCell> {{ formatDateTime(measurement.measured_at) }} </TableCell>
                        <TableCell> {{ formatDateTime(measurement.created_at) }} </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>

<style scoped></style>
