<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login — AITS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --brand-accent: #4f8ef7;
      --body-bg:      #161d27;
      --card-bg:      #1a2233;
      --card-border:  rgba(255,255,255,.07);
      --text-primary: #e6edf3;
      --text-muted:   #7d8590;
    }
    *, *::before, *::after { box-sizing: border-box; }
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--body-bg);
      color: var(--text-primary);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .login-card {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: 14px;
      padding: 40px 36px;
      width: 100%;
      max-width: 400px;
    }
    .brand-row {
      display: flex; align-items: center; gap: 10px;
      margin-bottom: 32px;
    }
    .brand-icon {
      width: 36px; height: 36px;
      background: var(--brand-accent);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; color: #fff;
    }
    .brand-name { font-size: 18px; font-weight: 600; }
    .brand-sub  { font-size: 11px; color: var(--text-muted); letter-spacing: .8px; text-transform: uppercase; }
    h1 { font-size: 20px; font-weight: 600; margin-bottom: 6px; }
    p  { font-size: 13px; color: var(--text-muted); margin-bottom: 28px; }
    label {
      display: block;
      font-size: 11px; font-weight: 600;
      text-transform: uppercase; letter-spacing: .5px;
      color: var(--text-muted);
      margin-bottom: 6px;
    }
    .input-wrap {
      position: relative;
      margin-bottom: 18px;
    }
    .input-wrap i {
      position: absolute; left: 12px; top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted); font-size: 15px;
    }
    input[type="email"],
    input[type="password"] {
      width: 100%;
      background: rgba(255,255,255,.05);
      border: 1px solid var(--card-border);
      border-radius: 8px;
      padding: 10px 38px 10px 38px;
      color: var(--text-primary);
      font-size: 14px;
      font-family: 'DM Sans', sans-serif;
      outline: none;
      transition: border-color .2s, background .2s;
    }
    .toggle-pw {
      position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
      color: var(--text-muted); font-size: 15px; cursor: pointer;
      background: none; border: none; padding: 4px;
      display: flex; align-items: center; border-radius: 4px;
      transition: color .2s;
    }
    .toggle-pw:hover { color: var(--text-primary); }
    input:focus {
      border-color: var(--brand-accent);
      background: rgba(79,142,247,.06);
    }
    input::placeholder { color: var(--text-muted); }
    .btn-login {
      width: 100%;
      background: var(--brand-accent);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 11px;
      font-size: 14px;
      font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      margin-top: 8px;
      transition: background .2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login:hover { background: #3d7de8; }
    .alert-error {
      background: #dc2626;
      border: none;
      color: #fff;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13px;
      margin-bottom: 20px;
      display: flex; align-items: center; gap: 8px;
    }

    .back-link {  
      display: flex; align-items: center; gap: 6px; justify-content: center;
      margin-top: 20px; font-size: 13px; color: var(--text-muted);
      text-decoration: none; transition: color .2s;
    }
    .back-link:hover { color: var(--text-primary); }
  </style>
</head>
<body>

<div class="login-card">
  <div class="brand-row">
    <div class="brand-icon"><i class="bi bi-cpu-fill"></i></div>
    <div>
      <div class="brand-name">AITS</div>
      <div class="brand-sub">Alin IT Services</div>
    </div>
  </div>

  <h1>Admin Login</h1>
  <p>Sign in to access the admin panel.</p>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert-error">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <?= esc(session()->getFlashdata('error')) ?>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('admin/login') ?>" method="POST">
    <?= csrf_field() ?>

    <label for="email">Email address</label>
    <div class="input-wrap">
      <i class="bi bi-envelope"></i>
      <input type="email" id="email" name="email"
             placeholder="admin@aits.com"
             value="<?= old('email') ?>" required />
    </div>

    <label for="password">Password</label>
    <div class="input-wrap">
      <i class="bi bi-lock"></i>
      <input type="password" id="password" name="password"
             placeholder="••••••••" required />
      <button type="button" class="toggle-pw" onclick="toggleVis('password', this)">
        <i class="bi bi-eye"></i>
      </button>
    </div>

    <button type="submit" class="btn-login">
      <i class="bi bi-shield-lock-fill"></i>
      Sign in as Admin
    </button>
  </form>
    <a href="<?= base_url('admin/forgot-password') ?>" class="back-link">
    Forgot your password?
  </a>
</div>

<script>
function toggleVis(inputId, btn) {
  const input = document.getElementById(inputId);
  const isPassword = input.type === 'password';
  input.type = isPassword ? 'text' : 'password';
  btn.querySelector('i').className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
  input.focus();
}
</script>
</body>
</html>