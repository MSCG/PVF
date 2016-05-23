<?php

class Atwix_PVF_Model_Observer
{
    const REGISTRY_IDENTIFIER = 'pvf_current_object';

    public function addPVFButton($observer)
    {
        $_block = $observer->getBlock();
        $_type  = $_block->getType();
        if ($_type == 'adminhtml/catalog_product_edit') {

            Mage::unregister(self::REGISTRY_IDENTIFIER);
            Mage::register(self::REGISTRY_IDENTIFIER, Mage::registry('current_product'));
            $_block->setChild('product_view_button',
                $_block->getLayout()->createBlock('atwix_pvf/adminhtml_widget_button')
            );

            $_deleteButton = $_block->getChild('delete_button');
            /* Prepend the new button to the 'Delete' button if exists */
            if (is_object($_deleteButton)) {
                $_deleteButton->setBeforeHtml($_block->getChild('product_view_button')->toHtml());
            } else {
                /* Prepend the new button to the 'Reset' button if 'Delete' button does not exist */
                $_resetButton = $_block->getChild('reset_button');
                if (is_object($_resetButton)) {
                    $_resetButton->setBeforeHtml($_block->getChild('product_view_button')->toHtml());
                }
            }
        } else if ($_type == 'adminhtml/catalog_category_edit_form') {
            //If category isnt even saved, dont show anything
            if (is_object(Mage::registry('current_category'))) {
                $data = Mage::registry('current_category')->getData('path_ids');
                $data = array_filter($data);
                if (empty($data)) return;
            }
            Mage::unregister(self::REGISTRY_IDENTIFIER);
            Mage::register(self::REGISTRY_IDENTIFIER, Mage::registry('current_category'));
            $_block->setChild('category_view_button',
                $_block->getLayout()->createBlock('atwix_pvf/adminhtml_widget_button')
            );

            $_deleteButton = $_block->getChild('delete_button');
            /* Prepend the new button to the 'Delete' button if exists */
            if (is_object($_deleteButton)) {
                $_deleteButton->setBeforeHtml($_block->getChild('category_view_button')->toHtml());
            }
        }
    }
}