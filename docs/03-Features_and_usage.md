# Freemius PHP SDK Documentation

## III. Core Features and Usage

This section provides detailed, task-oriented guides and examples for using the SDK's core features.

### 1. Managing Plugins and Add-ons

This section covers how to interact with your Freemius plugins and add-ons using the SDK. The `plugins.json` endpoint provides a comprehensive list of all plugins along with their associated information.

#### Understanding API Scopes and Access Permissions

Before diving into the specifics of interacting with the `plugins.json` endpoint, it's crucial to understand the concept of API scopes. Scopes define the level of access and permissions granted to your API requests. Choosing the correct scope ensures you can perform the desired actions.

The Freemius API supports the following scopes related to plugins:

- **`developer`:** This scope grants access to all plugins associated with your developer account. It allows you to retrieve, update, and delete plugins, as well as manage their versions, plans, features, and other related data.
- **`plugin`:** This scope limits access to a single, specific plugin. You can retrieve and update information about the plugin, but you cannot perform actions that affect other plugins or developer-level settings.

When initializing the Freemius SDK, you specify the scope using the first parameter of the `Freemius` constructor:

```php
use Freemius\SDK\Freemius;

// Developer scope: access to all plugins
$freemius = new Freemius('developer', $developerId, $publicKey, $secretKey); 

// Plugin scope: access to a single plugin (e.g., plugin ID 123)
$freemius = new Freemius('plugin', 123, $publicKey, $secretKey); 
```

Choosing the correct scope is essential for security and to prevent unintended actions. For example, if you only need to retrieve information about a single plugin, using the `plugin` scope is recommended to limit potential risks.

#### Retrieving Plugin Information

To retrieve information about plugins, you'll use the `api()` method with the `GET` HTTP method and the `/plugins.json` endpoint. This endpoint returns a list of plugins, and you can filter the results based on various properties.

**Example: Retrieving All Plugins (Developer Scope)**

```php
$plugins = $freemius->api('/plugins.json', 'GET');

// Loop through the plugins
foreach ($plugins->plugins as $plugin) {
    echo 'Plugin ID: ' . $plugin->id . '<br>';
    echo 'Title: ' . $plugin->title . '<br>';
    echo 'Slug: ' . $plugin->slug . '<br>';
    // ... other plugin details
}
```

**Example: Retrieving a Specific Plugin (Plugin Scope)**

```php
$plugin = $freemius->api('/plugins.json', 'GET');

// Access plugin properties
echo $plugin->title;
echo $plugin->slug;
echo $plugin->public_key;
// ...
```

**Filtering Plugin Data:**

Most properties of a plugin can be used as filters to refine the data retrieved from the Freemius API. This allows you to retrieve only the plugins that meet specific criteria, making your data retrieval more targeted and efficient.

**Example: Retrieving Plugins with a Specific Slug:**

```php
$plugins = $freemius->api('/plugins.json', 'GET', [
    'slug' => 'my-awesome-plugin', // Filter by slug
]);

// ... process the filtered plugins
```

**Example: Retrieving Active Plugins:**

```php
$plugins = $freemius->api('/plugins.json', 'GET', [
    'is_off' => false, // Filter by active plugins
]);

// ... process the filtered plugins
```

**Available Filters:**

You can filter plugins based on various properties, including:

- `id`
- `slug`
- `title`
- `default_plan_id`
- `is_off`
- `is_only_for_new_installs`
- `installs_limit`
- `accepted_payments`
- And more...

Refer to the Freemius API documentation for a complete list of filterable properties.

**Response Structure:**

The response from the `/plugins.json` endpoint will be a JSON object containing a `plugins` array. Each element in the `plugins` array represents a plugin and has the following properties:

| Property             | Description                                                                                                                                                                                                                                                                                                          |
|----------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `id`                 | The unique ID of the plugin.                                                                                                                                                                                                                                                                                            |
| `title`              | The title of the plugin.                                                                                                                                                                                                                                                                                             |
| `slug`               | The slug of the plugin (used in URLs).                                                                                                                                                                                                                                                                                    |
| `public_key`         | The public API key for the plugin.                                                                                                                                                                                                                                                                                       |
| `secret_key`         | The secret API key for the plugin. **Important:** Keep this key secret!                                                                                                                                                                                                                                                |
| `default_plan_id`    | The ID of the default pricing plan for the plugin.                                                                                                                                                                                                                                                                       |
| `plans`              | A comma-separated list of plan IDs associated with the plugin.                                                                                                                                                                                                                                                          |
| `features`           | A comma-separated list of feature IDs associated with the plugin.                                                                                                                                                                                                                                                        |
| `money_back_period` | The number of days in the money-back guarantee period.                                                                                                                                                                                                                                                                   |
| `created`            | The date and time the plugin was created.                                                                                                                                                                                                                                                                             |
| `updated`            | The date and time the plugin was last updated.                                                                                                                                                                                                                                                                           |
| `is_off`            | Indicates whether Freemius is disabled for this plugin.                                                                                                                                                                                                                                                                 |
| `is_only_for_new_installs` | Indicates whether Freemius is only activated for new installations of the plugin.                                                                                                                                                                                                                                       |
| `installs_limit`      | The maximum number of installations allowed for the plugin before Freemius is deactivated.                                                                                                                                                                                                                            |
| `accepted_payments`   | The accepted payment methods for the plugin (`0`: both PayPal and Credit Cards; `1`: PayPal only; `2`: Credit Cards only).                                                                                                                                                                                                 |

#### Updating Plugin Information

To update plugin information, use the `api()` method with the `PUT` HTTP method and the `/plugins/{plugin_id}.json` endpoint. You can update various properties, including the title, default plan, money-back period, and more. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123.json', 'PUT', [
    'title'             => 'My Updated Plugin Title',
    'default_plan_id'   => 456,
    'money_back_period' => 14,
]);

// Check if the update was successful
if (isset($response->id)) {
    echo 'Plugin updated successfully!';
} else {
    echo 'Error updating plugin.';
}
```

**Important Notes:**

- You can only update the properties that are allowed by the Freemius API.
- Make sure you have the correct permissions (API scope) to update the plugin.

#### Regenerating Secret Keys

If you need to regenerate the secret key for a plugin, you can use the `api()` method with the `PUT` HTTP method and the `/plugins/{plugin_id}/secret_key.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/secret_key.json', 'PUT');

// Get the new secret key
$newSecretKey = $response->secret_key;

echo 'New secret key: ' . $newSecretKey;
```

**Important Note:** 

- Store the new secret key securely. You'll need it for future API interactions with this plugin.

#### Deleting Plugins

To delete a plugin, use the `api()` method with the `DELETE` HTTP method and the `/plugins/{plugin_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123.json', 'DELETE');

// Check if the deletion was successful
if ($response === '') { // Successful DELETE requests return an empty response
    echo 'Plugin deleted successfully!';
} else {
    echo 'Error deleting plugin.';
}
```

**Important Note:**

- Deleting a plugin is a permanent action and will remove all associated data, including licenses, installs, and users.

#### Working with Plugin Tags/Versions

**Listing Plugin Versions:**

To list all available versions (tags) for a plugin, use the `api()` method with the `GET` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/tags.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$tags = $freemius->api('/plugins/123/tags.json', 'GET');

// Loop through the tags
foreach ($tags->tags as $tag) {
    echo 'Version: ' . $tag->version . '<br>';
    // ... other tag details
}
```

**Deploying New Versions:**

To deploy a new version of your plugin, you can upload a ZIP file using the `api()` method with the `POST` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/tags.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/tags.json', 'POST', [], [
    'file' => '/path/to/my-plugin.zip',
    'data' => json_encode(['add_contributor' => true]),
]);

// Get the new tag ID
$tagId = $response->id;

echo 'New version deployed with tag ID: ' . $tagId;
```

**Downloading Specific Versions:**

You can download a specific version of your plugin using the `getSignedUrl()` method. This method is available regardless of the SDK's scope.

**Example:**

```php
$downloadUrl = $freemius->getSignedUrl('/plugins/123/tags/5.zip', true); // true for premium version

// Redirect the user to the download URL
header('Location: ' . $downloadUrl);
exit;
```

**Updating Version Details:**

To update details about a specific version, use the `api()` method with the `PUT` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/tags/{tag_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/tags/5.json', 'PUT', [
    'release_mode' => 'released', // Change release mode to 'released'
]);

// ... check response for success
```

**Deleting Versions:**

To delete a specific version, use the `api()` method with the `DELETE` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/tags/{tag_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/tags/5.json', 'DELETE');

// ... check response for success
```

### 2. Working with Users

This section covers how to manage user accounts associated with your Freemius plugins.

#### Retrieving User Information

To retrieve information about a specific user, you'll use the `api()` method with the `GET` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/users/{user_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
// Assuming $freemius is initialized with the 'developer' scope
$user = $freemius->api('/plugins/678/users/901.json', 'GET');

// Access user properties
echo 'User ID: ' . $user->id . '<br>';
echo 'Email: ' . $user->email . '<br>';
echo 'First Name: ' . $user->first . '<br>';
echo 'Last Name: ' . $user->last . '<br>';
// ... other user details
```

**Filtering User Data:**

You can filter the list of users returned by the `/developers/{developer_id}/plugins/{plugin_id}/users.json` endpoint using various parameters. This allows you to retrieve only the users that meet specific criteria.

**Example: Retrieving Users by Email:**

```php
$users = $freemius->api('/plugins/678/users.json', 'GET', [
    'email' => 'john.doe@example.com',
]);

// ... process the filtered users
```

**Example: Retrieving Active Users:**

```php
$users = $freemius->api('/plugins/678/users.json', 'GET', [
    'filter' => 'active',
]);

// ... process the filtered users
```

**Available Filters:**

- `email`: Filter by email address.
- `filter`: Filter by user status (e.g., `all`, `active`, `never_paid`, `paid`, `paying`).
- `search`: Search by user ID, email, or name.

**Response Structure:**

The response from the user endpoints will be a JSON object containing either a single user object (when retrieving a specific user) or a `users` array (when retrieving a list of users). Each user object has the following properties:

| Property        | Description                                                                                                                                         |
|-----------------|-----------------------------------------------------------------------------------------------------------------------------------------------------|
| `id`            | The unique ID of the user.                                                                                                                         |
| `email`          | The email address of the user.                                                                                                                      |
| `first`         | The first name of the user.                                                                                                                         |
| `last`          | The last name of the user.                                                                                                                          |
| `public_key`    | The public API key for the user.                                                                                                                    |
| `secret_key`    | The secret API key for the user. **Important:** Keep this key secret!                                                                                |
| `is_verified`  | Indicates whether the user's email address has been verified.                                                                                      |
| `picture`      | The URL of the user's profile picture.                                                                                                              |
| `created`       | The date and time the user account was created.                                                                                                      |
| `updated`       | The date and time the user account was last updated.                                                                                                 |
| `gross`         | The total gross revenue generated by the user across all plugins.                                                                                 |

#### Creating Users

To create a new user account, use the `api()` method with the `POST` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/users.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/678/users.json', 'POST', [
    'email'    => 'newuser@example.com',
    'password' => 'SecurePassword123',
    'first'    => 'New',
    'last'     => 'User',
]);

// Check if the user creation was successful
if (isset($response->id)) {
    echo 'User created successfully with ID: ' . $response->id;
} else {
    echo 'Error creating user.';
}
```

**Parameters:**

- `email`: (Required) The email address of the new user.
- `password`: (Required) The password for the new user.
- `first`: (Optional) The first name of the new user.
- `last`: (Optional) The last name of the new user.
- `ip`: (Optional) The user's IP address.
- `picture`: (Optional) The URL of the user's profile picture.
- `is_verified`: (Optional) Whether the user's email is already verified (defaults to `false`).
- `after_email_confirm_url`: (Optional) The URL to redirect the user to after email confirmation.
- `send_verification_email`: (Optional) Whether to send a verification email (defaults to `true`).

**Response:**

If the user creation is successful, the response will contain the newly created user object with all its properties.

#### Updating Users

To update user information, use the `api()` method with the `PUT` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/users/{user_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/678/users/901.json', 'PUT', [
    'email'       => 'updated.email@example.com',
    'first'       => 'Updated',
    'last'        => 'Name',
    'is_verified' => true,
]);

// Check if the user update was successful
if (isset($response->id)) {
    echo 'User updated successfully!';
} else {
    echo 'Error updating user.';
}
```

**Parameters:**

- `email`: (Optional) The updated email address of the user.
- `password`: (Optional) The user's current password (required if changing the password).
- `new_password`: (Optional) The user's new password.
- `password_confirm`: (Optional) Confirmation of the new password.
- `first`: (Optional) The updated first name of the user.
- `last`: (Optional) The updated last name of the user.
- `picture`: (Optional) The updated URL of the user's profile picture.
- `is_verified`: (Optional) Whether the user's email is verified.
- `after_email_confirm_url`: (Optional) The URL to redirect the user to after email confirmation.

**Response:**

If the user update is successful, the response will contain the updated user object.

##### Deleting Users

To delete a user account, use the `api()` method with the `DELETE` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/users/{user_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/678/users/901.json', 'DELETE');

// Check if the user deletion was successful
if ($response === '') { // Successful DELETE requests return an empty response
    echo 'User deleted successfully!';
} else {
    echo 'Error deleting user.';
}
```

**Important Note:**

- Deleting a user account is a permanent action and will remove all associated data, including licenses and installs.


### 3. Managing Installs (Sites)

This section covers how to manage plugin installations on individual websites (referred to as "installs" in Freemius) using the SDK.

#### Retrieving Install Information

To retrieve information about a specific install, use the `api()` method with the `GET` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
// Assuming $freemius is initialized with the 'developer' scope
$install = $freemius->api('/plugins/123/installs/456.json', 'GET');

// Access install properties
echo 'Install ID: ' . $install->id . '<br>';
echo 'Site URL: ' . $install->url . '<br>';
echo 'Plugin Version: ' . $install->version . '<br>';
echo 'Plan ID: ' . $install->plan_id . '<br>';
// ... other install details
```

**Filtering Install Data:**

You can filter the list of installs returned by the `/developers/{developer_id}/plugins/{plugin_id}/installs.json` endpoint using various parameters. This allows you to retrieve only the installs that meet specific criteria.

**Example: Retrieving Installs for a Specific User:**

```php
$installs = $freemius->api('/plugins/123/installs.json', 'GET', [
    'user_id' => 789,
]);

// ... process the filtered installs
```

**Example: Retrieving Active Premium Installs:**

```php
$installs = $freemius->api('/plugins/123/installs.json', 'GET', [
    'filter' => 'active_premium',
]);

// ... process the filtered installs
```

**Available Filters:**

- `user_id`: Filter by the ID of the user who owns the install.
- `filter`: Filter by install status (e.g., `all`, `active`, `inactive`, `trial`, `paying`, `uninstalled`, `active_premium`, `active_free`).
- `search`: Search by domain, site ID, or uninstall reason info.
- `reason_id`: Filter by uninstall reason ID.

**Response Structure:**

The response from the install endpoints will be a JSON object containing either a single install object (when retrieving a specific install) or an `installs` array (when retrieving a list of installs). Each install object has the following properties:

| Property                 | Description                                                                                                                                                              |
|--------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `id`                    | The unique ID of the install.                                                                                                                                            |
| `plugin_id`             | The ID of the plugin associated with the install.                                                                                                                         |
| `user_id`               | The ID of the user who owns the install.                                                                                                                                  |
| `public_key`            | The public API key for the install.                                                                                                                                       |
| `secret_key`            | The secret API key for the install. **Important:** Keep this key secret!                                                                                                    |
| `url`                   | The URL of the website where the plugin is installed.                                                                                                                    |
| `title`                 | The title of the website.                                                                                                                                                 |
| `version`               | The version of the plugin installed on the website.                                                                                                                     |
| `plan_id`               | The ID of the currently active plan for the install.                                                                                                                     |
| `license_id`            | The ID of the license associated with the install (if applicable).                                                                                                       |
| `trial_plan_id`         | The ID of the trial plan (if applicable).                                                                                                                                 |
| `trial_ends`            | The date and time when the trial period ends (if applicable).                                                                                                            |
| `subscription_id`        | The ID of the subscription associated with the install (if applicable).                                                                                                  |
| `gross`                  | The total gross revenue generated by the install.                                                                                                                         |
| `country_code`          | The two-letter country code of the install.                                                                                                                                |
| `is_active`             | Indicates whether the plugin is currently active on the website.                                                                                                        |
| `is_uninstalled`         | Indicates whether the plugin has been uninstalled from the website.                                                                                                      |
| `is_locked`             | Indicates whether the install is locked (preventing further actions).                                                                                                    |
| `upgraded`               | The date and time the install was last upgraded to a paid plan.                                                                                                         |
| `created`                | The date and time the install was created.                                                                                                                                 |
| `updated`                | The date and time the install was last updated.                                                                                                                            |

#### Creating Installs

To register a new plugin install, use the `api()` method with the `POST` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/users/{user_id}/installs.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/users/789/installs.json', 'POST', [
    'url'       => 'https://example.com',
    'title'     => 'Example Website',
    'version'   => '1.2.3',
    'plan_id'   => 456, // Optional: If the install starts with a paid plan
    // ... other install details
]);

// Check if the install creation was successful
if (isset($response->id)) {
    echo 'Install created successfully with ID: ' . $response->id;
} else {
    echo 'Error creating install.';
}
```

**Parameters:**

- `url`: (Required) The URL of the website where the plugin is installed.
- `version`: (Required) The version of the plugin installed on the website.
- `title`: (Optional) The title of the website.
- `uid`: (Optional) The unique identifier generated by the Freemius SDK.
- `plan_id`: (Optional) The ID of the active plan for the install.
- `trial_plan_id`: (Optional) The ID of the trial plan (if applicable).
- `trial_ends`: (Optional) The date and time when the trial period ends.
- `language`: (Optional) The language of the website (e.g., 'en-US').
- `charset`: (Optional) The character encoding of the website (e.g., 'UTF-8').
- `platform_version`: (Optional) The version of the platform (e.g., WordPress version).
- `programming_language_version`: (Optional) The version of the programming language used by the platform (e.g., PHP version).

**Response:**

If the install creation is successful, the response will contain the newly created install object with all its properties.

#### Updating Installs

To update install information, use the `api()` method with the `PUT` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}.json` endpoint. The scope required for this endpoint depends on the properties you want to update:

- **`install` scope:** You can update the `url`, `title`, and `version` properties.
- **`developer` or `app` scope:** You can update all other properties.

**Example:**

```php
$response = $freemius->api('/plugins/123/installs/456.json', 'PUT', [
    'version' => '1.2.4', // Update the plugin version
    'plan_id' => 789,    // Update the active plan
    // ... other install details
]);

// Check if the update was successful
if (isset($response->id)) {
    echo 'Install updated successfully!';
} else {
    echo 'Error updating install.';
}
```

**Parameters:**

Refer to the Freemius API documentation for a complete list of updatable properties and their respective scope requirements.

**Response:**

If the install update is successful, the response will contain the updated install object.

#### Deleting Installs (Uninstalling)

To delete an install (uninstall the plugin from a website), use the `api()` method with the `DELETE` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}.json` endpoint. This endpoint requires the `developer` or `app` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/installs/456.json', 'DELETE');

// Check if the deletion was successful
if ($response === '') { // Successful DELETE requests return an empty response
    echo 'Install deleted successfully!';
} else {
    echo 'Error deleting install.';
}
```

**Important Note:**

- Deleting an install will remove all associated data for that specific installation, including licenses and subscriptions.

#### Working with Install Plans

**Retrieving Install Plans:**

To retrieve the available plans for an install, use the `api()` method with the `GET` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}/plans.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$plans = $freemius->api('/plugins/123/installs/456/plans.json', 'GET');

// Loop through the plans
foreach ($plans->plans as $plan) {
    echo 'Plan ID: ' . $plan->id . '<br>';
    echo 'Title: ' . $plan->title . '<br>';
    // ... other plan details
}
```

**Downgrading Plans:**

To downgrade an install to the plugin's default plan, use the `api()` method with the `PUT` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}/downgrade.json` endpoint. This endpoint requires the `developer` or `app` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/installs/456/downgrade.json', 'PUT');

// ... check response for success
```

**Starting and Canceling Trials:**

To start a trial for an install on a specific plan, use the `api()` method with the `POST` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}/plans/{plan_id}/trials.json` endpoint. This endpoint requires the `developer` or `app` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/installs/456/plans/789/trials.json', 'POST', [
    'trial_ends' => '2024-12-31 23:59:59', // Optional: Specify the trial end date
]);

// ... check response for success
```

To cancel an active trial for an install, use the `api()` method with the `DELETE` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}/trials.json` endpoint. This endpoint requires the `developer` or `app` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/installs/456/trials.json', 'DELETE');

// ... check response for success
```


### 4. Working with Licenses

This section covers how to manage licenses associated with your Freemius plugins using the SDK.

#### Retrieving License Information

To retrieve information about a specific license, use the `api()` method with the `GET` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/licenses/{license_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
// Assuming $freemius is initialized with the 'developer' scope
$license = $freemius->api('/plugins/123/licenses/456.json', 'GET');

// Access license properties
echo 'License ID: ' . $license->id . '<br>';
echo 'User ID: ' . $license->user_id . '<br>';
echo 'Plan ID: ' . $license->plan_id . '<br>';
echo 'Quota: ' . $license->quota . '<br>';
echo 'Expiration Date: ' . $license->expiration . '<br>';
// ... other license details
```

**Filtering License Data:**

You can filter the list of licenses returned by the `/developers/{developer_id}/plugins/{plugin_id}/licenses.json` endpoint using various parameters. This allows you to retrieve only the licenses that meet specific criteria.

**Example: Retrieving Active Licenses:**

```php
$licenses = $freemius->api('/plugins/123/licenses.json', 'GET', [
    'filter' => 'active',
]);

// ... process the filtered licenses
```

**Example: Searching for a License by ID:**

```php
$licenses = $freemius->api('/plugins/123/licenses.json', 'GET', [
    'search' => '456', // Search for license ID 456
]);

// ... process the filtered licenses
```

**Available Filters:**

- `filter`: Filter by license status (e.g., `all`, `active`, `cancelled`, `expired`, `abandoned`).
- `search`: Search by license ID.

**Response Structure:**

The response from the license endpoints will be a JSON object containing either a single license object (when retrieving a specific license) or a `licenses` array (when retrieving a list of licenses). Each license object has the following properties:

| Property            | Description                                                                                                                                                               |
|---------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `id`               | The unique ID of the license.                                                                                                                                             |
| `plugin_id`        | The ID of the plugin associated with the license.                                                                                                                          |
| `user_id`          | The ID of the user who owns the license.                                                                                                                                   |
| `plan_id`          | The ID of the plan associated with the license.                                                                                                                           |
| `pricing_id`       | The ID of the pricing option associated with the license.                                                                                                                 |
| `quota`            | The number of installs allowed for the license (for multi-site licensing).                                                                                                |
| `activated`        | The number of production installs currently activated with the license.                                                                                                  |
| `activated_local`  | The number of localhost installs currently activated with the license.                                                                                                   |
| `expiration`       | The date and time when the license expires.                                                                                                                              |
| `is_free_localhost`| Indicates whether the license can be used on unlimited localhost installs.                                                                                              |
| `is_block_features`| Indicates whether the plugin's features are blocked after the license expires. If `false`, only updates and support are blocked.                                          |
| `is_cancelled`     | Indicates whether the license has been cancelled.                                                                                                                         |
| `created`           | The date and time the license was created.                                                                                                                                |
| `updated`           | The date and time the license was last updated.                                                                                                                           |

#### Creating Licenses

The Freemius PHP SDK currently **does not directly support creating new licenses**. License creation is typically handled through the Freemius checkout process or through the Freemius API directly. 

If you need to create licenses programmatically, you can use the Freemius API endpoints documented in the Freemius API reference. You can still use the `Freemius` class's `api()` method to make requests to these endpoints.

#### Updating Licenses

To update license information, use the `api()` method with the `PUT` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/licenses/{license_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/licenses/456.json', 'PUT', [
    'quota'            => 10, // Update the license quota
    'expiration'       => '2025-12-31 23:59:59', // Update the expiration date
    'is_free_localhost' => false, // Disable unlimited localhost installs
]);

// Check if the update was successful
if (isset($response->id)) {
    echo 'License updated successfully!';
} else {
    echo 'Error updating license.';
}
```

**Parameters:**

- `quota`: (Optional) The new quota for the license.
- `activated`: (Optional) The new number of activated installs (use with caution).
- `activated_local`: (Optional) The new number of activated localhost installs (use with caution).
- `expiration`: (Optional) The new expiration date and time for the license.
- `is_block_features`: (Optional) Whether to block features after license expiry.
- `is_free_localhost`: (Optional) Whether to allow unlimited localhost installs.

**Response:**

If the license update is successful, the response will contain the updated license object.

#### Deleting Licenses

To cancel and delete a license, use the `api()` method with the `DELETE` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/licenses/{license_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/licenses/456.json', 'DELETE');

// Check if the deletion was successful
if ($response === '') { // Successful DELETE requests return an empty response
    echo 'License deleted successfully!';
} else {
    echo 'Error deleting license.';
}
```

**Important Note:**

- Deleting a license is a permanent action.

#### Activating and Deactivating Licenses on Installs

The Freemius PHP SDK provides methods for activating and deactivating licenses on specific installs.

**Activating a License:**

To activate a license on an install, use the `api()` method with the `PUT` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}/licenses/{license_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/installs/456/licenses/789.json', 'PUT');

// ... check response for success
```

**Deactivating a License:**

To deactivate a license from an install, use the `api()` method with the `DELETE` HTTP method and the same endpoint as above.

**Example:**

```php
$response = $freemius->api('/plugins/123/installs/456/licenses/789.json', 'DELETE');

// ... check response for success
```



### 5. Managing Subscriptions and Payments

This section covers how to manage subscriptions and payments associated with your Freemius plugins using the SDK.

#### Retrieving Subscription Information

To retrieve information about a specific subscription, use the `api()` method with the `GET` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/subscriptions/{subscription_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
// Assuming $freemius is initialized with the 'developer' scope
$subscription = $freemius->api('/plugins/123/subscriptions/456.json', 'GET');

// Access subscription properties
echo 'Subscription ID: ' . $subscription->id . '<br>';
echo 'Install ID: ' . $subscription->install_id . '<br>';
echo 'User ID: ' . $subscription->user_id . '<br>';
echo 'Plan ID: ' . $subscription->plan_id . '<br>';
echo 'Billing Cycle: ' . $subscription->billing_cycle . ' months<br>';
echo 'Next Payment Date: ' . $subscription->next_payment . '<br>';
// ... other subscription details
```

**Filtering Subscription Data:**

You can filter the list of subscriptions returned by the `/developers/{developer_id}/plugins/{plugin_id}/subscriptions.json` endpoint using various parameters:

**Example: Retrieving Active Annual Subscriptions:**

```php
$subscriptions = $freemius->api('/plugins/123/subscriptions.json', 'GET', [
    'billing_cycle' => 12, // Filter by annual billing cycle
    'filter'        => 'active', // Filter by active subscriptions
]);

// ... process the filtered subscriptions
```

**Available Filters:**

- `billing_cycle`: Filter by billing cycle (in months, e.g., `1` for monthly, `12` for annual).
- `gateway`: Filter by payment gateway (e.g., `all`, `paypal`, `stripe`).
- `plan_id`: Filter by plan ID.
- `filter`: Filter by subscription status (e.g., `all`, `active`, `cancelled`).

**Response Structure:**

The response from the subscription endpoints will be a JSON object containing either a single subscription object (when retrieving a specific subscription) or a `subscriptions` array (when retrieving a list of subscriptions). Each subscription object has the following properties:

| Property              | Description                                                                                                                                                              |
|-----------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `id`                  | The unique ID of the subscription.                                                                                                                                            |
| `install_id`          | The ID of the install associated with the subscription.                                                                                                                  |
| `plugin_id`            | The ID of the plugin associated with the subscription.                                                                                                                         |
| `user_id`              | The ID of the user who owns the subscription.                                                                                                                              |
| `coupon_id`           | The ID of the coupon applied to the subscription (if applicable).                                                                                                        |
| `total_gross`         | The total gross revenue generated by the subscription.                                                                                                                    |
| `amount_per_cycle`    | The amount charged per billing cycle.                                                                                                                                      |
| `billing_cycle`       | The billing cycle in months (e.g., `1` for monthly, `12` for annual).                                                                                                     |
| `outstanding_balance`| The outstanding balance on the subscription.                                                                                                                            |
| `failed_payments`     | The number of failed payment attempts for the subscription.                                                                                                              |
| `payment_method`      | The payment method used for the subscription (e.g., `paypal`, `stripe`).                                                                                                |
| `trial_ends`           | The date and time when the trial period ends (if applicable).                                                                                                            |
| `next_payment`        | The date and time of the next scheduled payment.                                                                                                                           |
| `created`             | The date and time the subscription was created.                                                                                                                            |
| `updated`             | The date and time the subscription was last updated.                                                                                                                        |

#### Creating Subscriptions

The Freemius PHP SDK currently **does not directly support creating new subscriptions**. Subscription creation is typically handled through the Freemius checkout process or through the Freemius API directly. 

If you need to create subscriptions programmatically, you can use the Freemius API endpoints documented in the Freemius API reference. You can still use the `Freemius` class's `api()` method to make requests to these endpoints.

#### Canceling Subscriptions

To cancel a subscription, use the `api()` method with the `DELETE` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/subscriptions/{subscription_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/subscriptions/456.json', 'DELETE');

// Check if the cancellation was successful
if ($response === '') { // Successful DELETE requests return an empty response
    echo 'Subscription cancelled successfully!';
} else {
    echo 'Error cancelling subscription.';
}
```

**Important Note:**

- Cancelling a subscription will stop future payments but will not issue a refund for the current billing cycle.

#### Retrieving Payment Information

To retrieve information about payments associated with an install, use the `api()` method with the `GET` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}/payments.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$payments = $freemius->api('/plugins/123/installs/456/payments.json', 'GET');

// Loop through the payments
foreach ($payments->payments as $payment) {
    echo 'Payment ID: ' . $payment->id . '<br>';
    echo 'Gross Amount: ' . $payment->gross . '<br>';
    echo 'Gateway: ' . $payment->gateway . '<br>';
    // ... other payment details
}
```

**Filtering Payment Data:**

You can filter the list of payments using various parameters:

**Example: Retrieving Refunds:**

```php
$refunds = $freemius->api('/plugins/123/installs/456/payments.json', 'GET', [
    'filter' => 'refunds',
]);

// ... process the filtered refunds
```

**Available Filters:**

- `search`: Search by payment ID, external payment ID, or user email.
- `billing_cycle`: Filter by billing cycle (e.g., `1`, `12`, `0` for lifetime).
- `filter`: Filter by payment status (e.g., `all`, `refunds`, `not_refunded`).
- `extended`: Include additional payment details in the response.

**Response Structure:**

The response from the payment endpoints will be a JSON object containing a `payments` array. Each element in the `payments` array represents a payment and has the following properties:

| Property            | Description                                                                                                                                                              |
|---------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `id`               | The unique ID of the payment.                                                                                                                                             |
| `user_id`          | The ID of the user associated with the payment.                                                                                                                          |
| `install_id`       | The ID of the install associated with the payment.                                                                                                                         |
| `subscription_id`   | The ID of the subscription associated with the payment (if applicable).                                                                                                  |
| `plan_id`          | The ID of the plan associated with the payment.                                                                                                                           |
| `license_id`       | The ID of the license associated with the payment (if applicable).                                                                                                       |
| `gross`            | The gross amount of the payment (positive for payments, negative for refunds).                                                                                           |
| `bound_payment_id`| The ID of the payment that this payment is bound to (e.g., a refund bound to the original payment).                                                                      |
| `external_id`     | The ID of the payment in the external payment gateway (e.g., PayPal payment ID).                                                                                          |
| `gateway`          | The payment gateway used for the payment (e.g., `paypal`, `stripe`).                                                                                                    |
| `country_code`     | The two-letter country code of the payment.                                                                                                                                |
| `vat_id`          | The VAT/Tax ID associated with the payment (if applicable).                                                                                                             |
| `vat`             | The VAT amount applied to the payment.                                                                                                                                    |
| `coupon_id`        | The ID of the coupon applied to the payment (if applicable).                                                                                                             |
| `created`          | The date and time the payment was created.                                                                                                                                |
| `updated`          | The date and time the payment was last updated.                                                                                                                           |

#### Refunding Payments

To refund a payment, use the `api()` method with the `DELETE` HTTP method and the `/developers/{developer_id}/plugins/{plugin_id}/payments/{payment_id}.json` endpoint. This endpoint requires the `developer` scope.

**Example:**

```php
$response = $freemius->api('/plugins/123/payments/456.json', 'DELETE');

// Check if the refund was successful
if (isset($response->id)) {
    echo 'Payment refunded successfully!';
} else {
    echo 'Error refunding payment.';
}
```

**Important Notes:**

- Refunding a payment will create a new payment record with a negative `gross` amount, linked to the original payment using the `bound_payment_id` property.
- Make sure you have the necessary permissions and integrations with your payment gateway to process refunds.

