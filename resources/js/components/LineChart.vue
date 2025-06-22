<script setup lang="ts">
import { computed } from 'vue';
import { Line } from 'vue-chartjs'; // The Line component from vue-chartjs

import { Measurement } from '@/types/Measurement'; // Import German locale for formatting
import dayjs from 'dayjs';
import 'dayjs/locale/de';
import localizedFormat from 'dayjs/plugin/localizedFormat';

// Extend dayjs with necessary plugins and set locale
dayjs.extend(localizedFormat);
dayjs.locale('de');

// Define props expected by this component
interface Props {
    measurements: Measurement[];
    chartTitle?: string;
    metricName?: string;
    unit?: string;
}

const props = defineProps<Props>();

// Define colors for the line segments
const LIGHT_COLOR_BACKGROUND = 'rgba(66, 165, 245, 0.4)'; // Lighter blue
const LIGHT_COLOR_BORDER = 'rgba(30, 136, 229, 0.6)'; // Lighter blue border
const STRONG_COLOR_BACKGROUND = '#42A5F5'; // Original blue
const STRONG_COLOR_BORDER = '#1E88E5'; // Original darker blue

// Reactive variable for chart data
const chartData = computed(() => {
    if (!props.measurements || props.measurements.length === 0) {
        return {
            labels: [],
            datasets: [],
        };
    }

    const filteredMeasurements = props.metricName ? props.measurements.filter((m) => m.metric_name === props.metricName) : props.measurements;

    // Sort measurements by timestamp to ensure chronological order on the chart
    filteredMeasurements.sort((a, b) => {
        const dateA = a.measured_at ? dayjs(a.measured_at).valueOf() : 0;
        const dateB = b.measured_at ? dayjs(b.measured_at).valueOf() : 0;
        return dateA - dateB;
    });

    const labels: string[] = [];
    const GAP_THRESHOLD_HOURS = 2; // Define the maximum acceptable time gap in hours

    // Determine the start index of the LAST continuous segment
    let lastSegmentStartIndex = 0; // Assume the whole dataset is one segment initially
    for (let i = 0; i < filteredMeasurements.length; i++) {
        const m = filteredMeasurements[i];
        const currentMeasuredAt = m.measured_at ? dayjs(m.measured_at) : null;
        labels.push(currentMeasuredAt ? currentMeasuredAt.format('HH:mm') : ''); // Build labels for the whole X-axis

        if (i > 0) {
            const previousMeasuredAt = filteredMeasurements[i - 1].measured_at ? dayjs(filteredMeasurements[i - 1].measured_at) : null;
            if (currentMeasuredAt && previousMeasuredAt) {
                const diffHours = currentMeasuredAt.diff(previousMeasuredAt, 'hour');
                if (diffHours > GAP_THRESHOLD_HOURS) {
                    // If a gap is found, this point marks the start of a *new* continuous segment
                    // We keep updating this, so by the end, it will be the start of the LAST segment
                    lastSegmentStartIndex = i;
                }
            }
        }
    }

    const datasets = [];

    const lightData: (number | null)[] = [];
    const strongData: (number | null)[] = [];

    // Populate lightData and strongData arrays
    for (let i = 0; i < filteredMeasurements.length; i++) {
        if (i < lastSegmentStartIndex) {
            // Data before the last continuous segment
            lightData.push(filteredMeasurements[i].value);
            strongData.push(null); // No strong color here
        } else {
            // Data from the start of the last continuous segment onwards
            lightData.push(null); // No light color here
            strongData.push(filteredMeasurements[i].value);
        }
    }

    // Add the "light" dataset if it contains any non-null data
    if (lightData.some((val) => val !== null)) {
        datasets.push({
            label: `${props.metricName || 'Value'} (Past - ${props.unit || ''})`,
            backgroundColor: LIGHT_COLOR_BACKGROUND,
            borderColor: LIGHT_COLOR_BORDER,
            data: lightData,
            tension: 0.1,
            pointRadius: 3,
            pointBackgroundColor: LIGHT_COLOR_BORDER,
            pointBorderColor: '#fff',
        });
    }

    // Add the "strong" (last part) dataset if it contains any non-null data
    if (strongData.some((val) => val !== null)) {
        datasets.push({
            label: `${props.metricName || 'Value'} (Current - ${props.unit || ''})`,
            backgroundColor: STRONG_COLOR_BACKGROUND,
            borderColor: STRONG_COLOR_BORDER,
            data: strongData,
            tension: 0.1,
            pointRadius: 3,
            pointBackgroundColor: STRONG_COLOR_BORDER,
            pointBorderColor: '#fff',
        });
    }

    return {
        labels: labels,
        datasets: datasets,
    };
});

// Chart options for basic styling and interactivity
const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        title: {
            display: !!props.chartTitle,
            text: props.chartTitle || 'Measurement Data',
        },
        tooltip: {
            mode: 'index',
            intersect: false,
        },
        legend: {
            display: true,
            labels: {
                // Use a filter to only show labels for datasets that actually contain data
                filter: function (item, chart) {
                    if (!chart || !chart.data || !chart.data.datasets || !chart.data.datasets[item.datasetIndex]) {
                        return false;
                    }
                    const dataset = chart.data.datasets[item.datasetIndex];
                    return dataset.data.some((val) => val !== null);
                },
            },
        },
    },
    scales: {
        x: {
            type: 'category',
            labels: chartData.value.labels,
            title: {
                display: true,
                text: 'Time',
            },
        },
        y: {
            title: {
                display: true,
                text: `Value (${props.unit || ''})`,
            },
        },
    },
    elements: {
        point: {
            radius: 3,
            backgroundColor: '#1E88E5',
            borderColor: '#fff',
            borderWidth: 1,
            hoverRadius: 5,
            hoverBorderWidth: 2,
        },
    },
}));

// If the props change, the computed properties will automatically re-evaluate
// and the chart will re-render.
</script>

<template>
    <div class="relative h-96 w-full rounded-lg bg-white p-4 shadow-md">
        <Line v-if="chartData.datasets.length > 0 && chartData.labels.length > 0" :data="chartData" :options="chartOptions" class="h-full w-full" />
        <div v-else class="flex h-full items-center justify-center text-gray-500">No measurement data available for charting.</div>
    </div>
</template>
