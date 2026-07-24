<?php
/**
 * Global Settings Helper Functions
 * Provides cached, fast access to system settings across all models, controllers, views, and helpers.
 */

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null) {
        static $settingsCache = null;

        if ($settingsCache === null) {
            $settingsCache = [];
            try {
                if (function_exists('getConnection')) {
                    $db = getConnection();
                    $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
                    if ($stmt) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $settingsCache[$row['setting_key']] = $row['setting_value'];
                        }
                    }
                }
            } catch (Exception $e) {
                // Silently fallback if database is unavailable
            }
        }

        if (array_key_exists($key, $settingsCache) && $settingsCache[$key] !== '' && $settingsCache[$key] !== null) {
            return $settingsCache[$key];
        }

        return $default;
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amount, $showSymbol = true) {
        $num = is_numeric($amount) ? (float)$amount : 0.0;
        $symbol = get_setting('currency_symbol', get_setting('currency', 'USD'));
        $formatted = number_format($num, 2);
        return $showSymbol ? $symbol . ' ' . $formatted : $formatted;
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $customFormat = null) {
        if (empty($date)) return '';
        $format = $customFormat ?: get_setting('date_format', 'Y-m-d');
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        return $timestamp ? date($format, $timestamp) : $date;
    }
}

if (!function_exists('get_company_name')) {
    function get_company_name() {
        return get_setting('company_name', get_setting('site_title', 'My Business ERP'));
    }
}

if (!function_exists('get_company_logo')) {
    function get_company_logo() {
        $logo = get_setting('company_logo');
        if (!empty($logo) && file_exists(BASE_PATH . '/' . $logo)) {
            return BASE_URL . '/' . $logo;
        }
        return null;
    }
}
