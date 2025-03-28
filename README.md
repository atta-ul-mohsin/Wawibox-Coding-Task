# Dental Price Comparison System

## Quick Start Guide

### Prerequisites
- PHP 8.0 or later
- Composer installed

### Setup Instructions
1. Clone the repository:
   ```bash
   git clone https://github.com/atta-ul-mohsin/Wawibox-Coding-Task
   ```
2. Navigate to project directory:
   ```bash
   cd Wawibox-Coding-Task
   ```
3. Install dependencies:
   ```bash
   composer install
   ```

### Running the Application
Execute the main script:
```bash
php public/index.php
```
This will display sample order comparisons.

### Testing the System

**Run tests:**
```bash
# Unit tests
vendor/bin/phpunit tests/Unit

# Integration tests
vendor/bin/phpunit tests/Integration
```

### Project Structure Overview
```
src/               # Core application source code
tests/             # All test cases
   Unit/           # Unit tests directory
   Integration/    # Integration tests directory  
public/            # Public entry point
vendor/            # Composer dependencies (auto-generated)
```

### Customizing Orders
Modify `public/index.php` to test different scenarios:
```php
$orderItems = [
    new OrderItem('Dental Floss', 5),  // Change quantity
    new OrderItem('Ibuprofen', 10)     // Modify product
];
```

### Technical Dependencies
- PHPUnit 9+ for testing framework
- Pure PHP implementation (no database required)
