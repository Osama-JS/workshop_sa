<?php

if (!function_exists('storage_asset')) {
    /**
     * Generate an asset path for storage files compatible with standard symlinks,
     * subfolders, and cPanel/shared hosting root mode.
     *
     * @param string|null $path
     * @param string $fallback
     * @return string
     */
    function storage_asset(?string $path, string $fallback = ''): string
    {
        if (empty($path)) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Clean any duplicate or leading storage/ / app/public/ prefixes
        $clean = ltrim(preg_replace('#^(storage/|app/public/)+#', '', $path), '/');

        // 1. If public/storage is a valid working symlink or file exists in public/storage
        if (is_link(public_path('storage')) || file_exists(public_path('storage/' . $clean))) {
            return asset('storage/' . $clean);
        }

        // 2. Shared hosting / cPanel root mode (where project root is public_html without symlink)
        if (file_exists(base_path('storage/app/public/' . $clean))) {
            return asset('storage/app/public/' . $clean);
        }

        // 3. Fallback to storage/app/public if running without symlink in root
        if (!is_link(base_path('storage')) && !is_link(public_path('storage'))) {
            return asset('storage/app/public/' . $clean);
        }

        // 4. Default standard fallback
        return asset('storage/' . $clean);
    }
}
