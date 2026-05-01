<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSortOrderToProducts extends Migration
{
    public function up()
    {
        $missing = [];

        if (! $this->db->fieldExists('short_description', 'products')) {
            $missing['short_description'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'description',
            ];
        }

        if (! $this->db->fieldExists('price', 'products')) {
            $missing['price'] = [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ];
        }

        if (! $this->db->fieldExists('price_label', 'products')) {
            $missing['price_label'] = [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'month',
                'null'       => true,
            ];
        }

        if (! $this->db->fieldExists('icon', 'products')) {
            $missing['icon'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => 'bi-box-seam',
            ];
        }

        if (! $this->db->fieldExists('color', 'products')) {
            $missing['color'] = [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => '#1877f2',
            ];
        }

        if (! $this->db->fieldExists('youtube_url', 'products')) {
            $missing['youtube_url'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ];
        }

        if (! $this->db->fieldExists('is_featured', 'products')) {
            $missing['is_featured'] = [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ];
        }

        if (! $this->db->fieldExists('sort_order', 'products')) {
            $missing['sort_order'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ];
        }

        if (! empty($missing)) {
            $this->forge->addColumn('products', $missing);
        }
    }

    public function down()
    {
        $columns = ['short_description', 'price', 'price_label', 'icon', 'color', 'youtube_url', 'is_featured', 'sort_order'];

        foreach ($columns as $column) {
            if ($this->db->fieldExists($column, 'products')) {
                $this->forge->dropColumn('products', $column);
            }
        }
    }
}

