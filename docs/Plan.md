## Freemius PHP SDK Documentation Plan

This document outlines a comprehensive documentation plan for the unofficial Freemius PHP SDK, aiming for clarity, beginner-friendliness, and a task-oriented approach inspired by the Laravel documentation style.

### I. Introduction

1.  **What is Freemius?**
    -   A brief, beginner-friendly explanation of the Freemius platform, its purpose for plugin and theme developers, and the core concepts (licensing, subscriptions, user accounts, etc.).
2.  **What is the Freemius PHP SDK?**
    -   Introduce the SDK as a tool for interacting with the Freemius API from PHP applications.
    -   Explain its purpose, benefits (PHP 8.2, Composer, Guzzle, security), and target audience (PHP developers working with Freemius).
3.  **Why an Unofficial SDK?**
    -   Clearly state the motivations for creating this unofficial SDK:
        -   Composer autoloading issues with the official SDK.
        -   Lack of clear, task-oriented documentation in the official SDK.
        -   Concerns about the maintenance status of the official SDK.

### II. Getting Started

1.  **Installation**
    -   Step-by-step instructions for installing the SDK using Composer.
    -   Include the exact Composer command (`composer require skpassegna/freemius-php-sdk`).
2.  **Basic Configuration**
    -   Explain the core concepts:
        -   **API Scope:**  `app`, `developer`, `user`, `install`, `plugin`, `store` - what each scope represents and when to use it.
        -   **API Credentials:** How to obtain your developer ID, public key, and secret key from the Freemius dashboard.
    -   Provide code examples for initializing the `Freemius` class with different scopes and credentials.

### III. Core Features and Usage

This section will be the heart of the documentation, covering the SDK's functionality in a task-oriented manner.

1.  **Managing Plugins and Add-ons**
    -   **Retrieving Plugin Information:** How to get details about your plugins and add-ons (title, slug, plans, features, etc.).
    -   **Updating Plugin Information:** How to update plugin details (title, default plan, money-back period, etc.).
    -   **Regenerating Secret Keys:** How to regenerate a plugin's secret key.
    -   **Deleting Plugins:** How to delete a plugin (and its associated data).
    -   **Working with Plugin Tags/Versions:**
        -   Listing plugin versions.
        -   Deploying new versions (uploading ZIP files).
        -   Downloading specific versions.
        -   Updating version details (release mode).
        -   Deleting versions.

2.  **Working with Users**
    -   **Retrieving User Information:** How to get user details (email, name, public key, etc.).
    -   **Creating Users:** How to create new user accounts.
    -   **Updating Users:** How to update user information (email, password, etc.).
    -   **Deleting Users:** How to delete user accounts.

3.  **Managing Installs (Sites)**
    -   **Retrieving Install Information:** How to get details about plugin installs (site URL, version, plan, license, etc.).
    -   **Creating Installs:** How to register a new plugin install.
    -   **Updating Installs:** How to update install information (version, plan, license, etc.).
    -   **Deleting Installs:** How to uninstall a plugin from a site.
    -   **Working with Install Plans:**
        -   Retrieving install plans.
        -   Downgrading plans.
        -   Starting and canceling trials.

4.  **Working with Licenses**
    -   **Retrieving License Information:** How to get license details (plan, quota, expiration, etc.).
    -   **Creating Licenses:**  How to generate new licenses.
    -   **Updating Licenses:** How to update license details (quota, expiration, etc.).
    -   **Deleting Licenses:** How to cancel and delete licenses.
    -   **Activating and Deactivating Licenses:** How to activate and deactivate licenses on installs.

5.  **Managing Subscriptions and Payments**
    -   **Retrieving Subscription Information:** How to get subscription details (plan, billing cycle, next payment, etc.).
    -   **Creating Subscriptions:** How to subscribe users to plans.
    -   **Canceling Subscriptions:** How to cancel user subscriptions.
    -   **Retrieving Payment Information:** How to get payment details (gross amount, gateway, etc.).
    -   **Refunding Payments:** How to issue refunds for payments.

6.  **Working with Plans and Features**
    -   **Retrieving Plan Information:** How to get plan details (title, description, pricing, features, etc.).
    -   **Creating Plans:** How to create new pricing plans.
    -   **Updating Plans:** How to update plan details (title, pricing, features, etc.).
    -   **Deleting Plans:** How to delete pricing plans.
    -   **Retrieving Feature Information:** How to get feature details (title, description, etc.).
    -   **Creating Features:** How to create new features.
    -   **Updating Features:** How to update feature details (title, description, etc.).
    -   **Deleting Features:** How to delete features.

7.  **Managing Coupons and Promotions**
    -   **Retrieving Coupon Information:** How to get coupon details (code, discount, plans, etc.).
    -   **Creating Coupons:** How to create new coupons.
    -   **Updating Coupons:** How to update coupon details (code, discount, etc.).
    -   **Deleting Coupons:** How to delete coupons.

8.  **Working with Emails**
    -   **Retrieving Email Templates:** How to get email template content (subject, plain text, HTML).
    -   **Updating Email Templates:** How to customize email templates.
    -   **Managing Email Addresses:** How to set up and manage email addresses for your plugin (general, support, personal, etc.).

9.  **Handling Events**
    -   **Retrieving Events:** How to get a log of events (installations, upgrades, cancellations, etc.).
    -   **Understanding Event Types:** Explain the different types of events and their data.
    -   **Retrying Webhooks:** How to retry failed webhooks for events.

10. **Global Search**
    -   **Searching for Entities:** How to search for users, installs, payments, etc. using a global search.

11. **Error Handling**
    -   **Understanding Exceptions:** Explain the SDK's exception classes (`ApiException`, `InvalidArgumentException`, etc.) and their meanings.
    -   **Handling API Errors:** Provide examples of how to catch and handle exceptions thrown by the SDK.

### IV. Advanced Usage

1.  **Clock Synchronization:** Explain the `findClockDiff()` method and how to synchronize the server clock with the Freemius API server for accurate time-based operations.
2.  **Signed URLs:**  Explain how to generate signed URLs for secure downloads and other actions.
3.  **Customizing HTTP Requests:** Provide information on how to modify Guzzle request options for advanced scenarios (e.g., setting custom headers, timeouts, etc.).

### V. Contributing

-   Encourage contributions and provide guidelines for submitting issues, bug reports, and pull requests.

### VI. License

-   State the license under which the SDK is distributed (GPL-2.0+).

### VII. Appendices

-   **API Endpoint Reference:** A complete reference of all available API endpoints, their parameters, and expected responses. This can be generated automatically from the Freemius API documentation.
-   **Glossary:** Define all Freemius-specific terms used in the documentation for easy reference.

**Key Points:**

-   **Task-Oriented:** The documentation is structured around common tasks developers need to perform when integrating with Freemius.
-   **Beginner-Friendly:**  Explanations are clear and concise, assuming no prior knowledge of the Freemius API.
-   **Comprehensive:** The documentation covers all major features of the SDK.
-   **Well-Organized:** The documentation is logically structured with clear headings, subheadings, and examples.
-   **Consistent with Laravel Documentation:** The documentation style is inspired by the Laravel documentation, providing a familiar and easy-to-navigate experience for PHP developers.

This detailed plan will guide the creation of a comprehensive and user-friendly documentation for the unofficial Freemius PHP SDK, making it easier for developers to integrate Freemius into their PHP applications. 
