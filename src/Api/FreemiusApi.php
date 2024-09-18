<?php

namespace Freemius\SDK\Api;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Authentication\SignatureAuthenticator;
use Freemius\SDK\Api\Endpoints\CouponsEndpoint;
use Freemius\SDK\Api\Endpoints\EmailsEndpoint;
use Freemius\SDK\Api\Endpoints\EventsEndpoint;
use Freemius\SDK\Api\Endpoints\FeaturesEndpoint;
use Freemius\SDK\Api\Endpoints\InstallsEndpoint;
use Freemius\SDK\Api\Endpoints\LicensesEndpoint;
use Freemius\SDK\Api\Endpoints\PaymentsEndpoint;
use Freemius\SDK\Api\Endpoints\PlansEndpoint;
use Freemius\SDK\Api\Endpoints\PluginsEndpoint;
use Freemius\SDK\Api\Endpoints\SubscriptionsEndpoint;
use Freemius\SDK\Api\Endpoints\UsersEndpoint;
use Freemius\SDK\Http\CurlHttpClient;
use Freemius\SDK\Http\HttpClientInterface;

/**
 * Main API client for interacting with the Freemius API.
 */
class FreemiusApi
{
    private string $scope;
    private int $developerId;
    private string $publicKey;
    private string $secretKey;
    private bool $sandbox;
    private string $baseUrl;

    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;

    /**
     * FreemiusApi constructor.
     *
     * @param string                   $scope        The API scope (e.g., 'developer', 'app', 'user', 'install').
     * @param int                      $developerId  The Freemius developer ID.
     * @param string                   $publicKey    The Freemius public key.
     * @param string                   $secretKey    The Freemius secret key.
     * @param bool                     $sandbox      Whether to use the sandbox API environment.
     * @param HttpClientInterface|null  $httpClient   The HTTP client to use for API requests (optional).
     * @param AuthenticatorInterface|null $authenticator The authenticator to use for API requests (optional).
     */
    public function __construct(
        string $scope,
        int $developerId,
        string $publicKey,
        string $secretKey,
        bool $sandbox = false,
        ?HttpClientInterface $httpClient = null,
        ?AuthenticatorInterface $authenticator = null
    ) {
        $this->scope        = $scope;
        $this->developerId  = $developerId;
        $this->publicKey    = $publicKey;
        $this->secretKey    = $secretKey;
        $this->sandbox      = $sandbox;

        // Load configuration
        $config = require __DIR__ . '/../../config.php';
        $this->baseUrl = $this->sandbox ? $config['API_SANDBOX_BASE_URL'] : $config['API_BASE_URL'];

        $this->httpClient   = $httpClient ?? new CurlHttpClient($this->baseUrl, $this->scope);
        $this->authenticator = $authenticator
            ?? new SignatureAuthenticator($scope, $developerId, $publicKey, $secretKey);
    }

    /**
     * Get the Plugins endpoint.
     *
     * @return PluginsEndpoint
     */
    public function plugins(): PluginsEndpoint
    {
        return new PluginsEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Users endpoint.
     *
     * @return UsersEndpoint
     */
    public function users(): UsersEndpoint
    {
        return new UsersEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Installs endpoint.
     *
     * @return InstallsEndpoint
     */
    public function installs(): InstallsEndpoint
    {
        return new InstallsEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Plans endpoint.
     *
     * @return PlansEndpoint
     */
    public function plans(): PlansEndpoint
    {
        return new PlansEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Features endpoint.
     *
     * @return FeaturesEndpoint
     */
    public function features(): FeaturesEndpoint
    {
        return new FeaturesEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Licenses endpoint.
     *
     * @return LicensesEndpoint
     */
    public function licenses(): LicensesEndpoint
    {
        return new LicensesEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Subscriptions endpoint.
     *
     * @return SubscriptionsEndpoint
     */
    public function subscriptions(): SubscriptionsEndpoint
    {
        return new SubscriptionsEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Payments endpoint.
     *
     * @return PaymentsEndpoint
     */
    public function payments(): PaymentsEndpoint
    {
        return new PaymentsEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Coupons endpoint.
     *
     * @return CouponsEndpoint
     */
    public function coupons(): CouponsEndpoint
    {
        return new CouponsEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Emails endpoint.
     *
     * @return EmailsEndpoint
     */
    public function emails(): EmailsEndpoint
    {
        return new EmailsEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }

    /**
     * Get the Events endpoint.
     *
     * @return EventsEndpoint
     */
    public function events(): EventsEndpoint
    {
        return new EventsEndpoint($this->httpClient, $this->authenticator, $this->developerId, $this->scope);
    }
}