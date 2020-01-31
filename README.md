# Handle LDAP-Connections securely

Very often people have trouble connecting with PHP to an LDAP-Server in a secure way.

The problem most of the time are certificates. And especially when those are self-signed certificates
the usual thing people do is to completely ignore certificates. Lots of answers on 
Stackoverflow that tell to use `TLS_REQCERT never` instead of solving the actual issue 
and not only verifying the certificate but actually using the CA-Certificate and therefore 
adding the extra layer of security.

## What to do?

So instead of using `TLS_REQCERT never` in your `ldap.conf`-file you should do the following:

Download the self-signed certificate from your server using this command:

```bash
echo 
   | openssl s_client -connect openldap:636 2>/dev/null 
   | openssl x509 -text 
   | sed -n -e '/BEGIN\ CERTIFICATE/,/END\ CERTIFICATE/ p' 
   > /path/to/cert.pem
```

Feel free to replace `/path/to` with whatever path you want to store the certificate at.

And then you can add the following to your `ldap.conf`-file:

```
TLS_CACERT /path/to/cert.pem
TLS_CACERTDIR /path/to
```

## Which `ldap.conf`-file 

you might ask now.

Well. Every time the LDAP-extension is called it will search a number of locations  
and parse possibly available files for additional informations.

Those locations by default are the following:

* `/etc/ldap/ldap.conf`
* `˜/ldaprc`
* `˜/.ldaprc`

Add the above lines to one of those files and you're ready to go.

## Easier way

But actually you don't need that file at all, when you are on a supported version of PHP,
as you can always add that information using `ldap_set_option` like this:

```php
ldap_set_option(null, LDAP_OPT_X_TLS_CACERTDIR, '/path/to');
ldap_set_option(null, LDAP_OPT_X_TLS_CACERTFILE, '/path/to/cert.pem');

$ldap = ldap_connect('ldaps://ldap.example.com:636');
```

And you are ready to go. Important is that you call it before the `ldap_connect` with `null` as the first parameter.

## Want to test it?

Clone this repo and run the following commands in the repos top-level:

```bash
$ docker-compose up -d
$ docker-compose run php -f test.php
bool(true)
bool(true)
```

Should you get `false` somethings wrong.... ;-)

For more information have a look at [the list of LDAP-Constants](https://www.php.net/manual/de/function.ldap-set-option.php)