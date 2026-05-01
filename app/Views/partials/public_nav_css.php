/* ── Shared public navbar CSS — include in each public page's <style> ── */

/* ── Navbar ─────────────────────────────────────── */
.site-nav {
  background: var(--white);
  border-bottom: 1px solid var(--border);
  padding: 0 32px;
  height: 64px;
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 200;
}
.nav-brand {
  display: flex; align-items: center; gap: 10px;
  text-decoration: none; flex-shrink: 0;
}
.brand-icon {
  width: 36px; height: 36px; background: var(--brand);
  border-radius: 9px; display: flex; align-items: center;
  justify-content: center; font-size: 18px; color: #fff;
}
.brand-name { font-size: 18px; font-weight: 700; color: var(--text); }
.brand-sub  { font-size: 10px; color: var(--muted); letter-spacing: .8px; text-transform: uppercase; }

.nav-links {
  display: flex; align-items: center; gap: 4px;
}
.nav-auth {
  display: flex; align-items: center; gap: 8px; margin-left: 8px;
}
.nav-link-item {
  padding: 8px 14px; border-radius: 8px;
  font-size: 14px; font-weight: 500; color: var(--muted);
  text-decoration: none; transition: all .2s;
}
.nav-link-item:hover { background: var(--body-bg); color: var(--text); }
.nav-link-item.nav-active { color: var(--brand); font-weight: 600; }
.btn-nav-login {
  padding: 8px 18px; border-radius: 8px; font-size: 14px; font-weight: 600;
  border: 1px solid var(--border); color: var(--text);
  text-decoration: none; background: var(--white); white-space: nowrap;
  transition: all .2s;
}
.btn-nav-login:hover { border-color: var(--brand); color: var(--brand); }
.btn-nav-register {
  padding: 8px 18px; border-radius: 8px; font-size: 14px; font-weight: 600;
  background: var(--brand); color: #fff;
  text-decoration: none; white-space: nowrap; transition: background .2s;
}
.btn-nav-register:hover { background: var(--brand-dark); color: #fff; }

/* ── Hamburger ───────────────────────────────────── */
.hamburger {
  display: none; flex-direction: column; justify-content: center;
  gap: 5px; cursor: pointer; padding: 6px;
  border: none; background: none; border-radius: 6px;
}
.hamburger span {
  display: block; width: 22px; height: 2px;
  background: var(--text); border-radius: 2px;
  transition: transform .3s, opacity .3s;
}
.hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger.open span:nth-child(2) { opacity: 0; }
.hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ── Footer ──────────────────────────────────────── */
.site-footer {
  background: var(--white);
  border-top: 1px solid var(--border);
  padding: 24px 32px;
  text-align: center;
}
.site-footer p { font-size: 13px; color: var(--muted); margin: 0; }
.site-footer a  { color: var(--brand); text-decoration: none; }
.site-footer a:hover { text-decoration: underline; }

/* ── Mobile ──────────────────────────────────────── */
@media (max-width: 768px) {
  .site-nav { padding: 0 16px; height: 58px; }
  .hamburger { display: flex; }
  .nav-links {
    display: none;
    flex-direction: column;
    align-items: stretch;
    position: absolute;
    top: 58px; left: 0; right: 0;
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 12px 16px 16px;
    gap: 2px;
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
  }
  .nav-links.open { display: flex; }
  .nav-auth {
    flex-direction: column; align-items: stretch;
    margin-left: 0; margin-top: 8px;
    padding-top: 10px; border-top: 1px solid var(--border);
    gap: 8px;
  }
  .nav-link-item { padding: 10px 14px; }
  .btn-nav-login,
  .btn-nav-register { text-align: center; padding: 11px 18px; }
}
