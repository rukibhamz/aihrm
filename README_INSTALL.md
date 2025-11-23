# AI HR Management System - Manual Installation

If the automated installer fails, please follow these steps:

1.  **Install Dependencies**:
    Open a terminal in `c:\xampp\htdocs\aihrm\platform` and run:
    ```bash
    composer install
    npm install
    npm run build
    ```

2.  **Configure Environment**:
    Copy `.env.example` to `.env`:
    ```bash
    cp .env.example .env
    ```
    Edit `.env` and set your database credentials:
    ```
    DB_DATABASE=aihrm
    DB_USERNAME=root
    DB_PASSWORD=
    ```

3.  **Generate Key**:
    ```bash
    php artisan key:generate
    ```

4.  **Run Migrations**:
    ```bash
    php artisan migrate
    ```

5.  **Serve**:
    ```bash
    php artisan serve
    ```
