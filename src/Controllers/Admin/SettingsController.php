<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\Setting;

final class SettingsController extends AdminController
{
    private const TEXT_KEYS = [
        'site_name', 'site_tagline', 'contact_phone', 'contact_address',
        'footer_copyright', 'social_x_url', 'social_linkedin_url', 'contact_notify_email',
        'google_site_verification', 'ga_measurement_id',
    ];

    private const IMAGE_KEYS = ['site_logo', 'default_og_image'];

    // mail_password is handled separately (never echoed back to the page,
    // only overwritten when a new value is submitted) — see index()/update().
    private const MAIL_TEXT_KEYS = [
        'mail_host', 'mail_port', 'mail_encryption', 'mail_username',
        'mail_from_address', 'mail_from_name',
    ];

    public function index(Request $request): void
    {
        $values = [];
        foreach ([...self::TEXT_KEYS, ...self::IMAGE_KEYS, ...self::MAIL_TEXT_KEYS] as $key) {
            $values[$key] = Setting::get($key);
        }
        $values['mail_password_set'] = Setting::get('mail_password') !== '';
        $values['blog_enabled'] = Setting::get('blog_enabled', '0') === '1';

        $this->render('admin/settings/index', [
            'pageTitle' => 'Settings',
            'values' => $values,
        ]);
    }

    public function update(Request $request): void
    {
        $this->requireCsrf($request);

        foreach (self::TEXT_KEYS as $key) {
            Setting::set($key, $request->trimmedInput($key));
        }

        foreach (self::MAIL_TEXT_KEYS as $key) {
            Setting::set($key, $request->trimmedInput($key));
        }

        $mailPassword = $request->trimmedInput('mail_password');
        if ($mailPassword !== '') {
            Setting::set('mail_password', $mailPassword);
        }

        Setting::set('blog_enabled', $request->input('blog_enabled') ? '1' : '0');

        foreach (self::IMAGE_KEYS as $key) {
            try {
                $resolved = $this->resolveImage(
                    $_FILES[$key] ?? [],
                    $request->trimmedInput($key . '_alt_text'),
                    $request->input($key . '_existing_media_id')
                );
            } catch (\RuntimeException $e) {
                $this->flash('error', $e->getMessage());
                redirect('/admin/settings');
            }
            if ($resolved !== null) {
                Setting::set($key, $resolved);
            }
        }

        $this->flash('success', 'Settings saved.');
        redirect('/admin/settings');
    }
}
