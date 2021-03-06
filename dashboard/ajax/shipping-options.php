<?php
require_once '../../init.php';
require_once '../../lib/Shippo/Shippo.php';

$customer_id = $_REQUEST['customer_id'];
$shop_id = $_REQUEST['shop_id'];
$customer = new Customer($DB, $customer_id);
$shop = new Shop($DB,$shop_id);
$orders = $customer->get_orders();

$total_weight = 0;
$options = array();

Shippo::setApiKey(SHIPPO_KEY);
$fromAddress = array(
    'object_purpose' => 'PURCHASE',
    'name' => $shop->first_name . ' ' . $shop->last_name,
    'street1' => $shop->address_1,
    'city' => $shop->city,
    'state' => $shop->state,
    'zip' => $shop->postal_code,
    'country' => $shop->country,
    'phone' => $shop->phone,
    'email' => $shop->email,
    "validate" => false
);

$toAddress = array(
    'object_purpose' => 'PURCHASE',
    'name' => $customer->first_name . ' ' . $customer->last_name,
    'street1' => $customer->address_1,
    'city' => $customer->city,
    'state' => $customer->state,
    'zip' => $customer->postal_code,
    'country' => $customer->country,
    'phone' => $customer->phone,
    'email' => $customer->email,
    "validate" => false
);

foreach($orders as $order) {
    $product = new Product($DB, $order->product_id);
    if ((!isset($max_length)) || $product->length > $max_length) {
        $max_length = $product->length;
    }
    if ((!isset($max_width)) || $product->length > $max_width) {
        $max_width = $product->width;
    }
    if ((!isset($max_height)) || $product->length > $max_height) {
        $max_height = $product->height;
    }
    $total_weight += $product->weight;
    
}

$parcel = array(
    'length'=> $max_length,
    'width'=> $max_width,
    'height'=> $max_height,
    'distance_unit'=> $shop->distance_unit,
    'weight'=> $total_weight,
    'mass_unit'=> $shop->weight_unit,
);

$shipments = Shippo_Shipment::create( array(
    'object_purpose'=> 'PURCHASE',
    'address_from'=> $fromAddress,
    'address_to'=> $toAddress,
    'parcel'=> $parcel,
    'async'=> false
    )
);

foreach($shipments['rates_list'] as $shipment) {
    $option = array(
        'amount' => $shipment->amount,
        'currency' => $shipment->currency,
        'img'    => $shipment->provider_image_75,
        'servicelevel_name' => $shipment->servicelevel_name,
        'days'   => $shipment->days,
        'terms'  => $shipment->duration_terms,
        'attributes' => $shipment->attributes,
        'object_id'  => $shipment->object_id
    );
    $options[] = $option;
}
?>

<div class="panel-heading">
    Shipping Options
</div>
<!-- /.panel-heading -->
<div class="panel-body">
    <div class="table-responsive" id="payment-details">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <th></th>
                    <th></th>
                    <th><b>Name</b></th>
                    <th><b>Amount</b></th>
                    <th><b>Days</b></th>
                    <th><b>Terms</b></th>
                    <th></th>
                </tr>
                <?php foreach ($options as $option) { ?>
                <tr>
                    <td><input value=<?= $option['object_id']?> type="radio" name="selected-option"></td>
                    <td><img src="<?= $option['img'] ?>"></td>
                    <td><?= $option['servicelevel_name'] ?></td>
                    <td><?= $option['currency'] == 'USD' ? '$' : '' ?><?= $option['amount'] ?></td>
                    <td><?= $option['days'] ?></td>
                    <td><?= $option['terms'] ?></td>
                    <td><?= strtolower(implode(', ', $option['attributes'])) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- /.table-responsive -->
</div>
<!-- /.panel-body -->

