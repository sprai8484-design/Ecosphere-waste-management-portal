<?php
// api/process_payment.php — Dummy Payment Gateway + Transaction Record
// POST JSON: { product_id, buyer_name, buyer_email, buyer_phone, quantity,
//              card_number, card_expiry, card_cvv, card_name }

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonOut(['error' => 'POST only'], 405);

$body = json_decode(file_get_contents('php://input'), true) ?? [];

// ── Collect fields ────────────────────────────────────────────────
$productId  = (int)($body['product_id'] ?? 0);
$buyerName  = clean(trim($body['buyer_name']  ?? ''));
$buyerEmail = clean(trim($body['buyer_email'] ?? ''));
$buyerPhone = clean(trim($body['buyer_phone'] ?? ''));
$quantity   = (int)($body['quantity'] ?? 1);
$cardNumber = preg_replace('/\D/', '', $body['card_number'] ?? '');
$cardExpiry = clean(trim($body['card_expiry'] ?? ''));
$cardCVV    = preg_replace('/\D/', '', $body['card_cvv'] ?? '');
$cardName   = clean(trim($body['card_name'] ?? ''));
$payMethod  = clean(trim($body['payment_method'] ?? 'Card'));

// ── Validate buyer info ───────────────────────────────────────────
if (!$productId)   jsonOut(['error' => 'Invalid product.'], 422);
if (!$buyerName)   jsonOut(['error' => 'Buyer name is required.'], 422);
if (!$buyerEmail || !filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
    jsonOut(['error' => 'Valid email is required.'], 422);
}
if ($quantity < 1) jsonOut(['error' => 'Quantity must be at least 1.'], 422);

// ── Validate payment details (dummy) ─────────────────────────────
if ($payMethod === 'Card') {
    if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
        jsonOut(['error' => 'Invalid card number.'], 422);
    }
    if (!preg_match('/^\d{2}\/\d{2}$/', $cardExpiry)) {
        jsonOut(['error' => 'Card expiry must be MM/YY.'], 422);
    }
    // Check expiry not in past
    [$expM, $expY] = explode('/', $cardExpiry);
    $expDate = \DateTime::createFromFormat('my', $expM . $expY);
    if ($expDate < new \DateTime('first day of this month')) {
        jsonOut(['error' => 'Card has expired.'], 422);
    }
    if (strlen($cardCVV) < 3) {
        jsonOut(['error' => 'Invalid CVV.'], 422);
    }
    if (!$cardName) {
        jsonOut(['error' => 'Cardholder name is required.'], 422);
    }
    // Simulate decline for card number starting with 0000
    if (str_starts_with($cardNumber, '0000')) {
        jsonOut(['error' => 'Payment declined. Please check your card details or use a different card.'], 402);
    }
}

// ── Load product ──────────────────────────────────────────────────
try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT * FROM resell_products WHERE id=:id AND status='Approved' LIMIT 1");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch();
    if (!$product) jsonOut(['error' => 'Product not found or not available.'], 404);

    if ($quantity > (int)$product['quantity']) {
        jsonOut(['error' => "Only {$product['quantity']} unit(s) available."], 422);
    }

    $totalPrice = round((float)$product['price'] * $quantity, 2);
    $last4      = $payMethod === 'Card' ? substr($cardNumber, -4) : null;
    $txnUID     = generateTxnUID();

    // ── Simulate payment processing delay (real gateway would call API here)
    usleep(500000); // 0.5s simulate network

    // ── Simulate 95% success rate (for demo; always succeeds if card valid)
    // In sandbox mode, we always succeed unless card starts with 0000 (declined above)

    // ── Begin transaction ─────────────────────────────────────────
    $pdo->beginTransaction();

    // Insert transaction record
    $txnStmt = $pdo->prepare("
        INSERT INTO resell_transactions
          (transaction_uid, product_id, buyer_name, buyer_email, buyer_phone,
           seller_name, seller_email, product_name, product_uid,
           quantity, unit_price, total_price, payment_method, payment_status, card_last4)
        VALUES
          (:txnuid, :pid, :bname, :bemail, :bphone,
           :sname, :semail, :pname, :puid,
           :qty, :uprice, :tprice, :pmethod, 'Success', :last4)
    ");
    $txnStmt->execute([
        ':txnuid'  => $txnUID,
        ':pid'     => $productId,
        ':bname'   => $buyerName,
        ':bemail'  => $buyerEmail,
        ':bphone'  => $buyerPhone,
        ':sname'   => $product['seller_name'],
        ':semail'  => $product['seller_email'],
        ':pname'   => $product['product_name'],
        ':puid'    => $product['product_uid'],
        ':qty'     => $quantity,
        ':uprice'  => $product['price'],
        ':tprice'  => $totalPrice,
        ':pmethod' => $payMethod,
        ':last4'   => $last4,
    ]);

    // Update product status / quantity
    $newQty = (int)$product['quantity'] - $quantity;
    if ($newQty <= 0) {
        $pdo->prepare("UPDATE resell_products SET status='Sold', quantity=0, updated_at=NOW() WHERE id=:id")
            ->execute([':id' => $productId]);
    } else {
        $pdo->prepare("UPDATE resell_products SET quantity=:qty, updated_at=NOW() WHERE id=:id")
            ->execute([':qty' => $newQty, ':id' => $productId]);
    }

    $pdo->commit();

    jsonOut([
        'success'          => true,
        'transaction_uid'  => $txnUID,
        'product_uid'      => $product['product_uid'],
        'product_name'     => $product['product_name'],
        'seller_name'      => $product['seller_name'],
        'buyer_name'       => $buyerName,
        'buyer_email'      => $buyerEmail,
        'quantity'         => $quantity,
        'unit_price'       => (float)$product['price'],
        'total_price'      => $totalPrice,
        'payment_method'   => $payMethod,
        'card_last4'       => $last4,
        'transaction_date' => date('Y-m-d H:i:s'),
        'message'          => 'Payment successful!',
    ]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    jsonOut(['error' => 'Payment processing error: ' . $e->getMessage()], 500);
}
