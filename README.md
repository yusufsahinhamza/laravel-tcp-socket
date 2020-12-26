Laravel TCP Socket
==========

Installation
------------
For install this package Edit your project's ```composer.json``` file to require yusufsahinhamza/laravel-tcp-socket

```php
"require": {
    "yusufsahinhamza/laravel-tcp-socket": "master"
},
```
Now, update Composer:
```
composer update
```
Once composer is finished, you need to add the service provider. Open ```config/app.php```, and add a new item to the providers array.
```
'YusufSahinHAMZA\Socket\SocketServiceProvider',
```
Next, add a Facade for more convenient usage. In ```config/app.php``` add the following line to the aliases array:
```
'Socket' => 'YusufSahinHAMZA\Socket\Facades\Socket',
```
Publish config files:
```
php artisan vendor:publish --provider="YusufSahinHAMZA\Socket\SocketServiceProvider"
```
for change username, password and other configuration change ```config/tcpsocket.php```

Usage
-----

### Configuration
Open ```config/tcp-socket.php```<br/>
<br />
Enter Server Ip Address and Port
<br />
You can change server protocol between ```SOL_TCP``` and ```SOL_UDP```

### Connect To Server
```php
$socket = Socket::connect();

$socket = Socket::connect($ip);
$socket = Socket::connect(null, $port);
$socket = Socket::connect($ip, $port);
```

### Disconnect from Server
```php
Socket::disconnect();
```

### Send Message
```php
Socket::sendMessage('test message'); //send message to connected server
```

Disclaimer
-----

This repository is basically for my personal use. All responsibility is yours if you want to use it.
