<?php

passthru("echo | openssl s_client -connect openldap:636 2>/dev/null | openssl x509 -text | sed -n -e '/BEGIN\ CERTIFICATE/,/END\ CERTIFICATE/ p' > /usr/local/ssl/certs/cert.pem");

ldap_set_option(null, LDAP_OPT_X_TLS_CACERTDIR, '/usr/local/ssl/certs');
ldap_Set_option(null, LDAP_OPT_X_TLS_CACERTFILE, '/usr/local/ssl/certs/cert.pem');
ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 0);

$ldap = ldap_connect('ldaps://openldap:636');
ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
var_Dump(ldap_bind($ldap, 'cn=admin,dc=example,dc=org', 'admin'));

$ldap = ldap_connect('ldap://openldap:389');
ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_start_tls($ldap);
var_dump(ldap_bind($ldap, 'cn=admin,dc=example,dc=org', 'admin'));
