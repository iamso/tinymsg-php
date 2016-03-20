# PHP Client for Tiny Msg

Client for tiny real-time messaging server, see https://github.com/iamso/tinymsg.

## Install

### With Composer

Run the following command:

```bash
composer require tinymsg/msg
```

Or add the package to your `composer.json`:

```json
{
  "require": {
    "tinymsg/msg": "*"
  }
}
```

### Without Composer

Just download it and put it somewhere :)

## Usage

### Include

```php
// With Composer
require 'vendor/autoload.php';

// Without Composer
require 'path/to/tinymsg/msg.php';
```

### Create instance

```php
// Importing the namespace
use tinymsg\Msg;

// Server without SSL
$msg = new Msg('channel-name', 'tinymsg.domain.tld');

// Server with SSL
$msg = new Msg('channel-name', 'tinymsg.domain.tld', true);

// Localhost with special port
$msg = new Msg('channel-name', 'localhost:7777', false, 7777);

// Using the fully qualified namespace name
$msg = new tinymsg/Msg(...);
```
### Send a message

```php
// Send a message
$msg->send('string');
$msg->send(array(1,2,3,4));
$msg->send(array('key' => 'value'));
```

### Open/close socket

When you create an Msg instance, the constructor opens the socket connection.
You can close and reopen the connection, if needed.

```php
// Close the socket connection
$msg->close();

// (Re-) Open the socket connection
$msg->open();

```

## License
Copyright (c) 2016 Steve Ottoz

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
