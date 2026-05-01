@echo off
setlocal EnableDelayedExpansion
title AITS — Production Deploy

echo.
echo  ==========================================
echo   Alin IT Services — Production Optimizer
echo  ==========================================
echo.

:: ── 1. Composer ───────────────────────────────────────
echo [1/6] Composer autoload optimize...
call composer dump-autoload --optimize --no-dev
echo.

:: ── 2. Clear CI cache ──────────────────────────────────
echo [2/6] Clear application cache...
php spark cache:clear
echo.

:: ── 3. Clear debugbar ─────────────────────────────────
echo [3/6] Clear debugbar files...
php spark debugbar:clear
echo.

:: ── 4. Clear logs ─────────────────────────────────────
echo [4/6] Clear logs...
php spark logs:clear
echo.

:: ── 5. Optimize (cache config + routes + services) ────
echo [5/6] Spark optimize (config + routes + services cache)...
php spark optimize
echo.

:: ── 6. Verify routes ──────────────────────────────────
echo [6/6] Route list:
php spark routes
echo.

echo  ==========================================
echo   Done! Set CI_ENVIRONMENT=production
echo   in your .env file if not already set.
echo  ==========================================
echo.
pause
