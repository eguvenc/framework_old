<?php

/**
 * Shopping Cart Class
 *
 * @package       packages
 * @subpackage    cart
 * @category      Shopping Cart
 * @link
 */
Class Cart
{
    // These are the regular expression rules that we use to validate the product ID and product name
    public $product_id_rules = '\.a-z0-9_-'; // alpha-numeric, dashes, underscores, or periods
    public $product_name_rules = '\.\:\-_ a-z0-9'; // alpha-numeric, dashes, underscores, colons or periods
    public $_cart_contents = array();

    /**
     * Shopping Class Constructor
     *
     * The constructor loads the Sess helper, used to store the shopping cart contents.
     */
    public function __construct($params = array())
    {
        global $logger;

        if (!isset(getInstance()->cart)) {
            getInstance()->cart = $this; // Make available it in the controller $this->cart->method();
        }

        $this->init($params);

        $logger->debug('Cart Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Initialize params and grab the cart object
     * 
     * @param array $params
     * @return object
     */
    function init($params = array())
    {
        $config = array();         // Are any config settings being passed manually?  If so, set them

        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                $config[$key] = $val;
            }
        }

        new Sess;

        if (getInstance()->sess->get('cart_contents') !== false) {  // Grab the shopping cart array from the session table, if it exists
            $this->_cart_contents = getInstance()->sess->get('cart_contents');
        } else {
            $this->_cart_contents['cart_total'] = 0;    // No cart exists so we'll set some base values
            $this->_cart_contents['total_items'] = 0;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Insert items into the cart and save it to the session table
     *
     * @access   public
     * @param    array
     * @return   bool
     */
    public function insert($items = array())
    {
        global $logger;

        if (!is_array($items) OR count($items) == 0) { // Was any cart data passed? No? Bah...
            $logger->error('The insert method must be passed an array containing data.');

            return false;
        }

        // You can either insert a single product using a one-dimensional array, 
        // or multiple products using a multi-dimensional one. The way we
        // determine the array type is by looking for a required array key named "id"
        // at the top level. If it's not found, we will assume it's a multi-dimensional array.

        $save_cart = false;

        if (isset($items['id'])) {
            if ($this->_insert($items) == true) {
                $save_cart = true;
            }
        } else {
            foreach ($items as $val) {
                if (is_array($val) AND isset($val['id'])) {
                    if ($this->_insert($val) == true) {
                        $save_cart = true;
                    }
                }
            }
        }

        if ($save_cart) {   // Save the cart data if the insert was successful
            $this->_saveCart();

            return true;
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Insert
     *
     * @access    private
     * @param    array
     * @return    bool
     */
    private function _insert($items = array())
    {
        global $logger;

        if (!is_array($items) OR count($items) == 0) {  // Was any cart data passed? No? Bah...
            $logger->error('The insert method must be passed an array containing data.');

            return false;
        }

        // --------------------------------------------------------------------
        // Does the $items array contain an id, quantity, price, and name?  These are required
        if (!isset($items['id']) OR !isset($items['qty']) OR !isset($items['price']) OR !isset($items['name'])) {
            $logger->error('The cart array must contain a product ID, quantity, price, and name.');

            return false;
        }

        // --------------------------------------------------------------------

        $items['qty'] = trim(preg_replace('/([^0-9])/i', '', $items['qty']));  // Prep the quantity. It can only be a number.  Duh...
        $items['qty'] = trim(preg_replace('/(^[0]+)/i', '', $items['qty']));   // Trim any leading zeros

        if (!is_numeric($items['qty']) OR $items['qty'] == 0) {         // If the quantity is zero or blank there's nothing for us to do
            return false;
        }

        // --------------------------------------------------------------------
        // Validate the product ID. It can only be alpha-numeric, dashes, underscores or periods
        // Not totally sure we should impose this rule, but it seems prudent to standardize IDs.
        // Note: These can be user-specified by setting the $this->product_id_rules variable.
        if (!preg_match("/^[" . $this->product_id_rules . "]+$/i", $items['id'])) {
            $logger->error('Invalid product ID.  The product ID can only contain alpha-numeric characters, dashes, and underscores');

            return false;
        }

        // --------------------------------------------------------------------
        // Validate the product name. It can only be alpha-numeric, dashes, underscores, colons or periods.
        // Note: These can be user-specified by setting the $this->product_name_rules variable.
        if (!preg_match("/^[" . $this->product_name_rules . "]+$/i", $items['name'])) {
            $logger->error('An invalid name was submitted as the product name: ' . $items['name'] . ' The name can only contain alpha-numeric characters, dashes, underscores, colons, and spaces');

            return false;
        }

        // --------------------------------------------------------------------
        // Prep the price.  Remove anything that isn't a number or decimal point.
        $items['price'] = trim(preg_replace('/([^0-9\.])/i', '', $items['price']));
        $items['price'] = trim(preg_replace('/(^[0]+)/i', '', $items['price']));           // Trim any leading zeros

        if (!is_numeric($items['price'])) {          // Is the price a valid number?
            $logger->error('An invalid price was submitted for product ID: ' . $items['id']);

            return false;
        }

        // --------------------------------------------------------------------
        // We now need to create a unique identifier for the item being inserted into the cart.
        // Every time something is added to the cart it is stored in the master cart array.  
        // Each row in the cart array, however, must have a unique index that identifies not only 
        // a particular product, but makes it possible to store identical products with different options.  
        // For example, what if someone buys two identical t-shirts (same product ID), but in 
        // different sizes?  The product ID (and other attributes, like the name) will be identical for 
        // both sizes because it's the same shirt. The only difference will be the size.
        // Internally, we need to treat identical submissions, but with different options, as a unique product.
        // Our solution is to convert the options array to a string and MD5 it along with the product ID.
        // This becomes the unique "row ID"

        if (isset($items['options']) AND count($items['options']) > 0) {
            $rowid = md5($items['id'] . implode('', $items['options']));
        } else {
            // No options were submitted so we simply MD5 the product ID.
            // Technically, we don't need to MD5 the ID in this case, but it makes
            // sense to standardize the format of array indexes for both conditions
            $rowid = md5($items['id']);
        }

        // --------------------------------------------------------------------
        // Now that we have our unique "row ID", we'll add our cart items to the master array
        // let's unset this first, just to make sure our index contains only the data from this submission

        unset($this->_cart_contents[$rowid]);

        $this->_cart_contents[$rowid]['rowid'] = $rowid;          // Create a new index with our new row ID

        foreach ($items as $key => $val) {          // And add the new items to the cart array      
            $this->_cart_contents[$rowid][$key] = $val;
        }

        return true;  // Woot!
    }

    // --------------------------------------------------------------------

    /**
     * Update the cart
     *
     * This function permits the quantity of a given item to be changed. 
     * Typically it is called from the "view cart" page if a user makes
     * changes to the quantity before checkout. That array must contain the
     * product ID and quantity for each item.
     *
     * @access    public
     * @param    array
     * @param    string
     * @return    bool
     */
    public function update($items = array())
    {
        if (!is_array($items) OR count($items) == 0) {    // Was any cart data passed?
            return false;
        }

        // You can either update a single product using a one-dimensional array, 
        // or multiple products using a multi-dimensional one.  The way we
        // determine the array type is by looking for a required array key named "id".
        // If it's not found we assume it's a multi-dimensional array

        $save_cart = false;

        if (isset($items['rowid']) AND isset($items['qty'])) {
            if ($this->_update($items) == true) {
                $save_cart = true;
            }
        } else {
            foreach ($items as $val) {
                if (is_array($val) AND isset($val['rowid']) AND isset($val['qty'])) {
                    if ($this->_update($val) == true) {
                        $save_cart = true;
                    }
                }
            }
        }

        if ($save_cart) {      // Save the cart data if the insert was successful
            $this->_saveCart();

            return true;
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Update the cart
     *
     * This function permits the quantity of a given item to be changed. 
     * Typically it is called from the "view cart" page if a user makes
     * changes to the quantity before checkout. That array must contain the
     * product ID and quantity for each item.
     *
     * @access   private
     * @param    array
     * @return   bool
     */
    private function _update($items = array())
    {
        // Without these array indexes there is nothing we can do
        if (!isset($items['qty']) OR !isset($items['rowid']) OR !isset($this->_cart_contents[$items['rowid']])) {
            return false;
        }

        $items['qty'] = preg_replace('/([^0-9])/i', '', $items['qty']);         // Prep the quantity

        if (!is_numeric($items['qty'])) {          // Is the quantity a number?
            return false;
        }

        // Is the new quantity different than what is already saved in the cart?
        // If it's the same there's nothing to do

        if ($this->_cart_contents[$items['rowid']]['qty'] == $items['qty']) {
            return false;
        }

        // Is the quantity zero?  If so we will remove the item from the cart.
        // If the quantity is greater than zero we are updating

        if ($items['qty'] == 0) {
            unset($this->_cart_contents[$items['rowid']]);
        } else {
            $this->_cart_contents[$items['rowid']]['qty'] = $items['qty'];
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Save the cart array to the session DB
     *
     * @access    private
     * @return    bool
     */
    private function _saveCart()
    {
        // Unset these so our total can be calculated correctly below
        unset($this->_cart_contents['total_items']);
        unset($this->_cart_contents['cart_total']);

        // Lets add up the individual prices and set the cart sub-total

        $total = 0;
        foreach ($this->_cart_contents as $key => $val) {
            if (!is_array($val) OR !isset($val['price']) OR !isset($val['qty'])) {  // We make sure the array contains the proper indexes
                continue;
            }

            $total += ($val['price'] * $val['qty']);

            // Set the subtotal
            $this->_cart_contents[$key]['subtotal'] = ($this->_cart_contents[$key]['price'] * $this->_cart_contents[$key]['qty']);
        }

        $this->_cart_contents['total_items'] = count($this->_cart_contents);           // Set the cart total and total items.
        $this->_cart_contents['cart_total'] = $total;

        if (count($this->_cart_contents) <= 2) {         // Is our cart empty?  If so we delete it from the session
            getInstance()->sess->remove('cart_contents'); // Nothing more to do... coffee time!

            return false;
        }

        // If we made it this far it means that our cart has data.
        // Let's pass it to the Session class so it can be stored
        getInstance()->sess->set(array('cart_contents' => $this->_cart_contents));

        return true;   // Woot!
    }

    // --------------------------------------------------------------------

    /**
     * Cart Total
     *
     * @access    public
     * @return    integer
     */
    public function total()
    {
        return $this->_cart_contents['cart_total'];
    }

    // --------------------------------------------------------------------

    /**
     * Total Items
     *
     * Returns the total item count
     *
     * @access    public
     * @return    integer
     */
    public function totalItems()
    {
        return $this->_cart_contents['total_items'];
    }

    // --------------------------------------------------------------------

    /**
     * Cart Contents
     *
     * Returns the entire cart array
     *
     * @access    public
     * @return    array
     */
    public function contents()
    {
        $cart = $this->_cart_contents;

        // Remove these so they don't create a problem when showing the cart table
        unset($cart['total_items']);
        unset($cart['cart_total']);

        return $cart;
    }

    // --------------------------------------------------------------------

    /**
     * Has options
     *
     * Returns true if the rowid passed to this function correlates to an item
     * that has options associated with it.
     *
     * @access    public
     * @return    array
     */
    public function hasOptions($rowid = '')
    {
        if (!isset($this->_cart_contents[$rowid]['options']) OR count($this->_cart_contents[$rowid]['options']) === 0) {
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Product options
     *
     * Returns the an array of options, for a particular product row ID
     *
     * @access    public
     * @return    array
     */
    public function productOptions($rowid = '')
    {
        if (!isset($this->_cart_contents[$rowid]['options'])) {
            return array();
        }

        return $this->_cart_contents[$rowid]['options'];
    }

    // --------------------------------------------------------------------

    /**
     * Format Number
     *
     * Returns the supplied number with commas and a decimal point.
     *
     * @access    public
     * @return    integer
     */
    public function formatNumber($n = '')
    {
        if ($n == '') {
            return '';
        }

        $n = trim(preg_replace('/([^0-9\.])/i', '', $n));        // Remove anything that isn't a number or decimal point.

        return number_format($n, 2, '.', ',');
    }

    // --------------------------------------------------------------------

    /**
     * Destroy the cart
     *
     * Empties the cart and kills the session
     *
     * @access    public
     * @return    null
     */
    public function destroy()
    {
        unset($this->_cart_contents);

        $this->_cart_contents['cart_total'] = 0;
        $this->_cart_contents['total_items'] = 0;

        getInstance()->sess->remove('cart_contents');
    }

}

// END Cart Class

/* End of file Cart.php */
/* Location: ./packages/cart/releases/0.0.1/cart.php */