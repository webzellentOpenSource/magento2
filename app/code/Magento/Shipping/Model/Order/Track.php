<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Shipping\Model\Order;

use Magento\Framework\Api\AttributeValueFactory;

/**
 * @method \Magento\Sales\Model\Resource\Order\Shipment\Track _getResource()
 * @method \Magento\Sales\Model\Resource\Order\Shipment\Track getResource()
 * @method int getParentId()
 * @method float getWeight()
 * @method float getQty()
 * @method int getOrderId()
 * @method string getDescription()
 * @method string getTitle()
 * @method string getCarrierCode()
 * @method string getCreatedAt()
 * @method \Magento\Sales\Model\Order\Shipment\Track setCreatedAt(string $value)
 * @method string getUpdatedAt()
 *
 */
class Track extends \Magento\Sales\Model\Order\Shipment\Track
{
    /**
     * @var \Magento\Shipping\Model\CarrierFactory
     */
    protected $_carrierFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\MetadataServiceInterface $metadataService
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory
     * @param \Magento\Shipping\Model\CarrierFactory $carrierFactory
     * @param \Magento\Framework\Model\Resource\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\Db $resourceCollection
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\MetadataServiceInterface $metadataService,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory,
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        \Magento\Framework\Model\Resource\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\Db $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $metadataService,
            $customAttributeFactory,
            $localeDate,
            $dateTime,
            $storeManager,
            $shipmentFactory,
            $resource,
            $resourceCollection,
            $data
        );
        $this->_carrierFactory = $carrierFactory;
    }

    /**
     * Retrieve detail for shipment track
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getNumberDetail()
    {
        $carrierInstance = $this->_carrierFactory->create($this->getCarrierCode());
        if (!$carrierInstance) {
            $custom = [];
            $custom['title'] = $this->getTitle();
            $custom['number'] = $this->getTrackNumber();
            return $custom;
        } else {
            $carrierInstance->setStore($this->getStore());
        }

        $trackingInfo = $carrierInstance->getTrackingInfo($this->getNumber());
        if (!$trackingInfo) {
            return __('No detail for number "%1"', $this->getNumber());
        }

        return $trackingInfo;
    }
}
