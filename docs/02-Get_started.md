# Freemius PHP SDK Documentation

## II. Getting Started

### 1. Installation

The easiest way to install the Freemius PHP SDK is through Composer. Run the following command in your project's root directory:

```bash
composer require skpassegna/freemius-php-sdk
```

### 2. Basic Configuration

Before you can use the SDK, you need to understand a few core concepts and configure it with your Freemius credentials.

#### API Scope

The Freemius API uses different "scopes" to control access to data. When initializing the SDK, you need to specify the scope that matches your needs:

- **`app`:** For interacting with application-level data.
- **`developer`:**  For managing multiple plugins and accessing developer-level information.
- **`user`:** For interacting with user accounts.
- **`install`:** For managing plugin installations on individual websites.
- **`plugin`:** For interacting with data related to a single plugin.
- **`store`:** For interacting with data related to a store.

#### API Credentials

To interact with the Freemius API, you'll need your API credentials:

- **Developer ID:**  Your unique developer ID on Freemius.
- **Public Key:**  Your public API key.
- **Secret Key:**  Your secret API key.

You can find these credentials in your Freemius developer dashboard:

1.  Go to your [Freemius Dashboard](https://dashboard.freemius.com/).
2.  Click on your profile icon in the top right corner.
3.  Select "My Profile."
4.  Your Developer ID, Public Key, and Secret Key will be listed in the "Keys" section.

#### Initializing the SDK

Once you have your API credentials and have chosen the appropriate scope, you can initialize the `Freemius` class:

```php
use Freemius\SDK\Freemius;

// Replace with your actual credentials
$scope = 'developer'; // Or 'plugin', 'user', etc.
$developerId = 12345;
$publicKey = 'pk_your_public_key';
$secretKey = 'sk_your_secret_key';

$freemius = new Freemius($scope, $developerId, $publicKey, $secretKey);

// If you are using the sandbox environment:
$freemius = new Freemius($scope, $developerId, $publicKey, $secretKey, true);
```

Now you have a `$freemius` object that you can use to interact with the Freemius API.

Prev [Introduction](01-Introduction.md) | Next [Core Features and Usage](03-Features_and_usage.md)