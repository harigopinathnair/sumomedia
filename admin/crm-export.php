<?php
require_once 'auth.php';

$status_filter = $_GET['status'] ?? 'all';

$valid = ['all', 'new', 'engaged', 'proposal_sent', 'won', 'lost'];
if (!in_array($status_filter, $valid)) $status_filter = 'all';

if ($status_filter === 'all') {
    $leads = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    $filename = 'leads-all-' . date('Y-m-d') . '.csv';
} else {
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE status = ? ORDER BY created_at DESC");
    $stmt->execute([$status_filter]);
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $filename = 'leads-' . $status_filter . '-' . date('Y-m-d') . '.csv';
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');

// BOM for Excel UTF-8 compatibility
fputs($out, "\xEF\xBB\xBF");

// Header row
fputcsv($out, [
    'ID', 'Name', 'Email', 'Phone', 'Source', 'Status',
    'Pricing Plan', 'Value (₹)', 'Budget', 'Challenge', 'Goal',
    'Comment', 'UTM Source', 'UTM Medium', 'UTM Campaign', 'UTM Content',
    'Landing URL', 'Date'
]);

$stage_labels = [
    'new'           => 'New',
    'engaged'       => 'Engaged',
    'proposal_sent' => 'Proposal Sent',
    'won'           => 'Won',
    'lost'          => 'Lost',
];

foreach ($leads as $l) {
    fputcsv($out, [
        $l['id'],
        $l['name'],
        $l['email'],
        $l['phone'],
        ucfirst($l['source']),
        $stage_labels[$l['status']] ?? $l['status'],
        $l['pricing_plan']  ?? '',
        number_format((float)$l['value'], 2, '.', ''),
        $l['budget']        ?? '',
        $l['challenge']     ?? '',
        $l['goal']          ?? '',
        $l['comment']       ?? '',
        $l['utm_source']    ?? '',
        $l['utm_medium']    ?? '',
        $l['utm_campaign']  ?? '',
        $l['utm_content']   ?? '',
        $l['utm_url']       ?? '',
        date('d M Y H:i', strtotime($l['created_at'])),
    ]);
}

fclose($out);
exit;
