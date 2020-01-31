FROM php:cli

# Install mysql and cron
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libldap2-dev 
        
RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/
RUN docker-php-ext-install ldap 
RUN apt-get purge -y --auto-remove libldap2-dev

ADD ./ /app

WORKDIR /app

CMD ["php", "-a"]
