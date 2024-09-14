# Freemius PHP SDK Documentation

## IV. Advanced Usage

This section covers some advanced usage scenarios for the Freemius PHP SDK, allowing you to fine-tune your interactions with the Freemius API and handle specific situations.

### 1. Clock Synchronization

Accurate timekeeping is crucial for various operations, such as managing license expirations, trial periods, and subscription renewals. To ensure that your server's clock is synchronized with the Freemius API server, the SDK provides the `findClockDiff()` method.

The `findClockDiff()` method calculates the difference in seconds between your server's clock and the Freemius API server's clock. You can use this difference to adjust time-based operations in your application.

**Example:**

```php
// Calculate the clock difference
$clockDiff = $freemius->findClockDiff();

// Adjust a license expiration date
$licenseExpiration = new DateTime('2025-12-31 23:59:59');
$licenseExpiration->modify("+$clockDiff seconds");

echo 'Adjusted License Expiration: ' . $licenseExpiration->format('Y-m-d H:i:s');
```

**Important Note:**

- The `findClockDiff()` method makes an API call to the Freemius server, so it might introduce a slight performance overhead. You can cache the clock difference for a reasonable period to reduce the number of API calls.

### 2. Signed URLs

Signed URLs provide a secure way to grant temporary access to resources, such as plugin downloads, without exposing your API credentials. The Freemius PHP SDK provides the `getSignedUrl()` method to generate signed URLs.

**Example:**

```php
// Generate a signed URL for a premium plugin download
$downloadUrl = $freemius->getSignedUrl('/plugins/123/tags/1.0.0.zip', true); // true for premium version

// Redirect the user to the download URL
header('Location: ' . $downloadUrl);
exit;
```

**Parameters:**

- `path`: (Required) The path to the resource you want to access (e.g., `/plugins/123/tags/1.0.0.zip`).
- `isPremium`: (Optional) Whether the resource is a premium version (defaults to `false`).

**How Signed URLs Work:**

The `getSignedUrl()` method generates a URL that includes a signature based on your API credentials and the requested resource. This signature verifies that the request is authorized and prevents unauthorized access. The signed URL is valid for a limited time, after which it expires.

**Important Note:**

- Signed URLs are a security feature and should be used whenever you need to grant temporary access to resources.

### 3. Customizing HTTP Requests

The Freemius PHP SDK uses the Guzzle HTTP client to make API requests. Guzzle provides a flexible and powerful way to customize HTTP requests, allowing you to set custom headers, timeouts, and other options.

**Accessing the Guzzle Client:**

You can access the underlying Guzzle client through the `_client` property of the `Freemius` class.

**Example:**

```php
// Set a custom timeout for API requests
$freemius->_client->setDefaultOption('timeout', 10); // Set a 10-second timeout

// Add a custom header to all requests
$freemius->_client->setDefaultOption('headers', [
    'X-My-Custom-Header' => 'My Custom Value',
]);
```

**Guzzle Options:**

Refer to the [Guzzle documentation](https://docs.guzzlephp.org/en/stable/) for a complete list of available options and how to configure them.

**Important Note:**

- Customizing HTTP requests should be done with caution, as it can affect the functionality and security of the SDK. Only modify request options if you understand the implications.

**Next:**

In the next part of the documentation, we'll discuss how to contribute to the Freemius PHP SDK project. 


