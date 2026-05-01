<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name'              => 'Business Starter',
                'slug'              => 'business-starter',
                'short_description' => 'Everything you need to get your business online fast.',
                'description'       => 'Full setup including domain, email hosting, SSL and basic website.',
                'price'             => 49.99,
                'price_label'       => 'month',
                'icon'              => 'bi-rocket-takeoff-fill',
                'color'             => '#1877f2',
                'is_active'         => 1,
                'is_featured'       => 1,
                'sort_order'        => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'CRM Integration',
                'slug'              => 'crm-integration',
                'short_description' => 'Manage your customers, leads and sales in one place.',
                'description'       => 'Full CRM setup with customer management, lead tracking and reports.',
                'price'             => 89.99,
                'price_label'       => 'month',
                'icon'              => 'bi-graph-up-arrow',
                'color'             => '#0f9d58',
                'is_active'         => 1,
                'is_featured'       => 1,
                'sort_order'        => 2,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'Security Shield',
                'slug'              => 'security-shield',
                'short_description' => 'Keep your business protected around the clock.',
                'description'       => 'Firewall setup, daily backups and 24/7 monitoring.',
                'price'             => 39.99,
                'price_label'       => 'month',
                'icon'              => 'bi-shield-lock-fill',
                'color'             => '#e53935',
                'is_active'         => 1,
                'is_featured'       => 0,
                'sort_order'        => 3,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'Cloud Hosting Pro',
                'slug'              => 'cloud-hosting-pro',
                'short_description' => 'Fast, reliable hosting for growing businesses.',
                'description'       => 'Managed cloud hosting with auto-scaling and daily backups.',
                'price'             => 69.99,
                'price_label'       => 'month',
                'icon'              => 'bi-cloud-fill',
                'color'             => '#7c4dff',
                'is_active'         => 1,
                'is_featured'       => 0,
                'sort_order'        => 4,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'IT Support Plan',
                'slug'              => 'it-support-plan',
                'short_description' => 'Dedicated IT support whenever you need it.',
                'description'       => 'Priority support tickets, remote assistance and monthly reports.',
                'price'             => 29.99,
                'price_label'       => 'month',
                'icon'              => 'bi-headset',
                'color'             => '#fb8c00',
                'is_active'         => 1,
                'is_featured'       => 0,
                'sort_order'        => 5,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'Email Marketing',
                'slug'              => 'email-marketing',
                'short_description' => 'Reach your customers with professional email campaigns.',
                'description'       => 'Email campaign setup, templates, analytics and list management.',
                'price'             => 34.99,
                'price_label'       => 'month',
                'icon'              => 'bi-envelope-paper-fill',
                'color'             => '#00acc1',
                'is_active'         => 1,
                'is_featured'       => 0,
                'sort_order'        => 6,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($products as $product) {
            $exists = $this->db->table('products')
                               ->where('slug', $product['slug'])
                               ->get()->getRow();
            if (! $exists) {
                $this->db->table('products')->insert($product);
                echo "Product inserted: {$product['name']}\n";
            } else {
                echo "Product already exists: {$product['name']}, skipping.\n";
            }
        }
    }
}