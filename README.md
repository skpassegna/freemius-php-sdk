## Freemius PHP SDK - Unofficial but Refactored and Modernized

> Still under heavy dev :smiley:


This is an unofficial, fully refactored, and modernized Freemius PHP SDK designed for seamless integration with the Freemius platform. It adheres to SOLID principles, leverages PHP 8+ features, and provides a clean, object-oriented interface for interacting with the Freemius API.

### Installation

Install the SDK via Composer:

```bash
composer 
```

### Usage

#### 1. Initialize the Freemius API Client

```php
use Freemius\SDK\Api\FreemiusApi;

// Replace with your actual Freemius API credentials
$scope        = 'developer'; // 'developer', 'app', 'user', 'install'
$developerId  = 12345;
$publicKey    = 'pk_your_public_key';
$secretKey    = 'sk_your_secret_key';
$sandbox      = true; // Set to false for production

$api = new FreemiusApi($scope, $developerId, $publicKey, $secretKey, $sandbox);
```

#### 2. Access API Endpoints

The `FreemiusApi` object provides access to various API endpoints, each representing a specific resource:

```php
// Access the Plugins endpoint
$pluginsEndpoint = $api->plugins();

// Access the Users endpoint
$usersEndpoint = $api->users();

// Access the Installs endpoint
$installsEndpoint = $api->installs();

// Access the Plans endpoint
$plansEndpoint = $api->plans();

// Access the Features endpoint
$featuresEndpoint = $api->features();

// Access the Licenses endpoint
$licensesEndpoint = $api->licenses();

// Access the Subscriptions endpoint
$subscriptionsEndpoint = $api->subscriptions();

// Access the Payments endpoint
$paymentsEndpoint = $api->payments();

// Access the Coupons endpoint
$couponsEndpoint = $api->coupons();

// Access the Emails endpoint
$emailsEndpoint = $api->emails();

// Access the Events endpoint
$eventsEndpoint = $api->events();
```

#### 3. Perform API Operations

Each endpoint provides methods for performing various operations on its corresponding resource. Here are some examples:

**Plugins:**

- **Get a list of plugins for the developer:**
  ```php
  $plugins = $api->plugins()->getPlugins();
  ```

- **Get a specific plugin:**
  ```php
  $plugin = $api->plugins()->getPlugin(123);
  ```

- **Create a new plugin:**
  ```php
  $newPluginData = [
      'title' => 'My New Plugin',
      // ... other plugin data
  ];
  $newPlugin = $api->plugins()->createPlugin($newPluginData);
  ```

**Users:**

- **Get a list of users for a plugin:**
  ```php
  $users = $api->users()->getUsers(123);
  ```

- **Get a specific user:**
  ```php
  $user = $api->users()->getUser(123, 456);
  ```

- **Download a CSV file of users for a plugin:**
  ```php
  $csvContent = $api->users()->downloadUsersCSV(123);
  ```

**Installs:**

- **Get a list of installs for a plugin:**
  ```php
  $installs = $api->installs()->getInstalls(123);
  ```

- **Get a specific install:**
  ```php
  $install = $api->installs()->getInstall(123, 456);
  ```

- **Download a specific plugin version for an install:**
  ```php
  $zipContent = $api->installs()->downloadVersion(123, 456, 789);
  ```

**Plans:**

- **Get a list of plans for a plugin:**
  ```php
  $plans = $api->plans()->getPlans(123);
  ```

- **Get a specific plan:**
  ```php
  $plan = $api->plans()->getPlan(123, 456);
  ```

**Features:**

- **Get a list of features for a plugin:**
  ```php
  $features = $api->features()->getFeatures(123);
  ```

- **Get a specific feature:**
  ```php
  $feature = $api->features()->getFeature(123, 456);
  ```

**Licenses:**

- **Get a list of licenses for a plugin:**
  ```php
  $licenses = $api->licenses()->getLicenses(123);
  ```

- **Get a specific license:**
  ```php
  $license = $api->licenses()->getLicense(123, 456);
  ```

**Subscriptions:**

- **Get a list of subscriptions for a plugin:**
  ```php
  $subscriptions = $api->subscriptions()->getSubscriptions(123);
  ```

- **Get a specific subscription:**
  ```php
  $subscription = $api->subscriptions()->getSubscription(123, 456);
  ```

**Payments:**

- **Get a list of payments for a plugin:**
  ```php
  $payments = $api->payments()->getPayments(123);
  ```

- **Get a specific payment:**
  ```php
  $payment = $api->payments()->getPayment(123, 456);
  ```

**Coupons:**

- **Get a list of coupons for a plugin:**
  ```php
  $coupons = $api->coupons()->getCoupons(123);
  ```

- **Get a specific coupon:**
  ```php
  $coupon = $api->coupons()->getCoupon(123, 456);
  ```

**Emails:**

- **Get a list of email templates for a plugin:**
  ```php
  $emailTemplates = $api->emails()->getEmailTemplates(123);
  ```

- **Get a specific email template:**
  ```php
  $emailTemplate = $api->emails()->getEmailTemplate(123, 'after_purchase');
  ```

**Events:**

- **Get a list of events for a plugin:**
  ```php
  $events = $api->events()->getEvents(123);
  ```

- **Get a specific event:**
  ```php
  $event = $api->events()->getEvent(123, 456);
  ```

#### 4. Error Handling

All API operations can potentially throw an `ApiException` if the request fails. Handle these exceptions gracefully:

```php
try {
    // Perform API operation
} catch (ApiException $e) {
    // Handle API error
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    // You can access the API response using $e->getResponse()
}
```

### Contributing

Contributions are welcome! Feel free to open issues and pull requests on the GitHub repository: [https://github.com/skpassegna/freemius-php-sdk](https://github.com/skpassegna/freemius-php-sdk)

### License

This SDK is licensed under the GPL-2.0+ license. See the LICENSE file for more details.
