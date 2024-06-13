<p align="center"><img src="https://raw.githubusercontent.com/bryanfks-dev/Sripuja-Elektronik-RMS/master/public/images/logo_dark.svg" width="400" alt="Sripuja Logo"></p>

# Sripuja Elektronik: Internal Retail System for Store Operational Management  

Sripuja Elektronik is a retail store specializing in home appliances electronics and has been operating since 1999. However, all administrative task have been done manually to date. In the face of growing digitalization, by adapting to technology to improve efficiency and accuracy in business operations. Digital transformation has become a necessity to maintain competitiveness and relevance in a dynamic market. By adopting an integrated and automated management system, Sripuja Elektronik is determined to improve its administrative processes, from recording employee attendance to analyzing business performance. To meet the growing business needs, we have developed a Retail Management System (RMS) software from scratch, We hope that Sripuja Elektronik will be able to solve problems related to store management such as providing an eye-candy UI/UX design, exceptional performance, and features that solve precise business challenges.

## Features

The application consist of 8 critical features:
1. **Inventory Management**
2. **Employee Management**
3. **Customer Management**
4. **Supplier Management**
5. **Transaction Management**
7. **Operational Report**
8. **Attendance**
9. **Chart & Stats**

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

7. **Add new admin user:**
   ```
   php artisan app:create-admin
   ```

8. **Build assets:**
    ```
    npm run dev
    ```

9. **Start the development server:**
    ```
    php artisan serve
    ```
10. **Start the cron job:**
    ```
    php artisan schedule:work
    ```
11. **Access the application:**
Open your web browser and navigate to 'http://localhost:8000'

## License
This project is licensed under the Apache 2.0 License. See the LICENSE file for details.

## Contact
For any inquiries or feedback, please contact us at halo.sripuja@gmail.com
