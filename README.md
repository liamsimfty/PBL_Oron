# ORON Gaming Platform

A modern web-based gaming platform built with PHP and Oracle Database, featuring a digital game store, user authentication, shopping cart functionality, and payment processing.

## 🎮 Overview

ORON is a comprehensive gaming platform that allows users to browse, purchase, and manage their digital game collection. The platform features a modern, responsive design with secure user authentication, shopping cart functionality, and integrated payment processing through Midtrans.

## ✨ Features

| ![Store](https://github.com/user-attachments/assets/9384de86-8541-4829-91dd-1c9c2b02dc31) | ![Game Detail](https://github.com/user-attachments/assets/e1813628-4ca4-4dbf-9840-be16c36c6c8d) |
|:--:|:--:|
| **Store**<br>Extensive catalog with real-time search and filters | **Game Detail**<br>Full pricing, description, and discount display |

| ![Cart](https://github.com/user-attachments/assets/17c5a61c-f38a-49b4-9bc8-68b717f6dd54) | ![Payment](https://github.com/user-attachments/assets/77befde9-c620-4eeb-95d2-4a02e71ebd22) |
|:--:|:--:|
| **Shopping Cart**<br>Add/remove games, manage quantities, IDR pricing | **Midtrans Payment**<br>Secure checkout via multiple methods |


### 📚 Game Library
- **Personal Library**: Access to purchased games
- **Game Management**: Organize and manage game collection
- **Purchase History**: Track all transactions and purchases

### 🛡️ Security Features
- **reCAPTCHA Protection**: Bot protection on login forms
- **Password Hashing**: Secure password storage using PHP's password_hash()
- **SQL Injection Prevention**: Prepared statements with Oracle database
- **Session Security**: Secure session management
- **Input Validation**: Comprehensive input sanitization

### 👤 User Management
- **User Registration**: Secure account creation with validation
- **Login System**: Password-protected authentication with reCAPTCHA
- **Password Recovery**: Secure password reset functionality
- **Profile Management**: User profile and account settings
- **Session Management**: Secure session handling


## 🛠️ Technology Stack

### Backend
- **PHP 7.4+**: Server-side scripting language
- **Oracle Database**: Primary database system
- **Composer**: Dependency management

### Frontend
- **HTML5**: Semantic markup
- **CSS3**: Modern styling with custom design system
- **JavaScript**: Interactive functionality
- **Font Awesome**: Icon library
- **Swiper.js**: Carousel and slider functionality

### External Services
- **Midtrans**: Payment gateway integration
- **Google reCAPTCHA**: Bot protection
- **Google Fonts**: Typography (Lemon Milk font family)

### Dependencies
```json
{
    "midtrans/midtrans-php": "^2.6",
    "vlucas/phpdotenv": "^5.6",
    "google/recaptcha": "^1.3"
}
```

## 📋 Prerequisites

Before running this project, ensure you have:

- **PHP 7.4 or higher**
- **Oracle Database** (XE or higher)
- **Oracle Instant Client** (for PHP Oracle extension)
- **Composer** (for dependency management)
- **Web Server** (Apache/Nginx)
- **PHP Extensions**:
  - `oci8` (Oracle database extension)
  - `curl`
  - `json`
  - `mbstring`

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd PBL_Oron
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Database Setup
1. Create an Oracle database instance
2. Import the database schema from `assets/databases/`
3. Configure database connection in `.env` file

### 4. Environment Configuration
Create a `.env` file in the root directory:
```env
DB_USERNAME=your_oracle_username
DB_PASSWORD=your_oracle_password
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
RECAPTCHA_SITE_KEY=your_recaptcha_site_key
RECAPTCHA_SECRET_KEY=your_recaptcha_secret_key
```

### 5. Web Server Configuration
Configure your web server to point to the project root directory and ensure PHP has access to the Oracle extension.

## 📁 Project Structure

```
PBL_Oron/
├── assets/                 # Static assets
│   ├── databases/         # Database files
│   ├── images/           # General images
│   ├── storeImg/         # Product images
│   └── reset.css         # CSS reset
├── features/             # Core application features
│   ├── cart/            # Shopping cart functionality
│   ├── connection/      # Database connection
│   ├── history/         # Transaction history
│   ├── library/         # User game library
│   ├── login/           # Authentication system
│   ├── pages/           # Static pages
│   ├── process/         # Payment processing
│   ├── profile/         # User profile management
│   └── store/           # Game store functionality
├── Styling/             # Frontend assets
│   ├── css/            # Stylesheets
│   ├── images/         # UI images
│   └── JS/             # JavaScript files
├── vendor/              # Composer dependencies
├── composer.json        # PHP dependencies
└── README.md           # This file
```

## 🔧 Configuration

### Database Configuration
The application connects to Oracle Database using the configuration in `features/connection/connection.php`. Ensure your Oracle instance is running and accessible.

### Payment Configuration
Midtrans payment gateway is configured in the cart and process files. Update the API keys in your `.env` file for production use.

### reCAPTCHA Configuration
Google reCAPTCHA is used for bot protection. Configure your site and secret keys in the `.env` file.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
