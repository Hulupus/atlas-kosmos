<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Bell, Check } from 'lucide-vue-next';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle
} from '@/components/ui/card';
import Heading from '@/components/Heading.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Geräte',
        href: route('devices.index')
    }
];

const notifications = [
    {
        title: 'Your call has been confirmed.',
        description: '1 hour ago'
    },
    {
        title: 'You have a new message!',
        description: '1 hour ago'
    },
    {
        title: 'Your subscription is expiring soon!',
        description: '2 hours ago'
    }
];

const deviceGroups = [
    {
        title: 'Gnosis',
        description: 'Eine Sensorik-Gruppe für Aquaponiksysteme, die kritische Wasserparameter überwachen, um optimale Bedingungen für Fische und Pflanzen zu sichern',
        devices: [
            {
                name: 'Epimetheus'
            },
            {
                name: 'Prometheus'
            }
        ]
    }
];
</script>

<template>
    <Head title="Geräte" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-for="(group, index) in deviceGroups" :key="index" class="px-4 py-6 w-full">
            <Heading :title="group.title" :description="group.description" />

            <div class="flex flex-wrap gap-3">
                <Card :class="cn('w-[380px]', $attrs.class ?? '')" v-for="(device, index) in group.devices" :key="index">
                    <CardHeader>
                        <CardTitle>{{ device.name }}</CardTitle>
                        <CardDescription>You have 3 unread messages.</CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4">

                        <div>
                            <div
                                v-for="(notification, index) in notifications" :key="index"
                                class="mb-4 grid grid-cols-[25px_minmax(0,1fr)] items-start pb-4 last:mb-0 last:pb-0"
                            >
                                <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                                <div class="space-y-1">
                                    <p class="text-sm font-medium leading-none">
                                        {{ notification.title }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ notification.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button class="w-full">
                            <Check class="mr-2 h-4 w-4" />
                            Mark all as read
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
