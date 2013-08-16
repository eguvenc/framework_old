### Shopping Cart Class

------

The Cart Class permits items to be added to a session that stays active while a user is browsing your site. These items can be retrieved and displayed in a standard "shopping cart" format, allowing the user to update the quantity or remove items from the cart.

Please note that the Cart Class ONLY provides the core "cart" functionality. It does not provide shipping, credit card authorization, or other processing components.

### Initializing the Shopping Cart Class

<strong>Important:</strong> The Cart class utilizes Obullo's [Session Helper](/user_guide/en/1.0.1/session-helper.html) to save the cart information to a database, so before using the Cart class you must set up a database table as indicated in the [Session Documentation](/user_guide/en/1.0.1/session-helper.html) , and set the session preferences in your <kbd>application/config/config.php</kbd> file to utilize a database.

```php
new Cart();
```

Once loaded, the Cart object will be available using: <dfn>$this->cart->method();</dfn>

### Grabbing the Instance

Also using new Cart(false); boolean you can grab the instance of Obullo libraries,"$this->cart->method()" will not available in the controller.

```php
$cart = new Cart(false);

$cart->method();
```

**Note:** The Cart Class will load and initialize the Session Helper automatically, so unless you are using sessions elsewhere in your application, you do not need to load the Session Helper.

### Adding an Item to The Cart

------

To add an item to the shopping cart, simply pass an array with the product information to the <dfn>$cart->insert()</dfn> function, as shown below:

```php
$data = array(
               'id'      => 'sku_123ABC',
               'qty'     => 1,
               'price'   => 39.95,
               'name'    => 'T-Shirt',
               'options' => array('Size' => 'L', 'Color' => 'Red')
            );

$this->cart->insert($data); 
```

**Important:** The first four array indexes above (<dfn>id, qty, price,</dfn> and <dfn>name</dfn>) are *required*. If you omit any of them the data will not be saved to the cart. The fifth index (<dfn>options</dfn>) is optional. It is intended to be used in cases where your product has options associated with it. Use an array for options, as shown above.

The five reserved indexes are:

  <ul><li><strong>id</strong> - Each product in your store must have a unique identifier. Typically this will be an "sku" or other such identifier.</li>
    <li><strong>qty</strong> - The quantity being purchased.</li>
    <li><strong>price</strong> - The price of the item.</li>
    <li><strong>name</strong> - The name of the item.</li>
    <li><strong>options</strong> - Any additional attributes that are needed to identify the product. These must be passed via an array.</li></ul> 

In addition to the five indexes above, there are two reserved words: <dfn>rowid</dfn> and <dfn>subtotal</dfn>. These are used internally by the Cart class, so please do NOT use those words as index names when inserting data into the cart.

Your array may contain additional data. Anything you include in your array will be stored in the session. However, it is best to standardize your data among all your products in order to make displaying the information in a table easier.

### Adding Multiple Items to The Cart

------

By using a multi-dimensional array, as shown below, it is possible to add multiple products to the cart in one action. This is useful in cases where you wish to allow people to select from among several items on the same page.

```php
$data = array(
               array(
                       'id'      => 'sku_123ABC',
                       'qty'     => 1,
                       'price'   => 39.95,
                       'name'    => 'T-Shirt',
                       'options' => array('Size' => 'L', 'Color' => 'Red')
                    ),
               array(
                       'id'      => 'sku_567ZYX',
                       'qty'     => 1,
                       'price'   => 9.95,
                       'name'    => 'Coffee Mug'
                    ),
               array(
                       'id'      => 'sku_965QRS',
                       'qty'     => 1,
                       'price'   => 29.95,
                       'name'    => 'Shot Glass'
                    )
            );

$this->cart->insert($data); 
```

### Displaying the Cart

------

To display the cart you will create a [view file](http://obullo.com/user_guide/en/1.0.1/views.html) with code similar to the one shown below.

Please note that this example uses the [form helper](http://obullo.com/user_guide/en/1.0.1/form-helper.html).

Your controller should be looks like this ..

```php
<?php Class Start extends Controller
{
    function __construct()
    {   
        parent::__construct();

        loader::helper('ob/form');
        loader::lib('ob/cart');
    }                               

    public function index()
    {      
        view_var('title', 'Welcome to Shopping Cart !');
        
        $data = array(
                       array(
                               'id'      => 'sku_123ABC',
                               'qty'     => 1,
                               'price'   => 39.95,
                               'name'    => 'T-Shirt',
                               'options' => array('Size' => 'L', 'Color' => 'Red')
                            ),
                       array(
                               'id'      => 'sku_567ZYX',
                               'qty'     => 1,
                               'price'   => 9.95,
                               'name'    => 'Coffee Mug'
                            ),
                       array(
                               'id'      => 'sku_965QRS',
                               'qty'     => 1,
                               'price'   => 29.95,
                               'name'    => 'Shot Glass'
                            )
                    );

        $this->cart->insert($data); 
        
        view('view_cart', '', FALSE);
    }
    
} //end.
  
?>
```

And your <b>view_cart.php</b> file should be looks like that ..

```php
// view_cart.php contents 

<?php echo form_open('path/to/controller/update/function'); ?>

<table cellpadding="6" cellspacing="1" style="width:100%" border="0">

<tr>
  <th>QTY</th>
  <th>Item Description</th>
  <th style="text-align:right">Item Price</th>
  <th style="text-align:right">Sub-Total</th>
</tr>

<?php $i = 1; ?>

<?php foreach($this->cart->contents() as $items): ?>

    <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
    
    <tr>
      <td><?php echo form_input(array('name' => $i.'[qty]', 'value' => $items['qty'], 
      'maxlength' => '3', 'size' => '5')); ?></td>
      <td>
        <?php echo $items['name']; ?>
                    
            <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
                    
            <p>
                <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
                    
                    <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
                                    
                <?php endforeach; ?>
            </p>
                
            <?php endif; ?>
                
      </td>
      <td style="text-align:right"><?php echo $this->cart->format_number($items['price']); ?></td>
      <td style="text-align:right"><?php echo $this->cart->format_number($items['subtotal']); ?></td>
    </tr>

<?php $i++; ?>

<?php endforeach; ?>

<tr>
  <td colspan="2"> </td>
  <td class="right"><strong>Total</strong></td>
  <td class="right"><?php echo $this->cart->format_number($this->cart->total()); ?></td>
</tr>

</table>

<p><?php echo form_submit('', 'Update your Cart'); ?></p>
```

### Updating The Cart

------

To update the information in your cart, you must pass an array containing the <kbd>Row ID</kbd> and quantity to the <dfn>$this->cart->update()</dfn> function:

**Note:** If the quantity is set to zero, the item will be removed from the cart.

```php
$data = array(
               'rowid' => 'b99ccdf16028f015540f341130b6d8ec',
               'qty'   => 3
            );

$this->cart->update($data);

// Or a multi-dimensional array

$data = array(
               array(
                       'rowid'   => 'b99ccdf16028f015540f341130b6d8ec',
                       'qty'     => 3
                    ),
               array(
                       'rowid'   => 'xw82g9q3r495893iajdh473990rikw23',
                       'qty'     => 4
                    ),
               array(
                       'rowid'   => 'fh4kdkkkaoe30njgoe92rkdkkobec333',
                       'qty'     => 2
                    )
            );

$this->cart->update($data); 
```

<strong>What is a Row ID?</strong> The <kbd>row ID</kbd> is a unique identifier that is generated by the cart code when an item is added to the cart. The reason a unique ID is created is so that identical products with different options can be managed by the cart.

For example, let's say someone buys two identical t-shirts (same product ID), but in different sizes. The product ID (and other attributes) will be identical for both sizes because it's the same shirt. The only difference will be the size. The cart must therefore have a means of identifying this difference so that the two sizes of shirts can be managed independently. It does so by creating a unique "row ID" based on the product ID and any options associated with it.

In nearly all cases, updating the cart will be something the user does via the "view cart" page, so as a developer, it is unlikely that you will ever have to concern yourself with the "row ID", other then making sure your "view cart" page contains this information in a hidden form field, and making sure it gets passed to the update function when the update form is submitted. Please examine the construction of the "view cart" page above for more information.

### Function Reference

------

#### $this->cart->insert();

------

Permits you to add items to the shopping cart, as outlined above.

#### $this->cart->update();

------

Permits you to update items in the shopping cart, as outlined above.

#### $this->cart->total();

------

Displays the total amount in the cart.

#### $this->cart->total_items();

------

Displays the total number of items in the cart.

#### $this->cart->contents();

------

Returns an array containing everything in the cart.

#### $this->cart->has_options(rowid);

------

Returns TRUE (boolean) if a particular row in the cart contains options. This function is designed to be used in a loop with <dfn>$this->cart->contents()</dfn>, since you must pass the <kbd<rowid</kbd> to this function, as shown in the <kbd>Displaying the Cart</kbd> example above.

#### $this->cart->options(rowid);

------

Returns an array of options for a particular product. This function is designed to be used in a loop with <dfn>$this->cart->contents()</dfn>, since you must pass the <kbd>rowid</kbd> to this function, as shown in the <dfn>Displaying the Cart</dfn> example above.

#### $this->cart->destroy();

------

Permits you to destroy the cart. This function will likely be called when you are finished processing the customer's order.