<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Sripuja Elektronik: Retail Management System

Sripuja Elektronik is a retail store specializing in home appliances electronics and has been operating since 1999. However, all administrative task have been done manually to date. To meet the growing business needs, we have developed a Retail Management System (RMS) software from scratch, providing an eye-candy UI/UX design, exceptional performance, and features that solve precise business challenges.

## Features

The application consist of 8 critical features:
1. **Inventory Management**
2. **Employee Management**
3. **Customer Management**
4. **Supplier Management**
5. **Transaction Management**
6. **Reporting**
7. **Attendance**
8. **Chart & Stats**

### Key Enhancements
- **Revamped UI/UX**: Responsive design for various devices, dark mode, and latest design trends.
- **Improved Database Design**: Adapted to accommodate the new and updated features.
- **Advanced Features**: Resources management, enhanced reporting, and more.

## Installation Instructions

To set up the application locally, follow these steps:

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL server

### Steps

1. **Clone the repository:**
   ```
   git clone https://github.com/yourusername/sripuja-elektronik-rms.git
   cd sripuja-elektronik-rms
   ```

2. **Install PHP dependencies:**
    ```
    composer install
    ```

3. **Install JavaScript dependencies:**
    ```
    npm install
    ```

4. **Set up the environment file:**
    ```
    cp .env.example .env
    ```

5. **Generate application key:**
    ```
    php artisan key:generate
    ```

6. **Run migrations and seed the database:**
    ```
    php artisan migrate --seed
    ```

7. **Build assets:**
    ```
    npm run dev
    ```

8. **Start the development server:**
    ```
    php artisan serve
    ```
9. **Access the application:**
Open your web browser and navigate to 'http://localhost:8000'

## License
This project is licensed under the Apache 2.0 License. See the LICENSE file for details.

## Contact
For any inquiries or feedback, please contact us at halo.sripuja@gmail.com
