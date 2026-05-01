<?= $this->extend('layouts/admin') ?>

<?php $activeNav  = 'support'; ?>
<?php $breadcrumb = 'Ticket #' . $ticket['id']; ?>

<?= $this->section('content') ?>

<style>
  .reply-bubble { border-radius:14px;padding:14px 18px;margin-bottom:16px;max-width:85%; }
  .reply-customer { background:rgba(79,142,247,.12); }
  .reply-admin    { background:var(--card-bg);border:1px solid var(--card-border);margin-left:auto; }
  .reply-meta     { font-size:11px;color:var(--text-muted);margin-top:6px; }
  .badge-open        { background:#dbeafe;color:#1d4ed8; }
  .badge-in_progress { background:#fef3c7;color:#92400e; }
  .badge-resolved    { background:#d1fae5;color:#065f46; }
  .badge-closed      { background:#f3f4f6;color:#374151; }
</style>

<div class="page-header">
  <div style="display:flex;align-items:center;gap:10px;">
    <a href="<?= base_url('admin/support') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="page-title">Ticket #<?= $ticket['id'] ?></div>
    <span class="badge badge-<?= $ticket['status'] ?> rounded-pill px-3">
      <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
    </span>
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

<div class="row g-4">
  <div class="col-lg-8">

    <div class="card border-0 shadow-sm rounded-3 mb-4">
      <div class="card-body p-4">
        <h5 style="font-size:16px;font-weight:700;margin-bottom:8px;"><?= esc($ticket['subject']) ?></h5>
        <p style="font-size:14px;color:var(--text-muted);margin-bottom:0;"><?= nl2br(esc($ticket['description'])) ?></p>
        <?php if ($ticket['image_path']): ?>
          <a href="<?= base_url('uploads/' . $ticket['image_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-3">
            <i class="bi bi-paperclip"></i> View Attachment
          </a>
        <?php endif; ?>
      </div>
    </div>

    <?php if (! empty($replies)): ?>
    <div class="mb-4">
      <h6 style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">Conversation</h6>
      <?php foreach ($replies as $reply): ?>
      <div class="reply-bubble <?= $reply['is_admin_reply'] ? 'reply-admin' : 'reply-customer' ?>">
        <div style="font-size:14px;"><?= nl2br(esc($reply['message'])) ?></div>
        <div class="reply-meta">
          <?= $reply['is_admin_reply'] ? '🛡️ AITS Support (Admin)' : '👤 ' . esc($ticket['user_name']) ?>
          · <?= date('M d, Y H:i', strtotime($reply['created_at'])) ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($ticket['status'] !== 'closed'): ?>
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 style="font-size:14px;font-weight:600;margin-bottom:16px;">Reply to Customer</h6>
        <form action="<?= base_url('admin/support/reply/' . $ticket['id']) ?>" method="POST">
          <?= csrf_field() ?>
          <div class="mb-3">
            <textarea name="message" class="form-control" rows="4" placeholder="Type your reply..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Send Reply</button>
        </form>
      </div>
    </div>
    <?php endif; ?>

  </div>

  <div class="col-lg-4">

    <div class="card border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body p-4">
        <h6 style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">Customer</h6>
        <div style="font-size:14px;font-weight:600;"><?= esc($ticket['user_name']) ?></div>
        <div style="font-size:13px;color:var(--text-muted);"><?= esc($ticket['user_email']) ?></div>
        <?php if ($ticket['company_name']): ?>
          <div style="font-size:13px;color:var(--text-muted);"><?= esc($ticket['company_name']) ?></div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body p-4">
        <h6 style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">Update Status</h6>
        <form action="<?= base_url('admin/support/status/' . $ticket['id']) ?>" method="POST">
          <?= csrf_field() ?>
          <select name="status" class="form-select form-select-sm mb-2">
            <option value="open"        <?= $ticket['status'] === 'open'        ? 'selected' : '' ?>>🔵 Open</option>
            <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>🟡 In Progress</option>
            <option value="resolved"    <?= $ticket['status'] === 'resolved'    ? 'selected' : '' ?>>🟢 Resolved</option>
            <option value="closed"      <?= $ticket['status'] === 'closed'      ? 'selected' : '' ?>>⚫ Closed</option>
          </select>
          <button type="submit" class="btn btn-sm btn-outline-primary w-100">Update Status</button>
        </form>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">Ticket Info</h6>
        <table style="font-size:13px;width:100%;">
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Priority</td><td class="text-end" style="font-weight:600;"><?= ucfirst($ticket['priority']) ?></td></tr>
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Opened</td><td class="text-end"><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td></tr>
          <tr><td style="color:var(--text-muted);">Last update</td><td class="text-end"><?= date('M d, Y', strtotime($ticket['updated_at'])) ?></td></tr>
        </table>
      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>
