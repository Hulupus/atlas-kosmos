<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { cn } from '@/lib/utils';
import type { BreadcrumbItem } from '@/types';
import { DeviceGroup } from '@/types/DeviceGroup';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { BadgeInfo, Bell, Database, Gauge, KeyRound, Rocket } from 'lucide-vue-next';
import { defineProps } from 'vue';

const props = defineProps<{
    deviceGroups: DeviceGroup[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Geräte',
        href: route('devices.index'),
    },
];

const notifications = [
    {
        title: 'Your call has been confirmed.',
        description: '1 hour ago',
    },
    {
        title: 'You have a new message!',
        description: '1 hour ago',
    },
    {
        title: 'Your subscription is expiring soon!',
        description: '2 hours ago',
    },
];

const page = usePage();

const successMessage = page.props.flash.success || '';
const deviceToken = page.props.flash.deviceToken || null;
const deviceName = page.props.flash.deviceName || '';
</script>

<template>
    <Head title="Geräte" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="w-full px-4 py-6">
            <Link :href="route('devices.create')">
                <Alert class="mb-6">
                    <BadgeInfo class="h-4 w-4" />
                    <AlertTitle>Mitgestalten!</AlertTitle>
                    <AlertDescription class="flex">
                        Fehlt dir ein Gerät? Dann melde <span class="font-bold">hier</span> ein neues Gerät an
                    </AlertDescription>
                </Alert>
            </Link>
            <Alert class="mb-6" variant="destructive" v-if="successMessage">
                <Rocket class="h-4 w-4" />
                <AlertTitle> {{ deviceName }} erfolgreich erstellt</AlertTitle>
                <AlertDescription class="flex">
                    {{ successMessage }} <span v-if="deviceToken"> Dein Token ist: {{ deviceToken }}</span>
                </AlertDescription>
            </Alert>

            <div v-for="(group, index) in props.deviceGroups" :key="index" class="pb-4">
                <Heading :title="group.name" :description="group.description ? group.description : ''" />

                <div class="flex flex-wrap gap-3">
                    <Card :class="cn('w-[380px]', $attrs.class ?? '')" v-for="(device, index) in group.devices" :key="index">
                        <CardHeader class="flex justify-between">
                            <div>
                                <CardTitle>{{ device.name }}</CardTitle>
                                <CardDescription>You have 3 unread messages.</CardDescription>
                            </div>
                            <div>
                                <Button class="mr-3" variant="outline" size="icon">
                                    <KeyRound class="h-4 w-4" />
                                </Button>
                                <Button variant="outline" size="icon">
                                    <Bell class="h-4 w-4" />
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent class="grid gap-4">
                            <div>
                                <div
                                    v-for="(notification, index) in notifications"
                                    :key="index"
                                    class="mb-4 grid grid-cols-[25px_minmax(0,1fr)] items-start pb-4 last:mb-0 last:pb-0"
                                >
                                    <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                                    <div class="space-y-1">
                                        <p class="text-sm leading-none font-medium">
                                            {{ notification.title }}
                                        </p>
                                        <p class="text-sm text-muted-foreground">
                                            {{ notification.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-end gap-3">
                            <Link v-if="device.has_dashboard" :href="'/devices/' + device.name.toLowerCase()" class="w-3/5">
                                <Button>
                                    <Gauge class="mr-2 h-4 w-4" />
                                    Übersicht ansehen
                                </Button>
                            </Link>
                            <Link class="w-2/5" :href="route('measurements', { device_id: device.id })">
                                <Button variant="outline">
                                    <Database class="mr-2 h-4 w-4" />
                                    Messwerte
                                </Button>
                            </Link>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
