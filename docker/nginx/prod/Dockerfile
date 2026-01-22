FROM nginx:1.27.1-alpine

WORKDIR /var/www

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY public/ /var/www/public/

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD wget -qO- http://127.0.0.1/health || exit 1
