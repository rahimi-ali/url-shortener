# URL Shortener

A URL shortener built as an exercise in extreme wheel reinvention!

## Installation
1. Bring up the http server, database and cache with `docker compose up -d`
2. Configure (copy and edit `.json.example` files in config directory to `.json` files)
3. Install Dependencies with `docker compose exec fpm composer install`
4. Run database migrations with `docker compose exec fpm php migrations/migrate.php`
5. Use the API (Postman collection and environment are committed to the repo in `api` directory)

## Endpoints
- Link Redirection
- Registration
- Login
- Profile
- Link Creation
- Link List (of the authenticated user)
- Link Deletion

## Improvements
- [ ] Add tests for router and feature tests for auth and link APIs.
- [ ] Add more translation languages and add a detect language middleware to provide correct translations to the user.
- [ ] Add a rate limiter middleware to prevent abuse.
- [ ] Cache configuration values in some in memory cache so it doesn't have to be read from file every request.

## Specs

### What's custom-built?
- [x] Routing
- [x] Dependency Injection Abstractions
- [x] Configuration manager (with JSON config files)
- [x] Database Abstraction (Repository pattern with PDO)
- [x] Validation (with the `rahimi-ali/php-dto` library which I had previously written)

### What's not custom (yet)?
- [ ] Dependency Injection Core (something to replace `php-di`) -> quite hard and a lot of reflection involved
- [ ] PSR-7 and PSR-15 implementations (something to replace `laminas-diactoros`) -> quite hard, especially making it performant
- [ ] HTTP Emitter (something to replace `http-emitter`) -> probably very easy
- [ ] PSR-3 Logger (something to replace `monolog`) -> very easy
