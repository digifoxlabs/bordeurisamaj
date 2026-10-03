# Kamakhya Devalaya

A Laravel 12 and Tailwind CSS membership register for the Kamakhya Devalaya community.

## Run locally

1. Copy `.env.example` to `.env` if needed and set the database connection.
2. Install PHP and frontend dependencies, then initialize the application:

```sh
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

For active frontend development, use `npm run dev` in a second terminal.

## Admin access

Open `/admin` and sign in with:

- Email: `admin@kamakhya.com`
- Password: `password@123`

The seeder creates or refreshes that account. Change the password from the administrator profile before deploying publicly.

## Membership flow

The public home page links to the bilingual registration form. Name and mobile number are required; mobile is checked while typing and enforced as unique in the database. The review page allows editing or submission without losing the draft. Member photos can be selected from a device or captured with its camera, then cropped before submission. Administrators can search, print, update, and delete member records, update their login profile, and upload, download, or delete privately stored documents from the Documents area.

Assamese labels live in `lang/en/membership.php`; replace those values with preferred translations. Dark mode is available from the header on both public and administrator pages.
