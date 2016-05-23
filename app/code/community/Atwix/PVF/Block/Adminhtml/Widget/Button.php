<?php

class Atwix_PVF_Block_Adminhtml_Widget_Button extends Mage_Adminhtml_Block_Widget_Button
{
    /**
     * @var Mage_Catalog_Model_Product || Mage_Catalog_Model_Category
     */
    private $_object;

    /**
     * Block construct, setting data for button, getting current product
     */
    protected function _construct()
    {
        if (NULL == Mage::registry(Atwix_PVF_Model_Observer::REGISTRY_IDENTIFIER)) throw new BadMethodCallException('No current object is set');
        $this->_object = Mage::registry(Atwix_PVF_Model_Observer::REGISTRY_IDENTIFIER);
        if (FALSE === $this->_object) throw new Exception('Wrong object, expected Category or Product model.');
        parent::_construct();
        $this->setData(array(
            // Shortened button text
            'label'    => $this->__('Preview'),
            // Changed URL so '/index.php' is excluded from path
            'onclick'  => sprintf("window.open('%s')", $this->_getUrl()),
            'disabled' => !$this->_isVisible(),
            'title'    => (!$this->_isVisible()) ?
                // Clearer copy for when product button is disabled due to product status set to "disabled"
                $this->__('(Preview not available due to status.)') :
                // Shorter copy to match button text
                $this->__('Preview')
        ));
    }

    /**
     * Returns a URL
     *
     * @return string
     */
    protected function _getUrl()
    {
        if ($this->_object instanceof Mage_Catalog_Model_Product) {
            $store_url = Mage::app()->getStore(2) //TODO dont hardcode store view id
            ->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

            return $store_url . $this->generateUrl('product', $this->_object->getId());
        } else if ($this->_object instanceof Mage_Catalog_Model_Category) {
            $store_url = Mage::app()->getStore(2) //TODO dont hardcode store view id
                ->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

            return $store_url . $this->generateUrl('category', $this->_object->getId());
        } else {
            return '';
        }
    }

    /**
     * Generates a url by identifier (category or product)
     *
     * @param $identifier
     * @param $id
     *
     * @return string
     */
    protected function generateUrl($identifier, $id) {
        return 'catalog/' . $identifier . '/view/id/' . $id;
    }

    /**
     * Checking objects visibility
     *
     * @return bool
     */
    private function _isVisible()
    {
        if ($this->_object instanceof Mage_Catalog_Model_Product) {
            return $this->_object->isVisibleInCatalog() && $this->_object->isVisibleInSiteVisibility();
        } else if ($this->_object instanceof Mage_Catalog_Model_Category) {
            return $this->_object->getIsActive();
        } else {
            return FALSE;
        }
    }
}
