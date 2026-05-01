<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Resend Verification — AITS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  <style>
    :root { --brand-accent:#4f8ef7; --body-bg:#161d27; --card-bg:#1a2233; --card-border:rgba(255,255,255,.07); --text-primary:#e6edf3; --text-muted:#7d8590; }
    body { font-family:'DM Sans',sans-serif; background:var(--body-bg); color:var(--text-primary); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px; }
    .auth-card { background:var(--card-bg); border:1px solid var(--card-border); border-radius:14px; padding:40px 36px; width:100%; max-width:400px; }
    .brand-row { display:flex; align-items:center; gap:10px; margin-bottom:28px; }
    .brand-icon { width:36px; height:36px; background:var(--brand-accent); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:18px; color:#fff; }
    .brand-name { font-size:18px; font-weight:600; }
    .brand-sub  { font-size:11px; color:var(--text-muted); letter-spacing:.8px; text-transform:uppercase; }
    h1 { font-size:20px; font-weight:600; margin-bottom:6px; }
    p.sub { font-size:13px; color:var(--text-muted); margin-bottom:24px; }
    label { display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:var(--text-muted); margin-bottom:6px; }
    .input-wrap { position:relative; margin-bottom:16px; }
    .input-wrap i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:15px; }
    input { width:100%; background:rgba(255,255,255,.05); border:1px solid var(--card-border); border-radius:8px; padding:10px 13px 10px 38px; color:var(--text-primary); font-size:14px; font-family:'DM Sans',sans-serif; outline:none; transition:border-color .2s; }
    input:focus { border-color:var(--brand-accent); background:rgba(79,142,247,.06); }
    input::placeholder { color:var(--text-muted); }
    .btn-submit { width:100%; background:var(--brand-accent); color:#fff; border:none; border-radius:8px; padding:11px; font-size:14px; font-weight:600; font-family:'DM Sans',sans-serif; cursor:pointer; margin-top:8px; transition:background .2s; display:flex; align-items:center; justify-content:center; gap:8px; }
    .btn-submit:hover { background:#3d7de8; }
    .alert-success { background:rgba(46,160,67,.15); border:1px solid rgba(46,160,67,.2); color:#3fb950; border-radius:8px; padding:10px 14px; font-size:13px; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
    .back-link { display:flex; align-items:center; gap:6px; justify-content:center; margin-top:20px; font-size:13px; color:var(--text-muted); text-decoration:none; transition:color .2s; }
    .back-link:hover { color:var(--text-primary); }
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
  <h1>Resend Verification</h1>
  <p class="sub">Enter your email and we'll send a new verification link.</p>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert-success">
      <i class="bi bi-check-circle-fill"></i>
      <?= esc(session()->getFlashdata('success')) ?>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('resend-verification') ?>" method="POST">
    <?= csrf_field() ?>
    <label>Email Address</label>
    <div class="input-wrap">
      <i class="bi bi-envelope"></i>
      <input type="email" name="email" placeholder="your@email.com" required/>
    </div>
    <button type="submit" class="btn-submit">
      <i class="bi bi-send"></i> Resend Link
    </button>
  </form>

  <a href="<?= base_url('login') ?>" class="back-link">
    <i class="bi bi-arrow-left"></i> Back to login
  </a>
</div>
</body>
</html>