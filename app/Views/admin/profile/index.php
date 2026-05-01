<?= $this->extend('layouts/admin') ?>

<?php $activeNav = 'profile'; ?>

<?= $this->section('content') ?>

<style>
.pw-wrap { position: relative; }
.pw-wrap .toggle-pw {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  background: none; border: none; padding: 4px; cursor: pointer;
  color: var(--text-muted); font-size: 15px; display: flex; align-items: center;
  border-radius: 4px; transition: color .2s;
}
.pw-wrap .toggle-pw:hover { color: var(--text-primary); }
.pw-wrap .form-control { padding-right: 38px; }
</style>

<div class="page-header">
  <div>
    <div class="page-title">My Profile</div>
    <div class="page-subtitle">Manage your account details and password</div>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach (session()->getFlashdata('errors') as $err): ?>
        <li><?= esc($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form action="<?= base_url('admin/profile/update') ?>" method="post">
  <?= csrf_field() ?>

  <div class="aits-card" style="max-width:600px">
    <div class="aits-card-header">
      <span class="aits-card-title">Account Information</span>
    </div>

    <div style="padding:24px;display:flex;flex-direction:column;gap:16px">

      <div>
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control"
               value="<?= esc(old('name', $user['name'] ?? '')) ?>" required>
      </div>

      <div>
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
               value="<?= esc(old('email', $user['email'] ?? '')) ?>" required>
      </div>

      <hr>

      <p style="font-size:13px;color:var(--text-muted);margin:0">
        Leave password fields blank to keep your current password.
      </p>

      <div>
        <label class="form-label">Current Password</label>
        <div class="pw-wrap">
          <input type="password" id="current_password" name="current_password" class="form-control" autocomplete="current-password">
          <button type="button" class="toggle-pw" onclick="toggleVis('current_password', this)"><i class="bi bi-eye"></i></button>
        </div>
      </div>

      <div>
        <label class="form-label">New Password</label>
        <div class="pw-wrap">
          <input type="password" id="new_password" name="new_password" class="form-control" autocomplete="new-password">
          <button type="button" class="toggle-pw" onclick="toggleVis('new_password', this)"><i class="bi bi-eye"></i></button>
        </div>
      </div>

      <div>
        <label class="form-label">Confirm New Password</label>
        <div class="pw-wrap">
          <input type="password" id="new_password_confirm" name="new_password_confirm" class="form-control" autocomplete="new-password">
          <button type="button" class="toggle-pw" onclick="toggleVis('new_password_confirm', this)"><i class="bi bi-eye"></i></button>
        </div>
      </div>

      <div style="padding-top:8px">
        <button type="submit" class="btn-aits btn-aits-primary">
          <i class="bi bi-check-lg"></i> Save Changes
        </button>
      </div>

    </div>
  </div>

</form>

<script>
function toggleVis(inputId, btn) {
  const input = document.getElementById(inputId);
  const isPassword = input.type === 'password';
  input.type = isPassword ? 'text' : 'password';
  btn.querySelector('i').className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
  input.focus();
}
</script>

<?= $this->endSection() ?>
