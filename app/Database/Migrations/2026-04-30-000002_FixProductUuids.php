<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixProductUuids extends Migration
{
    public function up()
    {
        // Assign a proper UUID to any product that has an empty or invalid id
        $products = $this->db->table('products')
            ->select('id')
            ->get()
            ->getResultArray();

        foreach ($products as $product) {
            if (strlen($product['id']) !== 36) {
                $uuid = sprintf(
                    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    mt_rand(0, 0xffff),
                    mt_rand(0, 0x0fff) | 0x4000,
                    mt_rand(0, 0x3fff) | 0x8000,
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                );

                // Must drop FK temporarily to update the PK
                $this->db->query('SET FOREIGN_KEY_CHECKS=0');
                $this->db->table('products')
                    ->where('id', $product['id'])
                    ->update(['id' => $uuid]);
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            }
        }
    }

    public function down()
    {
        // Not reversible — UUIDs cannot be reverted to empty strings safely
    }
}
