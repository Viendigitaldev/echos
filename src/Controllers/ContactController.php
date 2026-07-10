<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\View;
use App\Models\ContactSubmission;
use App\Models\OfficeLocation;
use App\Models\Page;
use App\Models\Setting;
use App\Services\CsrfService;
use App\Services\JsonLd;
use App\Services\Mailer;
use App\Services\SeoMetaService;

final class ContactController
{
    /** Max submissions allowed from one IP within RATE_LIMIT_WINDOW_MINUTES. */
    private const RATE_LIMIT_MAX = 3;
    private const RATE_LIMIT_WINDOW_MINUTES = 15;

    public function index(Request $request): void
    {
        $page = Page::findBySlug('contact');
        $blocks = Page::blocksFor((int) $page['id']);
        $seo = SeoMetaService::resolve($page, Setting::get('site_name', 'Echos'));

        $flash = $_SESSION['contact_flash'] ?? null;
        unset($_SESSION['contact_flash']);

        (new View())->render('contact/index', [
            'title' => $seo['title'],
            'metaDescription' => $seo['description'],
            'ogImage' => $seo['ogImage'],
            'intro' => $blocks['intro'],
            'locationsHeading' => $blocks['locations_heading'],
            'locations' => OfficeLocation::ordered(),
            'flash' => $flash,
            'old' => $_SESSION['contact_old'] ?? [],
            'jsonLd' => [JsonLd::breadcrumb([
                ['name' => 'Home', 'path' => '/'],
                ['name' => 'Contact', 'path' => '/contact'],
            ])],
        ]);

        unset($_SESSION['contact_old']);
    }

    public function submit(Request $request): void
    {
        if (!CsrfService::validate($request->input('_csrf'))) {
            $_SESSION['contact_flash'] = ['type' => 'error', 'message' => 'Your session expired. Please try again.'];
            redirect('/contact');
        }

        $name = $request->trimmedInput('name');
        $email = $request->trimmedInput('email');
        $message = $request->trimmedInput('message');
        $honeypot = $request->trimmedInput('website');

        $errors = [];
        if ($honeypot !== '') {
            // Silently drop bot submissions without revealing the honeypot to the client.
            redirect('/contact');
        }

        if (ContactSubmission::countRecentByIp($request->ip(), self::RATE_LIMIT_WINDOW_MINUTES) >= self::RATE_LIMIT_MAX) {
            $_SESSION['contact_flash'] = ['type' => 'error', 'message' => 'Too many submissions from this connection. Please try again in a few minutes.'];
            $_SESSION['contact_old'] = $request->all();
            redirect('/contact');
        }

        if ($name === '') {
            $errors[] = 'Name is required.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }
        if ($message === '') {
            $errors[] = 'Message is required.';
        }

        if ($errors !== []) {
            $_SESSION['contact_flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
            $_SESSION['contact_old'] = $request->all();
            redirect('/contact');
        }

        ContactSubmission::insert([
            'reason' => $request->trimmedInput('reason'),
            'name' => $name,
            'email' => $email,
            'phone' => $request->trimmedInput('phone'),
            'company' => $request->trimmedInput('company'),
            'message' => $message,
            'ip_address' => $request->ip(),
        ]);

        $notifyEmail = Setting::get('contact_notify_email');
        if ($notifyEmail !== '') {
            Mailer::send(
                $notifyEmail,
                'New contact submission — Echos',
                "Name: {$name}\nEmail: {$email}\nMessage:\n{$message}"
            );
        }

        // One-time flag rather than a flash message: the confirmation now lives
        // on its own page (bookmarkable URL for GA4 conversion goals) instead
        // of a banner back on /contact.
        $_SESSION['contact_submitted'] = true;
        redirect('/thank-you');
    }

    public function thankYou(Request $request): void
    {
        if (empty($_SESSION['contact_submitted'])) {
            redirect('/contact');
        }
        unset($_SESSION['contact_submitted']);

        $page = Page::findBySlug('thank-you');
        $blocks = Page::blocksFor((int) $page['id']);
        $seo = SeoMetaService::resolve($page, Setting::get('site_name', 'Echos'));

        (new View())->render('contact/thank-you', [
            'title' => $seo['title'],
            'metaDescription' => $seo['description'],
            'ogImage' => $seo['ogImage'],
            'noindex' => true,
            'block' => $blocks['content'],
        ]);
    }
}
