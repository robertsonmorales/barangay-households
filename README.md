## Framework

### Getting Started

#### What command to run / install
- mkdir -p storage/framework/{sessions,views,cache}
- mkdir -p storage/framework/cache/data
- mkdir -p bootstrap/cache
- copy .env.example content then create .env
- composer install
- php artisan optimize:clear
- php artisan key:generate --ansi
- php artisan migrate:fresh --seed
- npm install
- npm run watch
- php artisan serve

### Note
- php artisan vendor:publish --tag=laravel-pagination (for pagination)
- php artisan storage:link (for file upload)

### Running into Errors
- if you found error in npm install
- try removing node_modules and package-lock.json file
- then run npm install

### PACKAGES FOR SECURITY
- PURIFIER Input Sanitizer (https://github.com/mewebstudio/Purifier)
- LARAVEL FEATURE POLICY (https://github.com/mazedlx/laravel-feature-policy)
- LARAVEL MITNICK (https://github.com/getspooky/Laravel-Mitnick)
- LARAVEL CSP (https://github.com/spatie/laravel-csp)
