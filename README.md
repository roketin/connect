# Roketin Client Template
[![Latest Version](https://img.shields.io/github/release/roketin/connect.svg?style=flat-square)](https://github.com/roketin/connect/releases)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://packagist.org/packages/laravel/framework)
[![Total Downloads](https://img.shields.io/packagist/dt/roketin/connect.svg?style=flat-square)](https://packagist.org/packages/roketin/connect)

RClient is standard client application to [Roketin API](http://www.roketin.com)  to accelerate connecting and integrating basic feature of Roketin Engine API to client's website.

## API Documentation

Documentation for the Roketin API can be found on the [Documentation](http://docs.rengine.apiary.io/#).

## Installation

### Laravel 5

```php
"require": {
    "laravel/framework": "5.0.*",
    "roketin/connect": "v0.0.5"
}
```

Next, run the Composer update command from the Terminal:

    composer update

    or

    composer update "roketin/connect"

## CONFIGURATION
1. Open config/app.php and addd this line to your Service Providers Array
  ```php
    Roketin\Providers\RoketinServiceProvider::class,
  ```

2. Open config/app.php and addd this line to your Aliases

```php
    'Roketin' => Roketin\Facades\RoketinFacade::class
  ```

3. Publish the config using the following command:

    $ php artisan vendor:publish --provider="Roketin\Providers\RoketinServiceProvider"

4. Create an .env file based on .env.example file and change the value based on client credentials
  
  ```
    APP_ENV=local
    APP_DEBUG=true
    APP_KEY=somestringrandom
    APP_URL=http://localhost

    ROKETIN_API=http://dev.roketin.com/api/v2.2/
    ROKETIN_PUBLIC=http://dev.roketin.com/

    ROKETIN_TOKEN=aBCd1234
    ROKETIN_USERNAME=roketin
    ROKETIN_RX=4241639264053293060625251241576152575759

    VERITRANS_SERVER=494DKU0E71241K7BC15597DACA94D1F43
    VERITRANS_ENVIRONMENT=sandbox
  ```

## HOW TO USE
* [Basic Usage](#basic)
* [Conditions](#conditions)
* [Sorting](#sorting)
* [Grouping](#grouping)
* [Pagination](#pagination)
* [Tags](#tags)
* [Shipping](#shipping)
* [Sales Order](#order)
* [Subscribe](#subscribe)
* [Message](#message)
* [B2b](#join)
* [Voucher](#voucher)
* [Users](#user)

## Basic

You can call a Roketin Object by using: **Roketin::model()->get()**

```php
    use Roketin;
    
    $menus = Roketin::menus()->get();
    $posts = Roketin::posts()->get();
    $products = Roketin::products()->get();
    etc..
```

Fethcing single object with id/slug/etc:

```php
    /*
     * Same as fetching object, but in singular form (without 's')
     * the second argument can be id or slug or etc ..
     * this is dynamic function call to Roketin Engine API
     */
    
    $home = Roketin::menu('home')->get();
    $post = Roketin::post('latest-update')->get();

```

## Conditions



Fetching object with simple where conditions:

```php
    /**
     * @param $field
     * @param $operation
     * @param $value
     */

    $posts = Roketin::posts()->where('title','like','vacation')->get();
    
    //NOTE : 
    //It doesn't need to add % if using 'like' operator

```

Fetching object with simple orWhere conditions:

```php
    /**
     * @param $field
     * @param $operation
     * @param $value
     */

    $posts = Roketin::posts()
                        ->where('title','like','vacation')
                        ->orWhere('title','like','holiday')
                        ->get();
    
    //NOTE : 
    //It doesn't need to add % if using 'like' operator

```

Advance where orWhere grouping conditions:

```php
    /**
     * @param $field
     * @param $operation
     * @param $value
     */

    $posts = Roketin::posts()
                        ->where('title','like','vacation')
                        ->orWhere('title','like','holiday')
                        ->where('date','>=','2016-04-10')
                        ->where('date','<=','2016-04-18')
                        ->get();
    
    //NOTE : 
    //It will result query grouping 
    // (title like vacation or title like holiday) 
    // AND 
    // (date >= 2016-04-10 and date <= 2016-04-18 )

```

## Sorting

Fetch a Roketin Object API by sorting on it's field:

```php
    /*
     * sorting object before fetch
     * 
     * @param $field
     * @param $direction (optional) default is ASC
     * /

    $posts = Roketin::posts()->sortBy('created_at')->get();
    $posts = Roketin::posts()->sortBy('created_at','DESC')->get();
```

## Grouping

Fetch a Roketin Object API by grouping on it's field:
```php
    /*
     * grouping object before fetch
     * 
     * @param $field
     * /

    $posts = Roketin::posts()->groupBy('created_at')->get();
```
  
## Pagination

Paginating fetch object

```php
    /*
     * paginate object before fetch
     * 
     * @param $size default value is 10
     * @param $page (optional)
     * /

    $posts = Roketin::posts()->paginate(10)->get();
    $posts = Roketin::posts()->paginate(10,2)->get();
```

## Tags

Get all tags post:
```php
    $tags = Roketin::tags()->get()
```

Get all posts by tag:
```php
    /*
     * @param $tag separated by ';'
     * @param $is_blog (optional) default value is false
     */
    $posts = Roketin::tags('tag_1;tag_2',false)->get()
```

## Shipping

Get all available countries:
```php
    $countries = Roketin::shipping()->countries()
```

Get all available provinces (currently available in Indonesia only):
```php
    $province = Roketin::shipping()->province()
```

Get all available city (currently available in Indonesia only):
```php
    /*
     * @param $provinceid
     */

    $cities = Roketin::shipping()->province(9)->cities()
```

Calculate shipping costs:
```php
    /*
     * @param $destination = city id
     * @param $courier = JNE/TIKI/POS
     * @param $weight = item weight in KG (optional) default value 1
     * @param $origin = city id
     */

    $costs = Roketin::shipping()->costs(23, 'JNE')
```

## Order
Create sales order:
```php
    /*
     * @param array $generalData
     * @param array $customerData
     * @param array $products
     * @param $bcc(optional), default = null
     */
     
     $generalData = [
            "notes"         => "some string here",
            "is_email_only" => true, //default value false (for customer guest)
            "ship_cost"     => 10000,
            'ship_provider' => "JNE"
     ];

     $customerData = [
            "first_name" => "Roketin",
            "last_name"  => "User",
            "phone"      => "+628123456789",
            "email"      => "user@roketin.com",
     ];

     $products = [
         [
             "id"         => "2623",
             "qty"        => "1",
             "sku"        => "ADVHEL001",
             "price_type" => "retail_price",
         ],
     ];                                 
    $order = Roketin::order()->create($generalData, $customerData, $products, 'test@mailinator.com')
```
 
> **Note:**
> - For detailed attribute, see sales order API documentation [HERE](http://docs.rengine.apiary.io/#reference/sales-order/sales-order)

----
Confirm payment order:
```php
    /*
     * @param $invoice_number
     * @param $payment_type
     * @param $total
     * @param $customer_name
     * @param $customer_bank
     * @param $transaction_number
     * @param Image $image
     * @param $bank_account(optional), default = null
     * @param $paid_date(optional), default = null
     * @param $bcc(optional), default = null
     */
     
    //you can create image for bank transfer that 
    //showing transfer is success
    //by using Image::make()
    $img = Image::make(Input::file('image'))
    
    $payment = Roketin::order()
                ->confirm('SI16041300058', 
                          'TRANSFER', 
                          '150000', 
                          'Customer Roketin', 
                          'Bank BCA', 
                          'TRX-123', 
                          $img, 
                          '0853909090',
                          '2016-04-10',
                          'bcc@mailinator.com')
```
---
Void an Sales Order and it's invoice:
```php
    /*
     * @param $invoice_number
     */

    $order = Roketin::order()->void('ASD02262016')
```

## Subscribe
Submit a subscription email:
```php
    /*
     * @param $email
     * @param $bcc(optional), default = null
     */

    $subscribe = Roketin::subscribe('somebody@anythin.com', 'bcc@mailinator.com')
```

## Message
Send a message to Roketin Engine Inbox:
```php
    /*
     * @param $sender_name
     * @param $sender_email
     * @param $sender_phone
     * @param $message_title
     * @param $message_body
     * @param $bcc(optional), default = null
     */

    $msg = Roketin::message()
                    ->send(
                    'test',
                    'test@mailinator.com',
                    '123123',
                    'test mesage',
                    'hai',
                    'bcc@mailinator.com')
```

## Join
Send a join message to Roketin Engine Inbox:
```php
    /*
     * @param $sender_name
     * @param $sender_email
     * @param $sender_phone
     * @param $message_title
     * @param $message_body
     * @param $bcc(optional), default = null
     */

    $msg = Roketin::message()
                    ->send(
                    'test',
                    'test@mailinator.com',
                    '123123',
                    'test mesage',
                    'hai',
                    'bcc@mailinator.com')
```

## Vouchers
Check validity of a voucher:
```php
    /*
     * @param $code
     * @param $voucher_type (optional), default = null
     * voucher type can be giftvoucher (voucher in 
     * exchange to money nominal) or
     * other (voucher to exchange to free product)
     * default is voucher_type is other
     */

    $check = Roketin::voucher()->check('AS123D')
```
---
invalidate a voucher (use voucher):
```php
    /*
     * @param $voucher_code
     * @param $voucher_type (optional) default is other
     * @param $used_by (optional) default is logged in user
     */

    $check = Roketin::voucher()->invalidate('AS123D')
```
   
   # User
   Register new user:
```php
    /*
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $phone
     * @param $password
     * @param $password_confirmation
     * @param $bcc(optional), default = null
     * @return user object
     */

    $user = Roketin::user()->register('first_name', 'last_name', 'email', 'phone', 'password', 'password_confirmation', 'bcc');
```

User activation:
```php
    /*
     * @param $token
     * @return true if success activation
     * @return error object if present
     */

    $activation = Roketin::user()->activate('token');
```

Resend activation code to email:
```php
    /*
     * @param $email
     * @return true if success activation
     * @return error object if present
     */

    $resend = Roketin::user()->resendActivation('someone@somthing.com');
```

Forgot password (generate and send token to user email):
```php
    /*
     * @param $email
     * @param $bcc(optional), default = null
     * @return true if success activation
     * @return error object if present
     */

    Roketin::user()->forgot('someone@somthing.com', 'bcc@mailinator.com');
```


Reset password:
```php
    /*
     * @param $token
     * @param $password
     * @param $password_confirmation
     * @param $bcc(optional), default = null
     * @return true if success activation
     * @return error object if present
     */

    Roketin::user()->resetPassword('token','asdf','asdf', 'bcc@mailinator.com');
```

Login:
```php
    /*
     * @param $email
     * @param $password
     * @param $type (optional) default = user, available = vendor
     * @return true if success activation
     * @return error object if present
     */

    Roketin::auth()->login('somebody@somthing.com','asdf');
```

Current User:
```php
    /*
     * @return user object
     */

    Roketin::auth()->user();
```

Update user data:
```php
    /*
     * @return user object
     */

    Roketin::user()->update(['first_name' => 'John']);
```
> **Note:**
> - For detailed attribute, see sales order API documentation [HERE](http://docs.rengine.apiary.io/#reference/users/update)

Get transaction history data:
```php
    /*
     * @return user object
     */

    Roketin::user()->transactionHistory()->get();
```
> **Note:**
> - you can also use where(), orWhere(), etc query with this method

Logout:
```php
    /*
     * @return boolean
     */

    Roketin::auth()->logout();
```
