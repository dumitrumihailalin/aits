<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?> — AITS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root { --brand-accent: #1877f2; --body-bg: #f0f2f5; }
    *, *::before, *::after { box-sizing: border-box; }
    body {
      font-family: 'DM Sans', sans-serif; background: var(--body-bg);
      min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 32px 16px;
    }
    .auth-card {
      background: #1877f2; border-radius: 14px; padding: 40px 36px;
      width: 100%; max-width: 420px; box-shadow: 0 8px 40px rgba(24,119,242,.25);
    }
    .brand-row { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; }
    .brand-icon { width: 36px; height: 36px; background: rgba(255,255,255,.2); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #fff; }
    .brand-name { font-size: 18px; font-weight: 600; color: #fff; }
    .brand-sub  { font-size: 11px; color: rgba(255,255,255,.6); letter-spacing: .8px; text-transform: uppercase; }
    h1 { font-size: 20px; font-weight: 600; color: #fff; margin-bottom: 4px; }
    p.sub { font-size: 13px; color: rgba(255,255,255,.7); margin-bottom: 24px; }
    label { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: rgba(255,255,255,.75); margin-bottom: 6px; }
    .field { margin-bottom: 16px; }
    .input-wrap { position: relative; }
    .input-wrap > i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.6); font-size: 15px; pointer-events: none; }
    .toggle-pw { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.6); font-size: 15px; cursor: pointer; background: none; border: none; padding: 4px; display: flex; align-items: center; border-radius: 4px; z-index: 2; transition: color .2s; }
    .toggle-pw:hover { color: #fff; }
    input { width: 100%; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2); border-radius: 8px; padding: 10px 40px 10px 38px; color: #fff; font-size: 14px; font-family: 'DM Sans', sans-serif; outline: none; transition: border-color .2s; }
    input:focus { border-color: rgba(255,255,255,.5); background: rgba(255,255,255,.25); }
    input::placeholder { color: rgba(255,255,255,.45); }
    .btn-submit { width: 100%; background: #fff; color: #1877f2; border: none; border-radius: 8px; padding: 12px; font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer; margin-top: 8px; transition: background .2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-submit:hover { background: #e8f0fe; }
    .alert-error { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color: #fff; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .strength-bar { height: 3px; border-radius: 3px; margin-top: 6px; background: rgba(255,255,255,.15); overflow: hidden; }
    .strength-fill { height: 100%; width: 0; border-radius: 3px; transition: width .3s, background .3s; }
    .strength-label { font-size: 11px; color: rgba(255,255,255,.6); margin-top: 4px; min-height: 16px; }
  </style>
</head>
<body>
<div class="auth-card">
  <div class="brand-row">
    <div class="brand-icon"><i class="bi bi-cpu-fill"></i></div>
    <div>
      <div class="brand-name">AITS</div>
      <div class="brand-sub">Alin IT Services</div>
    </div>
  </div>

  <h1>Reset Password</h1>
  <p class="sub">Enter your new password below.</p>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert-error">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <?= esc(session()->getFlashdata('error')) ?>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('reset-password/' . esc($token)) ?>" method="POST">
    <?= csrf_field() ?>

    <div class="field">
      <label>New Password</label>
      <div class="input-wrap">
        <i class="bi bi-lock"></i>
        <input type="password" id="password" name="password" placeholder="Min. 8 characters" required />
        <button type="button" class="toggle-pw" onclick="toggleVis('password', this)">
          <i class="bi bi-eye"></i>
        </button>
      </div>
      <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
      <div class="strength-label" id="strengthLabel"></div>
    </div>

    <div class="field">
      <label>Confirm Password</label>
      <div class="input-wrap">
        <i class="bi bi-lock-fill"></i>
        <input type="password" id="password_confirm" name="password_confirm" placeholder="Repeat password" required />
        <button type="button" class="toggle-pw" onclick="toggleVis('password_confirm', this)">
          <i class="bi bi-eye"></i>
        </button>
      </div>
    </div>

    <button type="submit" class="btn-submit">
      <i class="bi bi-check-lg"></i> Update Password
    </button>
  </form>
</div>

<script>
function toggleVis(inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input) return;
  const isPassword = input.type === 'password';
  input.type = isPassword ? 'text' : 'password';
  btn.querySelector('i').className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
  input.focus();
}
document.getElementById('password').addEventListener('input', function () {
  const val = this.value;
  const fill = document.getElementById('strengthFill');
  const label = document.getElementById('strengthLabel');
  let score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const levels = [
    { w: '25%', bg: '#da3633', txt: 'Weak' },
    { w: '50%', bg: '#d29922', txt: 'Fair' },
    { w: '75%', bg: '#fff',    txt: 'Good' },
    { w: '100%',bg: '#2ea043', txt: 'Strong' },
  ];
  const l = val.length ? levels[Math.max(0, score - 1)] : null;
  fill.style.width      = l ? l.w  : '0';
  fill.style.background = l ? l.bg : 'transparent';
  label.textContent     = l ? l.txt : '';
});
</script>
</body>
</html>