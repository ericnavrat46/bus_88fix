<?php
echo min("50000.00", 450000);
echo "\n";
echo json_encode(['valid' => true, 'discount_amount' => min("50000.00", 450000)]);
