# QR Code Generator - Laravel Application

A professional QR Code Generator built with Laravel 12, supporting 10 different QR code types with a beautiful multi-step wizard interface.

## Features

✨ **10 QR Code Types**
- Website URL
- Email (with subject and message)
- Plain Text
- PDF Documents
- Restaurant Menus
- Discount Coupons
- Events
- Mobile Apps
- Locations (Google Maps)
- WiFi Networks

🎨 **Modern UI**
- Multi-step wizard (3 steps)
- Color customization with picker
- Preset color palettes
- Responsive design
- File upload with drag-and-drop
- Real-time preview

📥 **Download Options**
- PNG format
- SVG format
- QR code history

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL
- Laravel Herd (or any PHP development environment)

## Installation

### 1. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Configure Environment

The `.env` file is already configured with:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qr_generator
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Set Up Database

The database has already been created and migrations have been run. If you need to reset:

```bash
# Create database (if needed)
mysql -u root -e "CREATE DATABASE IF NOT EXISTS qr_generator"

# Run migrations
php artisan migrate:fresh
```

### 4. Create Storage Link

```bash
php artisan storage:link
```

### 5. Build Assets

```bash
# For development (with hot reload)
npm run dev

# For production
npm run build
```

## Running the Application

### Development Server

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server (for hot reload)
npm run dev
```

The application will be available at: **http://localhost:8000**

### Using Laravel Herd

If you're using Laravel Herd, the site should be automatically available at:
```
http://qr-generator.test
```

## Usage

### Creating a QR Code

1. **Select QR Type**: Choose from 10 different types on the home page
2. **Step 1 - Customize**:
   - Enter a name for your QR code
   - Choose primary color (QR code color)
   - Choose background color
   - Use preset colors or enter custom hex values
3. **Step 2 - Setup Info**: Fill in type-specific information
   - For URL: Enter website URL
   - For Email: Enter email, subject, and message
   - For PDF: Upload PDF file (max 10MB)
   - For WiFi: Enter SSID, encryption, and password
   - etc.
4. **Step 3 - Design QR Code**: Preview and download
   - View generated QR code
   - Download as PNG or SVG
   - Create another QR code

### Viewing History

Click "History" in the navigation to view all previously generated QR codes. You can re-download them in PNG or SVG format.

## Project Structure

```
qr-generator/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── QrCodeController.php      # Main controller
│   │   └── Requests/
│   │       └── StoreQrCodeRequest.php    # Form validation
│   ├── Models/
│   │   ├── QrCode.php                    # QR code model
│   │   └── QrCodeFile.php                # File model
│   └── Services/
│       └── QrCodeService.php             # QR generation logic
├── database/
│   └── migrations/
│       ├── *_create_qr_codes_table.php
│       └── *_create_qr_code_files_table.php
├── resources/
│   ├── css/
│   │   └── app.css                       # Tailwind CSS
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php             # Main layout
│       └── qr-codes/
│           ├── index.blade.php           # Type selection
│           ├── create.blade.php          # Multi-step wizard
│           ├── history.blade.php         # QR history
│           └── forms/                    # Type-specific forms
│               ├── url.blade.php
│               ├── email.blade.php
│               ├── text.blade.php
│               ├── pdf.blade.php
│               ├── menu.blade.php
│               ├── coupon.blade.php
│               ├── event.blade.php
│               ├── app.blade.php
│               ├── location.blade.php
│               └── wifi.blade.php
├── routes/
│   └── web.php                           # Application routes
└── storage/
    └── app/
        └── public/
            ├── qr-codes/                 # Generated QR codes
            └── qr-files/                 # Uploaded files
```

## Database Schema

### `qr_codes` Table
- `id` - Primary key
- `type` - QR code type (url, email, text, etc.)
- `name` - User-defined name
- `data` - JSON field storing type-specific data
- `colors` - JSON field storing primary and secondary colors
- `qr_image_path` - Path to generated QR code image
- `created_at`, `updated_at`

### `qr_code_files` Table
- `id` - Primary key
- `qr_code_id` - Foreign key to qr_codes
- `file_type` - Type of file (pdf, image, etc.)
- `file_path` - Storage path
- `original_name` - Original filename
- `file_size` - File size in bytes
- `created_at`, `updated_at`

## Technologies Used

### Backend
- **Laravel 12** - PHP framework
- **SimpleSoftwareIO/simple-qrcode** - QR code generation library
- **MySQL** - Database

### Frontend
- **Tailwind CSS v4** - Utility-first CSS framework
- **Vite** - Build tool and dev server
- **Vanilla JavaScript** - For wizard navigation and AJAX

## QR Code Types Details

### 1. Website URL
- Direct link to any website
- Validates URL format

### 2. Email
- Opens email client with pre-filled:
  - Email address
  - Subject line
  - Message body
- Uses MAILTO format

### 3. Text
- Plain text content (max 500 characters)
- Perfect for short messages

### 4. PDF
- Upload PDF file (max 10MB)
- Hosted and accessible via QR scan

### 5. Menu
- Upload menu PDF OR enter menu URL
- Ideal for restaurants

### 6. Coupon
- Upload coupon image (required)
- Optional logo image
- Logo displays while loading

### 7. Event
- Comprehensive event information:
  - Event image
  - Company/host name
  - Event name (required)
  - Description
  - Date and time
  - Location
  - Amenities (checkboxes)
  - Dress code
  - Contact information

### 8. App
- Mobile app promotion:
  - App color
  - App icon/image
  - App name
  - Website URL
  - App Store link
  - Google Play Store link

### 9. Location
- Address input
- Opens in Google Maps
- Automatic geocoding

### 10. WiFi
- Network name (SSID)
- Encryption type (WPA/WPA2, WEP, None)
- Password (if encrypted)
- Uses standard WIFI QR format

## Customization

### Colors
- Primary color: QR code pixels
- Secondary color: Background
- 5 preset color combinations
- Custom hex color input
- Real-time preview

### File Uploads
- Drag-and-drop interface
- File preview for images
- File size validation
- Type validation (PDF, images)
- Stored in `storage/app/public/qr-files/`

## API Endpoints

All routes are defined in `routes/web.php`:

- `GET /` - QR code type selection
- `GET /qr-codes/create/{type}` - Create QR wizard
- `POST /qr-codes` - Generate and store QR code
- `POST /qr-codes/preview` - Preview QR code
- `GET /qr-codes/{id}/download/{format}` - Download QR (PNG/SVG)
- `GET /qr-codes/history` - View QR history

## Troubleshooting

### QR Code Not Generating
- Check that the database connection is working
- Ensure `storage/app/public` directory is writable
- Verify `php artisan storage:link` was run

### File Upload Errors
- Check PHP upload limits in `php.ini`:
  - `upload_max_filesize = 10M`
  - `post_max_size = 10M`
- Ensure storage directory permissions are correct

### Assets Not Loading
- Run `npm run build` or `npm run dev`
- Clear browser cache
- Check that Vite is running (for dev mode)

## License

This project is open-source and available for educational purposes.

## Support

For issues or questions, please refer to the walkthrough documentation in the `.gemini/antigravity/brain/` directory.

---

**Enjoy creating beautiful QR codes!** 🎉
