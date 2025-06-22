<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Heading from '@/components/Heading.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Device } from '@/types/Device';
import { DeviceGroup } from '@/types/DeviceGroup';
import { Measurement } from '@/types/Measurement';
import { Head, router, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/de';
import localizedFormat from 'dayjs/plugin/localizedFormat';
import { AlertCircle, Download } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';

dayjs.extend(localizedFormat);
dayjs.locale('de');

const props = defineProps<{
    measurements: Measurement[];
    selectedDevice?: Device;
    selectedDeviceId?: number;
    deviceGroups: DeviceGroup[];
}>();

const error = usePage().props.flash.error;

const selectedDeviceForFilter = ref<string>(props.selectedDeviceId ? String(props.selectedDeviceId) : '');

const handleDeviceSelection = (newValue: any) => {
    selectedDeviceForFilter.value = newValue;

    // Construct the URL based on the selected device ID
    const newUrl = selectedDeviceForFilter.value ? route('measurements', { device_id: selectedDeviceForFilter.value }) : route('measurements');

    router.get(newUrl, {}, { preserveState: true });
};

const formatDateTime = (timestamp: Date | string | null) => {
    if (!timestamp) return 'N/A';
    return dayjs(timestamp).format('DD.MM.YYYY HH:mm:ss');
};

const breadcrumbs = computed<BreadcrumbItem[]>(() => {
    const items: BreadcrumbItem[] = [
        {
            title: 'Messwerte',
            href: route('measurements'),
        },
    ];

    if (props.selectedDevice) {
        items.push({
            title: props.selectedDevice.name,
            href: '',
        });
    }

    return items;
});

const displaySelectedDeviceName = computed(() => {
    if (selectedDeviceForFilter.value === '') {
        return 'Gerät auswählen';
    }

    return props.selectedDevice ? props.selectedDevice.name : 'Wähle ein Gerät aus';
});

const exportMeasurementsToCsv = () => {
    if (props.measurements.length === 0) {
        alert('No measurements to export for the selected device.');
        return;
    }

    // Define CSV headers
    const headers = [
        'Device Name',
        'Metric Name',
        'Value',
        'Unit',
        'Measured At',
        'Notes'
    ];

    // Map measurement data to CSV rows
    const rows = props.measurements.map(m => [
        `"${props.selectedDevice.name.replace(/"/g, '""') || 'N/A'}"`, // Handle quotes and ensure device name is available
        `"${m.metric_name.replace(/"/g, '""')}"`,
        m.value,
        `"${(m.unit || 'N/A').replace(/"/g, '""')}"`,
        `"${m.measured_at ? dayjs(m.measured_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A'}"`,
        `"${(m.notes || 'N/A').replace(/"/g, '""')}"`
    ]);

    // Combine headers and rows into CSV content
    const csvContent = [
        headers.join(','), // Join headers with commas
        ...rows.map(e => e.join(',')) // Join each row's elements with commas
    ].join('\n'); // Join all lines with newlines

    // Create a Blob and download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    // Set filename
    const fileName = props.selectedDevice?.name
        ? `measurements_${props.selectedDevice.name}_${dayjs().format('YYYYMMDD_HHmmss')}.csv`
        : `measurements_${dayjs().format('YYYYMMDD_HHmmss')}.csv`;

    if (link.download !== undefined) { // Feature detection for download attribute
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', fileName);
        link.style.visibility = 'hidden'; // Hide the link
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link); // Clean up
        URL.revokeObjectURL(url); // Free up the URL object
    } else {
        // Fallback for browsers that don't support download attribute
        window.open(`data:text/csv;charset=utf-8,${encodeURIComponent(csvContent)}`);
    }
};

</script>

<template>
    <Head title="Messwerte" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="w-full px-4 py-6">
            <Alert v-if="error && !selectedDevice" variant="destructive" class="mb-5">
                <AlertCircle class="h-4 w-4" />
                <AlertTitle> Fehler! </AlertTitle>
                <AlertDescription>
                    {{ error }}
                </AlertDescription>
            </Alert>

            <div v-if="!selectedDevice">
                <Heading title="Messwerte betrachten" description="Wähle bitte ein Gerät unten im Menü aus, um die Messwerte zu betrachten" />
            </div>
            <div v-else>
                <Heading :title="'Messungen vom Gerät ' + selectedDevice.name" description="Dies ist die Unterschrift" />
            </div>

            <div class="grid gap-2">
                <Label> Gerät </Label>
                <div class="flex gap-3">
                    <Select v-model="selectedDeviceForFilter" @update:modelValue="handleDeviceSelection">
                        <SelectTrigger class="w-3/5">
                            <SelectValue>{{ displaySelectedDeviceName }}</SelectValue>
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup v-for="(group, index) in deviceGroups" :key="index">
                                <SelectLabel>{{ group.name }}</SelectLabel>
                                <SelectItem v-for="(device, index) in group.devices" :key="index" :value="device.id">
                                    {{ device.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <Button @click="exportMeasurementsToCsv" :disabled="props.measurements.length === 0">
                        <Download />
                        Export CSV
                    </Button>
                </div>
            </div>

            <div v-if="!selectedDevice" class="grid w-3/5 place-items-center pt-10">
                <div class="gap-5 rounded text-center space-y-2">
                    <AppLogoIcon class="size-72 fill-muted" />
                    <span class="font-bold text-muted"> Kein Gerät ausgewählt </span>
                </div>
            </div>
            <div v-else class="pt-10">
                <Table>
                    <TableCaption>Messungen von {{ selectedDevice.name }} </TableCaption>
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
        </div>
    </AppLayout>
</template>

<style scoped></style>
