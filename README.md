# Renttek_VirtualControllers

Enables creating of routes with "virtual controllers"

[![Build Status](https://travis-ci.org/renttek/magento2-virtual-controllers.svg?branch=master)](https://travis-ci.org/renttek/magento2-virtual-controllers)
[![Latest Stable Version](https://poser.pugx.org/renttek/magento2-virtual-controllers/version)](https://packagist.org/packages/renttek/magento2-virtual-controllers)
[![License](https://poser.pugx.org/renttek/magento2-virtual-controllers/license)](https://packagist.org/packages/renttek/magento2-virtual-controllers)

What is a virtual controllers?
A virtual controller is a path + an optional layout handle which will be set.
With that it is possible to create custom routes where you can place custom blocks to your wishes.
(In Magento 2 this is currently only possible by creating a route.xml & a dummy controller action, which returns a `\Magento\Framework\View\Result\Page`)

## Comparison

Here is a short comparison of the minimal required code to create a custom route (example/page/view) in Magento 2 (given there is already a module My_Module)

### Vanilla Magento 2

<module_dir>/etc/frontend/routes.xml
```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:framework:App/etc/routes.xsd">
    <router id="standard">
        <route id="example" frontName="example">
            <module name="My_Module"/>
        </route>
    </router>
</config>
```

<module_dir>/Controllers/Page/View.php
```php
<?php

namespace Mapa\Content\Controller\Page;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends Action
{   
    private $resultPageFactory;
    
    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
```

### With this Module

<module_dir>/etc/virtual_controllers.xml
```xml
<?xml version="1.0"?>
<controllers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:module:Renttek_VirtualControllers:etc/virtual_controllers.xsd">
    <controller path="example/page/view" />
</controllers>
```

## Features

### Handles

Besides the much simpler creation of custom routes, every route will get a few layout handles set:
* "default"
* "virtual_controller"
* The given path, but all characters except `[a-z_]` are replaced by underscores. (`example/page/view` => `example_page_view`)

The generated handle can also be set manually in the xml configuration like this:

<module_dir>/etc/virtual_controllers.xml
```xml
<?xml version="1.0"?>
<controllers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:module:Renttek_VirtualControllers:etc/virtual_controllers.xsd">
    <controller path="example/page/view" handle="my_custom" />
</controllers>
```

Routes can also be disabled by setting `disabled=true` in the configuration:
```xml
<?xml version="1.0"?>
<controllers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:module:Renttek_VirtualControllers:etc/virtual_controllers.xsd">
    <controller path="example/page/view" disabled="true" />
</controllers>
```

### Forwards

Another feature is the possibility of creating forwards without creating a controller.
Forwards are needed if you want to display another URL for a page. (e.g. 'my/shoppingcart' => 'checkout/cart')

```xml
<?xml version="1.0"?>
<controllers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:module:Renttek_VirtualControllers:etc/virtual_controllers.xsd">
    
    <forward path="my/shoppingcart"
             module="checkout"
             controller="cart"
             action="index"/>
</controllers>
```

Only the attributes 'path' and 'module' are required. 'controller' and 'action' will default to 'index' if no value is given.
The above example could also be written without specifying 'action':

```xml
<?xml version="1.0"?>
<controllers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:module:Renttek_VirtualControllers:etc/virtual_controllers.xsd">
    
    <forward path="my/shoppingcart"
             module="checkout"
             controller="cart"/>
</controllers>
```

Forwards are only URLs/path that **CAN** be called. It does not extend/manipulate url generation in any way.

----

All routes are merged by the path attribute, so it is possible to disable routes from other modules.
