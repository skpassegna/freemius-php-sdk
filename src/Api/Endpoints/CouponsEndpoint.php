<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Coupon;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius coupons.
 */
class CouponsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;
    private string $scope;

    /**
     * CouponsEndpoint constructor.
     *
     * @param HttpClientInterface   $httpClient   The HTTP client to use for API requests.
     * @param AuthenticatorInterface $authenticator The authenticator to use for API requests.
     * @param int                    $developerId  The Freemius developer ID.
     * @param string                 $scope        The API scope.
     */
    public function __construct(
        HttpClientInterface $httpClient,
        AuthenticatorInterface $authenticator,
        int $developerId,
        string $scope
    ) {
        $this->httpClient   = $httpClient;
        $this->authenticator = $authenticator;
        $this->developerId  = $developerId;
        $this->scope        = $scope;
    }

    /**
     * Retrieve a list of coupons for a plugin.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $params   Optional query parameters (e.g., 'code', 'plan_id', 'pricing_id', 'status', 'fields', 'count').
     *
     * @return Coupon[] An array of Coupon entities.
     * @throws ApiException If the API request fails.
     */
    public function getCoupons(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/coupons.json',
            $this->scope,
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        if (!isset($response['coupons']) || !is_array($response['coupons'])) {
            throw new ApiException($response, 'Invalid API response: missing coupons data.');
        }

        $coupons = [];
        foreach ($response['coupons'] as $couponData) {
            $coupons[] = new Coupon(
                $couponData['id'],
                $couponData['plugin_id'],
                $couponData['code'],
                $couponData['discount'],
                $couponData['type'],
                $couponData['plan_id'] ?? null,
                $couponData['pricing_id'] ?? null,
                $couponData['redemptions'],
                $couponData['max_redemptions'] ?? null,
                $couponData['expiry'],
                $couponData['status'],
                $couponData['created'],
                $couponData['updated']
            );
        }

        return $coupons;
    }

    /**
     * Retrieve a specific coupon.
     *
     * @param int   $pluginId The plugin ID.
     * @param int   $couponId The coupon ID.
     * @param array $params   Optional query parameters (e.g., 'fields').
     *
     * @return Coupon The Coupon entity.
     * @throws ApiException If the API request fails.
     */
    public function getCoupon(int $pluginId, int $couponId, array $params = []): Coupon
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/coupons/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $couponId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return new Coupon(
            $response['id'],
            $response['plugin_id'],
            $response['code'],
            $response['discount'],
            $response['type'],
            $response['plan_id'] ?? null,
            $response['pricing_id'] ?? null,
            $response['redemptions'],
            $response['max_redemptions'] ?? null,
            $response['expiry'],
            $response['status'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Create a new coupon.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $data     The coupon data.
     *
     * @return Coupon The created Coupon entity.
     * @throws ApiException If the API request fails.
     */
    public function createCoupon(int $pluginId, array $data): Coupon
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/coupons.json',
            $this->scope,
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->post(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('POST', $url, $data)
        );

        return new Coupon(
            $response['id'],
            $response['plugin_id'],
            $response['code'],
            $response['discount'],
            $response['type'],
            $response['plan_id'] ?? null,
            $response['pricing_id'] ?? null,
            $response['redemptions'],
            $response['max_redemptions'] ?? null,
            $response['expiry'],
            $response['status'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Update an existing coupon.
     *
     * @param int   $pluginId The plugin ID.
     * @param int   $couponId The coupon ID.
     * @param array $data     The coupon data to update.
     *
     * @return Coupon The updated Coupon entity.
     * @throws ApiException If the API request fails.
     */
    public function updateCoupon(int $pluginId, int $couponId, array $data): Coupon
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/coupons/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $couponId
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return new Coupon(
            $response['id'],
            $response['plugin_id'],
            $response['code'],
            $response['discount'],
            $response['type'],
            $response['plan_id'] ?? null,
            $response['pricing_id'] ?? null,
            $response['redemptions'],
            $response['max_redemptions'] ?? null,
            $response['expiry'],
            $response['status'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Delete a coupon.
     *
     * @param int $pluginId The plugin ID.
     * @param int $couponId The coupon ID.
     *
     * @throws ApiException If the API request fails.
     */
    public function deleteCoupon(int $pluginId, int $couponId): void
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/coupons/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $couponId
        );

        $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );
    }
}