<?php
/**
 * Mailgun notification helper
 * Sends a lead alert email to rankmonk@gmail.com
 */

$_mailer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($_mailer_autoload)) {
    require_once $_mailer_autoload;
}

if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

use Mailgun\Mailgun;

function send_lead_notification(array $lead): void {
    if (!class_exists('Mailgun\Mailgun')) return;

    $mg     = Mailgun::create(defined('MAILGUN_API_KEY') ? MAILGUN_API_KEY : '');
    $domain = defined('MAILGUN_DOMAIN') ? MAILGUN_DOMAIN : '';

    $name      = $lead['name']      ?? '—';
    $email     = $lead['email']     ?? '—';
    $phone     = $lead['phone']     ?? '—';
    $source    = $lead['source']    ?? '—';
    $budget    = $lead['budget']    ?? '—';
    $goal      = $lead['goal']      ?? '—';
    $challenge = $lead['challenge'] ?? '—';

    $source_label = $source === 'audit' ? 'Free Audit Form' : 'Contact Form';

    $text = <<<TEXT
New lead received on HariGopinath.com

Name      : {$name}
Email     : {$email}
Phone     : {$phone}
Source    : {$source_label}
Budget    : {$budget}
Challenge : {$challenge}
Goal      : {$goal}

View in CRM: http://localhost/harippc/admin/crm.php
TEXT;

    $html = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:Inter,Arial,sans-serif;background:#f6f9fc;margin:0;padding:0;">
<div style="max-width:560px;margin:40px auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #cbd6e2;">
  <div style="background:#2d3e50;padding:28px 32px;">
    <p style="color:#fff;font-size:1.1rem;font-weight:700;margin:0;">🔔 New Lead — HariGopinath.com</p>
  </div>
  <div style="padding:28px 32px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="font-size:0.92rem;color:#2d3e50;">
      <tr><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-weight:700;width:130px;">Name</td><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;">{$name}</td></tr>
      <tr><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-weight:700;">Email</td><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;"><a href="mailto:{$email}" style="color:#FF7A59;">{$email}</a></td></tr>
      <tr><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-weight:700;">Phone</td><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;">{$phone}</td></tr>
      <tr><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-weight:700;">Source</td><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;">{$source_label}</td></tr>
      <tr><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-weight:700;">Budget</td><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;">{$budget}</td></tr>
      <tr><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;font-weight:700;">Challenge</td><td style="padding:8px 0;border-bottom:1px solid #f0f0f0;">{$challenge}</td></tr>
      <tr><td style="padding:8px 0;font-weight:700;vertical-align:top;">Goal</td><td style="padding:8px 0;">{$goal}</td></tr>
    </table>
    <div style="margin-top:28px;">
      <a href="http://localhost/harippc/admin/crm.php"
         style="display:inline-block;padding:12px 28px;background:#FF7A59;color:#fff;border-radius:6px;font-weight:700;text-decoration:none;font-size:0.95rem;">
        View in CRM &rarr;
      </a>
    </div>
  </div>
  <div style="background:#f6f9fc;padding:16px 32px;font-size:0.78rem;color:#7c98b6;border-top:1px solid #cbd6e2;">
    HariGopinath.com &mdash; Revenue &gt; Reports.
  </div>
</div>
</body>
</html>
HTML;

    try {
        $mg->messages()->send($domain, [
            'from'    => 'HariGopinath.com <postmaster@' . $domain . '>',
            'to'      => 'Hari Gopinath <' . (defined('NOTIFY_EMAIL') ? NOTIFY_EMAIL : '') . '>',
            'subject' => '🔔 New Lead: ' . $name . ' via ' . $source_label,
            'text'    => $text,
            'html'    => $html,
        ]);
    } catch (Exception $e) {
        // Silently fail — don't break the user-facing success page
        error_log('Mailgun error: ' . $e->getMessage());
    }
}
