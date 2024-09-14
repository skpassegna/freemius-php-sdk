# Freemius PHP SDK
_Under quick refatoring and editing :-)_
This is an unofficial PHP SDK for seamless integration with the Freemius platform. It provides a convenient way to interact with the Freemius API, enabling you to manage licenses, subscriptions, user accounts, and more.

## Motivation

This project was initiated by Samuel due to challenges encountered with the official Freemius PHP SDK. The main motivations for creating this unofficial SDK are:

- **Composer Autoloading Issues:** The official SDK, after installation, was not easily manageable by Composer's autoloading mechanism. This SDK aims to resolve that by adhering to PSR-4 autoloading standards.
- **Lack of Clear Documentation:** The official SDK's documentation was not structured in a task-oriented or concept-oriented manner, making it difficult to find specific information and understand how to use the SDK effectively. This SDK will strive to provide clear, well-organized documentation that focuses on common use cases and concepts.
- **Lack of Maintenance:** The official SDK appears to be unmaintained, with the last commit being 2 years ago and the initial commit 9 years ago. This SDK will be actively maintained to ensure compatibility with the latest PHP versions and Freemius API changes.

## Features

- **PHP 8.2 Compatibility:** This SDK is designed and tested for compatibility with PHP 8.2, ensuring you can leverage the latest language features and performance improvements.
- **PSR-4 Autoloading:** The SDK adheres to PSR-4 autoloading standards, making it easy to integrate into your projects using Composer.
- **Guzzle Integration:** The SDK utilizes the powerful Guzzle HTTP client for making API requests, providing a modern and efficient way to interact with the Freemius API.
- **Enhanced Security:** The SDK removes the insecure practice of disabling SSL verification, ensuring secure communication with the Freemius API.
- **Comprehensive Error Handling:** The SDK includes a robust exception handling system, providing clear and informative error messages to help you diagnose and resolve issues quickly.
- **Extensive Unit Tests:** The SDK is thoroughly tested with a comprehensive suite of unit tests, ensuring its reliability and stability.
- **Task-Oriented Documentation:** The SDK will be accompanied by clear and well-organized documentation that focuses on common tasks and concepts, making it easy to learn and use.

## Installation

Install the SDK using Composer:

```
composer require skpassegna/freemius-php-sdk
```

## Usage

[Detailed usage instructions and examples are provided in the documentation.](https://github.com/skpassegna/freemius-php-sdk/#)

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request on GitHub.

## License

This SDK is licensed under the GPL-2.0+ license. 