<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LocaleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $supported = config('App')->supportedLocales;
        $locale    = session()->get('locale') ?? config('App')->defaultLocale;

        if (! in_array($locale, $supported, true)) {
            $locale = config('App')->defaultLocale;
        }

        $request->setLocale($locale);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
