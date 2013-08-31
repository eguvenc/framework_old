## Encryption Class

The Encryption Class provides two-way data encryption. It uses a scheme that pre-compiles the message using a randomly hashed bitwise XOR encoding scheme, which is then encrypted using the Mcrypt library. If Mcrypt is not available on your server the encoded message will still provide a reasonable degree of security for encrypted sessions or other such "light" purposes. If Mcrypt is available, you'll effectively end up with a double-encrypted message string, which should provide a very high degree of security.

### Setting your Key

------

A key is a piece of information that controls the cryptographic process and permits an encrypted string to be decoded. In fact, the key you chose will provide the <b>only</b> means to decode data that was encrypted with that key, so not only must you choose the key carefully, you must never change it if you intend use it for persistent data.

It goes without saying that you should guard your key carefully. Should someone gain access to your key, the data will be easily decoded. If your server is not totally under your control it's impossible to ensure key security so you may want to think carefully before using it for anything that requires high security, like storing credit card numbers.

To take maximum advantage of the encryption algorithm, your key should be 32 characters in length (128 bits). The key should be as random a string as you can concoct, with numbers and uppercase and lowercase letters. Your key should <b>not</b> be a simple text string. In order to be cryptographically secure it needs to be as random as possible.

Your key can be either stored in your <dfn>app/config/config.php</dfn>, or you can design your own storage mechanism and pass the key dynamically when encoding/decoding.

To save your key to your <dfn>app/config/config.php</dfn>, open the file and set:

```php
$config['encryption_key'] = "YOUR KEY";
```

### Message Length

------

It's important for you to know that the encoded messages the encryption function generates will be approximately 2.6 times longer than the original message. For example, if you encrypt the string "my super secret data", which is 21 characters in length, you'll end up with an encoded string that is roughly 55 characters (we say "roughly" because the encoded string length increments in 64 bit clusters, so it's not exactly linear). Keep this information in mind when selecting your data storage mechanism. Cookies, for example, can only hold 4K of information.

### Initializing the Class

------

```php
new Encrypt();
```

Once loaded, the Encrypt library object will be available using: <dfn>$this->encrypt->method()</dfn>;

### Grabbing the Instance

------

Also using new Encrypt(false); boolean you can grab the instance of Obullo libraries,"$this->encrypt->method()" will not available in the controller.

```php
$encrypt = new Encrypt(false);

$encrypt->method();
```

#### $this->encrypt->encode()

Performs the data encryption and returns it as a string. Example:

```php
$msg = 'My secret message';

$encrypted_string = $this->encrypt->encode($msg);
```

You can optionally pass your encryption key via the second parameter if you don't want to use the one in your config file:

```php
$msg = 'My secret message';
$key = 'super-secret-key';

$encrypted_string = $this->encrypt->encode($msg, $key);
```

#### $this->encrypt->decode()

Decrypts an encoded string. Example:

```php
$encrypted_string = 'APANtByIGI1BpVXZTJgcsAG8GZl8pdwwa84';
$plaintext_string = $this->encrypt->decode($encrypted_string);
```


#### $this->encrypt->setCipher();

Permits you to set an Mcrypt cipher. By default it uses <samp>MCRYPT_RIJNDAEL_256</samp>. Example:

```php
$encrypt->setCipher(MCRYPT_BLOWFISH);
```

Please visit php.net for a list of [available ciphers](http://php.net/mcrypt).

If you'd like to manually test whether your server supports Mcrypt you can use:

```php
echo ( ! function_exists('mcrypt_encrypt')) ? 'Nope' : 'Yup';
```

#### $this->encrypt->setMode();

Permits you to set an Mcrypt mode. By default it uses <samp>MCRYPT_MODE_ECB</samp>. Example:

```php
$this->encrypt->setMode(MCRYPT_MODE_CFB);
```

Please visit php.net for a list of [available modes](http://php.net/mcrypt).

#### $this->encrypt->sha1();

SHA1 encoding function. Provide a string and it will return a 160 bit one way hash. Note: SHA1, just like MD5 is non-decodable. Example:

```php
$hash = $this->encrypt->sha1('Some string');
```

Many PHP installations have SHA1 support by default so if all you need is to encode a hash it's simpler to use the native function:

```php
$hash = sha1('Some string');
```

If your server does not support SHA1 you can use the provided function.