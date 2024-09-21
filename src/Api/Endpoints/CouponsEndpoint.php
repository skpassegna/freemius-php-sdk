<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Coupon;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius coupons.
 */
class CouponsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * CouponsEndpoint constructor.
     *
     * @param HttpClientInterface $httpClient
     * @param AuthenticatorInterface $authenticator
     * @param Scope $scope
     * @param int $scopeId
     * @param string $apiVersion
     */
    public function __construct(
        HttpClientInterface $httpClient,
        AuthenticatorInterface $authenticator,
        Scope $scope,
        int $scopeId,
        string $apiVersion
    ) {
        $this->httpClient = $httpClient;
        $this->authenticator = $authenticator;
        $this->scope = $scope;
        $this->scopeId = $scopeId;
        $this->apiVersion = $apiVersion;
    }

    /**
     * Retrieve a list of coupons for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param array $params Optional query parameters (e.g., 'code', 'plan_id', 'pricing_id', 'status', 'fields', 'count').
     *
     * @return Coupon[] An array of Coupon entities.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getCoupons(int $pluginId, array $params = []): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/coupons.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
                $couponData['expiry'] ?? null,
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
     * @param int $pluginId The plugin ID.
     * @param int $couponId The coupon ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return Coupon The Coupon entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getCoupon(int $pluginId, int $couponId, array $params = []): Coupon
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/coupons/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
            $response['expiry'] ?? null,
            $response['status'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Create a new coupon.
     *
     * @param int $pluginId The plugin ID.
     * @param array $data The coupon data.
     *
     * @return Coupon The created Coupon entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function createCoupon(int $pluginId, array $data): Coupon
    {
        $this->validateScope([Scope::DEVELOPER]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/coupons.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
            $response['expiry'] ?? null,
            $response['status'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Update an existing coupon.
     *
     * @param int $pluginId The plugin ID.
     * @param int $couponId The coupon ID.
     * @param array $data The coupon data to update.
     *
     * @return Coupon The updated Coupon entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function updateCoupon(int $pluginId, int $couponId, array $data): Coupon
    {
        $this->validateScope([Scope::DEVELOPER]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/coupons/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
            $response['expiry'] ?? null,
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
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function deleteCoupon(int $pluginId, int $couponId): void
    {
        $this->validateScope([Scope::DEVELOPER]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/coupons/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $couponId
        );

        $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );
    }

    /**
     * Validate the current scope against allowed scopes.
     *
     * @param Scope[] $allowedScopes
     *
     * @throws ApiException If the scope is invalid.
     */
    private function validateScope(array $allowedScopes): void
    {
        if (!in_array($this->scope, $allowedScopes)) {
            throw new ApiException([], 'Invalid scope for this method.');
        }
    }
}