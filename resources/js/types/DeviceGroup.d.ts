/**
 * @interface DeviceGroup
 * Represents a device group in the application.
 */
export interface DeviceGroup {
    /**
     * The unique identifier for the device group.
     */
    id: number;

    /**
     * The unique name of the device group.
     */
    name: string;

    /**
     * An optional description for the device group.
     */
    description: string | null;

    /**
     * The timestamp when the device group was created.
     * Typically a string from the backend (e.g., ISO 8601 format)
     * or a Date object if explicitly parsed on the frontend.
     */
    created_at: string | Date;

    /**
     * The timestamp when the device group was last updated.
     * Typically a string from the backend (e.g., ISO 8601 format)
     * or a Date object if explicitly parsed on the frontend.
     */
    updated_at: string | Date;

    // If you also included the devices_count from `withCount('devices')`:
    /**
     * The number of devices associated with this group.
     * This property is typically added when using `withCount('devices')` in Laravel.
     */
    devices_count?: number; // Optional, as it's not always present

    /**
     * An array of devices that belong to this group.
     * This property is present when the 'devices' relationship is eager loaded (e.g., using `with('devices')` in Laravel).
     */
    devices?: Device[]; // NEW: Optional array of related Device objects
}
