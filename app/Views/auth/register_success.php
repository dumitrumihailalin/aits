<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Check Your Email — AITS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  <style>
    :root { --brand-accent:#4f8ef7; --body-bg:#161d27; --card-bg:#1a2233; --card-border:rgba(255,255,255,.07); --text-primary:#e6edf3; --text-muted:#7d8590; }
    body { font-family:'DM Sans',sans-serif; background:var(--body-bg); color:var(--text-primary); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px; }
    .auth-card { background:var(--card-bg); border:1px solid var(--card-border); border-radius:14px; padding:40px 36px; width:100%; max-width:440px; text-align:center; }
    .icon-circle { width:64px; height:64px; background:rgba(79,142,247,.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:var(--brand-accent); }
    h1 { font-size:22px; font-weight:600; margin-bottom:10px; }
    p  { color:var(--text-muted); font-size:14px; line-height:1.7; margin-bottom:24px; }
    .btn-link { display:inline-flex; align-items:center; gap:7px; background:var(--brand-accent); color:#fff; border:none; border-radius:8px; padding:10px 22px; font-size:14px; font-weight:600; font-family:'DM Sans',sans-serif; cursor:pointer; text-decoration:none; transition:background .2s; }
    .btn-link:hover { background:#3d7de8; color:#fff; }
    .resend { margin-top:16px; font-size:13px; color:var(--text-muted); }
    .resend a { color:var(--brand-accent); text-decoration:none; }
  </style>
</head>
<body>
<div class="auth-card">
  <div class="icon-circle"><i class="bi bi-envelope-check"></i></div>
  <h1>Check your email</h1>
  <p>We sent a verification link to your email address.<br>Click the link in the email to activate your account.</p>
  <a href="<?= base_url('login') ?>" class="btn-link">
    <i class="bi bi-arrow-right"></i> Go to Login
  </a>
  <div class="resend">
    Didn't receive it? <a href="<?= base_url('resend-verification') ?>">Resend verification email</a>
  </div>
</div>
</body>
</html>