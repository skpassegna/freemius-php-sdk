# Freemius PHP SDK Documentation

## VII. Appendices

### A. API Endpoint Reference

This appendix provides a comprehensive reference of all available API endpoints, their parameters, and expected responses. 

**Important Note:**

This reference is based on the current version of the Freemius API and the functionality supported by the Freemius PHP SDK. The availability and behavior of specific endpoints might change in future Freemius API updates.

**Base URL:**

- Production: `https://api.freemius.com`
- Sandbox: `https://sandbox-api.freemius.com`

**Endpoints:**

**(Please note that this is a partial list. A complete endpoint reference will be added in a future update.)**

**1. Plugins:**

- **GET `/plugins.json`**
    - **Scope:** `developer`, `plugin`
    - **Description:** Retrieves a list of plugins.
    - **Parameters:**
        - `id`: (Optional) Filter by plugin ID.
        - `slug`: (Optional) Filter by plugin slug.
        - `title`: (Optional) Filter by plugin title.
        - `default_plan_id`: (Optional) Filter by default plan ID.
        - `is_off`: (Optional) Filter by active/inactive plugins.
        - `is_only_for_new_installs`: (Optional) Filter by plugins only active for new installs.
        - `installs_limit`: (Optional) Filter by install limit.
        - `accepted_payments`: (Optional) Filter by accepted payment methods.
    - **Response:** A JSON object containing a `plugins` array, where each element represents a plugin.

- **PUT `/plugins/{plugin_id}.json`**
    - **Scope:** `developer`
    - **Description:** Updates plugin information.
    - **Parameters:** (See "Updating Plugin Information" in the "Managing Plugins and Add-ons" section for details.)
    - **Response:** The updated plugin object.

- **DELETE `/plugins/{plugin_id}.json`**
    - **Scope:** `developer`
    - **Description:** Deletes a plugin.
    - **Response:** An empty response on success.

- **PUT `/plugins/{plugin_id}/secret_key.json`**
    - **Scope:** `developer`
    - **Description:** Regenerates the secret key for a plugin.
    - **Response:** A JSON object containing the new `secret_key`.

**2. Users:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/users.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves a list of users.
    - **Parameters:**
        - `email`: (Optional) Filter by email address.
        - `filter`: (Optional) Filter by user status (e.g., `active`, `never_paid`, `paid`, `paying`).
        - `search`: (Optional) Search by user ID, email, or name.
    - **Response:** A JSON object containing a `users` array, where each element represents a user.

- **GET `/developers/{developer_id}/plugins/{plugin_id}/users/{user_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves information about a specific user.
    - **Response:** A JSON object representing the user.

- **POST `/developers/{developer_id}/plugins/{plugin_id}/users.json`**
    - **Scope:** `developer`
    - **Description:** Creates a new user account.
    - **Parameters:** (See "Creating Users" in the "Working with Users" section for details.)
    - **Response:** The newly created user object.

- **PUT `/developers/{developer_id}/plugins/{plugin_id}/users/{user_id}.json`**
    - **Scope:** `developer`
    - **Description:** Updates user information.
    - **Parameters:** (See "Updating Users" in the "Working with Users" section for details.)
    - **Response:** The updated user object.

- **DELETE `/developers/{developer_id}/plugins/{plugin_id}/users/{user_id}.json`**
    - **Scope:** `developer`
    - **Description:** Deletes a user account.
    - **Response:** An empty response on success.

**3. Installs:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/installs.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves a list of installs.
    - **Parameters:**
        - `user_id`: (Optional) Filter by user ID.
        - `filter`: (Optional) Filter by install status (e.g., `active`, `inactive`, `trial`, `paying`, `uninstalled`).
        - `search`: (Optional) Search by domain, site ID, or reason info.
        - `reason_id`: (Optional) Filter by uninstall reason ID.
    - **Response:** A JSON object containing an `installs` array, where each element represents an install.

- **GET `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves information about a specific install.
    - **Response:** A JSON object representing the install.

- **POST `/developers/{developer_id}/plugins/{plugin_id}/users/{user_id}/installs.json`**
    - **Scope:** `developer`
    - **Description:** Creates a new install.
    - **Parameters:** (See "Creating Installs" in the "Managing Installs (Sites)" section for details.)
    - **Response:** The newly created install object.

- **PUT `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}.json`**
    - **Scope:** `install` (for updating `url`, `title`, `version`), `developer` or `app` (for updating other properties)
    - **Description:** Updates install information.
    - **Parameters:** (See "Updating Installs" in the "Managing Installs (Sites)" section for details.)
    - **Response:** The updated install object.

- **DELETE `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}.json`**
    - **Scope:** `developer` or `app`
    - **Description:** Deletes an install (uninstalls the plugin from a website).
    - **Response:** An empty response on success.

**4. Licenses:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/licenses.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves a list of licenses.
    - **Parameters:**
        - `filter`: (Optional) Filter by license status (e.g., `active`, `cancelled`, `expired`).
        - `search`: (Optional) Search by license ID.
    - **Response:** A JSON object containing a `licenses` array, where each element represents a license.

- **GET `/developers/{developer_id}/plugins/{plugin_id}/licenses/{license_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves information about a specific license.
    - **Response:** A JSON object representing the license.

- **PUT `/developers/{developer_id}/plugins/{plugin_id}/licenses/{license_id}.json`**
    - **Scope:** `developer`
    - **Description:** Updates license information.
    - **Parameters:** (See "Updating Licenses" in the "Working with Licenses" section for details.)
    - **Response:** The updated license object.

- **DELETE `/developers/{developer_id}/plugins/{plugin_id}/licenses/{license_id}.json`**
    - **Scope:** `developer`
    - **Description:** Cancels and deletes a license.
    - **Response:** The deleted license object.

**5. Subscriptions:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/subscriptions.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves a list of subscriptions.
    - **Parameters:**
        - `billing_cycle`: (Optional) Filter by billing cycle (e.g., `1`, `12`).
        - `gateway`: (Optional) Filter by payment gateway (e.g., `paypal`, `stripe`).
        - `plan_id`: (Optional) Filter by plan ID.
        - `filter`: (Optional) Filter by subscription status (e.g., `active`, `cancelled`).
    - **Response:** A JSON object containing a `subscriptions` array, where each element represents a subscription.

- **GET `/developers/{developer_id}/plugins/{plugin_id}/subscriptions/{subscription_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves information about a specific subscription.
    - **Response:** A JSON object representing the subscription.

- **DELETE `/developers/{developer_id}/plugins/{plugin_id}/subscriptions/{subscription_id}.json`**
    - **Scope:** `developer`
    - **Description:** Cancels a subscription.
    - **Response:** An empty response on success.

**6. Payments:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/installs/{install_id}/payments.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves a list of payments associated with an install.
    - **Parameters:**
        - `search`: (Optional) Search by payment ID, external payment ID, or user email.
        - `billing_cycle`: (Optional) Filter by billing cycle (e.g., `1`, `12`, `0` for lifetime).
        - `filter`: (Optional) Filter by payment status (e.g., `all`, `refunds`, `not_refunded`).
        - `extended`: (Optional) Include additional payment details in the response.
    - **Response:** A JSON object containing a `payments` array, where each element represents a payment.

- **DELETE `/developers/{developer_id}/plugins/{plugin_id}/payments/{payment_id}.json`**
    - **Scope:** `developer`
    - **Description:** Refunds a payment.
    - **Response:** A JSON object representing the refund payment.

**7. Plans:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/plans.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves a list of plans.
    - **Parameters:**
        - `is_featured`: (Optional) Filter by featured plans.
    - **Response:** A JSON object containing a `plans` array, where each element represents a plan.

- **GET `/developers/{developer_id}/plugins/{plugin_id}/plans/{plan_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves information about a specific plan.
    - **Response:** A JSON object representing the plan.

- **POST `/developers/{developer_id}/plugins/{plugin_id}/plans.json`**
    - **Scope:** `developer`
    - **Description:** Creates a new pricing plan.
    - **Parameters:** (See "Creating Plans" in the "Working with Plans and Features" section for details.)
    - **Response:** The newly created plan object.

- **PUT `/developers/{developer_id}/plugins/{plugin_id}/plans/{plan_id}.json`**
    - **Scope:** `developer`
    - **Description:** Updates plan information.
    - **Parameters:** (See "Updating Plans" in the "Working with Plans and Features" section for details.)
    - **Response:** The updated plan object.

- **DELETE `/developers/{developer_id}/plugins/{plugin_id}/plans/{plan_id}.json`**
    - **Scope:** `developer`
    - **Description:** Deletes a pricing plan.
    - **Response:** An empty response on success.

**8. Features:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/features.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves a list of features.
    - **Parameters:**
        - `plan_id`: (Optional) Filter by plan ID.
    - **Response:** A JSON object containing a `features` array, where each element represents a feature.

- **GET `/developers/{developer_id}/plugins/{plugin_id}/features/{feature_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves information about a specific feature.
    - **Response:** A JSON object representing the feature.

- **POST `/developers/{developer_id}/plugins/{plugin_id}/features.json`**
    - **Scope:** `developer`
    - **Description:** Creates a new feature.
    - **Parameters:** (See "Creating Features" in the "Working with Plans and Features" section for details.)
    - **Response:** The newly created feature object.

- **PUT `/developers/{developer_id}/plugins/{plugin_id}/features/{feature_id}.json`**
    - **Scope:** `developer`
    - **Description:** Updates feature information.
    - **Parameters:** (See "Updating Features" in the "Working with Plans and Features" section for details.)
    - **Response:** The updated feature object.

- **DELETE `/developers/{developer_id}/plugins/{plugin_id}/features/{feature_id}.json`**
    - **Scope:** `developer`
    - **Description:** Deletes a feature.
    - **Response:** An empty response on success.

**9. Coupons:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/coupons.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves a list of coupons.
    - **Parameters:**
        - `count`: (Optional) Limit the number of coupons retrieved.
    - **Response:** A JSON object containing a `coupons` array, where each element represents a coupon.

- **GET `/developers/{developer_id}/plugins/{plugin_id}/coupons/{coupon_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves information about a specific coupon.
    - **Response:** A JSON object representing the coupon.

- **POST `/developers/{developer_id}/plugins/{plugin_id}/coupons.json`**
    - **Scope:** `developer`
    - **Description:** Creates a new coupon.
    - **Parameters:** (See "Creating Coupons" in the "Managing Coupons and Promotions" section for details.)
    - **Response:** The newly created coupon object.

- **PUT `/developers/{developer_id}/plugins/{plugin_id}/coupons/{coupon_id}.json`**
    - **Scope:** `developer`
    - **Description:** Updates coupon information.
    - **Parameters:** (See "Updating Coupons" in the "Managing Coupons and Promotions" section for details.)
    - **Response:** The updated coupon object.

- **DELETE `/developers/{developer_id}/plugins/{plugin_id}/coupons/{coupon_id}.json`**
    - **Scope:** `developer`
    - **Description:** Deletes a coupon.
    - **Response:** An empty response on success.

**10. Emails:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/emails/{email_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves the content of a specific email template.
    - **Parameters:**
        - `email_id`: (Required) The ID of the email template (e.g., `user_register`, `welcome`).
    - **Response:** A JSON object representing the email template.

- **PUT `/developers/{developer_id}/plugins/{plugin_id}/emails/{email_id}.json`**
    - **Scope:** `developer`
    - **Description:** Updates the content of an email template.
    - **Parameters:** (See "Updating Email Templates" in the "Working with Emails" section for details.)
    - **Response:** The updated email template object.

- **GET `/apps/{app_id}/developers/{developer_id}/plugins/{plugin_id}/emails/addresses.json`**
    - **Scope:** `app`
    - **Description:** Retrieves the email address configuration for a plugin.
    - **Response:** A JSON object containing the email address configuration.

- **PUT `/apps/{app_id}/developers/{developer_id}/plugins/{plugin_id}/emails/addresses.json`**
    - **Scope:** `app`
    - **Description:** Updates the email address configuration for a plugin.
    - **Parameters:** (See "Updating Email Addresses" in the "Working with Emails" section for details.)
    - **Response:** The updated email address configuration object.

**11. Events:**

- **GET `/developers/{developer_id}/plugins/{plugin_id}/events.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves a list of events.
    - **Parameters:**
        - `user_id`: (Optional) Filter by user ID.
        - `install_id`: (Optional) Filter by install ID.
        - `type`: (Optional) Filter by event type.
    - **Response:** A JSON object containing an `events` array, where each element represents an event.

- **GET `/developers/{developer_id}/plugins/{plugin_id}/events/{event_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retrieves information about a specific event.
    - **Response:** A JSON object representing the event.

- **PUT `/developers/{developer_id}/plugins/{plugin_id}/events/{event_id}.json`**
    - **Scope:** `developer`
    - **Description:** Retries webhooks for a specific event.
    - **Response:** An empty response on success.

**12. Search:**

- **GET `/developers/{developer_id}/search.json`**
    - **Scope:** `developer`
    - **Description:** Performs a global search for entities.
    - **Parameters:**
        - `query`: (Required) The search term.
    - **Response:** A JSON object containing multiple categories of search results (e.g., `users`, `installs`, `payments`).


### B. Glossary

This glossary defines Freemius-specific terms used throughout the documentation.

- **Add-on:** A premium extension or plugin that enhances the functionality of a parent plugin.
- **API:** Application Programming Interface. A set of rules and specifications that allow different software systems to communicate and interact with each other.
- **Billing Cycle:** The recurring period for which a subscription is billed (e.g., monthly, annual).
- **Checkout Process:** The process by which a customer purchases a Freemius product, including selecting a plan, entering payment information, and completing the transaction.
- **Coupon:** A code that provides a discount on a Freemius product.
- **Developer Dashboard:** The web-based interface where developers manage their Freemius products, licenses, users, and other settings.
- **Endpoint:** A specific URL that represents a resource or function in the Freemius API.
- **Event:** An action or occurrence that happens within the Freemius system, such as a new installation, an upgrade, or a cancellation.
- **Feature:** A specific functionality or capability of a Freemius product.
- **Freemius:** A platform that simplifies the process of selling and managing premium WordPress plugins and themes.
- **Freemius SDK:** A software development kit that provides tools and libraries for interacting with the Freemius API.
- **Gateway:** A payment processing service that handles transactions for Freemius products (e.g., PayPal, Stripe).
- **Install:** A specific instance of a Freemius plugin installed on a website.
- **License:** A unique identifier that grants permission to use a Freemius product.
- **License Key:** A string of characters that represents a license and is used to activate the product.
- **Multi-site Licensing:** A licensing model that allows a single license to be used on multiple websites.
- **Parent Plugin:** The main plugin that an add-on extends or enhances.
- **Plan:** A pricing option for a Freemius product, which can include different features, support levels, and billing cycles.
- **Plugin:** A software component that adds functionality to a WordPress website.
- **Promotion:** A marketing campaign or offer that provides incentives for purchasing a Freemius product.
- **Quota:** The number of installs allowed for a license.
- **Refund:** A reversal of a payment for a Freemius product.
- **Sandbox Environment:** A testing environment where developers can experiment with the Freemius API without affecting live data.
- **Scope:** A set of permissions that define the level of access granted to an API request.
- **Secret Key:** A confidential API key used to authenticate requests to the Freemius API.
- **Signed URL:** A URL that includes a cryptographic signature to verify its authenticity and prevent unauthorized access.
- **Subscription:** A recurring payment for a Freemius product, typically billed on a monthly or annual basis.
- **Theme:** A collection of files that determine the design and layout of a WordPress website.
- **Trial Period:** A limited time during which a customer can use a Freemius product for free before deciding to purchase a subscription.
- **User:** A person who has registered an account with Freemius.
- **Webhook:** A mechanism for receiving real-time notifications from Freemius about events.

This glossary provides a quick reference for understanding the terminology used in the Freemius PHP SDK documentation.