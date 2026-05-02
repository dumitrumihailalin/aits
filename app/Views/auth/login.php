<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?> — AITS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --brand-accent: #1877f2;
      --body-bg:      #f0f2f5;
      --card-bg:      #1877f2;
      --card-border:  rgba(255,255,255,.15);
      --text-primary: #ffffff;
      --text-muted:   rgba(255,255,255,.7);
      --input-bg:     rgba(255,255,255,.15);
      --input-border: rgba(255,255,255,.2);
      --input-focus:  rgba(255,255,255,.3);
    }
    *, *::before, *::after { box-sizing: border-box; }
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--body-bg);
      color: var(--text-primary);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      padding: 32px 16px;
    }
    .auth-card {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: 14px;
      padding: 40px 36px;
      width: 100%; max-width: 420px;
      box-shadow: 0 8px 40px rgba(24,119,242,.25);
    }
    .brand-row { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; }
    .brand-icon {
      width: 36px; height: 36px;
      background: rgba(255,255,255,.2);
      border-radius: 9px; display: flex; align-items: center;
      justify-content: center; font-size: 18px; color: #fff;
    }
    .brand-name { font-size: 18px; font-weight: 600; color: #fff; }
    .brand-sub  { font-size: 11px; color: rgba(255,255,255,.6); letter-spacing: .8px; text-transform: uppercase; }
    h1 { font-size: 20px; font-weight: 600; margin-bottom: 4px; color: #fff; }
    p.sub { font-size: 13px; color: rgba(255,255,255,.7); margin-bottom: 24px; }
    label {
      display: block; font-size: 11px; font-weight: 600;
      text-transform: uppercase; letter-spacing: .5px;
      color: rgba(255,255,255,.75); margin-bottom: 6px;
    }
    .field { margin-bottom: 16px; }
    .input-wrap { position: relative; }
    .input-wrap > i {
      position: absolute; left: 12px; top: 50%;
      transform: translateY(-50%);
      color: rgba(255,255,255,.6); font-size: 15px;
      pointer-events: none; z-index: 1;
    }
    .toggle-pw {
      position: absolute; right: 10px; top: 50%;
      transform: translateY(-50%);
      color: rgba(255,255,255,.6); font-size: 15px;
      cursor: pointer; background: none; border: none; padding: 4px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 4px; z-index: 2; transition: color .2s;
    }
    .toggle-pw:hover { color: #fff; }
    input {
      width: 100%;
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 8px;
      padding: 10px 40px 10px 38px;
      color: #fff;
      font-size: 14px; font-family: 'DM Sans', sans-serif;
      outline: none; transition: border-color .2s, background .2s;
    }
    input:focus {
      border-color: rgba(255,255,255,.5);
      background: var(--input-focus);
    }
    input::placeholder { color: rgba(255,255,255,.45); }
    .btn-submit {
      width: 100%; background: #fff; color: #1877f2;
      border: none; border-radius: 8px; padding: 12px;
      font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif;
      cursor: pointer; margin-top: 8px; transition: background .2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-submit:hover { background: #e8f0fe; }
    .alert-error {
      background: #dc2626; border: none;
      color: #fff; border-radius: 8px; padding: 10px 14px;
      font-size: 13px; margin-bottom: 16px;
      display: flex; align-items: flex-start; gap: 8px;
    }
    .alert-success {
      background: #1877f2; border: 1px solid rgba(255,255,255,.3);
      color: #000; border-radius: 8px; padding: 10px 14px;
      font-size: 13px; margin-bottom: 16px;
      display: flex; align-items: center; gap: 8px;
    }
    .forgot-link {
      display: block; text-align: right;
      font-size: 12px; color: rgba(255,255,255,.7);
      text-decoration: none; margin-top: 6px; transition: color .2s;
    }
    .forgot-link:hover { color: #fff; }
    .divider {
      display: flex; align-items: center; gap: 12px;
      margin: 20px 0; color: rgba(255,255,255,.5); font-size: 12px;
    }
    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px;
      background: rgba(255,255,255,.15);
    }
    .register-link {
      text-align: center; font-size: 13px;
      color: rgba(255,255,255,.7); margin-top: 4px;
    }
    .register-link a {
      color: #fff; font-weight: 600;
      text-decoration: none;
    }
    .register-link a:hover { text-decoration: underline; }
  </style>
</head>
<body>
<div class="auth-card">
  <a href="<?= base_url() ?>" style="text-decoration:none">
  <div class="brand-row">
    <div class="brand-icon"><i class="bi bi-cpu-fill"></i></div>
    <div>
      <div class="brand-name">AITS</div>
      <div class="brand-sub">Alin IT Services</div>
    </div>
  </div>
  </a>

  <h1><?= lang('Auth.welcome_back') ?></h1>
  <p class="sub"><?= lang('Auth.sign_in_subtitle') ?></p>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert-error">
      <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;margin-top:1px"></i>
      <span><?= session()->getFlashdata('error') ?></span>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert-success">
      <i class="bi bi-check-circle-fill"></i>
      <?= esc(session()->getFlashdata('success')) ?>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert-error">
      <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;margin-top:1px"></i>
      <div>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
          <div><?= esc($error) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('login') ?>" method="POST">
    <?= csrf_field() ?>
    <?php if (!empty($redirect)): ?>
      <input type="hidden" name="redirect" value="<?= esc($redirect) ?>">
    <?php endif; ?>

    <div class="field">
      <label><?= lang('Auth.email_address') ?></label>
      <div class="input-wrap">
        <i class="bi bi-envelope"></i>
        <input type="email" name="email"
               placeholder="contact@company.com"
               value="<?= old('email') ?>" required />
      </div>
    </div>

    <div class="field">
      <label><?= lang('Auth.password') ?></label>
      <div class="input-wrap">
        <i class="bi bi-lock"></i>
        <input type="password" id="password" name="password"
               placeholder="<?= lang('Auth.your_password') ?>" required />
        <button type="button" class="toggle-pw" onclick="toggleVis('password', this)">
          <i class="bi bi-eye"></i>
        </button>
      </div>
      <a href="<?= base_url('forgot-password') ?>" class="forgot-link">
        <?= lang('Auth.forgot_password') ?>
      </a>
    </div>

    <button type="submit" class="btn-submit">
      <i class="bi bi-box-arrow-in-right"></i> <?= lang('Auth.sign_in_btn') ?>
    </button>

  </form>

  <div class="divider"><?= lang('Auth.or') ?></div>

  <div class="register-link">
    <?= lang('Auth.no_account') ?> <a href="<?= base_url('register') ?>"><?= lang('Auth.register_company') ?></a>
  </div>

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
</script>
</body>
</html>
