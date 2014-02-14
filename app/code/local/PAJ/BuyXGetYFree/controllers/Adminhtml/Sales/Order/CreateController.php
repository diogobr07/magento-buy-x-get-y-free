<?php

require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml').DS.'Sales'.DS.'Order'.DS.'CreateController.php';

class PAJ_BuyXGetYFree_Adminhtml_Sales_Order_CreateController extends Mage_Adminhtml_Sales_Order_CreateController {

	public function loadBlockAction(){
		
		parent::loadBlockAction();
		
		// cart isn't empty 
		if($this->_getQuote()->getItemsCount()){
			// Spend X get Y Free
			$this->spendXgetYfree();
			
		}

		return parent::loadBlockAction();
	}

	public function spendXgetYfree(){

		// Get admin variables for SPEND x get y free
		$spendProductYID = explode (",",Mage::getStoreConfig('buyxgetyfree_section2/general/spend_producty_product_id'));
		$spendCartTotalRequired = explode (",",Mage::getStoreConfig('buyxgetyfree_section2/general/spend_cart_total_required'));

		foreach ($spendProductYID as $index => $product_id) {
			
			if(empty($product_id)){
				// goes to the next iteration
				continue;
			}

			// check subtotal required for the product
			if( $this->_getQuote()->getSubtotal() >= $spendCartTotalRequired[$index] && !($this->_getQuote()->hasProductId( $spendProductYID[$index] ) ) ){

				$this->_getOrderCreateModel()->addProduct( $spendProductYID[$index] );

			} else if ($this->_getQuote()->getSubtotal() < $spendCartTotalRequired[$index] ){

				$this->_getOrderCreateModel()->removeItem( $spendProductYID[$index] );
			
			}
		}

	}
}