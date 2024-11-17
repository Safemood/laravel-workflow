# Laravel Workflow

[![Latest Version on Packagist](https://img.shields.io/packagist/v/safemood/laravel-workflow?style=flat-square&color=blue
)](https://packagist.org/packages/safemood/laravel-workflow)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/safemood/laravel-workflow/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/safemood/laravel-workflow/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/safemood/laravel-workflow/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/safemood/laravel-workflow/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/safemood/laravel-workflow.svg?style=flat-square)](https://packagist.org/packages/safemood/laravel-workflow)

Laravel Workflow combines feature details into one class, allowing for action definition and event tracking, simplifying understanding and revealing hidden logic.

- [Laravel Workflow](#laravel-workflow)
  - [Installation](#installation)
  - [Create a Workflow](#create-a-workflow)
  - [Create Actions](#create-actions)
  - [Basic Example](#basic-example)
    - [Define Workflow Logic](#define-workflow-logic)
    - [Execute Workflow](#execute-workflow)
  - [Conditional Action Execution](#conditional-action-execution)
  - [Using DTO for Context](#using-dto-for-context)

## Installation

You can install the package via Composer:

```bash
composer require safemood/laravel-workflow
```

## Create a Workflow

You can create a workflow using the artisan command:

```bash
php artisan make:workflow PaymentWorkflow
```

## Create Actions

You can create an action using the artisan command:

```bash
php artisan make:workflow-action ValidateCartItems
```

```php
<?php

namespace App\Actions;

use Safemood\Workflow\Action;
use App\DTO\CartDTO;

class ValidateCartItems extends Action
{
    public function handle(CartDTO &$context)
    {
        // Simulate extra validation logic
        if (empty($context->cart)) {
            throw new \Exception('Cart is empty');
        }

       // you can pass data to the next action if you want
	  $context->validated = true; 
        
    }
}
```


## Basic Example

Once you have set up your workflows and actions, you can define your business logic and orchestrate them within your Laravel application.

### Define Workflow Logic

In your `PaymentWorkflow` class, you define the sequence of actions and conditions that make up your workflow:

```php
<?php

namespace App\Workflows;

use App\Actions\CalculateTotal;
use App\Actions\MakePayment;
use App\Actions\ValidateCartItems;
use App\Events\PaymentProcessed;
use App\Jobs\SendEmails;
use App\Observers\UserObserver;
use App\Models\Order;
use Safemood\Workflow\WorkflowManager;

class PaymentWorkflow extends WorkflowManager
{
    public function handle()
    {
        // Actions to be executed before the main action
        $this->addBeforeActions([
            new ValidateCartItems(),
            new CalculateTotal()
        ]);

        // The main action of the workflow
        $this->addMainAction(new MakePayment());

        // Actions to be executed after the main action
        $this->addAfterAction(new SendEmails()); // Normal laravel Job in this example

        // Observers to register for specific entities
        $this->registerObservers([
            Order::class => OrderObserver::class,
        ]);

        // Good Debugging or if you want to understand what is happining during the workflow execution: 
	  
	   $this->trackEvents([
            PaymentProcessed::class
        ]);
	  
       // $this->trackAllEvents(); // or

       // $this->trackEventsIn('App\Events\\'); 

       
    }
}
```

### Execute Workflow

```php
<?php

namespace App\Http\Controllers;

use App\Workflows\PaymentWorkflow;
use Illuminate\Http\Request;
use App\DTO\CartDTO;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
         // Example context data representing a user's cart and user information
        $context = new CartDTO(
            [
                ['id' => 1, 'name' => 'Product A', 'price' => 100, 'quantity' => 2],
                ['id' => 2, 'name' => 'Product B', 'price' => 50, 'quantity' => 1]
            ],
            123
        );

        // Execute the PaymentWorkflow with the provided context
        $paymentWorkflow = (new PaymentWorkflow)->run($context);

        // Check if the workflow execution was successful
        $success = $paymentWorkflow->passes();

        // Check if the workflow execution failed
        $failure = $paymentWorkflow->failed();

        // Dump the workflow for debugging
        // $paymentWorkflow->dd();

        // Handle the response based on the workflow outcome
        if ($success) {
            return $paymentWorkflow->successResponse();
        }  

        return $paymentWorkflow->failureResponse();
    }
}


```

## Conditional Action Execution

You can use the when method to conditionally execute an action.

```php
<?php

namespace App\Workflows;

use App\Actions\CalculateTotal;
use App\Actions\MakePayment;
use App\Actions\ValidateCartItems;
use App\Actions\SendEmailReceipt;
use Safemood\Workflow\WorkflowManager;

class PaymentWorkflow extends WorkflowManager
{
    public function handle()
    {
        $this->when(false, function () {
            $this->trackAllEvents();
        });

        $this->when(true, function () {
            $this->registerObservers([
                DummyModel::class => DummyModelObserver::class,
            ]);
        });

        $this->when(true, function () {
            $this->addBeforeActions([
                new DummyAction(),
                new DummyAction(),
            ]);
        });

        $this->unless(
             value: false,
             callback: fn () => $this->trackAllEvents(),
             default: fn () => $this->doSomething()
        );
    }
}
```

## Using DTO for Context  

DTOInterface and BaseDTO allow you to handle context type-safely by using DTOs instead of raw arrays. Here's an example of defining a CartDTO class:

```php
<?php

namespace App\DTO;

use Safemood\Workflow\DTO\BaseDTO;

class CartDTO extends BaseDTO
{
    public function __construct(
        public array $cart,
        public int $user_id,
        public bool $validated = false
    ) {}
    // BaseDTO already implements the DTOInterface
    // Automatically generates getter, setter, and toArray() methods
}
```