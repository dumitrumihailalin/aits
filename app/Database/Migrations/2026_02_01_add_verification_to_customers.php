<?php

// app/Database/Migrations/XXXX_add_verification_to_customers.php
$this->forge->addColumn('customers', [
    'verification_token' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
    'is_verified'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
]);