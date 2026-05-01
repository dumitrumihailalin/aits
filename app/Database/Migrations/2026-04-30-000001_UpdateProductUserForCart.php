<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProductUserForCart extends Migration
{
    public function up()
    {
        // Add 'cart' to the status enum
        $this->db->query("
            ALTER TABLE `product_user`
            MODIFY COLUMN `status`
                ENUM('active','inactive','expired','cancelled','cart')
                CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
                NOT NULL DEFAULT 'active'
        ");
    }

    public function down()
    {
        $this->db->query("
            ALTER TABLE `product_user`
            MODIFY COLUMN `status`
                ENUM('active','inactive','expired','cancelled')
                CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
                NOT NULL DEFAULT 'active'
        ");
    }
}
