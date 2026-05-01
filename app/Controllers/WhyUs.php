<?php

namespace App\Controllers;

class WhyUs extends BaseController
{
    public function index()
    {
        return view('why_us', [
            'title'       => 'Why Choose AITS — Alin IT Services',
            'metaDesc'    => 'Discover why 500+ businesses trust Alin IT Services for cloud, CRM, ERP and IT support. Enterprise security, 24/7 support, fast setup.',
            'activeNav'   => 'why-us',
        ]);
    }
}
