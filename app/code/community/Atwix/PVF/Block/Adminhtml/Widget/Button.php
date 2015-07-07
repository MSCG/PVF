<?php

class Atwix_PVF_Block_Adminhtml_Widget_Button extends Mage_Adminhtml_Block_Widget_Button
{
    /**
     * @var Mage_Catalog_Model_Product Product instance
     */
    private $_product;

    /**
     * Block construct, setting data for button, getting current product
     */
    protected function _construct()
    {
        $this->_product = Mage::registry('current_product');
        parent::_construct();
        $this->setData(array(
            // Shortened button text
            'label'     => Mage::helper('catalog')->__('View Product'),
            // Changed URL so '/index.php' is excluded from path
            'onclick'   => 'window.open(\'' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $this->_product->getUrlPath() .'\')',
            'disabled'  => !$this->_isVisible(),
            'title' => (!$this->_isVisible())?
                // Clearer copy for when product button is disabled due to product status set to "disabled"
                Mage::helper('catalog')->__('(Product Status is set to Disabled)'):
                // Shorter copy to match button text
                Mage::helper('catalog')->__('View Product')
        ));
    }

    /**
     * Checking product visibility
     *
     * @return bool
     */
    private function _isVisible()
    {
        return $this->_product->isVisibleInCatalog() && $this->_product->isVisibleInSiteVisibility();
    }
}
