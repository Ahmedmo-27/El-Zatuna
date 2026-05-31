<?php

return [
    // Menu / titles
    'logs' => 'Logs',
    'logs_dashboard' => 'Logs Dashboard',
    'system_logs' => 'System Logs',
    'activity_logs' => 'Activity Logs',
    'activity_logs_subtitle' => 'Audit trail of actions performed across the platform',

    // Table headers
    'id' => 'ID',
    'date_time' => 'Date & time',
    'log_name' => 'Log',
    'event' => 'Type',
    'description' => 'Description',
    'causer' => 'Performed by',
    'subject' => 'Subject',
    'properties' => 'Details',
    'ip_address' => 'IP address',
    'changes' => 'Changes',
    'no_records' => 'No activity has been recorded yet.',

    // Events
    'event_created' => 'Created',
    'event_updated' => 'Updated',
    'event_deleted' => 'Deleted',
    'event_restored' => 'Restored',

    // Filters
    'search' => 'Search',
    'search_placeholder' => 'Search description or user',
    'all_events' => 'All types',
    'all_logs' => 'All logs',
    'from_date' => 'From date',
    'to_date' => 'To date',
    'show_results' => 'Show results',

    // Actions
    'view_details' => 'View details',
    'delete' => 'Delete',
    'clear_old' => 'Clear logs older than 30 days',
    'record_deleted' => 'Activity record deleted.',
    'records_cleared' => ':count old activity record(s) cleared.',
    'system' => 'System',

    // Details modal
    'old_value' => 'Old value',
    'new_value' => 'New value',
    'attribute' => 'Attribute',
    'no_changes' => 'No attribute changes were recorded.',

    // Hints
    'hint_title_1' => 'System Logs',
    'hint_description_1' => 'Application-level log file (errors, warnings, info, debug) rendered by the Log Viewer library.',
    'hint_title_2' => 'Activity Logs',
    'hint_description_2' => 'Who did what and when: model create/update/delete events recorded by the Activity Log library.',
    'hint_title_3' => 'Retention',
    'hint_description_3' => 'Activity records are kept in the database. Use "Clear logs" to prune old entries and keep the table lean.',
];
