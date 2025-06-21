<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Geräte',
        href: route('devices.index'),
    },
    {
        title: 'Erstellen',
        href: '',
    },
];

const form = useForm({
    name: '',
    device_group: '',
    location: '',
    description: '',
    webclient_start_url: '',
});

const resetForm = () => {
    form.reset('name');
    form.reset('device_group');
    form.reset('location');
    form.reset('description');
    form.reset('webclient_start_url');
};

const handleSubmit = () => {
    form.post(route('devices.store'), {
        onFinish: () => {},
    });
};
</script>

<template>
    <Head title="Gerät erstellen" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="w-full px-4 py-6">
            <Heading title="Füge ein neues Gerät hinzu" description="Fülle die Felder unten aus, um ein neues Gerät zu registrieren" />

            <form @submit.prevent="handleSubmit" class="flex flex-col gap-6">
                <!-- Gerätename -->
                <div class="grid gap-2">
                    <Label for="name" :class="{ 'text-red-600': form.errors.name }">Gerätename <span class="text-red-500">*</span></Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        :class="{ 'border-red-500': form.errors.name }"
                        placeholder="z.B. Wohnzimmer-Sensor"
                    />
                    <p class="mt-1 text-xs text-gray-500">Ein global eindeutiger Name für Ihr Gerät.</p>
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Gerätegruppe (Shadcn-Vue Select) -->
                <div class="grid gap-2">
                    <Label for="device_group" :class="{ 'text-red-600': form.errors.device_group }"
                        >Gerätegruppe <span class="text-red-500">*</span></Label
                    >
                    <Select v-model="form.device_group">
                        <SelectTrigger class="w-full" :class="{ 'border-red-500': form.errors.device_group }">
                            <SelectValue placeholder="Wähle eine Gruppe aus" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Gruppen</SelectLabel>
                                <SelectItem value="gnosis"> Gnosis </SelectItem>
                                <SelectItem value="apollo"> Apollo </SelectItem>
                                <SelectItem value="hermes"> Hermes </SelectItem>
                                <SelectItem value="other"> Andere </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <p class="mt-1 text-xs text-gray-500">Weisen Sie Ihr Gerät einer logischen Gruppe zu.</p>
                    <InputError :message="form.errors.device_group" />
                </div>

                <!-- Standort (optional) -->
                <div>
                    <Label for="location" :class="{ 'text-red-600': form.errors.location }">Standort (optional)</Label>
                    <Input
                        id="location"
                        v-model="form.location"
                        type="text"
                        :class="{ 'border-red-500': form.errors.location }"
                        placeholder="z.B. Küche"
                    />
                    <InputError :message="form.errors.location" />
                </div>

                <!-- Beschreibung (optional) -->
                <div>
                    <Label for="description" :class="{ 'text-red-600': form.errors.description }">Beschreibung (optional)</Label>
                    <Textarea
                        id="description"
                        v-model="form.description"
                        rows="3"
                        :class="{ 'border-red-500': form.errors.description }"
                        placeholder="Beschreiben Sie kurz den Zweck des Geräts..."
                    ></Textarea>
                    <InputError :message="form.errors.description" />
                </div>

                <!-- Webclient Start-URL (optional) -->
                <div>
                    <Label for="webclient_start_url" :class="{ 'text-red-600': form.errors.webclient_start_url }"
                        >Webclient Start-URL (optional)</Label
                    >
                    <Input
                        id="webclient_start_url"
                        v-model="form.webclient_start_url"
                        type="url"
                        :class="{ 'border-red-500': form.errors.webclient_start_url }"
                        placeholder="https://example.com/device/sensor-1"
                    />
                    <p class="mt-1 text-xs text-gray-500">Geben Sie eine URL für den Zugriff auf die Webclient-Oberfläche des Geräts an.</p>
                    <InputError :message="form.errors.webclient_start_url" />
                </div>

                <!-- Formularaktionen -->
                <div class="flex justify-end space-x-3 pt-4">
                    <Button type="button" variant="outline" @click="resetForm" class="inline-flex items-center px-4 py-2" :disabled="form.processing">
                        Abbrechen
                    </Button>
                    <Button type="submit" class="inline-flex items-center px-4 py-2" :disabled="form.processing">
                        <span v-if="form.processing">Gerät wird hinzugefügt...</span>
                        <span v-else>Gerät hinzufügen</span>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<style scoped></style>
