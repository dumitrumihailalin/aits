<?= $this->extend('layouts/admin') ?>

<?php $activeNav = 'support'; ?>

<?= $this->section('content') ?>

<style>
  .status-tabs { display:flex;gap:4px;background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:6px;margin-bottom:24px;flex-wrap:wrap; }
  .status-tab { padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;gap:6px;transition:all .15s; }
  .status-tab:hover { background:rgba(255,255,255,.06);color:var(--text-primary); }
  .status-tab.active { background:var(--brand-accent);color:#fff; }
  .status-tab .count { background:rgba(0,0,0,.15);border-radius:20px;padding:1px 8px;font-size:11px; }
  .status-tab.active .count { background:rgba(255,255,255,.25); }
  .badge-open        { background:#dbeafe;color:#1d4ed8; }
  .badge-in_progress { background:#fef3c7;color:#92400e; }
  .badge-resolved    { background:#d1fae5;color:#065f46; }
  .badge-closed      { background:#f3f4f6;color:#374151; }
  .badge-low         { background:#f3f4f6;color:#374151; }
  .badge-medium      { background:#dbeafe;color:#1d4ed8; }
  .badge-high        { background:#fef3c7;color:#92400e; }
  .badge-urgent      { background:#fee2e2;color:#991b1b; }
  .unread-dot { width:8px;height:8px;background:var(--brand-accent);border-radius:50%;display:inline-block;margin-right:6px; }
</style>

<div class="page-header">
  <div>
    <div class="page-title">Support Tickets</div>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 mb-4"
       style="background:#1877f2;border:none;border-radius:10px;color:#fff;font-size:14px;padding:14px 20px;">
    <i class="bi bi-check-circle-fill" style="font-size:18px;"></i>
    <span><?= session()->getFlashdata('success') ?></span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="filter:brightness(0) invert(1);"></button>
  </div>
<?php endif; ?>

<div class="status-tabs">
  <a href="<?= base_url('admin/support') ?>"                          class="status-tab <?= $status === 'all'         ? 'active' : '' ?>">All <span class="count"><?= $counts['all'] ?></span></a>
  <a href="<?= base_url('admin/support?status=open') ?>"              class="status-tab <?= $status === 'open'        ? 'active' : '' ?>">🔵 Open <span class="count"><?= $counts['open'] ?></span></a>
  <a href="<?= base_url('admin/support?status=in_progress') ?>"       class="status-tab <?= $status === 'in_progress' ? 'active' : '' ?>">🟡 In Progress <span class="count"><?= $counts['in_progress'] ?></span></a>
  <a href="<?= base_url('admin/support?status=resolved') ?>"          class="status-tab <?= $status === 'resolved'    ? 'active' : '' ?>">🟢 Resolved <span class="count"><?= $counts['resolved'] ?></span></a>
  <a href="<?= base_url('admin/support?status=closed') ?>"            class="status-tab <?= $status === 'closed'      ? 'active' : '' ?>">⚫ Closed <span class="count"><?= $counts['closed'] ?></span></a>
</div>

<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-0">
    <?php if (empty($tickets)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-headset" style="font-size:40px;"></i>
        <p class="mt-3">No tickets found for this status.</p>
      </div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead style="background:#f8faff;font-size:12px;color:#6b7280;">
        <tr>
          <th class="ps-4 py-3">#</th>
          <th>Customer</th>
          <th>Subject</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Created</th>
          <th></th>
        </tr>
      </thead>
      <tbody style="font-size:14px;">
        <?php foreach ($tickets as $ticket): ?>
        <tr>
          <td class="ps-4 text-muted">#<?= $ticket['id'] ?></td>
          <td>
            <?php if (! $ticket['is_read']): ?><span class="unread-dot"></span><?php endif; ?>
            <div style="font-weight:600;display:inline;"><?= esc($ticket['user_name']) ?></div>
            <div style="font-size:12px;color:var(--text-muted);"><?= esc($ticket['company_name'] ?? $ticket['user_email']) ?></div>
          </td>
          <td>
            <div style="font-weight:500;"><?= esc($ticket['subject']) ?></div>
            <div style="font-size:12px;color:var(--text-muted);"><?= esc(substr($ticket['description'], 0, 50)) ?>...</div>
          </td>
          <td><span class="badge badge-<?= $ticket['priority'] ?> rounded-pill px-3"><?= ucfirst($ticket['priority']) ?></span></td>
          <td><span class="badge badge-<?= $ticket['status'] ?> rounded-pill px-3"><?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?></span></td>
          <td style="font-size:13px;color:var(--text-muted);"><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td>
          <td><a href="<?= base_url('admin/support/' . $ticket['id']) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>
