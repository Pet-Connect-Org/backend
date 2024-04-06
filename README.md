## How to install and run laravel in your own machine

[See here](https://laravel-jwt-auth.readthedocs.io/en/latest/laravel-installation/)

---

# Install Steps

1. Install dep

```bash
   composer install
```

2. Run migration

```bash
   php artisan migrate
```

3. Data seeder

```bash
   php artisan db:seed
```

4. Run server

```bash
   php artisan serve
```

5. Api docs

    [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation)

---

# Document

1. Publish swagger vendor

```bash
   php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

2. Generate
   php artisan l5-swagger:generate

```bash
   php artisan l5-swagger:generate
```

---

## Pet type ref

[https://basepaws.com/cat-breeds](https://basepaws.com/cat-breeds)

---

## Author

[Bui Ngoc](https://www.facebook.com/Bui.Ngoc.1302/)
