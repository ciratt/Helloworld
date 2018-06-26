<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ciratt\Helloworld\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\Bootstrap;

class ImportProductCommand extends Command {

    public function __construct() {
        parent::__construct();
    }

    protected function configure() {

        $this->setName('alx:importProduct');
        $this->setDescription('Product import');
        $this->addArgument('csvPath', InputArgument::REQUIRED, __('specifica il percorso del csv'));
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $csv_path = $input->getArgument('csvPath');

        include("app/bootstrap.php");
        $bootstrap = Bootstrap::create(BP, $_SERVER);
        $objectManager = $bootstrap->getObjectManager();

        $output->writeln('class ' . get_class($objectManager));

        $csv_handle = fopen($csv_path, "r");

        $conta = 0;

        $state = $objectManager->get('Magento\Framework\App\State');
        $state->setAreaCode('frontend');
        
        while (!feof($csv_handle)) {
            $riga = fgetcsv($csv_handle, 2048, ';');
            $conta++;
            if ($conta == 1)
                continue;

            $sku = $riga[0];
            $name = $riga[1];
            $attrSetId = $riga[2];
            $categiories = $riga[3];
            $status = $riga[4];
            $typeId = $riga[5];
            $price = $riga[6];
            $websiteIds = explode(',', $riga[7]);
            $categoryIds = explode(',', $riga[8]);
            $urlKey = $riga[9];
            $minSaleQty = $riga[10];
            $maxSaleQty = $riga[11];
            $qty = $riga[12];
            $visibility = $riga[13];

            $simple_product = $objectManager->create('\Magento\Catalog\Model\Product');
            $simple_product->setSku($sku);
            $simple_product->setName($name);
            $simple_product->setAttributeSetId($attrSetId);            
            $simple_product->setCategories($categiories);
            $simple_product->setStatus($status);
            $simple_product->setTypeId($typeId);
            $simple_product->setPrice($price);
            $simple_product->setWebsiteIds($websiteIds);
            $simple_product->setCategoryIds($categoryIds);
            $simple_product->setUrlKey($urlKey);
            $simple_product->setStockData(array(
                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                'manage_stock' => 1, //manage stock
                'min_sale_qty' => $minSaleQty, //Minimum Qty Allowed in Shopping Cart
                'max_sale_qty' => $maxSaleQty, //Maximum Qty Allowed in Shopping Cart
                'is_in_stock' => 1, //Stock Availability
                'qty' => $qty //qty
                    )
            );
            $simple_product->setVisibility($visibility);


            $simple_product->save();
            $simple_product_id = $simple_product->getId();

            $output->writeln('riga ' . $conta);
            $output->writeln('new product id  ' . $simple_product_id);
            break;
        }
    }

}
