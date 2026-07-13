<?php
/**
 * @var string $siteName
 * @var string $logoUrl
 * @var string $reason
 * @var string $name
 * @var string $email
 * @var string $phone
 * @var string $company
 * @var string $message
 * @var string $submittedAt
 * @var string $ipAddress
 * @var string $adminUrl
 */

$rows = [
    'Reason' => $reason,
    'Name' => $name,
    'Email' => $email,
    'Phone' => $phone,
    'Company' => $company,
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($siteName) ?> — New contact submission</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f2; font-family:Arial, Helvetica, sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f2; padding:32px 16px;">
<tr>
<td align="center">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:8px; overflow:hidden; border:1px solid #e4e4e0;">

<tr>
<td style="background-color:#829b71; padding:24px 32px;">
<?php if ($logoUrl !== ''): ?>
<img src="<?= e($logoUrl) ?>" alt="<?= e($siteName) ?>" height="28" style="display:block; height:28px;">
<?php else: ?>
<span style="font-size:20px; font-weight:bold; color:#ffffff; letter-spacing:0.5px;"><?= e($siteName) ?></span>
<?php endif; ?>
</td>
</tr>

<tr>
<td style="padding:32px;">
<h1 style="margin:0 0 4px; font-size:18px; color:#1a1a1a;">New contact form submission</h1>
<p style="margin:0 0 24px; font-size:13px; color:#8a8a82;">Submitted <?= e($submittedAt) ?> from IP <?= e($ipAddress) ?></p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; color:#1a1a1a;">
<?php foreach ($rows as $label => $value): ?>
<?php if (trim((string) $value) === '') { continue; } ?>
<tr>
<td style="padding:8px 0; border-bottom:1px solid #efefeb; width:110px; color:#8a8a82; vertical-align:top;"><?= e($label) ?></td>
<td style="padding:8px 0; border-bottom:1px solid #efefeb; vertical-align:top;"><?= e($value) ?></td>
</tr>
<?php endforeach; ?>
</table>

<p style="margin:20px 0 6px; font-size:13px; color:#8a8a82; font-weight:bold;">Message</p>
<p style="margin:0 0 28px; font-size:14px; color:#1a1a1a; white-space:pre-wrap; background-color:#f8f8f6; border-radius:6px; padding:14px 16px;"><?= nl2br(e($message)) ?></p>

<table role="presentation" cellpadding="0" cellspacing="0">
<tr>
<td style="border-radius:6px; background-color:#829b71;">
<a href="<?= e($adminUrl) ?>" style="display:inline-block; padding:12px 22px; font-size:14px; color:#ffffff; text-decoration:none; font-weight:bold;">View in Admin</a>
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td style="padding:16px 32px; background-color:#f8f8f6; border-top:1px solid #efefeb;">
<p style="margin:0; font-size:12px; color:#8a8a82;">This is an automated notification from the <?= e($siteName) ?> contact form.</p>
</td>
</tr>

</table>
</td>
</tr>
</table>
</body>
</html>
