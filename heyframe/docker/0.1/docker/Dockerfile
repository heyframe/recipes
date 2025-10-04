#syntax=docker/dockerfile:1.4

# pin versions
FROM ghcr.io/heyframe/docker-base:8.2 AS base-image
FROM ghcr.io/heyframe/heyframe-cli:latest-php-8.2 AS heyframe-cli

# build

FROM heyframe-cli AS build

ARG HEYFRAME_PACKAGES_TOKEN

ADD . /src
WORKDIR /src

RUN --mount=type=secret,id=composer_auth,dst=/src/auth.json \
    --mount=type=cache,target=/root/.composer \
    --mount=type=cache,target=/root/.npm \
    /usr/local/bin/entrypoint.sh heyframe-cli project ci /src

# build final image

FROM base-image

COPY --from=build --chown=www-data --link /src /var/www/html
