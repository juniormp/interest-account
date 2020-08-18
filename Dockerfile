FROM php:7.4-fpm

# Instal cron
RUN apt-get update && apt-get -y install cron
# Copy cronjob file to the cron.d directory
COPY cronjob /etc/cron.d/cronjob
# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/cronjob
# Apply cron job
RUN crontab /etc/cron.d/cronjob
# Create the log file to be able to run tail
RUN touch /var/log/cron.log
# Run the command on container startup
CMD cron && tail -f /var/log/cron.log

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www
