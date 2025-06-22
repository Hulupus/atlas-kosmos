<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Heading from '@/components/Heading.vue';
import LineChart from '@/components/LineChart.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Device } from '@/types/Device';
import { DeviceGroup } from '@/types/DeviceGroup';
import { Measurement } from '@/types/Measurement';
import { Head, router, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/de';
import localizedFormat from 'dayjs/plugin/localizedFormat';
import { AlertCircle } from 'lucide-vue-next';
import { computed, ref } from 'vue';

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
    const newUrl = selectedDeviceForFilter.value ? route('graphs', { device_id: selectedDeviceForFilter.value }) : route('graphs');

    router.get(newUrl, {}, { preserveState: true });
};

const formatDateTime = (timestamp: Date | string | null) => {
    if (!timestamp) return 'N/A';
    return dayjs(timestamp).format('DD.MM.YYYY HH:mm:ss');
};

const breadcrumbs = computed<BreadcrumbItem[]>(() => {
    const items: BreadcrumbItem[] = [
        {
            title: 'Diagramme',
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
</script>

<template>
    <Head title="Diagramme" />

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
                <Heading title="Diagramme betrachten" description="Wähle bitte ein Gerät unten im Menü aus, um die Diagramme zu betrachten" />
            </div>
            <div v-else>
                <Heading :title="'Diagramme vom Gerät ' + selectedDevice.name" description="Dies ist die Unterschrift" />
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
                </div>
            </div>

            <div v-if="!selectedDevice" class="grid w-3/5 place-items-center pt-10">
                <div class="gap-5 rounded text-center space-y-2">
                    <AppLogoIcon class="size-72 fill-muted" />
                    <span class="font-bold text-muted"> Kein Gerät ausgewählt </span>
                </div>
            </div>
            <div v-else class="pt-10">
                <LineChart :measurements="measurements" />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
