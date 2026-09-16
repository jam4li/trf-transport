<?php

namespace Trust\INC;

/**
 * Multi-image gallery support for validation / tracking records.
 * Stores JSON in gallery_urls and keeps thumbnail_url as the first image
 * for backward compatibility with the original frontend.
 */
class ValidationGallery
{
    public static function boot()
    {
        add_action('init', [__CLASS__, 'ensure_schema']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_frontend_styles'], 20);
    }

    public static function ensure_schema()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpwv_validations';
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $column = $wpdb->get_results($wpdb->prepare("SHOW COLUMNS FROM `{$table}` LIKE %s", 'gallery_urls'));
        if (!empty($column)) {
            return;
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $wpdb->query("ALTER TABLE `{$table}` ADD COLUMN `gallery_urls` LONGTEXT NULL AFTER `thumbnail_url`");

        // Migrate existing single thumbnails into gallery_urls.
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $rows = $wpdb->get_results("SELECT id, thumbnail_url FROM `{$table}` WHERE thumbnail_url IS NOT NULL AND thumbnail_url <> '' AND (gallery_urls IS NULL OR gallery_urls = '')");
        foreach ($rows as $row) {
            $wpdb->update(
                $table,
                ['gallery_urls' => self::encode([esc_url_raw($row->thumbnail_url)])],
                ['id' => (int) $row->id]
            );
        }
    }

    public static function enqueue_admin_assets($hook)
    {
        $page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
        if (!in_array($page, ['wpv_add_validation', 'wpv_overview'], true)) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_script(
            'trust-validation-gallery',
            TRUST_URL . 'assets/js/admin/validation-gallery.js',
            ['jquery'],
            '1.0.0',
            true
        );
        wp_enqueue_style(
            'trust-validation-gallery-admin',
            TRUST_URL . 'assets/css/validation-gallery-admin.css',
            [],
            '1.0.0'
        );
    }

    public static function enqueue_frontend_styles()
    {
        wp_enqueue_style(
            'trust-validation-gallery',
            TRUST_URL . 'assets/css/validation-gallery.css',
            [],
            '1.0.0'
        );
    }

    public static function normalize_urls($urls)
    {
        if (!is_array($urls)) {
            return [];
        }

        $clean = [];
        foreach ($urls as $url) {
            $url = esc_url_raw(trim((string) $url));
            if ($url === '') {
                continue;
            }
            $clean[] = $url;
        }

        return array_values(array_unique($clean));
    }

    public static function urls_from_request()
    {
        $urls = [];

        if (isset($_POST['gallery_urls']) && is_array($_POST['gallery_urls'])) {
            $urls = wp_unslash($_POST['gallery_urls']);
        } elseif (!empty($_POST['gallery_urls_json'])) {
            $decoded = json_decode(wp_unslash($_POST['gallery_urls_json']), true);
            if (is_array($decoded)) {
                $urls = $decoded;
            }
        } elseif (!empty($_POST['thumbnail_url'])) {
            $urls = [wp_unslash($_POST['thumbnail_url'])];
        }

        return self::normalize_urls($urls);
    }

    public static function encode(array $urls)
    {
        return wp_json_encode(array_values(self::normalize_urls($urls)));
    }

    public static function decode($value)
    {
        if (empty($value)) {
            return [];
        }
        if (is_array($value)) {
            return self::normalize_urls($value);
        }

        $decoded = json_decode((string) $value, true);
        if (!is_array($decoded)) {
            return self::normalize_urls([(string) $value]);
        }

        return self::normalize_urls($decoded);
    }

    public static function gallery_from_row($row)
    {
        $gallery = [];
        if (is_object($row) && isset($row->gallery_urls)) {
            $gallery = self::decode($row->gallery_urls);
        } elseif (is_array($row) && isset($row['gallery_urls'])) {
            $gallery = self::decode($row['gallery_urls']);
        }

        if (!empty($gallery)) {
            return $gallery;
        }

        $thumb = '';
        if (is_object($row) && !empty($row->thumbnail_url)) {
            $thumb = $row->thumbnail_url;
        } elseif (is_array($row) && !empty($row['thumbnail_url'])) {
            $thumb = $row['thumbnail_url'];
        }

        return self::normalize_urls([$thumb]);
    }

    public static function db_fields_from_urls(array $urls)
    {
        $urls = self::normalize_urls($urls);
        return [
            'thumbnail_url' => $urls[0] ?? '',
            'gallery_urls'  => self::encode($urls),
        ];
    }

    public static function render_admin_field(array $urls = [])
    {
        $urls = self::normalize_urls($urls);
        ?>
        <div class="trust-gallery-field" data-trust-gallery>
            <label>تصاویر (می‌توانید چند تصویر انتخاب کنید)</label>
            <div class="trust-gallery-preview" data-trust-gallery-preview>
                <?php foreach ($urls as $url) : ?>
                    <div class="trust-gallery-item" data-url="<?php echo esc_attr($url); ?>">
                        <img src="<?php echo esc_url($url); ?>" alt="">
                        <button type="button" class="button-link trust-gallery-remove" aria-label="حذف">&times;</button>
                        <input type="hidden" name="gallery_urls[]" value="<?php echo esc_attr($url); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <p>
                <button type="button" class="button" data-trust-gallery-add>افزودن / انتخاب تصاویر</button>
            </p>
            <p class="description">از کتابخانه رسانه وردپرس چند تصویر انتخاب کنید. ترتیب نمایش همان ترتیب انتخاب است.</p>
        </div>
        <?php
    }
}
