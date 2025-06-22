<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import dayjs from 'dayjs'; // For date formatting
import 'dayjs/locale/de';
import relativeTime from 'dayjs/plugin/relativeTime';
import { ref } from 'vue';
// Shadcn-Vue Components
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Heading from '@/components/Heading.vue';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import { BadgeInfo, ChartNoAxesGantt, Plus } from 'lucide-vue-next';
import { Device } from '@/types/Device';

dayjs.extend(relativeTime);
dayjs.locale('de');

const props = defineProps<{
    totalDevices: number;
    totalMeasurements: number;
    recentDevices: Device[]
}>();

const showInfoDialog = ref(false);

// Helper to format dates
const formatRelativeDate = (dateString: string) => {
    return dayjs(dateString).fromNow();
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="w-full px-4 py-6">
            <Heading
                title="Wilkommen zum NERDS Atlas!"
                description="Ein zentraler Ort für die Verwaltung von IoT-Geräten und die Messdatenspeicherung derer Messwerte."
            />
            <div class="grid grid-cols-5 gap-4">
                <Card class="flex h-full flex-col rounded-lg hover:outline-2">
                    <CardContent class="flex-grow text-sm">
                        <CardDescription class="text-sm font-bold">Gesamtanzahl an Geräten</CardDescription>
                        <CardTitle class="text-4xl font-bold">{{ props.totalDevices }}</CardTitle>
                        <span class="text-muted-foreground"> Gesammte Anzahl an registrierten Geräten </span>
                    </CardContent>
                    <CardFooter>
                        <Button as-child>
                            <Link :href="route('devices.index')">Zu allen Geräten</Link>
                        </Button>
                    </CardFooter>
                </Card>

                <Card class="flex h-full flex-col rounded-lg hover:outline-2">
                    <CardContent class="flex-grow text-sm">
                        <CardDescription class="text-sm">Gesamtanzahl an Messungen</CardDescription>
                        <CardTitle class="text-4xl font-bold">{{ props.totalMeasurements }}</CardTitle>
                        <span class="text-muted-foreground"> Die Anzahl von allen Datenpunkten </span>
                    </CardContent>
                    <CardFooter>
                        <Button as-child>
                            <Link :href="route('measurements')">Betrachte die Daten</Link>
                        </Button>
                    </CardFooter>
                </Card>

                <Card class="col-span-2 flex h-full flex-col rounded-lg hover:outline-2">
                    <CardHeader>
                        <CardTitle class="text-xl">Quick Actions</CardTitle>
                    </CardHeader>
                    <CardContent class="flex-grow space-y-2">
                        <Button as-child class="w-full justify-start px-4 py-2">
                            <Link :href="route('devices.create')">
                                <Plus />
                                Füge ein neues Gerät hinzu
                            </Link>
                        </Button>
                        <Button as-child variant="outline" class="w-full justify-start px-4 py-2">
                            <Link :href="route('devices.index')">
                                <ChartNoAxesGantt />
                                Verwalte die Geräte
                            </Link>
                        </Button>
                        <AlertDialog v-model:open="showInfoDialog">
                            <AlertDialogTrigger as-child>
                                <Button variant="outline" class="w-full justify-start px-4 py-2">
                                    <BadgeInfo />
                                    Über NERDS Atlas
                                </Button>
                            </AlertDialogTrigger>
                            <AlertDialogContent>
                                <AlertDialogHeader>
                                    <AlertDialogTitle>About NERDS Atlas</AlertDialogTitle>
                                    <AlertDialogDescription>
                                        NERDS Atlas is your intuitive platform for managing IoT devices and visualizing sensor data efficiently.
                                        Connect your devices, collect real-time measurements, and gain insights into your environment.
                                    </AlertDialogDescription>
                                </AlertDialogHeader>
                                <AlertDialogFooter>
                                    <AlertDialogAction>Got it!</AlertDialogAction>
                                </AlertDialogFooter>
                            </AlertDialogContent>
                        </AlertDialog>
                    </CardContent>
                </Card>
            </div>

            <div class="w-4/5">
                <Separator class="my-10" />
            </div>

            <Heading title="Zuletzt hinzugefügte Geräte" />

            <div class="grid grid-cols-5 gap-4" v-if="props.recentDevices.length > 0">
                <Card v-for="device in props.recentDevices" :key="device.id" class="rounded-lg hover:outline-2">
                    <CardHeader class="pb-2">
                        <CardTitle>{{ device.name }}</CardTitle>
                        <CardDescription>{{ device.location || 'An keinem spezifischen Ort' }}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-gray-600">Hinzugefügt {{ formatRelativeDate(device.created_at) }}</p>
                    </CardContent>
                    <CardFooter class="flex justify-end p-4 pt-0">
                        <Button as-child>
                            <Link :href="route('measurements', { device_id: device.id })">Daten anzeigen</Link>
                        </Button>
                    </CardFooter>
                </Card>
            </div>
            <div v-else class="grid w-4/5 place-items-center pt-10">
                <div class="gap-5 rounded text-center space-y-2">
                    <AppLogoIcon class="size-72 fill-muted" />
                    <span class="font-bold text-muted">
                        Es gibt keine zuletzt hinzugefügten Geräte
                    </span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
