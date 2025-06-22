/**
 * @interface Measurement
 * Represents a measurement data point.
 */
export interface Measurement {
    /**
     * The unique identifier for the measurement.
     */
    id: number;

    /**
     * The ID of the device that recorded this measurement.
     */
    device_id: number;

    /**
     * The name of the metric (e.g., "Temperature", "Humidity").
     */
    metric_name: string;

    /**
     * The value of the measurement.
     */
    value: number;

    /**
     * The unit of the measurement (e.g., "°C", "%").
     */
    unit: string | null;

    /**
     * The timestamp when the measurement was taken.
     * Typically a string from the backend (ISO 8601 format) or a Date object.
     */
    measured_at: string | Date | null;

    /**
     * Optional notes associated with the measurement.
     */
    notes: string | null;

    /**
     * The timestamp when the measurement record was created in the database.
     */
    created_at: string | Date;

    /**
     * The timestamp when the measurement record was last updated in the database.
     */
    updated_at: string | Date;

    /**
     * The associated Device object, present if eager loaded using `with('device')`.
     */
    device?: Device;
}
