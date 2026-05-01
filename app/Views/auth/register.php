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
      width: 100%; max-width: 560px;
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
    .section-label {
      font-size: 10px; font-weight: 600; letter-spacing: 1px;
      text-transform: uppercase; color: rgba(255,255,255,.6);
      margin: 20px 0 14px; padding-bottom: 8px;
      border-bottom: 1px solid rgba(255,255,255,.15);
    }
    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
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
      color: rgba(255,255,255,.6); font-size: 15px; pointer-events: none;
      z-index: 1;
    }
    .toggle-pw {
      position: absolute; right: 10px; top: 50%;
      transform: translateY(-50%);
      color: rgba(255,255,255,.6); font-size: 15px;
      cursor: pointer; background: none; border: none; padding: 4px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 4px; z-index: 2;
      transition: color .2s;
    }
    .toggle-pw:hover { color: #fff; }
    input, select {
      width: 100%;
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 8px;
      padding: 10px 40px 10px 38px;
      color: #fff;
      font-size: 14px; font-family: 'DM Sans', sans-serif;
      outline: none; transition: border-color .2s, background .2s;
    }
    select { padding-left: 38px; padding-right: 13px; cursor: pointer; }
    select option { background: #1877f2; color: #fff; }
    input:focus, select:focus {
      border-color: rgba(255,255,255,.5);
      background: var(--input-focus);
    }
    input::placeholder { color: rgba(255,255,255,.45); }
    .btn-submit {
      width: 100%;
      background: #fff;
      color: #1877f2;
      border: none; border-radius: 8px; padding: 12px;
      font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif;
      cursor: pointer; margin-top: 8px; transition: background .2s, color .2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-submit:hover { background: #e8f0fe; }
    .alert-error {
      background: #dc2626; border: none;
      color: #fff; border-radius: 8px; padding: 10px 14px;
      font-size: 13px; margin-bottom: 16px;
    }
    .alert-error strong { color: #fff; }
    .alert-error ul { margin: 6px 0 0 16px; padding: 0; }
    .login-link { text-align: center; font-size: 13px; color: rgba(255,255,255,.7); margin-top: 20px; }
    .login-link a { color: #fff; font-weight: 600; text-decoration: none; }
    .login-link a:hover { text-decoration: underline; }
    .strength-bar {
      height: 3px; border-radius: 3px; margin-top: 6px;
      background: rgba(255,255,255,.15); overflow: hidden;
    }
    .strength-fill { height: 100%; width: 0; border-radius: 3px; transition: width .3s, background .3s; }
    .strength-label { font-size: 11px; color: rgba(255,255,255,.6); margin-top: 4px; min-height: 16px; }
    @media (max-width: 480px) {
      .row-2 { grid-template-columns: 1fr; }
      .auth-card { padding: 28px 20px; }
    }
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

  <h1>Create Account</h1>
  <p class="sub">Register your company to access our products and services.</p>

  <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert-error">
      <strong>Please fix the following:</strong>
      <ul>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('register') ?>" method="POST">
    <?= csrf_field() ?>

    <!-- Company Info -->
    <div class="section-label">Company Information</div>

    <div class="field">
      <label>Company Name <span style="color:#f85149">*</span></label>
      <div class="input-wrap">
        <i class="bi bi-building"></i>
        <input type="text" name="company_name"
               placeholder="Acme Corporation"
               value="<?= old('company_name') ?>" required />
      </div>
    </div>

    <div class="row-2">
      <div class="field">
        <label>Country <span style="color:#f85149">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-globe"></i>
          <select name="country" required>
            <option value="">Select country...</option>
            <option value="GB" <?= old('country') === 'GB' ? 'selected' : '' ?>>United Kingdom</option>
            <option value="US" <?= old('country') === 'US' ? 'selected' : '' ?>>United States</option>
            <option value="RO" <?= old('country') === 'RO' ? 'selected' : '' ?>>Romania</option>
            <option value="DE" <?= old('country') === 'DE' ? 'selected' : '' ?>>Germany</option>
            <option value="FR" <?= old('country') === 'FR' ? 'selected' : '' ?>>France</option>
            <option value="IT" <?= old('country') === 'IT' ? 'selected' : '' ?>>Italy</option>
            <option value="ES" <?= old('country') === 'ES' ? 'selected' : '' ?>>Spain</option>
            <option value="PT" <?= old('country') === 'PT' ? 'selected' : '' ?>>Portugal</option>
            <option value="NL" <?= old('country') === 'NL' ? 'selected' : '' ?>>Netherlands</option>
            <option value="BE" <?= old('country') === 'BE' ? 'selected' : '' ?>>Belgium</option>
            <option value="PL" <?= old('country') === 'PL' ? 'selected' : '' ?>>Poland</option>
            <option value="HU" <?= old('country') === 'HU' ? 'selected' : '' ?>>Hungary</option>
            <option value="BG" <?= old('country') === 'BG' ? 'selected' : '' ?>>Bulgaria</option>
            <option value="GR" <?= old('country') === 'GR' ? 'selected' : '' ?>>Greece</option>
            <option value="TR" <?= old('country') === 'TR' ? 'selected' : '' ?>>Turkey</option>
            <option value="CA" <?= old('country') === 'CA' ? 'selected' : '' ?>>Canada</option>
            <option value="MX" <?= old('country') === 'MX' ? 'selected' : '' ?>>Mexico</option>
            <option value="BR" <?= old('country') === 'BR' ? 'selected' : '' ?>>Brazil</option>
            <option value="AU" <?= old('country') === 'AU' ? 'selected' : '' ?>>Australia</option>
            <option value="JP" <?= old('country') === 'JP' ? 'selected' : '' ?>>Japan</option>
            <option value="TH" <?= old('country') === 'TH' ? 'selected' : '' ?>>Thailand</option>
            <option value="OTHER" <?= old('country') === 'OTHER' ? 'selected' : '' ?>>Other</option>
          </select>
        </div>
      </div>
      <div class="field">
        <label>City <span style="color:#f85149">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-geo-alt"></i>
          <input type="text" name="city"
                 placeholder="London"
                 value="<?= old('city') ?>" required />
        </div>
      </div>
    </div>

    <div class="field">
      <label>Address <span style="color:#f85149">*</span></label>
      <div class="input-wrap">
        <i class="bi bi-signpost"></i>
        <input type="text" name="address"
               placeholder="Street name, number"
               value="<?= old('address') ?>" required />
      </div>
    </div>

    <!-- Contact Person -->
    <div class="section-label">Contact Person</div>

    <div class="row-2">
      <div class="field">
        <label>Full Name <span style="color:#f85149">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-person"></i>
          <input type="text" name="name"
                 placeholder="Your full name"
                 value="<?= old('name') ?>" required />
        </div>
      </div>
      <div class="field">
        <label>Phone Number <span style="color:#f85149">*</span></label>
        <div class="input-wrap">
          <i class="bi bi-telephone"></i>
          <input type="tel" name="phone"
                 placeholder="+40 700 000 000"
                 value="<?= old('phone') ?>" required />
        </div>
      </div>
    </div>

    <div class="field">
      <label>Email Address <span style="color:#f85149">*</span></label>
      <div class="input-wrap">
        <i class="bi bi-envelope"></i>
        <input type="email" name="email"
               placeholder="contact@company.com"
               value="<?= old('email') ?>" required />
      </div>
    </div>

    <!-- Security -->
    <div class="section-label">Security</div>

    <div class="field">
      <label>Password <span style="color:#f85149">*</span></label>
      <div class="input-wrap">
        <i class="bi bi-lock"></i>
        <input type="password" id="password" name="password"
               placeholder="Min. 8 characters" required />
        <button type="button" class="toggle-pw" onclick="toggleVis('password', this)">
          <i class="bi bi-eye"></i>
        </button>
      </div>
      <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
      <div class="strength-label" id="strengthLabel"></div>
    </div>

    <div class="field">
      <label>Confirm Password <span style="color:#f85149">*</span></label>
      <div class="input-wrap">
        <i class="bi bi-lock-fill"></i>
        <input type="password" id="password_confirm" name="password_confirm"
               placeholder="Repeat your password" required />
        <button type="button" class="toggle-pw" onclick="toggleVis('password_confirm', this)">
          <i class="bi bi-eye"></i>
        </button>
      </div>
    </div>

    <button type="submit" class="btn-submit">
      <i class="bi bi-building-check"></i> Register Company Account
    </button>

  </form>

  <div class="login-link">
    Already have an account? <a href="<?= base_url('login') ?>">Sign in</a>
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

document.getElementById('password').addEventListener('input', function () {
  const val   = this.value;
  const fill  = document.getElementById('strengthFill');
  const label = document.getElementById('strengthLabel');
  if (!fill || !label) return;
  let score = 0;
  if (val.length >= 8)          score++;
  if (/[A-Z]/.test(val))        score++;
  if (/[0-9]/.test(val))        score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const levels = [
    { w: '25%', bg: '#da3633', txt: 'Weak' },
    { w: '50%', bg: '#d29922', txt: 'Fair' },
    { w: '75%', bg: '#4f8ef7', txt: 'Good' },
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