<p align="center">
	<a href="#"  target="_blank" title="Dinero">
		<img src="/public/brands/dinero-logo.png" alt="Dinero" width="340px">
	</a>
</p>

<br>

<p align="center">:rocket: Multi Account Money Tracker :sparkles: <a href="https://github.com/Shipu/dinero">Dinero</a></p>

<p align="center">
	<img src="https://img.shields.io/badge/version project-1.0-brightgreen" alt="version project">
    <img src="https://img.shields.io/badge/Php-8.1-informational" alt="stack php">
    <img src="https://img.shields.io/badge/Laravel-10.46-informational&color=brightgreen" alt="stack laravel">
    <img src="https://img.shields.io/badge/Filament-3.2-informational" alt="stack Filament">
    <img src="https://img.shields.io/badge/TailwindCss-3.1-informational" alt="stack Tailwind">
	<a href="https://opensource.org/licenses/GPL-3.0">
		<img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="GPLv3 License">
	</a>
</p>

## Dinero
Dinero is a multi account money tracker. It is a simple application that allows you to track your money in multiple accounts and categories. It is written in PHP (Laravel Framework) and  Filament v3.

## Features
- Multi Account (Tenants)
- Multi Currency
- Wallets
- Categories
- Budgets
- Goals
- Debts
- Transactions
- Reports

## Installation
1. Clone the repository

```ssh 
git clone https://github.com/Shipu/dinero.git
```

3. Switch to the repo folder

```
cd dinero
```

2. Install all the dependencies using composer

```ssh 
composer install
```

3. Copy the example env file and make the required configuration changes in the .env file

```ssh 
cp .env.example .env
```

4. Generate a new application key

```ssh 
php artisan key:generate
```

5. Run the database migrations with seeder (Set the database connection in .env before migrating)

```ssh 
php artisan migrate --seed
```

6. Run the application

```ssh 
php artisan serve
```

7. Browse the application

> Url: [http://localhost:8000/](http://localhost:8000/)

![img.png](img.png)

8. Login with the following credentials:
- Email: `demo@dinero.app`
- Password: `12345678`

## NativePHP
for the NativePHP version, please check the [nativephp branch](https://github.com/shipu/dinero/tree/native-php)
```ssh
git checkout native-php
```

## Demo
> Url: [http://dinero.bridgex.live](http://dinero.bridgex.live)

## Screenshots
![Dashboard](screenshots/dinero-dashboard.png)
![Wallets](screenshots/dinero-wallets.png)
![Categories](screenshots/dinero-categories.png)
![Budgets](screenshots/dinero-budgets.png)
![Goals](screenshots/dinero-goals.png)
![Debts](screenshots/dinero-debts.png)
![Transactions](screenshots/dinero-transactions.png)
![Accounts](screenshots/dinero-tenants.png)
![MyProfile](screenshots/dinero-my-profile.png)

### :sparkles: Contributors
<table>
  <tr>
    <td align="center"><a href="https://github.com/Shipu">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/4118421?v=4" width="100px;" alt=""/>
    <br /><sub><b>Shipu Ahamed</b></sub></a></td>    
    <td align="center"><a href="https://github.com/shojibflamon">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/5617542?v=4" width="100px;" alt=""/>
    <br /><sub><b>Md. Jahidul Islam</b></sub></a></td>   
    <td align="center"><a href="https://github.com/devalade">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/74435372?v=4" width="100px;" alt=""/>
    <br /><sub><b>Alade YESSOUFOU</b></sub></a></td>    
    <td align="center"><a href="https://github.com/RafaelBlum">
        <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/u/41844692?v=4" width="100px;" alt=""/>
    <br /><sub><b>Rafael Blum</b></sub></a></td> 
  </tr>
</table>

> No one is so wise that they don't have something to learn, nor so foolish that they don't have something to teach. `Blaise Pascal`.
