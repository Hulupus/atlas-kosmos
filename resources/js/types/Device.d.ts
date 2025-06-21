/**
 * @interface Device
 * A simplified representation of a Device, used when eager loaded with DeviceGroup.
 * You might have a more comprehensive Device.d.ts file elsewhere.
 */
export interface Device {
    id: number;
    name: string;
    location: string | null;
    description: string | null;
    // Add other essential device properties if needed for client-side use
    // For example:
    // device_group_id: number;
    // user_id: number | null;
    // last_callback_at: string | Date | null;
}
