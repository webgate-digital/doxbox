<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;

class CartService
{
    private $_productRepository;
    private $_cartRepository;

    public function __construct(ProductRepository $productRepository, CartRepository $cartRepository)
    {
        $this->_productRepository = $productRepository;
        $this->_cartRepository = $cartRepository;
    }

    /**
     * @param string $uuid
     * @param int $quantity
     * @return bool|array
     * @throws \App\Exceptions\UnauthorizedException
     * @throws \App\Exceptions\ValidationException
     */
    public function add(string $uuid, int $quantity): bool|array
    {
        try {
            $product = $this->_productRepository->availability(locale(), session()->get('currency'), $uuid)['item'];
        } catch (NotFoundException $e) {
            return false;
        }

        if ($product['count'] < ($this->getProductCount($product['uuid']) + $quantity)) {
            if (!$product['is_available_for_order']) {
                return false;
            }
        }

        for ($i = 0; $i < $quantity; $i++) {
            session()->push('cart', $product['uuid']);
        }

        // Return product data needed for Google Tag Manager
        return $product;
    }

    public function addMultipack(string $uuid, int $quantity, array $options): bool
    {
        try {
            $product = $this->_productRepository->availability(locale(), session()->get('currency'), $uuid)['item'];
        } catch (NotFoundException $e) {
            return false;
        }

        if ($product['count'] < ($this->getProductCount($product['uuid']) + $quantity)) {
            if (!$product['is_available_for_order']) {
                return false;
            }
        }

        foreach ($product['options'] as $productOption) {
            if (!array_key_exists($productOption['uuid'], $options)) {
                return false;
            }

            if (!$options[$productOption['uuid']]['value']) {
                return false;
            }

            if (!in_array($options[$productOption['uuid']]['value'], array_column($productOption['products'], 'uuid'), true)) {
                return false;
            }

            $uuidToSearch = $options[$productOption['uuid']]['value'];
            $originalProductKey = array_search($uuidToSearch, array_column($productOption['products'], 'uuid'), true);

            if ($originalProductKey === false) {
                return false;
            }

            $originalProduct = $productOption['products'][$originalProductKey];
            if ($originalProduct['count'] < ($this->getProductCount($originalProduct['uuid']) + $quantity)) {
                if (!$originalProduct['is_available_for_order']) {
                    return false;
                }
            }
        }

        foreach ($options as $optionUuid => $value) {
            for ($i = 0; $i < $quantity; $i++) {
                session()->push('multipack.' . $uuid . '.' . $optionUuid, $value['value']);
            }
        }

        for ($i = 0; $i < $quantity; $i++) {
            session()->push('cart', $uuid);
        }

        return true;
    }

    /**
     * @param $uuid
     * @return int|mixed
     */
    public function getProductCount($uuid)
    {
        $cart = session()->get('cart', []);
        $cartCount = array_count_values($cart)[$uuid] ?? 0;

        $multipack = session()->get('multipack', []);
        $multipackCount = 0;

        foreach ($multipack as $products) {
            foreach ($products as $uuids) {
                foreach ($uuids as $productUuid) {
                    if ($productUuid === $uuid) {
                        $multipackCount++;
                    }
                }
            }
        }

        return $cartCount + $multipackCount;
    }

    /**
     * @return array
     */
    public function update(string $locale, string $currency, array $cart, array $multipack, string $voucher = null, string $shippingCountry = null, string $shipping = null, string $payment = null, array $checkoutSupport = [], array $variants = []): array
    {
        if (!count($cart) && !count($variants)) {
            return [
                'count' => 0
            ];
        }

        $cart = $this->_cartRepository->list($locale, $currency, $cart, $multipack, $voucher, $shippingCountry, $shipping, $payment, $checkoutSupport, $variants);
        $cart['count'] = count(session()->get('cart', [])) + count(session()->get('variants', []));

        return $cart;
    }

    public function remove($uuid)
    {
        $cart = session()->get('cart', []);

        if (($key = array_search($uuid, $cart)) !== false) {
            unset($cart[$key]);
        }

        session()->put('cart', $cart);
    }

    public function delete($uuid)
    {
        $cart = session()->get('cart', []);

        $cart = array_values(array_diff($cart, [$uuid]));

        session()->put('cart', $cart);
        session()->forget('multipack.' . $uuid);
    }

    public function addVariant(string $uuid, int $quantity): mixed
    {
        try {
            $variant = $this->_productRepository->variantAvailability(locale(), session()->get('currency'), $uuid)['item'];
        } catch (NotFoundException $e) {
            return false;
        }

        // If variant doesn't have enough stock and it's not available for order, return false
        if ($variant['count'] < ($this->getVariantCount($variant['uuid']) + $quantity) && !$variant['is_available_for_order']) {
            return false;
        }

        for ($i = 0; $i < $quantity; $i++) {
            session()->push('variants', $variant['uuid']);
        }

        return $variant;
    }

    public function getVariantCount($uuid)
    {
        $cart = session()->get('variants', []);
        $cartCount = array_count_values($cart)[$uuid] ?? 0;

        return $cartCount;
    }

    public function removeVariant($uuid)
    {
        $variants = session()->get('variants', []);

        if (($key = array_search($uuid, $variants)) !== false) {
            unset($variants[$key]);
        }

        session()->put('variants', $variants);
    }

    public function deleteVariant($uuid)
    {
        $variants = session()->get('variants', []);

        $variants = array_values(array_diff($variants, [$uuid]));

        session()->put('variants', $variants);
    }
}
