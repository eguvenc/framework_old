## Bcrypt Class

------

Bcrypt class produces secure password hash using blowfish algorithm.

### Initializing the Class

------

```php
new Bcrypt();
$this->bcrypt->method();
```

Once loaded, the Bcrypt object will be available using: $this->bcrypt->method();

### Why should I use Bcrypt ?

- Its slowness and multiple rounds ensures high security (an attacker must deploy massive hardware to be able to crack the passwords).
- Bcrypt is a one-way hashing algorithm. This means that you cannot "decode" the plain password.
- Bcrypt class hashes each password with a different salt.

<b>MD5</b> is a good method to obscure <b>non-sensitive data</b>, because it's quite fast.
However, this is a big disadvantage when it comes to password hashing. With [rainbow tables](http://en.wikipedia.org/wiki/Rainbow_table), MD5 hashes can be very easily “decoded”.

That's the point where Bcrypt comes into play. Using a work factor of *12*, Bcrypt hashes the password in about *0.3 seconds*. MD5, on the other hand, takes less than *a microsecond*. 

> Read more about [why you should use Bcrypt](http://phpmaster.com/why-you-should-use-bcrypt-to-hash-stored-passwords/).

<a name="scheme"></a>

### Scheme 

To alter the default scheme, change the variable `$_identifier` of the class to one of the following parameters (without $-signs):

- `$2a$` - Hash which is potentially generated with the buggy algorithm.
- `$2x$` - "compatibility" option the buggy Bcrypt implementation.
- `$2y$` - Hash generated with the new, corrected algorithm implementation *(crypt_blowfish 1.1 and newer)*.

---

**Note:** The default scheme is `$2y$`, which makes use of the new, corrected hash implementation.  
*Other schemes should only be used when comparing values produced by an old version.*

---

### Structure

```php
$2a$12$Some22CharacterSaltXXO6NC3ydPIrirIzk1NdnTz0L/aCaHnlBa
```

- `$2a$` tells PHP to use which [Blowfish scheme](#scheme) *(Bcrypt is based on Blowfish)*
- `12$`  is the number of iterations the hashing mechanism uses.
- `Some22CharacterSaltXXO` is a random salt *(by OpenSSL)*

#### Diagram

```php
$2a$12$Some22CharacterSaltXXO6NC3ydPIrirIzk1NdnTz0L/aCaHnlBa
\___________________________/\_____________________________/
  \                            \
   \                            \ Actual Hash (31 chars)
    \
     \  $2a$   12$   Some22CharacterSaltXXO
        \__/    \    \____________________/
          \      \              \
           \      \              \ Salt (22 chars)
            \      \
             \      \ Number of Rounds (work factor)
              \
               \ Hash Header
```

> Diagram based on [Andrew Moore's structure](http://stackoverflow.com/a/5343655).

---


### Creating Password Hash

------

```php
<?php
$password = 'hello12348';

new Bcrypt();
$hash = $this->bcrypt->hashPassword($password, $workfactor = 8);

echo $hash;

// gives
// $2y$08$y/sXToti3CSEu6/U0J3j0.QAKj6ay1WjrjTXx9B8ITlX..vk3cEU.
```

**Note:** The second paramater <b>workfactor</b> shouldn't be more than <b>12</b> characters otherwise this may cause performance problems.


### Verifying Password Hash

------

```php
<?php
$password = 'hello12348';
$hash = '$2y$08$y/sXToti3CSEu6/U0J3j0.QAKj6ay1WjrjTXx9B8ITlX..vk3cEU.';

if($this->bcrypt->verifyPassword($password, $hash))
{
    echo 'Password verified';
}
```

### Function Reference

------

#### $this->bcrypt->hashPassword(string $password, integer $workfactor = 10);

Creates password hash using $workfactor, default $workfactor is 10.

#### $this->bcrypt->verifyPassword(string $password, string $hashedPassword);

Checks out that the password is correct.